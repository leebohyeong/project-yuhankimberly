<?php
namespace Groupidd\Common;

class Setting {
    private static $instance;
    private $settings;
    private $ENCRYPT;

    private function __construct(){
        $this->settings = parse_ini_file(__DIR__.'../../property.php', TRUE);
    }

    public static function getInstance(){
        if (!isset(self::$instance)) {
            self::$instance = new Setting();
        }
        return self::$instance;
    }

    public function __get($setting){
        if (array_key_exists($setting, $this->settings)){
            return $this->settings[$setting];
        } else {
            foreach ($this->settings as $section) {
                # code...
                if (array_key_exists($setting)){
                    return $section[$setting];
                }
            }
        }
    }
}
