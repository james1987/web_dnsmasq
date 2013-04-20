<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<title>Virtual Host Manage</title>
<link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.2.0.css" />
<link rel="stylesheet" type="text/css" href="css/zTreeStyle/zTreeStyle.css">
<link rel="stylesheet" type="text/css" href="css/vm.css" />
<script type="text/javascript" src="js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.2.0.js"></script>
<script type="text/javascript" src="js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="js/vm.js"></script>
</head>
<body>
<div data-role="page" id="main">

    <div data-role="header">
        <h1>Web_VHM</h1>
    </div><!-- /header -->

    <div id="layout">
        <div id="navigate">
            <p><a id="go_disk_pool_panel" href="#disk_pool_panel" data-role="button" data-transition="slidefade" data-inline="false">Disk_Pool</a></p>
            <p><a href="#create_VM_panel" id="go_create_VM_panel" data-role="button" data-rel="dialog" data-transition="pop">Create</a></p>
        </div>
        <div id="content" data-role="content">
            <div id="control_side">
                <ul id="control_tree" class="ztree"></ul>
            </div>
            <div id="info_panel">
                <h1>INFO_PANEL</h1>
            </div>
        </div>
    </div><!-- /content -->
    <input type="hidden" name="current_domain" id="current_domain" value="" />
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->

<!-- Start of fourth page -->
<div data-role="page" id="disk_pool_panel" data-title="Disk Pool Panel">
    <div data-role="header">
        <a href="#main" data-icon="home">Home</a>
        <h1>Disk Pool</h1>
        <a id="button_add_item_disk_image_bar" data-icon="plus" data-theme="b">ADD</a>
    </div><!-- /header -->
    <div id="disk_pool_content" data-role="content">
        <ul id="disk_pool_content_ul" data-role="listview">
            <li data-role="list-divider">
                <fieldset class="ui-grid-d" style="text-align:center">
                    <div class="ui-block-a">
                        <label>IMAGE_NAME</label>
                    </div>
                    <div class="ui-block-b">
                        <label>SIZE(MB)</label>
                    </div>
                    <div class="ui-block-c">
                        <label>FORMAT</label>
                    </div>
                    <div class="ui-block-d">
                        <label>OWNER_BY</label>
                    </div>
                    <div class="ui-block-e">
                        <label>ACTION</label>
                    </div>
                </fieldset>
            </li>
        </ul>
    </div>
    <div data-role="footer">
        <h4>Remark</h4>
        <div>
            <p id="libvirt_img_path"></p>
            <p id="libvirt_iso_path"></p>
            <p id="libvirt_max_conn"></p>
        </div>
    </div><!-- /footer -->
</div><!-- /page -->
<div data-role="page" id="create_VM_panel" data-title="Create VM Panel">
    <div data-role="header">
        <h1>Create a VM</h1>
        <a id="button_create" data-icon="check" data-theme="b">CREATE</a>
    </div><!-- /header -->
    <div data-role="config_content">
        <form id="form_create_VM">
            <div data-role="fieldcontain">
                <label for="VM_name">VM_Name</label>
                <!--<input type="text" name="VM_name" id="VM_name" value="" placeholder="AREA_01.vm.com" />-->
                <select name="VM_name" id="VM_name" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                </select>
            </div>
            <div data-role="fieldcontain">
                <label for="vcpu" class="select">Vcpu</label>
                <select name="vcpu" id="vcpu" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                        <option value="1">1</option>
                        <option value="2" selected="selected">2</option>
                        <option value="4">4</option>
                        <option value="8">8</option>
                </select>
            </div>
            <div data-role="fieldcontain">
                <label for="memory">Memory (MB)</label>
                <input type="range" name="memory" id="memory" value="1024" min="64" max="102400" data-highlight="true"  />
            </div>
            <div id="dev_cdrom_s">
            </div>
            <div id="dev_disk_s">
            </div>
            <div id="dev_nic_s">
            </div>
            <div data-role="fieldcontain">
                <label for="monitor">Monitor</label>
                <input type="text" name="monitor" id="monitor" value="spice:6901" placeholder="spice/vnc" />
            </div>
            <div id="fun_button_s">
                <a href="#add_dev_panel" id="button_add_dev_panel" data-role="button" data-rel="dialog" data-transition="pop" data-inline="true">Add</a>
                <a href="#remove_dev_panel" id="button_remove_dev_panel" data-role="button" data-rel="dialog" data-transition="pop" data-inline="true">Remove</a>
            </div>
            <input type="hidden" name="change" id="change" value="Y" />
        </form>
    </div><!-- /content -->
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->

<!-- Start of Add Dev page -->
<div data-role="page" id="add_dev_panel" data-title="Add Device Panel">
    <div data-role="header">
        <h1>Add Device</h1>
        <a href="#create_VM_panel" id="button_add_dev" data-icon="check">Add</a>
    </div><!-- /header -->
    <div id="dev_list_content" data-role="content">
        <fieldset data-role="controlgroup">
            <legend>Choose a device:</legend>
                <input type="radio" name="dev_choice" id="dev_choice_cdrom" value="cdrom" checked="checked" />
                <label for="dev_choice_cdrom">CDROM</label>
                <select for="dev_choice_cdrom" name="cdrom_choice" id="cdrom_choice" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                    <option value="rhel_6.2.iso" selected="selected">rhel_6.2.iso</option>
                </select>
        
                <input type="radio" name="dev_choice" id="dev_choice_disk" value="disk"  />
                <label for="dev_choice_disk">Disk</label>
                <select for="dev_choice_disk" name="disk_choice" id="disk_choice" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                </select>
        
                <input type="radio" name="dev_choice" id="dev_choice_nic" value="nic"  />
                <label for="dev_choice_nic">NIC</label>
                <select for="dev_choice_nic" name="nic_choice" id="nic_choice" data-theme="c" data-overlay-theme="d" data-native-menu="false">
                </select>
        </fieldset>
    </div>
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->

<!-- Start of Remove Dev page -->
<div data-role="page" id="remove_dev_panel" data-title="Remove Device Panel">
    <div data-role="header">
<!--        <a href="#create_VM_panel" data-icon="back">Back</a>-->
        <h1>Remove Device</h1>
    </div><!-- /header -->
    <div id="has_dev_list_content" data-role="content">
    </div>
    <div data-role="footer">
        <h4>Page Footer</h4>
    </div><!-- /footer -->
</div><!-- /page -->
</body>
</html>
