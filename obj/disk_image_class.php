<?php
class Disk_Image {
/* --------------------------BASIC------------------------- */
    var $img_name;
    var $size;
    var $format;
    var $owner_by_host;

    function getMembers() {
        return get_class_vars(__CLASS__);
    }
    function getMembers_value() {
        return get_object_vars($this);
    }

    function getIt($img_name) {
        global $conf;
        $l_r = new Redis();
        $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
        $elements = $l_r->hGetAll('H_' . $img_name . '_DI');
        $l_r->close();
        if ( Null == $elements ) {
            die ("The disk image " . $img_name . " don't exist in this system,\n
                  Please check out your disk image.");
            exit;
        }
        foreach ( $this->getMembers() as $k => $v ) {
            if ( "name" == $k ) {
                $this->name=$img_name;
                continue;
            }
            $this->$k = $elements[$k];
        }
    }

    function saveIt() {
        global $conf,$libvirt_conf;
        $img_name = $this->img_name;
        $create_img_ok = false;
        if(!file_exists($libvirt_conf['libvirt.iso_path'] . "/" . $this->img_name)) {
            $conn = libvirt_connect('qemu:///session', false);
            $h_name = libvirt_image_create($conn, $this->img_name, $this->size, $this->format);
            unset($conn);
            if ($h_name) {
                $create_img_ok = true;
            }
        }
        else {
            die("Image " . $this->img_name . " exist!");
        }

        if ($create_img_ok) {
            $l_r = new Redis();
            $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);

            try {
                if ( !in_array($img_name, get_disk_pool()) ) {
                    $l_r->zAdd(constant("DISK_POOL"), time(), $img_name);
                }
            }
            catch(Exception $e) {
                echo 'Message: ' .$e->getMessage();
            }

            if ( !$l_r->hMset('H_' . $img_name . '_DI', $this->getMembers_value()) ) {
                die ("Write disk image to H_ " . $img_name . "_DI failed,\n
                      Verify your redis server.");
                exit;
            }
            $l_r->close();
        }
    }

    function delIt() {
        global $conf,$libvirt_conf;
        $img_name = $this->img_name;
        $delete_img_ok = false;
        if(unlink($libvirt_conf['libvirt.iso_path'] . "/" . $this->img_name)) {
            $delete_img_ok = true;
        }

        if ($delete_img_ok) {
            $l_r = new Redis();
            $l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
            $l_r->zDelete(constant("DISK_POOL"), $img_name);
            $l_r->del('H_' . $img_name . '_DI');
            $l_r->close();
        }
    }
}
?>
