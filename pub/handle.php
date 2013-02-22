<?php
    include_once dirname(__FILE__) . '/../conf/function.php';
    switch ($_REQUEST['action'])
    {
        case 'create':
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
            $AI->saveIt();
            break;  
        case 'del':
            break;
        case 'update';
            break;
        case 'change';
            $AI = new Area_Info();
            $AI->getIt($_REQUEST['area_name']);
            $AI->service = $_REQUEST['service'];
            $AI->saveIt();
            break;
        default:
    }
?>
