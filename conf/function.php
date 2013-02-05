<?php
define("AREAS_TABLE","Z_AREAS");
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
function get_areas() {
    global $conf;
    $l_r = new Redis();
    $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
    return $l_r->zRange(constant("AREAS_TABLE"), 0, -1);
    $l_r->close();
}

include_once dirname(__FILE__) . '/../obj/area_info_class.php';
?>
