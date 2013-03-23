$(document).ready(function(){
    var server_ip = window.document.location.host;
    var url="http://" + server_ip + "/gaveme.php";
    var global_info;
    $.post(url,
    {
        how: "get_gi"
    },
    function (data,status) {
        global_info = data;
    },
    "json"
    );

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
        $.post(url,
        {
            how: "get_disk_pool",
        },
        function (data,status) {
            put_DI_value(data)
        },
        "json"
        );
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
});
