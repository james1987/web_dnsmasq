<?php
require dirname(__FILE__) . '/../conf/function.php';
$hostname = exec("hostname");
$domain_name = exec("hostname -d");
$conf_dir = '/etc/dnsmasq.d/';
$pid_file = '/var/run/dnsmasq.pid';

$l_r = new Redis();
$l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
$area = get_areas()[0];
$AI = new Area_Info();
while (true) {
    $AI->getIt($area);
    if ($AI->change != 'Y') { usleep(1000000); continue; }
    if ('' == $AI->lease_time) {
        $AI->lease_time = '12h';
    }
    if ('' == $AI->router) {
        $AI->router = exec("route -n|grep '^0.0.0.0'|awk '{print $2}'");
    }
    if ('' == $AI->dns) {
        $AI->dns = $AI->router;
    }
    $conf_file = $conf_dir . $AI->area_name . '.conf';
    $net_seg = explode('/',$AI->network_segment)[0];
    $net_seg = str_replace(explode('.',$net_seg)[3],'',$net_seg);
    $mask_len = explode('/',$AI->network_segment)[1];
    $dhcp_range = 'dhcp-range=' . $net_seg . $AI->start_IP . ',' . $net_seg . $AI->end_IP . ',' . get_mask($mask_len) . ',' . $AI->lease_time . "\r\n";
    $dhcp_router = 'dhcp-option=3,' . $AI->router . "\r\n";
    $dhcp_dns = 'dhcp-option=6,' . $AI->dns . "\r\n";
    $dhcp_domain = 'dhcp-option=15,' . $domain_name . "\r\n";
    $file = fopen($conf_file, 'w');
    fwrite($file,$dhcp_range);
    fwrite($file,$dhcp_router);
    fwrite($file,$dhcp_dns);
    fwrite($file,$dhcp_domain);
    $AI->change = 'N';
    $AI->saveIt();
    $AI->getIt($area);
    print_r($AI);
    fclose($file);

    if ('on' == $AI->service) {
        if (file_exists($pid_file)) {
            exec("killall -s HUP dnsmasq");
        }
        else {
            exec("/etc/init.d/dnsmasq start");
        }
    }
    else {
        if (file_exists($pid_file)) {
            exec("/etc/init.d/dnsmasq stop");
        }
    }
}
$l_r->close();
?>
