<?php
    require dirname(__FILE__) . '/../conf/function.php';
//    header('Access-Control-Allow-Origin: http://where.com');
//    header('Access-Control-Allow-Headers: POWERED-BY-MENGXIANHUI');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Content-Type: text/plain');
    $Full_URL = $_SERVER['REQUEST_URI'];
    $args = array("how","host_s","owner_by","node_s");
    if (file_exists("/sbin/ifconfig")) {
        exec("/sbin/ifconfig | grep '^[a-z]' | awk '{print $1}'",$net_devs);
    }
    else {
        exec("/bin/ifconfig | grep '^[a-z]' | awk -F \"[\':\t]\" '{print $1}'",$net_devs);
    }
    if (file_exists("/sbin/route")) {
        $router = exec("/sbin/route -n|grep '^0.0.0.0'|awk '{print $2}'");
    }
    else {
        $router = exec("/bin/route -n|grep '^0.0.0.0'|awk '{print $2}'");
    }
    if (file_exists("/sbin/ip")) {
        exec("/sbin/ip addr show|grep 'inet '|awk '{print $2}'",$net_segs);
    }
    else {
        exec("/bin/ip addr show|grep 'inet '|awk '{print $2}'",$net_segs);
    }
    $domain_name = exec("hostname -d");
    $gi_arr = array(
            "net_segs" => $net_segs,
            "net_devs" => $net_devs,
            "router" => $router,
            "domain_name" => $domain_name,
            "iso_path" => $libvirt_conf['libvirt.iso_path'],
            "image_path" => $libvirt_conf['libvirt.image_path'],
            "max_connections" => $libvirt_conf['libvirt.max_connections']
        );

    foreach ($args as $var) {
        ${$var} = Null;
        if (isset($_REQUEST[$var])) {
            if ("how" == $var or "owner_by" == $var) {
                ${$var} = rtrim($_REQUEST[$var], ',');
                continue;
            }
            ${$var} = explode(",",rtrim($_REQUEST[$var], ','));
        }
    }

    switch ($how)
    {
        case 'get_gi':
            $gi_arr = array_merge($gi_arr,array('areas' => get_areas()));
            echo json_encode($gi_arr);
            break;
        case 'get_AIs':
            $tmp_AI_arr = array();
            foreach(get_areas() as $area) {
                $AI = new Area_Info();
                $AI->getIt($area);
                array_push($tmp_AI_arr,$AI);
            }
            echo json_encode($tmp_AI_arr);
            break;
        case 'get_all_HM':
            $tmp_HOST_arr = array();
            foreach (get_hosts($owner_by) as $host_name) {
                $HM = new Host_Map();
                $HM->getIt($host_name);
                array_push($tmp_HOST_arr,$HM);
            }
            echo json_encode($tmp_HOST_arr);
            break;
        case 'get_all_host':
            echo json_encode(get_hosts($owner_by));
            break;
        case 'get_disk_pool':
            $tmp_DISK_POOL = array();
            foreach (get_disk_pool() as $img_name) {
                $DI = new Disk_Image();
                $DI->getIt($img_name);
                array_push($tmp_DISK_POOL,$DI);
            }
            echo json_encode($tmp_DISK_POOL);
            break;
        case 'get_vi':
            $tmp_NODES = array();
            if (Null == $node_s) {
                echo 'You need post node_s parameter';
                exit;
            }
            foreach ($node_s as $node) {
            #    $NODE = new Node();
            #    $NODE->getIt($node);
            #    array_push($tmp_NODES,$NODE);
                array_push($tmp_NODES,$node);
            }
            echo json_encode($tmp_NODES);
            break;
        default:
    }
?>
