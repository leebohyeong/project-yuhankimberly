<?php
/**
 * Database 객체
 *
 * 객체를 생성하고 from 명령어로 테이블을 연결합니다.
 * insert, update, delete 명령어로 쿼리문을 실행합니다.
 *
 * init() 으로 초기화 합니다.
 * close() 도 초기화 합니다. (연결도 끊고 PDO 객체도 소멸합니다.)
 *
 * 작성일: 2017-04-27
 * 수정일: 2017-04-27
 *
 **/

namespace Groupidd\Model;

use Groupidd\Common\Log;
use Groupidd\Common\Setting;
use PDO;
use PDOException;

class ModelBase
{
    protected $setting;
    protected $pdo;
    protected $user;
    protected $pass;
    protected $mode;
    protected $select;
    protected $from;
    protected $table;
    protected $join;
    protected $where;
    protected $whereIn;
    protected $groupby;
    protected $orderby;
    protected $limit;
    protected $rawQuery;
    protected $whereParam = array();
    private $log;

    protected static $instance = null;

    function __construct()
    {
        $this->setting = Setting::getInstance('dev');     // 개발환경:dev, 운영환경 : real
        $this->log = new Log();
        $dbInfo = $this->connectSetting();
        $this->connect($dbInfo);
    }

    /**
     * @param
     *
     * @return void
     */
    protected function connectSetting()
    {
        $host = $this->setting->DB['DB_HOST'];
        $user = $this->setting->DB['DB_USER'];
        $pass = $this->setting->DB['DB_PASSWORD'];
        $db = $this->setting->DB['DB_NAME'];
        $charset = $this->setting->DB['DB_ENCODING'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $dbInfo = array();
        $dbInfo['dsn'] = $dsn;
        $dbInfo['user'] = $user;
        $dbInfo['pass'] = $pass;
        return $dbInfo;
    }

    /**
     * @param
     *
     * @return void
     */
    protected function connect($dbInfo)
    {
        try {
            $this->pdo = new PDO($dbInfo['dsn'], $dbInfo['user'], $dbInfo['pass'], array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            $this->exceptionLog($e->getMessage());
        }

    }

    /**
     * @param string $mode "test"라고 입력하면 쿼리를 실행하지 않는다
     *
     * @return void
     */
    public function mode($mode)
    {
        $available = array('test', 'public');
        if (!in_array($mode, $available)) {
            return false;
        }
        $this->mode = $mode;
    }

    /**
     * SELECT 쿼리를 위한 내부함수
     * @param
     *
     * @return void
     */
    protected function _get()
    {
        if ($this->rawQuery) {
            return $this->sql;
        }
        if (is_null($this->select)) {
            $this->select = "SELECT * ";
        }

        $sql = $this->select . $this->from;
        if ($this->join) {
            $sql .= $this->join;
        }
        if ($this->where) {
            $sql .= $this->where;
        }
        if ($this->groupby) {
            $sql .= $this->groupby;
        }
        if ($this->orderby) {
            $sql .= $this->orderby;
        }
        if ($this->limit) {
            $sql .= $this->limit;
        }
        return $sql;
    }

    /**
     * @param PDOStatement $stmt
     * PDO prepare를 위한 parameter 값 연결
     *
     * @return void
     */
    private function bindParams($stmt)
    {
        foreach ($this->whereParam as $key => $value) {
            $type = PDO::PARAM_STR;
            switch ($value) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
            }
            $stmt->bindValue(":{$key}", $value, $type);
        }
    }

    /**
     * 쿼리문 실행
     *
     * @param string $sql
     * 쿼리문(내부함수에서 생성된다)
     *
     * @return array | int SELECT 일 경우 array, insert/update/delete 일 경우 int
     */
    protected function run($sql)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            $result = $this->exceptionLog($e->getMessage());
        }
        $isSelect = strpos($stmt->queryString, 'SELECT');

        $this->bindParams($stmt);


        if ($this->mode == "test") {
            return $stmt->debugDumpParams();
        }

