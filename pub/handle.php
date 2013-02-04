<?php
    include_once dirname(__FILE__) . '/../conf/function.php';
/* --------------------------BASIC------------------------- */
    $basic_arr = array("area_name", "network_segment", "start_IP", "end_IP");

    foreach ($basic_arr as $k) {
        if (isset($_REQUEST[$k])) {
            ${$k} = $_REQUEST[$k];
        }
        else {
            ${$k} = "";
        }
    }
/* --------------------------EXTEND------------------------- */
$lease_time;
$interface;
$router;
$dns;
$mx_host;
$ntp;
/* --------------------------EXTEND=>TFTP------------------------- */
$tftp_enable = false;
$tftp_server;
$tftp_root;
$boot_file;

    $AI = new Area_Info();
    foreach ($basic_arr as $k) {
        $AI->$k = ${$k};
    }
    var_dump($AI->getMembers_value());

?>
