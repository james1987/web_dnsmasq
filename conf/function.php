<?php
define("AREAS_TABLE","Z_AREAS");
define("HOSTS_TABLE","Z_HOSTS_AREA_TABLE");
define("DISK_POOL","Z_DISK_POOL_TABLE");
$conf = dirname(__FILE__) . '/db_info.ini';
$conf = parse_ini_file($conf);
$libvirt_conf = '/etc/php/fpm-php5.4/ext/libvirt-php.ini';
$libvirt_conf = parse_ini_file($libvirt_conf);
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
function get_mask($num) {
    $b_0 = '.0';
    $b_1 = '.128';
    $b_2 = '.192';
    $b_3 = '.224';
    $b_4 = '.240';
    $b_5 = '.248';
    $b_6 = '.252';
    $b_7 = '.254';
    $b_8 = '.255';
    $mask = '';
    for ($i = 0; $i < 4 ; $i++) {
        $num -= 8;
        if ($num > 0) {
            $mask .= $b_8;
        }
        else {
            $mask .= ${'b_' . (string) (8-abs($num))};
            $num = 0;
        }
    }
    return ltrim($mask,'.');
}
function pingAddress($ip) {
    $pingresult = shell_exec("/usr/sbin/fping $ip");
    $is_alive = "is alive";
    $deadoralive = strpos($pingresult, $is_alive);
    if ($deadoralive == false)
    {
        return false;
//        echo "The IP address, $ip, is dead";
    }
    else
    {
        return true;
//        echo "The IP address, $ip, is alive";
    }
}
function get_areas() {
    global $conf;
    $l_r = new Redis();
    $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
    return $l_r->zRange(constant("AREAS_TABLE"), 0, -1);
    $l_r->close();
}

function get_hosts($owner_by) {
    global $conf;
    $l_r = new Redis();
    $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
    return $l_r->zRange(str_replace("AREA",$owner_by,constant("HOSTS_TABLE")), 0, -1);
    $l_r->close();
}

function get_disk_pool() {
    global $conf;
    $l_r = new Redis();
    $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
    return $l_r->zRange(constant("DISK_POOL"), 0, -1);
    $l_r->close();
}

include_once dirname(__FILE__) . '/../obj/area_info_class.php';
include_once dirname(__FILE__) . '/../obj/host_map_class.php';
include_once dirname(__FILE__) . '/../obj/disk_image_class.php';
include_once dirname(__FILE__) . '/../obj/node_class.php';
?>
