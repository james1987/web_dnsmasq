<?php
class Host_Map {
/* --------------------------BASIC------------------------- */
    var $owner_by;
    var $hostname;
    var $mac_addr;
    var $ip_addr;

    function getMembers() {
        return get_class_vars(__CLASS__);
    }
    function getMembers_value() {
        return get_object_vars($this);
    }

    function getIt($hostname) {
        global $conf;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
        $elements = $l_r->hGetAll('H_' . $hostname . '_MAP');
        $l_r->close();
        if ( Null == $elements ) {
            die ("The host name " . $hostname . " don't exist in this system,\n
                  Please check out your host name.");
            exit;
        }
        foreach ( $this->getMembers() as $k => $v ) {
            if ( "hostname" == $k ) {
                $this->hostname=$hostname;
                continue;
            }
            $this->$k = $elements[$k];
        }
    }

    function saveIt() {
        global $conf;
        $owner_by = $this->owner_by;
        $hostname = $this->hostname;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);

        try {
            if ( !in_array($hostname, get_hosts($owner_by)) ) {
                $l_r->zAdd('HOSTS_' . $owner_by . '_TABLE', time(), $hostname);
            }
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

        if ( !$l_r->hMset('H_' . $hostname . '_MAP', $this->getMembers_value()) ) {
            die ("Write hostname to H_ " . $hostname . "_MAP failed,\n
                  Verify your redis server.");
            exit;
        }
        $l_r->close();
    }

    function delIt() {
        global $conf;
        $owner_by = $this->owner_by;
        $hostname = $this->hostname;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
        $l_r->zDelete('HOSTS_' . $owner_by . '_TABLE', $hostname);
        $l_r->del('H_' . $hostname . '_MAP');
        $l_r->close();
    }
}
?>
