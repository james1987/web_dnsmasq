$(document).ready(function(){
    var server_ip = window.document.location.host;
    var url="http://" + server_ip + "/gaveme.php";
    var handle = "handle.php";
    var global_info;
    var all_HM;
    var all_DI;
    var all_node_obj;
    var all_guest_obj;
    var idle_DI;
    var setting = {
        callback: {
            onClick: zTreeOnClick
        }
    };
    var zNodes = [
        {name:"root", open:true, children:[]}
    ];

    get_all_DI();
    init_fun();

    function init_fun() {
        $.post(url,
        {
            how: "get_gi"
        },
        function (data,status) {
            global_info = data;
            $.post(url,
            {
                how: "get_all_HM",
                owner_by: global_info.areas[0],
            },
            function (data,status) {
                all_HM = data;
                all_node_obj = obj_filter(all_HM,"role","NODE");
                get_vi();
            },
            "json"
            )
        },
        "json"
        );
    };

    function get_all_DI() {
        $.post(url,
        {
            how: "get_disk_pool",
        },
        function (data,status) {
            all_DI = data;
        },
        "json"
        );
    }

    function put_DI_value(pool) {
        for (i in pool) {
            DI = pool[i];
            $("#disk_pool_content_ul").append('\
                <li class="ui-li ui-li-static ui-btn-up-c ui-li-last">\
                    <fieldset class="ui-grid-d">\
                        <div class="ui-block-a">\
                            <input type="text" class="ui-disabled" name="input_img_name" id="input_img_name" value="' + DI.img_name + '" placeholder="host_01.domain.com" />\
                        </div>\
                        <div class="ui-block-b">\
                            <input type="text" class="ui-disabled" name="input_size" id="input_size" value="' + DI.size + '" placeholder="10000" />\
                        </div>\
                        <div class="ui-block-c">\
                            <input type="text" class="ui-disabled" name="input_format" id="input_format" value="' + DI.format + '" placeholder="qcow2" />\
                        </div>\
                        <div class="ui-block-d">\
                            <input type="text" class="ui-disabled" name="input_owner_by" id="input_owner_by" value="' + DI.owner_by_host + '" placeholder="Not Used" />\
                        </div>\
                        <div class="ui-block-e">\
                            <a id="button_save_disk_image" class="ui-disabled" data-role="button" data-icon="check" data-inline="true" data-theme="b">SAVE</a>\
                            <a id="button_del_disk_image" data-role="button" data-icon="delete" data-inline="true" data-theme="a">DEL</a>\
                        </div>\
                    </fieldset>\
                </li>'
            ).trigger("create");
        }
        if (0 == pool.length) {
            $("#disk_pool_content_ul").append('\
                <li class="ui-li ui-li-static ui-btn-up-c ui-li-last">\
                    <fieldset class="ui-grid-d">\
                        <div class="ui-block-a">\
                            <input type="text" name="input_img_name" id="input_img_name" value="" placeholder="host_01.' + global_info.domain_name + '" />\
                        </div>\
                        <div class="ui-block-b">\
                            <input type="text" name="input_size" id="input_size" value="" placeholder="10000" />\
                        </div>\
                        <div class="ui-block-c">\
                            <input type="text" name="input_format" id="input_format" value="" placeholder="qcow2" />\
                        </div>\
                        <div class="ui-block-d">\
                            <input type="text" class="ui-disabled" name="input_owner_by" id="input_owner_by" value="" placeholder="Don\'t edit" />\
                        </div>\
                        <div class="ui-block-e">\
                            <a id="button_save_disk_image" data-role="button" data-icon="check" data-inline="true" data-theme="b">SAVE</a>\
                            <a id="button_del_disk_image" data-role="button" data-icon="delete" data-inline="true" data-theme="a">DEL</a>\
                        </div>\
                    </fieldset>\
                </li>'
            ).trigger("create");
        }
        else {
            clone_item_disk_image();
        }
        $("#libvirt_img_path").html("&nbsp;&nbsp;&nbsp;&nbsp;Image Path:&nbsp;&nbsp;&nbsp;&nbsp;" + global_info.image_path);
        $("#libvirt_iso_path").html("&nbsp;&nbsp;&nbsp;&nbsp;ISO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Path:&nbsp;&nbsp;&nbsp;&nbsp;" + global_info.iso_path);
        $("#libvirt_max_conn").html("&nbsp;&nbsp;&nbsp;&nbsp;Max&nbsp;&nbsp;&nbsp;Conn:&nbsp;&nbsp;&nbsp;&nbsp;" + global_info.max_connections);
    }

    function clone_item_disk_image() {
            $("#disk_pool_content_ul li:last-child").clone().appendTo("#disk_pool_content_ul");
            $("#disk_pool_content_ul li:last-child").find("#button_save_disk_image").removeClass("ui-disabled");
            $("#disk_pool_content_ul li:last-child").find("input[type=text]").removeClass("ui-disabled");
            $("#disk_pool_content_ul li:last-child").find("#input_owner_by").val("Don\'t edit");
            $("#disk_pool_content_ul li:last-child").find("#input_owner_by").addClass("ui-disabled");
    }

    function clear_disk_pool_panel() {
        var ul_childs = $("#disk_pool_content_ul li").length;
        for (var i = 2; i <= ul_childs; i++) {
            $("#disk_pool_content_ul li:nth-child(2)").remove();
        }
    }

    $("#button_add_item_disk_image_bar").click( function() {
        if(1 < $("#disk_pool_content_ul li").length) {
            clone_item_disk_image();
        }
    });

    $("[id=disk_pool_content_ul]").find("input").die().live("click",function() {});
    $("[id=disk_pool_content_ul]").find("input").live("click",function() {
        if (1 == $(this).parent().parent().parent().index()) {
            if ("" == $(this).val()) {
                $(this).val($(this).attr('placeholder'));
            }
        }
    });


    $("[id=go_disk_pool_panel]").die().live("click",function() {});
    $("[id=go_disk_pool_panel]").live("click",function() {
        clear_disk_pool_panel();
        get_all_DI();
        put_DI_value(all_DI);
    });

    $("[id=button_save_disk_image]").die().live("click",function() {});
    $("[id=button_save_disk_image]").live("click",function() {
        cur_but = $(this);
        $.post("handle.php",
        {
            action:'add_disk_image',
            img_name:$(this).parent().parents().find("input#input_img_name").val(),
            size:$(this).parent().parents().find("input#input_size").val(),
            format:$(this).parent().parents().find("input#input_format").val(),
        },
        function(data,status){
            if ("success" == status) {
                if (cur_but.parent().parent().parent().index() == $("#disk_pool_content_ul li:last-child").index()) {
                    clone_item_disk_image();
                }
                cur_but.parent().parent().find("input").addClass("ui-disabled");
                cur_but.parent().parent().find("#input_owner_by").val("Not Used");
                cur_but.addClass("ui-disabled");
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("[id=button_del_disk_image]").die().live("click",function() {});
    $("[id=button_del_disk_image]").live("click",function() {
        cur_but = $(this);
        $.post("handle.php",
        {
            action:'delete_disk_image',
            img_name:$(this).parent().parents().find("input#input_img_name").val(),
        },
        function(data,status){
            if ("success" == status) {
                if(2 == $("#disk_pool_content_ul li").length) {
                    $("#disk_pool_content_ul li input").val("");
                    cur_but.parent().find("#button_save_disk_image").removeClass("ui-disabled");
                }
                else {
                    cur_but.parent().parent().parent().remove();
                }
            }
            else {
                alert("Not OK!");
            }
        });
    });

    function init_zTree(data) {
        var datas = new Array();
        for (var i = 0; i < data.length; i++) {
            var NODE_name = "";
            if (null == data[i].libvirtd_info) {
                NODE_name = data[i].node_name;
            }
            else {
                NODE_name = data[i].libvirtd_info.hostname;
            }
            datas.push({name:NODE_name, open:false, children:[], info_xml: data[i].node_info_xml});
            for (var k = 0; k < data[i].VMs.length; k++) {
                var VM_name = data[i].VMs[k].name;
                datas[i].children.push({name:VM_name, open:false, children:[], info_xml: data[i].VMs[k]});
            }
        }
        return datas;
    }

    function zTreeOnClick(event, treeId, treeNode) {
//        alert(treeNode.tId + ", " + treeNode.name);
        if (null != treeNode.info_xml) {
            var info_content = obj2element(treeNode.info_xml);
            $("#info_panel").html(info_content);
        }
        else {
            $("#info_panel").html("This host can't get any information!");
        }
    };

    function obj2element(obj) {
        var eles = ""; // elements
        $.each( obj,
            function (key, element) {
                reg=/^[0-9]+$/;
                if (!reg.test(key)) {
                    eles += key + "<br>";
                }
                if (typeof element != "object") {
                    eles += "&nbsp;&nbsp;&nbsp;&nbsp;" + element + "<br>";
                }
                else {
                    eles += obj2element(element);
                }
            });
            return eles;
    }

    function obj_filter(obj_s, type, match) {
        var tmp_arr = new Array();
        for (var i = 0; i < obj_s.length; i++) {
            type_str = eval("obj_s[" + i + "]." + type);
            if (match == type_str) {
                tmp_arr.push(obj_s[i]);
            }
        }
        return tmp_arr;
    }

    function get_vi() {
        node_s="";
        for (var i = 0; i < all_node_obj.length; i++) {
            node_s += all_node_obj[i].ip_addr + ',';
        }
        $.post(url,
        {
            how: "get_vi",
            node_s: node_s,
        },
        function (data,status) {
            zNodes[0].children = init_zTree(data);
            var zTreeObj = $.fn.zTree.init($("#control_tree"), setting, zNodes);
        },
        "json"
        );
    }
    $("#go_create_VM_panel").click( function() {
        all_guest_obj = obj_filter(all_HM,"role","GUEST");
        var VM_opt = "";
        var VM_hostname = "";
        for (var i = 0; i < all_guest_obj.length; i++) {
            VM_hostname = all_guest_obj[i].hostname;
            if (0 == i) {
                VM_opt = '<option value="' + VM_hostname + '" selected="selected">' + VM_hostname + '</option>';
            }
            else {
                VM_opt = '<option value="' + VM_hostname + '">' + VM_hostname + '</option>';
            }
            $("#VM_name").append(VM_opt).trigger("create");
        }
        $("#VM_name").selectmenu("refresh");
    });

    $("#button_create").click( function() {
        selected_cdrom = $("#dev_cdrom_s").find("input");
        selected_disk = $("#dev_disk_s").find("input");
        selected_nic = $("#dev_nic_s").find("select");
        nic_mac_s = $("#dev_nic_s").find("input");
        dev_cdrom_s="";
        dev_disk_s="";
        dev_nic_s="";
        for (var i = 0; i < selected_cdrom.length; i++) {
            dev_cdrom_s += selected_cdrom[i].value + ',';
        }
        for (var i = 0; i < selected_disk.length; i++) {
            dev_disk_s += selected_disk[i].value + ',';
        }
        for (var i = 0; i < selected_nic.length; i++) {
            dev_nic_s += selected_nic[i].value + ',';
            dev_nic_s += nic_mac_s[i].value + '|';
        }
        $.post(handle,
        {
            action:'create_VM',
            VM_name:$("#VM_name").val(),
            vcpu:$("#vcpu").val(),
            memory:$("#memory").val(),
            dev_cdrom_s:dev_cdrom_s,
            dev_disk_s:dev_disk_s,
            dev_nic_s:dev_nic_s,
            monitor:$("#monitor").val(),
        },
        function (data,status) {
//            alert(data)
        },
        "json"
        );
    });

    $("#button_add_dev_panel").click( function() {
        idle_DI = filter_idle_DI();
        nic_MAC_s = "";
        $("#disk_choice").find("option").remove();
        $("#nic_choice").find("option").remove();
        for (var i = 0; i < idle_DI.length; i++) {
            var img_name = idle_DI[i].img_name;
            var img_opt = "";
            if (VM_name.value == img_name) {
                img_opt = '<option value="' + img_name + '" selected="selected">' + img_name + '</option>';
            }
            else {
                img_opt = '<option value="' + img_name + '">' + img_name + '</option>';
            }
            $("#disk_choice").append(img_opt).trigger("create");
        }
        for (var i = 0; i < all_HM.length; i++) {
            var nic_MAC = all_HM[i].mac_addr;
            var nic_opt = "";
            if (VM_name.value == all_HM[i].hostname) {
                nic_opt = '<option value="' + nic_MAC + '" selected="selected">' + nic_MAC + '</option>';
            }
            else {
                nic_opt = '<option value="' + nic_MAC + '">' + nic_MAC + '</option>';
            }
            $("#nic_choice").append(nic_opt).trigger("create");
        }
        $("#disk_choice").selectmenu("refresh");
        $("#nic_choice").selectmenu("refresh");
    });
    
    function filter_idle_DI() {
        var tmp_arr = new Array();
        for (var i = 0; i < all_DI.length; i++) {
            if ("" != all_DI[i].owner_by_host) {
            }
            else {
                tmp_arr.push(all_DI[i]);
            }
        }
        return tmp_arr;
    }
    $("#button_add_dev").click( function() {
        var dev_x = $('input:radio[name=dev_choice]:checked').val();
        var dev_dest_x = eval('$("#dev_' + dev_x + '_s")');
        var dev_x_value = eval('$("#' + dev_x + '_choice").val()');
        if ("cdrom" == dev_x) {
            dev_dest_x.append('\
                    <div data-role="fieldcontain">\
                        <label for="' + dev_x + '_x">CD-ROM</label>\
                        <input type="text" name="' + dev_x + '_x" id="' + dev_x + '_x" value="' + dev_x_value + '" placeholder="" />\
                    </div>'
            ).trigger("create");
        }
        else if ("disk" == dev_x) {
            dev_dest_x.append('\
                    <div data-role="fieldcontain">\
                        <label for="' + dev_x + '_x">Disk</label>\
                        <input type="text" name="' + dev_x + '_x" id="' + dev_x + '_x" value="' + dev_x_value + '" placeholder="" />\
                    </div>'
            ).trigger("create");
        }
        else if ("nic" == dev_x) {
            dev_dest_x.append('\
                <div data-role="fieldcontain">\
                    <fieldset data-role="controlgroup" data-type="horizontal">\
                        <legend>Network</legend>\
                        <select name="nic_network" id="nic_network">\
                            <option value="default">default</option>\
                        </select>\
                        <input type="text" name="nic_mac" id="nic_mac" value="' + dev_x_value + '" placeholder="" style="width: 65%;" />\
                    </fieldset>\
                </div>'
            ).trigger("create");
        }
    });
});
