<?php
require dirname(__FILE__) . '/../conf/function.php';
$hostname = exec("hostname");
$domain_name = exec("hostname -d");
$main_cf = '/etc/dnsmasq.conf';
$conf_dir = '/etc/dnsmasq.d/';
$HM_dir = '/etc/dnsmasq.HM.d/';
$expand_hosts_dir = '/etc/dnsmasq.Hs.d/';
$pid_file = '/var/run/dnsmasq.pid';

if (!is_file($main_cf . '.bak')) {
    copy($main_cf,$main_cf . '.bak');
}
if(!is_dir($HM_dir)) {
    mkdir($HM_dir);
}
if(!is_dir($expand_hosts_dir)) {
    mkdir($expand_hosts_dir);
}

$l_r = new Redis();
$l_r->connect($conf['redis_host'], $conf['redis_port'], $conf['redis_timeout']);
$area = get_areas()[0];
$AI = new Area_Info();
$HM = new Host_Map();
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
    $HM_file = $HM_dir . $AI->area_name . '.hm';
    $expand_hosts_file = $expand_hosts_dir . $AI->area_name . '.hm';

    $file = fopen($main_cf, 'w');
    fwrite($file,"addn-hosts=" . $expand_hosts_dir . "\r\n");
    fwrite($file,"conf-dir=" . $conf_dir . "\r\n");
    fwrite($file,"dhcp-hostsfile=" . $HM_dir . "\r\n");
    fclose($file);

    $HMs = get_hosts($AI->area_name);
    $net_seg = explode('/',$AI->network_segment)[0];
    $net_seg = str_replace(explode('.',$net_seg)[3],'',$net_seg);
    $mask_len = explode('/',$AI->network_segment)[1];
    $dhcp_range = 'dhcp-range=' . $net_seg . $AI->start_IP . ',' . $net_seg . $AI->end_IP . ',' . get_mask($mask_len) . ',' . $AI->lease_time . "\r\n";
    $dhcp_router = 'dhcp-option=3,' . $AI->router . "\r\n";
    $dhcp_dns = 'dhcp-option=6,' . $AI->dns . "\r\n";
    $dhcp_domain = 'dhcp-option=15,' . $domain_name . "\r\n";
    $dhcp_ntp = 'dhcp-option=42,' . $AI->ntp . "\r\n";
    $mx_host = 'mx-host=' . $domain_name . ',' . $AI->mx_host . ',50' . "\r\n";
    $tftp_server = 'dhcp-option=66,' . $AI->tftp_server . "\r\n";
    $boot_file = 'dhcp-option=67,' . $AI->boot_file . "\r\n";
    $tftp_root = 'tftp-root=' . $AI->tftp_root . "\r\n";
    $file = fopen($conf_file, 'w');
    fwrite($file,$dhcp_range);
    fwrite($file,$dhcp_router);
    fwrite($file,$dhcp_dns);
    fwrite($file,$dhcp_domain);
    if ('on' == $AI->tftp_enable) {
        fwrite($file,"enable-tftp\r\n");
        fwrite($file,$tftp_server);
        fwrite($file,$boot_file);
        fwrite($file,$tftp_root);
    }
    fclose($file);

    $file = fopen($HM_file, 'w');
    foreach ( $HMs as $host_name) {
        $HM->getIt($host_name);
        $mac_ip = (string) $HM->mac_addr . "," . (string) $HM->ip_addr . "\r\n";
        echo $mac_ip;
        fwrite($file,$mac_ip);
    }
    fclose($file);

    $file = fopen($expand_hosts_file, 'w');
    foreach ( $HMs as $host_name) {
        $HM->getIt($host_name);
        $ip_hostname = (string) $HM->ip_addr . "\t" . $HM->hostname . "\r\n";
        echo $ip_hostname;
        fwrite($file,$ip_hostname);
    }
    fclose($file);

    $AI->change = 'N';
    $AI->saveIt();
    $AI->getIt($area);
    print_r($AI);

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
