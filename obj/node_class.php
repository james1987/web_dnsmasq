<?php
class Node {
/* --------------------------BASIC------------------------- */
    var $node_name;
    var $node_info;
    var $node_info_xml;
    var $hypervisor_info;
    var $libvirtd_info;
    var $VMs = array();

    function getMembers() {
        return get_class_vars(__CLASS__);
    }
    function getMembers_value() {
        return get_object_vars($this);
    }

    function getIt($node_name, $vm_type = "qemu", $conn_type = "libssh2", $conn_user = "root") {
        $this->node_name = $node_name;
        if (pingAddress($node_name)) {
            $conn = libvirt_connect($vm_type . '+' . $conn_type . '://' . $conn_user . '@' . $node_name . '/system', false);
//            $conn = libvirt_connect('qemu+libssh2://root@192.168.0.252/system', false);
            $this->node_info = libvirt_node_get_info($conn);
            $this->node_info_xml = simplexml_load_string(libvirt_connect_get_sysinfo($conn));
            $this->hypervisor_info = libvirt_connect_get_hypervisor($conn);
            $this->libvirtd_info = libvirt_connect_get_information($conn);
            $dm_res = libvirt_list_domain_resources($conn);
            foreach ($dm_res as $dm_r) {
                array_push($this->VMs, simplexml_load_string(libvirt_domain_get_xml_desc($dm_r, NULL)));
            }
            unset($conn);
        }
    }
}
?>
