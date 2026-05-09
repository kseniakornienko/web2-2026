<?php
function getMenuItems($db, $parent_id = null) {
    $stmt = $db->prepare("SELECT * FROM menu WHERE parent_id IS :parent_id ORDER BY order_index");
    $stmt->bindValue(':parent_id', $parent_id, $parent_id === null ? SQLITE3_NULL : SQLITE3_INTEGER);
    $result = $stmt->execute();
    $items = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $items[] = $row;
    }
    return $items;
}

function renderMenu($db, $parent_id = null, $level = 0) {
    $items = getMenuItems($db, $parent_id);
    if (empty($items)) return '';

    $html = '';
    foreach ($items as $item) {
        $hasChildren = !empty(getMenuItems($db, $item['id']));
        $itemClass = $hasChildren ? 'list-item list-item_open' : 'list-item';
        $parentAttr = $hasChildren ? 'data-parent' : '';
        
        $arrowHtml = $hasChildren ? 
            '<img class="list-item__arrow" src="img/chevron-down.png" alt="chevron-down">' : 
            '<div class="list-item__arrow" style="visibility: hidden; width: 1em;"></div>';
        
        $itemsHtml = '';
        if ($hasChildren) {
            $itemsHtml = '<div class="list-item__items">' . renderMenu($db, $item['id'], $level + 1) . '</div>';
        }
        
        $html .= "
            <div class=\"$itemClass\" $parentAttr>
                <div class=\"list-item__inner\">
                    $arrowHtml
                    <img class=\"list-item__folder\" src=\"img/folder.png\" alt=\"folder\">
                    <span>" . htmlspecialchars($item['name']) . "</span>
                </div>
                $itemsHtml
            </div>
        ";
    }
    return $html;
}
?>