<?php
    include_once dirname(__FILE__) . '/../conf/function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<title>web_dnsmasq</title>
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.css" />
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
        <h3>概况：</h3>
        <p>一切正常！</p>
<pre>
<?php
var_dump(get_areas());
foreach(get_areas() as $area) {
$AI = new Area_Info();
$AI->getIt($area);
var_dump($AI);
}
?>
</pre>
        <p><a href="#create_panel" data-role="button" data-rel="dialog" data-transition="pop">创建作用域</a></p>
    </div><!-- /content -->

    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->


<!-- Start of second page -->
<div data-role="page" id="create_panel" data-title="作用域创建面板">

    <div data-role="header">
        <h1>创建新域</h1>
        <a id="button_save" data-icon="check" data-theme="b">保存</a>
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
                    <option value="1.0.0.0/24">1.0.0.0/24</option>
                    <option value="192.168.1.0/24">192.168.1.0/24</option>
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
        </form>
    </div><!-- /content -->

    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->
</body>
</html>
