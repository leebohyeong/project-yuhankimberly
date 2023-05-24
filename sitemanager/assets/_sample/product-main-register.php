<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

$ROOT_PATH_ = $_SERVER['DOCUMENT_ROOT'];

$compositionArray = [
    "B" => [
        [
            "seq" => 4,
            "name" => "test4",
            "icon_url" => "744b7d62480402e8a8a01d315350d24f.png"
        ],
        [
            "seq" => 1,
            "name" => "test1",
            "icon_url" => "7aa0054e26832630550fe6e03b60d3f4.jpeg"
        ]
    ],
    "C" => [
        [
            "seq" => 5,
            "name" => "test5",
            "icon_url" => "3a934c9111978458f07a40948bd7b3c7.png"
        ],
        [
            "seq" => 2,
            "name" => "test2",
            "icon_url" => "d178d30ba0a86b9e3f8578cb73ac50f0.jpeg"
        ]
    ],
    "D" => [
        [
            "seq" => 6,
            "name" => "test6",
            "icon_url" => "23ebe85cbe0c44ba363c6a20e845f908.png"
        ],
        [
            "seq" => 3,
            "name" => "test3",
            "icon_url" => "c3bb734287f2c4e0bfd841a6f4a5d4bb.png"
        ]
    ]
];

$productCompositionCategory = [
    [
        'category' => 'B',
        'name' => '베이스'
    ],
    [
        'category' => 'C',
        'name' => '토핑'
    ],
    [
        'category' => 'D',
        'name' => '시럽'
    ],
];

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager">
<section class="sitemanager__body">
    <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#modalMaterials">구성선택</button>

    <div class="materials-selected-list"></div>

    <div id="modalMaterials" class="modal fade modal-materials" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-materials__container">
                    <div>
                        <ul class="modal-materials__category">
                            <?php
                            foreach ($productCompositionCategory as $row) {
                                ?>
                                <li>
                                    <a class="modal-materials__category-menu" href="#modal-materials-list-<?= strtolower($row['category']) ?>">
                                        <?= $row['name'] ?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <div class="modal-materials__search">
                            <input type="text" title="검색어 입력">
                            <button class="button" type="button">검색</button>
                        </div>
                        <?php
                        foreach ($compositionArray as $key => $value) {
                            ?>
                            <div id="modal-materials-list-<?= strtolower($key) ?>" class="modal-materials__list">
                                <p class="modal-materials__total">
                                    총 <strong><?= sizeof($value) ?></strong> 건
                                </p>
                                <ul class="modal-materials__items">
                                    <?php
                                    foreach ($value as $row) {
                                        ?>
                                        <li class="modal-materials__item">
                                            <label>
                                                <input type="checkbox" value="<?= $row['seq'] ?>">
                                                <i></i>
                                                <span style="background-image: url('https://www.baskinrobbins.co.kr/upload/product/1722591217.png')"></span>
                                                <strong><?= $row['name'] ?></strong>
                                            </label>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <p class="modal-materials__empty">검색 결과값이 없습니다</p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="modal-materials__selected-list">
                        <p>선택구성</p>
                        <div></div>
                        <p>
                            <button class="button button--primary" type="button">등록</button>
                        </p>
                    </div>
                </div>
                <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const materialsSelectedList = document.querySelector('.materials-selected-list');
            const deleteMaterialsSelectedList = (event) => {
                const button = event.target.closest('button');

                if (button) {
                    selectedListCodes.delete(button.value);
                    renderSelectedList(selectedListCodes, materialsSelectedList);
                }
            };

            const modal = document.getElementById('modalMaterials');
            const modalInstance = new bootstrap.Modal(modal, {
                backdrop: 'static'
            });

            const category = modal.querySelector('.modal-materials__category');
            const categoryMenus = [...category.querySelectorAll('a')];
            let currentCategoryMenu;
            let currentCategoryCode;
            const setCurrentCategory = categoryMenu => currentCategoryMenu = categoryMenu;
            const getCategoryCode = (value) => value.trim().replace(/(#?[a-z]+-)/g, '');
            const setCurrentCategoryCode = () => currentCategoryCode = getCategoryCode(currentCategoryMenu.hash);
            const toggleCategoryMenus = () => categoryMenus.forEach(menu =>
                menu.classList[
                    menu === currentCategoryMenu
                        ? 'add'
                        : 'remove'
                    ]('modal-materials__category-menu--active'));

            const search = modal.querySelector('.modal-materials__search');
            const searchInput = search.querySelector('input');
            const searchButton = search.querySelector('button');
            const searchLists = [...modal.querySelectorAll('.modal-materials__list')];
            const searchTotals = searchLists.map(panel => panel.querySelector('.modal-materials__total strong'));
            const searchItems = searchLists.map(panel => [...panel.querySelectorAll('.modal-materials__item')]);
            const searchEmpties = searchLists.map(panel => panel.querySelector('.modal-materials__empty'));
            const searchWords = [...searchItems].map(items => items.map(item => item.querySelector('strong').textContent.trim()));
            const changeSearchList = () => {
                const keyword = searchInput.value.trim();

                searchWords.forEach((words, panelIndex) => {
                    let total = 0;

                    words.forEach((word, index) => {
                        searchItems[panelIndex][index].classList[
                            word.includes(keyword)
                                ? (total++, 'remove')
                                : 'add'
                            ]('modal-materials__item--hide');
                    });

                    searchEmpties[panelIndex].classList[total ? 'remove' : 'add']('modal-materials__empty--active');
                    searchTotals[panelIndex].textContent = total;
                })
            };
            const clearSearch = () => {
                searchInput.value = '';
                searchItems.flat().forEach(empty => empty.classList.remove('modal-materials__item--hide'));
                searchEmpties.forEach(empty => empty.classList.remove('modal-materials__empty--active'));
                searchTotals.forEach((item, index) => item.textContent = searchItems[index].length);
            };

            const lists = [...modal.querySelectorAll('.modal-materials__list')];
            const toggleLists = () => lists.forEach(list =>
                list.classList[
                     currentCategoryMenu.hash.includes(list.id)
                        ? 'add'
                        : 'remove'
                    ]('modal-materials__list--active'));

            const items = [...modal.querySelectorAll('.modal-materials__item')];
            const itemsData = lists.reduce((data, list) => {
                const categoryCode = getCategoryCode(list.id);

                [...list.querySelectorAll('.modal-materials__item')].forEach(element => {
                    const input = element.querySelector('input')
                    const code = input.value.trim();
                    const image = element.querySelector('span').style.backgroundImage.replace(/^url\(["']?/, '').replace(/['"]?\)$/, '');
                    const name = element.querySelector('strong').textContent.trim();

                    data[code] = {element, input, categoryCode, code, image, name};
                })

                return data;
            }, {});
            const itemsInputs = items.map(item => item.querySelector('input'));
            const changeItem = (event) => {
                const target = event.target || event;
                const value = target.value.trim();

                selectedListTempCodes[target.checked ? 'add' : 'delete'](value);
                renderSelectedList(selectedListTempCodes, selectedListItemsContainer);
            };

            const selectedList = modal.querySelector('.modal-materials__selected-list');
            const {order: selectedListOrder, enum: selectedListOrderEnum, info: selectedListOrderInfo} = categoryMenus.reduce((result, menu, index) => {
                const code = getCategoryCode(menu.hash);
                const name =  menu.textContent.trim();

                result.order.push(code);
                result.enum[code] = index;
                result.enum[index] = code;
                result.info.push({code, name});

                return result;
            }, {order: [], enum: {}, info: []});
            const selectedListItemsContainer = selectedList.querySelector('div');
            const selectedListRegister = selectedList.querySelector('.button');
            const selectedListCodes = new Set();
            const selectedListTempCodes = new Set();
            const renderSelectedList = (codes, container) => {
                if (!codes.size) {
                    container.innerHTML = '';
                    return;
                }

                const items = {};

                [...codes.values()].forEach(selectedCode => {
                    const data = itemsData[selectedCode];
                    const categoryCode = data.categoryCode;

                    if (!items[categoryCode]) items[categoryCode] = [];

                    items[categoryCode].push(createMaterialsSelectedItem(data));
                });

                container.innerHTML = `<dl class="materials-selected-items">${selectedListOrder.reduce((result, orderCode) => {
                    if (items[orderCode]) result.push(`<div><dt>${selectedListOrderInfo[selectedListOrderEnum[orderCode]].name}</dt><dd>${items[orderCode].join('</dd><dd>')}</dd></div>`);

                    return result;
                }, []).join('')}</dl>`;
            };
            const deleteSelectedList = (event) => {
                const button = event.target.closest('button');

                if (button) {
                    const {input} = itemsData[button.value];

                    input.checked = false;
                    changeItem(input)
                }
            };
            const registerSelectedList = () => {
                if (selectedListTempCodes.size) {
                    selectedListCodes.clear();
                    [...selectedListTempCodes.values()].forEach(value => selectedListCodes.add(value));
                    renderSelectedList(selectedListCodes, materialsSelectedList);
                }

                modalInstance.hide();
            };

            const toggleMenuList = (event) => {
                const menu = event.target.closest('a');

                if (menu) {
                    event.preventDefault();

                    setCurrentCategory(menu);
                    setCurrentCategoryCode();
                    toggleCategoryMenus();
                    toggleLists();
                }
            }
            const resetModal = () => {
                const checkedValues = [...selectedListCodes.values()];
                categoryMenus[0].click();
                selectedListTempCodes.clear();
                [...selectedListCodes.values()].forEach(value => selectedListTempCodes.add(value));
                itemsInputs.forEach(input => input.checked = checkedValues.includes(input.value));
                clearSearch();
                renderSelectedList(selectedListTempCodes, selectedListItemsContainer);
            };
            const createMaterialsSelectedItem = (data) => {
                const {code, image, name} = data;

                return `<input type="hidden" name="composition_seq[]" value="${code}"><span style="background-image: url('${image}')"></span><strong>${name}</strong><button type="button" value="${code}"><span>삭제</span></button>`;
            };

            [...materialsSelectedList.querySelectorAll('input')].forEach(input => selectedListCodes.add(input.value.trim()));

            category.addEventListener('click', toggleMenuList);
            searchButton.addEventListener('click', changeSearchList);
            itemsInputs.forEach(item => item.addEventListener('change', changeItem));
            selectedListItemsContainer.addEventListener('click', deleteSelectedList);
            selectedListRegister.addEventListener('click', registerSelectedList);
            materialsSelectedList.addEventListener('click', deleteMaterialsSelectedList);
            modal.addEventListener('show.bs.modal', resetModal);
        })();
    </script>
</section>
</body>
</html>