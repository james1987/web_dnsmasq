<?php
    include_once dirname(__FILE__) . '/../conf/function.php';
    switch ($_REQUEST['action'])
    {
        case 'create':
            $domain_attr = array("area_name", "network_segment", "start_IP", "end_IP", "service", "change",
                            "lease_time", "interface", "router", "dns", "mx_host", "ntp",
                            "tftp_enable", "tftp_server", "tftp_root", "boot_file");
            foreach ($domain_attr as $k) {
                if (isset($_REQUEST[$k])) {
                    ${$k} = $_REQUEST[$k];
                }
                else {
                    ${$k} = "";
                }
            }
            $AI = new Area_Info();
            foreach ($domain_attr as $k) {
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
        case 'add_host_map';
            $host_map_attr = array("owner_by", "hostname", "mac_addr", "ip_addr");
            foreach ($host_map_attr as $k) {
                if (isset($_REQUEST[$k])) {
                    ${$k} = $_REQUEST[$k];
                }
                else {
                    ${$k} = "";
                }
            }
            $HM = new Host_Map();
            foreach ($host_map_attr as $k) {
                $HM->$k = ${$k};
            }
            $HM->saveIt();
            break;
        case 'delete_host_map':
            $HM = new Host_Map();
            $HM->getIt($_REQUEST['host_name']);
            $HM->delIt();
            break;
        default:
    }
?>
