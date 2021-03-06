<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<title>web_dnsmasq</title>
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.css" />
<link rel="stylesheet" href="css/main.css" />
<script src="js/jquery-1.8.2.js"></script>
<script src="js/jquery.mobile-1.2.0.js"></script>
<script src="js/main.js"></script>
</head>
<body>
<div data-role="page" id="main">

    <div data-role="header">
        <h1>Web_Dnsmasq</h1>
    </div><!-- /header -->

    <div data-role="content">   
        <h3>Overview:</h3>
        <div id="overview" data-role="collapsible-set">
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <p><a href="#create_panel" data-role="button" data-rel="dialog" data-transition="pop">Create</a></p>
            </div>
            <div class="ui-block-b">
                <p><a href="#delete_panel" data-role="button" data-rel="dialog" data-transition="pop">Delete</a></p>
            </div>
        </div>
    </div><!-- /content -->
    <input type="hidden" name="current_domain" id="current_domain" value="" />
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->

<!-- Start of second page -->
<div data-role="page" id="create_panel" data-title="Create Panel">
    <div data-role="header">
        <h1>Create a domain</h1>
        <a id="button_save" data-icon="check" data-theme="b">SAVE</a>
    </div><!-- /header -->
    <div data-role="content"> 
        <form id="form_create_area">
            <div data-role="fieldcontain">
                <label for="area_name">Area_Name</label>
                <input type="text" name="area_name" id="area_name" value="" placeholder="AREA_01" />
            </div>
            <div data-role="fieldcontain">
                <label for="network_segment" class="select">Network segment</label>
                <select name="network_segment" id="network_segment" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                </select>
            </div>
            <div data-role="fieldcontain">
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>IP range</legend>
                        <input type="radio" name="radio-choice-IP" id="radio-choice-IP_start" value="start" checked="checked" />
                        <label for="radio-choice-IP_start">Start</label>
                        <input type="radio" name="radio-choice-IP" id="radio-choice-IP_end" value="end" />
                        <label for="radio-choice-IP_end">End</label>
                </fieldset>
            </div>
            <div data-role="fieldcontain" id="IP_start_slider">
                <label for="start_IP">Start_IP</label>
                <input type="range" name="start_IP" id="start_IP" value="60" min="1" max="254" data-highlight="true"  />
            </div>
            <div data-role="fieldcontain" id="IP_end_slider" class="ui-hidden-accessible">
                <label for="end_IP">End_IP</label>
                <input type="range" name="end_IP" id="end_IP" min="1" max="254" data-highlight="true"  />
            </div>
            <div id="expand_panel" data-role="collapsible-set">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <div data-role="collapsible" data-theme="c" data-content-theme="c">
                        <h3>Expand Config</h3>
                            <div data-role="fieldcontain">
                                <label for="lease_time" class="select">Lease Time</label>
                                <select name="lease_time" id="lease_time" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                                    <option value="1m">1 minute</option>
                                    <option value="30m">30 minutes</option>
                                    <option value="1h">1 hour</option>
                                    <option value="12h" selected="selected">12 hours</option>
                                    <option value="24h">1 day</option>
                                    <option value="140h">1 week</option>
                                    <option value="infinite">infinite</option>
                                </select>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="interface" class="select">Interface</label>
                                <select name="interface" id="interface" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                                </select>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="router">Gateway</label>
                                <input type="text" name="router" id="router" value=""/>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="dns">DNS</label>
                                <input type="text" name="dns" id="dns" value=""/>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="mx_host">Mail Host in Domain</label>
                                <input type="text" name="mx_host" id="mx_host" value="" placeholder="smtp.domain.com" />
                            </div>
                            <div data-role="fieldcontain">
                                <label for="ntp">NTP</label>
                                <input type="text" name="ntp" id="ntp" value="time.nist.gov" />
                            </div>
                            <div data-role="collapsible" data-theme="c" data-content-theme="c">
                                <h4>TFTP Server</h4>
                                <div data-role="fieldcontain">
                                    <label for="tftp_server">TFTP Server</label>
                                    <input type="text" name="tftp_server" id="tftp_server" value="" />
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="tftp_root">TFTP Root</label>
                                    <input type="text" name="tftp_root" id="tftp_root" value="/mnt/tftp_root" />
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="boot_file">Boot File</label>
                                    <input type="text" name="boot_file" id="boot_file" value="pxelinux.0" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="service" id="service" value="off" />
            <input type="hidden" name="change" id="change" value="Y" />
        </form>
    </div><!-- /content -->
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->
<!-- Start of third page -->
<div data-role="page" id="delete_panel" data-title="Delete Panel">
    <div data-role="header">
        <h1>Delete a Domain</h1>
        <a id="button_delete" data-icon="check" data-theme="b">DELETE</a>
    </div><!-- /header -->
    <div data-role="content">
        <div data-role="fieldcontain">
            <label for="delete_domain" class="select">Delete Domain</label>
            <select name="delete_domain" id="delete_domain" data-theme="c" data-overlay-theme="d" data-native-menu="false">
            </select>
        </div>
    </div>
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->
<!-- Start of fourth page -->
<div data-role="page" id="host_map_panel" data-title="Host Map Panel">
    <div data-role="header">
        <a href="#main" data-icon="home">Home</a>
        <h1>Host Map</h1>
        <a id="button_add_item_host_map_bar" data-icon="plus" data-theme="b">ADD</a>
    </div><!-- /header -->
    <div id="host_map_content" data-role="content">
        <ul id="host_map_content_ul" data-role="listview">
            <li data-role="list-divider">
                <fieldset class="ui-grid-d" style="text-align:center">
                    <div class="ui-block-a">
                        <label>HOST_NAME</label>
                    </div>
                    <div class="ui-block-b">
                        <label>IP_ADDRESS</label>
                    </div>
                    <div class="ui-block-c">
                        <label>MAC_ADDRESS</label>
                    </div>
                    <div class="ui-block-d">
                        <label>ROLE</label>
                    </div>
                    <div class="ui-block-e">
                        <label>ACTION</label>
                    </div>
                </fieldset>
            </li>
        </ul>
    </div>
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->
</body>
</html>
