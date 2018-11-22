<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function cost_formatting($cost) {
    $cost = ceil($cost);
    if ($cost >= 1000) {
        $cost = number_format($cost,0,'',' ');
    }
    $cost .= " ₽";
    return $cost;
}
?>
