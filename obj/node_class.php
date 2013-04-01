<?php
class Node {
/* --------------------------BASIC------------------------- */
    var $node_name;
    var $node_info;
    var $node_info_xml;
    var $hypervisor_info;
    var $libvirtd_info;
    var $VMs;

    function getMembers() {
        return get_class_vars(__CLASS__);
    }
    function getMembers_value() {
        return get_object_vars($this);
    }

    function getIt($node_name, $vm_type = "qemu", $conn_type = "ssh", $conn_user = "root") {
        $this->node_name = $node_name;
        $conn = libvirt_connect($vm_type . '+' . $conn_type . '://' . $conn_user . '@' . $node_name . '/system', false);
        $this->node_info = libvirt_node_get_info($conn);
        $this->node_info_xml = libvirt_connect_get_sysinfo($conn);
        $this->hypervisor_info = libvirt_connect_get_hypervisor($conn);
        $this->libvirtd_info = libvirt_connect_get_information($conn);
//        var_dump(libvirt_node_get_cpu_stats_for_each_cpu($conn));
//        $doms = libvirt_list_domains($conn);
        $dm_res = libvirt_list_domain_resources($conn);
//        var_dump(libvirt_list_nodedevs($conn));
        foreach ($dm_res as $dm_r) {
            array_push($VMs, libvirt_domain_get_xml_desc($dm_r, NULL));
//            var_dump(libvirt_domain_get_info($dm_r));
        }
        unset($conn);
    }
}
?>
