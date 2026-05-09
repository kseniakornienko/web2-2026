<?php
function renderMenu(array $items): string {
    $output = '<ul>';
    foreach ($items as $item) {
        $output .= '<li>';
        $output .= '<a href="' . htmlspecialchars($item['link'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</a>';
        if (!empty($item['children']) && is_array($item['children'])) {
            $output .= renderMenu($item['children']);
        }
        $output .= '</li>';
    }
    $output .= '</ul>';
    return $output;
}

echo '<nav>' . renderMenu($menus) . '</nav>';


