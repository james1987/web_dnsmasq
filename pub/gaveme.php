<?php
    require dirname(__FILE__) . '/../conf/function.php';
//    header('Access-Control-Allow-Origin: http://where.com');
//    header('Access-Control-Allow-Headers: POWERED-BY-MENGXIANHUI');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Content-Type: text/plain');
    $Full_URL = $_SERVER['REQUEST_URI'];
    $args = array("how","host_s");
    exec("/sbin/ip addr show|grep 'inet '|awk '{print $2}'",$net_segs);
    exec("/sbin/ifconfig | grep '^[a-z]' | awk '{print $1}'",$net_devs);
    $router = exec("/sbin/route -n|grep '^0.0.0.0'|awk '{print $2}'");
    $gi_arr = array(
            "net_segs" => $net_segs,
            "net_devs" => $net_devs,
            "router" => $router
        );

    foreach ($args as $var) {
        ${$var} = Null;
        if (isset($_REQUEST[$var])) {
            if ("how" == $var) {
                ${$var} = $_REQUEST[$var];
                continue;
            }
            ${$var} = explode(",",$_REQUEST[$var]);
        }
    }

    switch ($how)
    {
        case 'get_gi':
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
        case 'get_all_host':
            if (Null == $range_ts[0] or Null == $range_ts[1]) {
                echo "请指定start_ts及end_ts";
                break;
            }
            echo json_encode(get_user_count_info($range_ts[0],$range_ts[1]));
            break;
        default:
    }
?>