        try {
            $result = $stmt->execute();
            if ($isSelect !== false) {
                $result = $stmt;
            }
        } catch (PDOException $e) {
            $this->exceptionLog($e->getMessage());
            return false;
        }

        return $result;
    }

    /**
     * 여러 행의 결과를 가져올 때 사용
     * @param
     *
     * @return array
     */
    public function getAll()
    {
        $sql = $this->_get();
        $result = $this->run($sql);

        if (is_string($result)) {
            return $result;
        }

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 여러 행의 결과를 가져올 때 사용 (컬럼의 값을 key값으로 가져올 때)
     *
     * @param string $key
     * key값으로 지정할 컬럼명
     *
     * @return array
     */
    public function getAllKeyList($key)
    {
        $sql = $this->_get();

        $result = $this->run($sql);

        if (is_string($result)) {
            return $result;
        }
        $aReturn = array();
        if ($result) {
            while ($aRow = $result->fetch(PDO::FETCH_ASSOC)) {
                $aReturn["{$aRow[$key]}"] = $aRow;
            }
            return $aReturn;
        }
        return false;
    }

    /**
     * 한 행의 결과를 가져올 때 사용
     * @param
     *
     * @return array
     */
    public function getOne()
    {
        $sql = $this->_get();

        $result = $this->run($sql);

        if (is_string($result)) {
            return $result;
        }
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 한 컬럼의 결과를 가져올 때 사용
     * @param
     *
     * @return string
     */
    public function getOneCol()
    {
        $sql = $this->_get();

        $result = $this->run($sql);

        if (is_string($result)) {
            return $result;
        }
        return $result->fetchColumn();
    }


    /**
     * 준비된 쿼리의 전체행의 개수를 가져올 때 사용
     *
     * @param string $seq
     * 기준이 되는 컬럼의 이름 (기본값 seq)
     *
     * @return int
     */
    public function getCountAll($seq = '*')
    {
        $this->select = "SELECT COUNT($seq)";
        $sql = $this->_get();

        $result = $this->run($sql);

        if (is_string($result)) {
            return $result;
        }
        return $result->fetchColumn();
    }

    /**
     * @param string $select
     * SELECT 컬럼. ,로 붙여서 줄 수 있다
     * 반복해서 실행하면 콤마(,)가 자동으로 붙는다.
     * @param boolean $replace
     * true 일 경우 SELECT 구문 초기화
     *
     * @return void
     */
    public function select($select = '', $replace = false)
    {
        if (is_null($this->select) || $replace) {
            $this->select = "SELECT {$select}";
        } else {
            $this->select .= ", {$select}";
        }
    }

    /**
     * from 이지만 insert/update/delete에도 쓰임
     *
     * @param string $from
     * 테이블 이름
     *
     * @return void
     */
    public function table($table = '')
    {
        $this->from($table);
    }

    /**
     * from 이지만 insert/update/delete에도 쓰임
     *
     * @param string $from
     * 테이블 이름
     *
     * @return void
     */
    public function from($from = '')
    {
        $this->table = "{$from}";
        $this->from = " FROM {$from}";
    }

    /**
     * @param string $table
     * 테이블명
     * @param string $condition
     * 조건절
     * @param string $type
     * left|right 등
     *
     * @return void
     */
    public function join($table, $condition, $type = '')
    {
        $sql = '';
        if ($type) {
            $sql = " {$type}";
        }
        $this->join = $sql .= " JOIN {$table} ON {$condition}";

    }

    /**
     * @param string $column
     * 컬럼명
     * @param string $paramKey
     * 파리미터 키
     * @param string $oprator
     * 조건절
     * @param boolean $setParam
     * 파라미터 세팅( 삭제예정 )
     *
     *
     * @return void
     */
    public function where($column = '', $paramKey = '', $operator = '=', $setParam = true)
    {
        $shortColumn = "";
        if (strpos($column, '.')) {
            $shortColumn = explode('.', $column)[1];
        } else {
            $shortColumn = $column;
        }
        $shortColumn = $this->_getParamKey($shortColumn);

        // 처음 where 절은 WEHRE로 이후에는 AND로 연결
        if (is_null($this->where)) {
            $this->where = " WHERE ";
        } else {
            $this->where .= " AND ";
        }
        // setParam 이 false 면 단순 key: value 형태의 where 절이 아닌 것으로 간주
        if (!$setParam) {

            // paramKey 가 array 면 key value로 바인드 해준다
            if (is_array($paramKey)) {
                // key value 바인드
                foreach ($paramKey as $key => $value) {
                    $this->where .= "{$column} {$operator} :{$key} ";
                    $this->whereParam[$key] = $value;
                }
                // paramKey가 string이면 그냥 where절에 연결
            } else {
                $this->where .= "{$column} {$operator} {$paramKey}";
            }
        } else {
            // $paramKey 가 null이면 그대로 NULL
            if (is_null($paramKey)) {
                $this->where .= "{$column} {$operator} NULL";
            } else {
                $this->where .= "{$column} {$operator} :{$shortColumn}";
                $this->whereParam[$shortColumn] = $paramKey;
            }
        }

    }


    /**
     * @param string $column
     * 컬럼명
     * @param string $paramKey
     * 파리미터 키
     * @param string $oprator
     * 조건절
     * @param boolean $setParam
     * 파라미터 세팅( 삭제예정 )
     *
     *
     * @return void
     */
    public function whereOperationLike($column = '', $paramKey = '', $paramValue = '', $andOr = '', $bracketStart = '', $bracketEnd = '')
    {
        // 처음 where 절은 WEHRE로 이후에는 AND로 연결
        if (is_null($this->where)) {
            $this->where = " WHERE ";
        }

        if (!is_null($bracketStart)) $this->where .= " {$bracketStart} ";
        $this->where .= "{$andOr} {$column} LIKE CONCAT('%', :$paramKey, '%')";
        $this->whereParam[$paramKey] = $paramValue;

        if (!is_null($bracketEnd)) $this->where .= " {$bracketEnd} ";
    }


    /**
     * @param string $column
     * 컬럼명
     * @param string $paramKey
     * 파리미터 키
     * @param string $oprator
     * 조건절
     * @param boolean $setParam
     * 파라미터 세팅( 삭제예정 )
     *
     *
     * @return void
     */
    public function whereConnect($column = '', $andOr = '', $bracketStart = '', $bracketEnd = '')
    {
        // 처음 where 절은 WEHRE로 이후에는 AND로 연결
        if (is_null($this->where)) {
            $this->where = " WHERE ";
        } else {
            $this->where .= " AND ";
        }

        if (!is_null($bracketStart)) $this->where .= " {$bracketStart} ";
        $this->where .= "{$andOr} {$column}";

        if (!is_null($bracketEnd)) $this->where .= " {$bracketEnd} ";
    }


    /**
     * @param array $array
     * where 절을 배열로 전달
     *
     * @return void
     */
    public function whereArray($array)
    {
        foreach ($array as $key => $value) {
            $this->where($key, $value);
        }
    }

    /**
     * @param string $column
     * 컬럼명
     * @param array $array
     * 배열로 where in 구문 작성
     *
     * @return void
     */
    public function whereIn($column, $array, $not = false)
    {
        $whereArrayValue = "'";
        $whereArrayValue .= implode("', '", $array);
        $whereArrayValue .= "'";
        if (is_null($this->where)) {
            if ($not) {
                $this->where = " WHERE {$column} NOT IN ({$whereArrayValue})";
            } else {
                $this->where = " WHERE {$column} IN ({$whereArrayValue})";
            }
        } else {
            if ($not) {
                $this->where .= " AND {$column} NOT IN ({$whereArrayValue})";
            } else {
                $this->where .= " AND {$column} IN ({$whereArrayValue})";
            }
//            $this->where .= " AND {$column} IN ({$whereArrayValue})";
        }
    }

    /**
     * BETWEEN 구문 작성
     * @param string $column
     * 컬럼명
     * @param string $start
     * 시작값
     * @param string $end
     * 끝값
     *
     * @return void
     */
    public function between($column = '', $start = '', $end = '')
    {
        # code...
        if (is_null($this->where)) {
            $this->where = " WHERE {$column} BETWEEN {$start} AND {$end}";
        } else {
            $this->where .= " AND {$column} BETWEEN {$start} AND {$end}";
        }
    }

    /**
     * LIKE 구문 작성 (내부용)
     * @param string $column
     * 컬럼명
     * @param string $value
     * 값
     * @param string $andOr
     * 연결부호
     *
     * @return void
     */
    public function _like($column = '', $value = '', $andOr = 'AND', $like = 'LIKE')
    {
        $shortColumn = "";
        if (strpos($column, '.')) {
            $shortColumn = explode('.', $column)[1];
        } else {
            $shortColumn = $column;
        }

        $shortColumn = $this->_getParamKey($shortColumn);

        if (is_null($this->where)) {
            $this->where = " WHERE {$column} {$like} CONCAT('%', :$shortColumn, '%')";
        } else {
            $this->where .= " {$andOr} {$column} {$like} CONCAT('%', :$shortColumn, '%')";
        }

        $this->whereParam[$shortColumn] = '%' . $value . '%';
    }

    /**
     * LIKE 구문 작성
     * @param string $column
     * 컬럼명
     * @param string $value
     * 값
     *
     * @return void
     */
    public function like($column = '', $value = '')
    {
        $this->_like($column, $value, 'AND');
        /*
        $shortColumn = "";
        if ( strpos( $column, '.' ) ) {
            $shortColumn = explode('.', $column)[1];
        } else {
            $shortColumn = $column;
        }
        if ( is_null($this->where) )
        {
          $this->where = " WHERE {$column} LIKE CONCAT(:$shortColumn, '%')";
        }
        else
        {
          $this->where .= " AND {$column} LIKE CONCAT(:$shortColumn, '%')";
        }

        $this->whereParam[$shortColumn] = '%'.$value.'%';
        */
    }

    /**
     * LIKE 구문 작성 2
     * @param string $column
     * 컬럼명
     * @param string $value
     * 값
     *
     * @return void
     */
    public function or_like($column = '', $value = '')
    {
        $this->_like($column, $value, 'OR');
    }

    /**
     * LIKE 구문 작성 3
     * @param string $column
     * 컬럼명
     * @param string $value
     * 값
     *
     * @return void
     */
    public function not_like($column = '', $value = '')
    {
        $this->_like($column, $value, 'AND', 'NOT LIKE');
    }

    /**
     * LIKE 구문 작성 4
     * @param string $column
     * 컬럼명
     * @param string $value
     * 값
     *
     * @return void
     */
    public function or_not_like($column = '', $value = '')
    {
        $this->_like($column, $value, 'OR', 'NOT LIKE');
    }

    /**
     * @param array $data
     * 배열로 키:값 PDOprepare 구문 작성
     *
     * @return void
     */
    public function insert($data)
    {
        if ($data) {
            $columns = '';
            $columns = implode(', ', array_keys($data));
            $val = implode(', :', array_keys($data));
            $val = ":" . $val . "";
        }
        if ($columns == '0') {
            return false;
        }
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUE ({$val})";

        $this->whereParam = array_merge($this->whereParam, $data);

        $this->sql = $sql;
        $result = $this->run($sql);

        return $result;
    }


    /**
     * @param array $data
     * 배열로 키:값 PDOprepare 구문 작성
     *
     * @return void
     */
    public function replace($data, $updateColumn = null)
    {
        $columns = implode(', ', array_keys($data));

        $out = array();
        $convertedData = array();
        $extra = ' ';
        foreach ($data as $key => $value) {
            if (strpos($value, '+') > -1) {
                if (count($out)) {
                    $extra .= ",";
                }
                $extra .= "{$key} = {$value}";
                unset($data[$key]);
            } else {
                $convertedKey = $this->_getParamKey($key, $data);
                array_push($out, "{$key} = :{$convertedKey}");
                $convertedData[$convertedKey] = $value;
            }
        }
        $keyVal = implode(", ", $out);
        $keyVal .= $extra;

        $keyVal .= $updateColumn == null ? "" : ", {$updateColumn} = now()";

        $sql = "REPLACE INTO {$this->table} SET {$keyVal}";

        if ($this->where !== null) {
            $sql .= $this->where;
        }

        $this->whereParam = array_merge($this->whereParam, $convertedData);
        $this->sql = $sql;
        $result = $this->run($sql);

        return $result;
    }

    public function multipleInsert($data)
    {

        if (!is_array($data)) {
            return false;
        }

        $columns = implode(', ', array_keys($data[0]));
        unset($data[0]);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUE ";

        foreach ($data as $key => $value) {
            $val = ':' . implode(', :', array_keys($value));
            $sql .= "({$val}),";
        }
        $sql = rtrim($sql, ',');

        $this->whereParam = array_merge($this->whereParam, $data);

        $this->sql = $sql;
        $result = $this->run($sql);

        return $result;
    }

    /**
     * @param array $data
     * 배열로 키:값 PDOprepare 구문 작성
     * @param string $parentSeq
     * 부모 키의 값
     * @param string $seq
     * 부모 키의 컬럼
     *
     * @return void
     */
    public function insert_batch($data, $parentSeq = '', $seq = 'seq')
    {
        foreach ($data as $key => $value) {
            if ($parentSeq != "") {
                $value[$seq] = $parentSeq;
            }
            $result = $this->insert($value);

            if (!$result) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $data
     * 교체될 배열 키:값
     * @param string $updatecolumn
     * 현재시간으로 작성될 컬럼명
     *
     * @return int
     */
    public function update($data, $updateColumn = null)
    {
        $columns = implode(', ', array_keys($data));

        $out = array();
        $convertedData = array();
        $extra = ' ';
        foreach ($data as $key => $value) {
            if (strpos($value, '+') > -1) {
                if (count($out)) {
                    $extra .= ",";
                }
                $extra .= "{$key} = {$value}";
                unset($data[$key]);
            } else {
                $convertedKey = $this->_getParamKey($key, $data);
                array_push($out, "{$key} = :{$convertedKey}");
                $convertedData[$convertedKey] = $value;
            }
        }
        $keyVal = implode(", ", $out);
        $keyVal .= $extra;

        $keyVal .= $updateColumn == null ? "" : ", {$updateColumn} = now()";

        $sql = "UPDATE {$this->table} SET {$keyVal}";

        if ($this->where !== null) {
            $sql .= $this->where;
        }

        $this->whereParam = array_merge($this->whereParam, $convertedData);
        $this->sql = $sql;
        $result = $this->run($sql);

        return $result;
    }

    /**
     * @param boolean $hard
     * softdelete / harddelete 구현(hard=true 일 경우 DELETE, soft는 UPDATE DEL_DATE)
     *
     * @return int
     */
    public function delete($data, $hard = false)
    {
        if (is_null($this->where)) {
            return false;
        }

        if ($hard) {                   // hard delete
            $sql = "DELETE FROM {$this->table}";

            $sql .= $this->where;
//        $sql .= $this->whereParam;
        } else {                        // soft delete

            $out = array();
            $convertedData = array();
            $extra = ' ';
            foreach ($data as $key => $value) {
                if (strpos($value, '+') > -1) {
                    if (count($out)) {
                        $extra .= ",";
                    }
                    $extra .= "{$key} = {$value}";
                    unset($data[$key]);
                } else {
                    $convertedKey = $this->_getParamKey($key, $data);
                    array_push($out, "{$key} = :{$convertedKey}");
                    $convertedData[$convertedKey] = $value;
                }
            }
            $keyVal = implode(", ", $out);
            $keyVal .= $extra;

            $keyVal .= ($keyVal == null) ? "" : ",";
            $keyVal .= "del_date = now()";
            $sql = "UPDATE {$this->table} SET {$keyVal}";

            if ($this->where !== null) {
                $sql .= $this->where;
            }

            $this->whereParam = array_merge($this->whereParam, $convertedData);
            $this->sql = $sql;
        }

        $result = $this->run($sql);

        return $result;
    }

    /**
     * @param string $groupby
     * 그룹 컬럼
     *
     * @return void
     */
    public function groupby($groupby)
    {
        if (is_null($this->groupby)) {
            $this->groupby = " GROUP BY {$groupby}";
        } else {
            $this->groupby .= ", {$groupby}";
        }
    }

    /**
     * @param string $orderby
     * 정렬 컬럼
     * @param string $direction
     * 정렬순서
     *
     * @return void
     */
    public function orderby($orderby, $direction = 'ASC')
    {
        if (is_null($this->orderby)) {
            $this->orderby = " ORDER BY {$orderby} {$direction}";
        } else {
            $this->orderby .= " , {$orderby} {$direction}";
        }
        // $this->orderby = $orderby;
    }

    /**
     * @param int $limit
     * limit 제한 개수
     * @param int $offset
     * 건너뛸 offset 값
     *
     * @return void
     */
    public function limit($limit, $offset = 0)
    {
        if ($offset == 0) {
            $this->limit = " LIMIT {$limit}";
        } else {
            $this->limit = " LIMIT {$offset}, {$limit}";
        }
    }


    /**
     * @param boolean $hard
     * @return int
     */
    public function truncate()
    {
        $sql = "TRUNCATE TABLE {$this->table}";
        $this->sql = $sql;
        $result = $this->run($sql);

        return $result;
    }


    /**
     * query 문 바로 실행
     * @param string $query
     * @param array $params // array( 1, 2, array(1,2,3) )
     * 바인딩은 $params 에 담아 보내고 WHERE IN 의 경우는 값을 array로 전달
     *
     * @return array
     */
    public function query($query = '', $params = array())
    {
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $whereArrayValue = "'";
                $whereArrayValue .= implode("', '", $param);
                $whereArrayValue .= "'";
                $query = str_replace(":{$key}", $whereArrayValue, $query);
            } else {
                $this->whereParam[$key] = $param;
            }
        }
        // $this->whereParam = array_merge( $this->whereParam, $params);

        $this->rawQuery = true;
        $this->sql = $query;
    }

    /**
     * PDO 종료
     * @param
     *
     * @return void
     */
    public function close()
    {
        $this->init();
        $this->pdo = null;
        $this->from = null;
        $this->table = null;
        $this->join = null;
        $this->where = null;
        $this->whereIn = null;
        $this->groupby = null;
        $this->orderby = null;
        $this->limit = null;
        $this->rawQuery = null;
        $this->whereParam = array();
    }

    /**
     *  Returns the last inserted id.
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Starts the transaction
     * @return boolean, true on success or false on failure
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     *  Execute Transaction
     * @return boolean, true on success or false on failure
     */
    public function executeTransaction()
    {
        return $this->pdo->commit();
    }

    /**
     *  Rollback of Transaction
     * @return boolean, true on success or false on failure
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    private function _getParamKey($key, $data = array())
    {
        $paramArray = array_merge($this->whereParam, $data);
        // die();
        while (isset($paramArray[$key])) {
            $key = $this->_getParamKey($key . 'a', $data);
            // return false;
        }
        return $key;
    }

    /**
     * 객체 초기화
     * @param
     *
     * @return void
     */
    public function init()
    {
        $this->mode = null;
        $this->select = null;
        $this->join = null;
        $this->where = null;
        $this->groupby = null;
        $this->orderby = null;
        $this->whereParam = array();
        $this->limit = null;
    }

    /**
     * 에러로그를 위한 내장함수
     * @param string $message
     * @param string $sql
     *
     * @return void
     */
    private function exceptionLog($message)
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message . '<br>';
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($this->sql)) {
            # Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $this->sql;
        }
        # Write into log
        $this->log->write($message);

        return $exception;
    }
}
