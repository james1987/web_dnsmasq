<?php
class Area_Info {
/* --------------------------BASIC------------------------- */
    var $area_name;
    var $start_addr = "100";
    var $end_addr = "200";
/* --------------------------EXTEND------------------------- */
    var $netmask;
    var $lease_time;
    var $interface;
    var $router;
    var $dns;
    var $mx_host;
    var $ntp;
/* --------------------------EXTEND=>TFTP------------------------- */
    var $tftp_enable = false;
    var $tftp_server;
    var $tftp_root;
    var $boot_file;

    function getMembers() {
        return get_class_vars(__CLASS__);
    }

    function getIt($area_name) {
        global $conf;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
        $elements = l_r.hGetAll('H_' . $area_name . '_INFO');
        $l_r->close();
        if ( Null == $elements ) {
            die ("The area " . $area_name . "don't exist in this system,\n
                  Please check out your area name.");
            exit;
        }
        foreach ( $this->getMembers() as $k => $v ) {
            if ( "area_name" == $k ) {
                $this->area_name=$area_name;
                continue;
            }
            $this->$k = $elements[$k];
        }
    }

    function saveIt() {
        global $conf;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
        if( !$l_r->rPush(constant("AREAS_TABLE"), $area_name) ) {
            die ("Write " . $area_name . " to " . constant("AREAS_TABLE") . " failed,\n
                  Please verify your redis server is runing, and can connect it.");
            exit;
        }
        if ( !$l_r->hMset('H_' . $area_name . '_INFO', $this->getMembers()) ) {
            die ("Write area_info to H_ " . $area_name . "_INFO failed,\n
                  Verify your redis server.");
            exit;
        }
        $l_r->close();
    }
}
?>
