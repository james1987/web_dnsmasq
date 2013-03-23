<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<title>Virtual Host Manage</title>
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.css" />
<link rel="stylesheet" href="css/main.css" />
<script src="js/jquery-1.8.2.js"></script>
<script src="js/jquery.mobile-1.2.0.js"></script>
<script src="js/vm.js"></script>
</head>
<body>
<div data-role="page" id="main">

    <div data-role="header">
        <h1>Web_VHM</h1>
    </div><!-- /header -->

    <div data-role="content">   
        <h3>Overview:</h3>
        <div id="overview" data-role="collapsible-set">
        </div>
        <div class="ui-grid-b">
            <div class="ui-block-a">
                <p><a href="#create_panel" data-role="button" data-rel="dialog" data-transition="pop">Create</a></p>
            </div>
            <div class="ui-block-b">
                <p><a href="#delete_panel" data-role="button" data-rel="dialog" data-transition="pop">Delete</a></p>
            </div>
            <div class="ui-block-c">
                <p><a id="go_disk_pool_panel" href="#disk_pool_panel" data-role="button" data-transition="slidefade" data-inline="false">Disk_Pool</a></p>
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
</body>
</html>
