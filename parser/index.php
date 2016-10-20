<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Демонстрационная версия продукта «1С-Битрикс: Управление сайтом»");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная страница");
?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQ1lSDmG_hz6fMzGi199o4erCyGZHe3h8" type="text/javascript"></script>

<?$APPLICATION->IncludeComponent("bitrix:news.list", "hp-top-slide", Array(
    "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
    "AJAX_MODE" => "N",	// Включить режим AJAX
    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
    "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
    "CACHE_TYPE" => "A",	// Тип кеширования
    "CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
    "DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
    "DISPLAY_DATE" => "Y",	// Выводить дату элемента
    "DISPLAY_NAME" => "Y",	// Выводить название элемента
    "DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
    "DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
    "FIELD_CODE" => array(	// Поля
        0 => "ID",
        1 => "CODE",
        2 => "XML_ID",
        3 => "NAME",
        4 => "TAGS",
        5 => "SORT",
        6 => "PREVIEW_TEXT",
        7 => "PREVIEW_PICTURE",
        8 => "DETAIL_TEXT",
        9 => "DETAIL_PICTURE",
        10 => "DATE_ACTIVE_FROM",
        11 => "ACTIVE_FROM",
        12 => "DATE_ACTIVE_TO",
        13 => "ACTIVE_TO",
        14 => "SHOW_COUNTER",
        15 => "SHOW_COUNTER_START",
        16 => "IBLOCK_TYPE_ID",
        17 => "IBLOCK_ID",
        18 => "IBLOCK_CODE",
        19 => "IBLOCK_NAME",
        20 => "IBLOCK_EXTERNAL_ID",
        21 => "DATE_CREATE",
        22 => "CREATED_BY",
        23 => "CREATED_USER_NAME",
        24 => "TIMESTAMP_X",
        25 => "MODIFIED_BY",
        26 => "USER_NAME",
        27 => "",
    ),
    "FILTER_NAME" => "",	// Фильтр
    "HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
    "IBLOCK_ID" => "1",	// Код информационного блока
    "IBLOCK_TYPE" => "-",	// Тип информационного блока (используется только для проверки)
    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
    "INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
    "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
    "NEWS_COUNT" => "20",	// Количество новостей на странице
    "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
    "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
    "PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
    "PAGER_TITLE" => "Новости",	// Название категорий
    "PARENT_SECTION" => "",	// ID раздела
    "PARENT_SECTION_CODE" => "",	// Код раздела
    "PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
    "PROPERTY_CODE" => array(	// Свойства
        0 => "",
        1 => "",
    ),
    "SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
    "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
    "SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
    "SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
    "SET_STATUS_404" => "N",	// Устанавливать статус 404
    "SET_TITLE" => "N",	// Устанавливать заголовок страницы
    "SHOW_404" => "N",	// Показ специальной страницы
    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
    "COMPONENT_TEMPLATE" => ".default"
),
    false
);?>


<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    ".default",
    array(
        // region Параметры компонента
        "AREA_FILE_SHOW"    =>  "file",  // Показывать включаемую область : array ( 'page' => 'для страницы', 'sect' => 'для раздела', )
        "AREA_FILE_SUFFIX"  =>  "",   // Суффикс имени файла включаемой области
        "EDIT_TEMPLATE"     =>  "",      // Шаблон области по умолчанию : array ( 'standard.php' => '[standard.php] Стандартная страница', )
        "PATH" => "/inc/inc_kvart_hp.php"
    )
);?>




    <section>
        <div class="s-container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.detail",
                "gal-hp",
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_ELEMENT_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "Y",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "BROWSER_TITLE" => "-",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_CODE" => "",
                    "ELEMENT_ID" => "1",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "ID",
                        2 => "CODE",
                        3 => "XML_ID",
                        4 => "NAME",
                        5 => "TAGS",
                        6 => "SORT",
                        7 => "PREVIEW_TEXT",
                        8 => "PREVIEW_PICTURE",
                        9 => "DETAIL_TEXT",
                        10 => "DETAIL_PICTURE",
                        11 => "DATE_ACTIVE_FROM",
                        12 => "ACTIVE_FROM",
                        13 => "DATE_ACTIVE_TO",
                        14 => "ACTIVE_TO",
                        15 => "SHOW_COUNTER",
                        16 => "SHOW_COUNTER_START",
                        17 => "IBLOCK_TYPE_ID",
                        18 => "IBLOCK_ID",
                        19 => "IBLOCK_CODE",
                        20 => "IBLOCK_NAME",
                        21 => "IBLOCK_EXTERNAL_ID",
                        22 => "DATE_CREATE",
                        23 => "CREATED_BY",
                        24 => "CREATED_USER_NAME",
                        25 => "TIMESTAMP_X",
                        26 => "MODIFIED_BY",
                        27 => "USER_NAME",
                        28 => "",
                    ),
                    "IBLOCK_ID" => "2",
                    "IBLOCK_TYPE" => "sliders",
                    "IBLOCK_URL" => "",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                    "MESSAGE_404" => "",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Страница",
                    "PROPERTY_CODE" => array(
                        0 => "LINK",
                        1 => "PIC",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_CANONICAL_URL" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "USE_PERMISSIONS" => "N",
                    "USE_SHARE" => "N",
                    "COMPONENT_TEMPLATE" => "gal-hp"
                ),
                false
            );?>
        </div>
    </section>

<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    ".default",
    array(
        // region Параметры компонента
        "AREA_FILE_SHOW"    =>  "file",  // Показывать включаемую область : array ( 'page' => 'для страницы', 'sect' => 'для раздела', )
        "AREA_FILE_SUFFIX"  =>  "",   // Суффикс имени файла включаемой области
        "EDIT_TEMPLATE"     =>  "",      // Шаблон области по умолчанию : array ( 'standard.php' => '[standard.php] Стандартная страница', )
        "PATH" => "/inc/inc_structure.php"
    )
);?>


<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    ".default",
    array(
        // region Параметры компонента
        "AREA_FILE_SHOW"    =>  "file",  // Показывать включаемую область : array ( 'page' => 'для страницы', 'sect' => 'для раздела', )
        "AREA_FILE_SUFFIX"  =>  "",   // Суффикс имени файла включаемой области
        "EDIT_TEMPLATE"     =>  "",      // Шаблон области по умолчанию : array ( 'standard.php' => '[standard.php] Стандартная страница', )
        "PATH" => "/inc/inc_hp_about.php"
    )
);?>

<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    ".default",
    array(
        // region Параметры компонента
        "AREA_FILE_SHOW"    =>  "file",  // Показывать включаемую область : array ( 'page' => 'для страницы', 'sect' => 'для раздела', )
        "AREA_FILE_SUFFIX"  =>  "",   // Суффикс имени файла включаемой области
        "EDIT_TEMPLATE"     =>  "",      // Шаблон области по умолчанию : array ( 'standard.php' => '[standard.php] Стандартная страница', )
        "PATH" => "/inc/inc_beach.php"
    )
);?>


<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    ".default",
    array(
        // region Параметры компонента
        "AREA_FILE_SHOW"    =>  "file",  // Показывать включаемую область : array ( 'page' => 'для страницы', 'sect' => 'для раздела', )
        "AREA_FILE_SUFFIX"  =>  "",   // Суффикс имени файла включаемой области
        "EDIT_TEMPLATE"     =>  "",      // Шаблон области по умолчанию : array ( 'standard.php' => '[standard.php] Стандартная страница', )
        "PATH" => "/inc/inc_hp_control_comp.php"
    )
);?>







<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>