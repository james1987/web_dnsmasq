<?php
define("AREAS_TABLE","L_AREAS");
$conf = dirname(__FILE__) . '/db_info.ini';
$conf = parse_ini_file($conf);
$today = date("Y-m-d");
$mirco_sec = get_mirco();
$now_time = date("H:i:s");

function forbidden() {
        header("HTTP/1.0 403 Forbidden");
        exit;
}
function notFound() {
        header("HTTP/1.0 404 Not Found");
        exit;
}
function get_mirco() {
    list($usec, $sec) = explode(" ", microtime());
    return  $sec . substr(explode(".", $usec)[1],0,-2);
}

include_once dirname(__FILE__) . '/../obj/area_info_class.php';
?>
