<?php
    include_once dirname(__FILE__) . '/../conf/function.php';
    switch ($_REQUEST['action'])
    {
        case 'create':
            $basic_arr = array("area_name", "network_segment", "start_IP", "end_IP",
                            "lease_time", "interface", "router", "dns", "mx_host", "ntp",
                            "tftp_enable", "tftp_server", "tftp_root", "boot_file");
            foreach ($basic_arr as $k) {
                if (isset($_REQUEST[$k])) {
                    ${$k} = $_REQUEST[$k];
                }
                else {
                    ${$k} = "";
                }
            }
            $AI = new Area_Info();
            foreach ($basic_arr as $k) {
                $AI->$k = ${$k};
            }
            $AI->saveIt();
            break;  
        case 'delete':
            $AI = new Area_Info();
            $AI->getIt($_REQUEST['area_name']);
            $AI->delIt();
            break;
        case 'update';
            break;
        case 'change';
            $AI = new Area_Info();
            $AI->getIt($_REQUEST['area_name']);
            if (isset($_REQUEST['service'])) {
                $AI->service = $_REQUEST['service'];
            }
            if (isset($_REQUEST['tftp_enable'])) {
                $AI->tftp_enable = $_REQUEST['tftp_enable'];
            }
            $AI->change = $_REQUEST['change'];
            $AI->saveIt();
            break;
        default:
    }
?>
