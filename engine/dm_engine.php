<?php
require dirname(__FILE__) . '/../conf/function.php';
$hostname = exec("hostname");
$conf_dir = '/etc/dnsmasq.d/';

$l_r = new Redis();
$l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
$area = get_areas()[0];
$AI = new Area_Info();
while (true) {
    $AI->getIt($area);
    if ($AI->change != 'Y') { usleep(1000000); continue; }
    $conf_file = $conf_dir . $AI->area_name . '.conf';
    $net_seg = explode('/',$AI->network_segment)[0];
    $net_seg = str_replace(explode('.',$net_seg)[3],'',$net_seg);
    $mask_len = explode('/',$AI->network_segment)[1];
    $dhcp_range = 'dhcp-range=' . $net_seg . $AI->start_IP . ',' . $net_seg . $AI->end_IP . ',' . get_mask($mask_len) . ',12h' . "\r\n";
    $file = fopen($conf_file, 'w');
    fwrite($file,$dhcp_range);
#dhcp-option=3,1.0.0.1
#dhcp-option=6,1.0.0.1
#dhcp-option=15,lab.jim
    print $conf_file;
    print $dhcp_range;
    $AI->change = 'N';
    $AI->saveIt();
    $AI->getIt($area);
    print_r($AI);
    fclose($file);
}
$l_r->close();
?>
