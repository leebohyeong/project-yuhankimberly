<?php //require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php"; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>

    <meta name="author" content="Group IDD 개발팀">

    <link rel="stylesheet" href="/sitemanager/assets/css/sitemanager.css">
    <script src="/sitemanager/assets/js/sitemanager.js"></script>
</head>
<body>


<button type="button" class="button" data-bs-toggle="modal" data-bs-target="#sortingModal">순서변경</button>

<div id="sortingModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="sorting-list">
                <form method="post" action="">
                    <h3>순서 변경</h3>
                    <p>* 드래그 앤 드롭 사용</p>
                    <ul>
                        <li>
                            <input type="hidden" name="order[]" value="">
                            aaa
                        </li>
                        <li>bbb</li>
                    </ul>
                    <p>
                        <button class="button button--primary" type="submit">저장</button>
                    </p>
                </form>
            </div>
            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    const sortingModal = document.getElementById('sortingModal');
    const sortingList = sortingModal.querySelector('.sorting-list ul');
    let sorting = new Sortable(sortingList, {
        animation: 150,
    });
    const originSorted = sorting.toArray();
    sortingModal.addEventListener('show.bs.modal', () => {
        if (sorting) sorting.sort(originSorted, false);
    });
</script>
</body>
</html>
