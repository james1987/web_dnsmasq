$(document).ready(function(){
    var url="http://172.26.3.2/gaveme.php";
    var global_info;
    $.post(url,
    {
        how: "get_gi"
    },
    function (data,status) {
        global_info = data;
        put_gi_value(data);
    },
    "json"
    );

    $.post(url,
    {
        how: "get_AIs"
    },
    function (data,status) {
        put_AI_value(data);
    },
    "json"
    );

    function put_gi_value(gi) {
        for (i in gi.net_segs) {
            $("#network_segment").append('<option value="' + gi.net_segs[i] + '">' + gi.net_segs[i] + '</option>');
        }
        for (i in gi.net_devs) {
            $('#interface').append('<option value="' + gi.net_devs[i] + '">' + gi.net_devs[i] + '</option>');
        }
        $('#interface').append('<option value="all" selected="selected">all</option>');
        $('#router').val(gi.router);
        $('#dns').val(gi.router);
        $('#tftp_server').val(gi.router);
    }

    function put_AI_value(AIs) {
        for (i in AIs) {
            AI = AIs[i];
            if ('on' == AI.service) {
                option_service = '<option value="on" selected="selected">On</option>';
            }
            else {
                option_service = '<option value="on">On</option>';
            }

            if ('on' == AI.tftp_enable) {
                option_tftp_enable = '<option value="on" selected="selected">On</option>';
            }
            else {
                option_tftp_enable = '<option value="on">On</option>';
            }
            $("#delete_domain").append('<option value="' + AI.area_name + '">' + AI.area_name + '</option>');
            $("#overview").append('\
            <div class="ui-grid-a">\
                <div class="ui-block-a">\
                    <div data-role="collapsible" data-theme="c" data-content-theme="c">\
                    <h3>' + AI.area_name + '</h3>\
                      <p class="s_content">Net_Segment:' + AI.network_segment + '</p>\
                      <p class="s_content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pool:' + AI.start_IP + '->' + AI.end_IP + '</p>\
                          <div data-role="collapsible" data-theme="c" data-content-theme="c">\
                            <h4>Detail Config</h4>\
                              <p class="s_content">Lease_Time:' + AI.lease_time + '</p>\
                              <p class="s_content">Interface:' + AI.interface + '</p>\
                              <p class="s_content">Gateway:' + AI.router + '</p>\
                              <p class="s_content">DNS:' + AI.dns + '</p>\
                              <p class="s_content">MX_Host:' + AI.mx_host + '</p>\
                              <p class="s_content">NTP:' + AI.ntp + '</p>\
                              <div data-role="collapsible" data-theme="c" data-content-theme="c">\
                                <h4>TFTP_Server</h4>\
                                      <select name="' + AI.area_name + '" id="tftp_enable" data-role="slider">\
                                          <option value="off">Off</option>' + option_tftp_enable + '</select>\
                                  <p class="s_content">Server:' + AI.tftp_server + '</p>\
                                  <p class="s_content">Root:' + AI.tftp_root + '</p>\
                                  <p class="s_content">Boot_File:' + AI.boot_file + '</p>\
                              </div>\
                          </div>\
                      <p><a id="go_host_map_panel" href="#host_map_panel" name="' + AI.area_name + '" data-role="button" data-transition="slidefade" data-inline="false">Host Map</a></p>\
                    </div>\
                </div>\
                <div class="ui-block-b">\
                    <select name="' + AI.area_name + '" id="AI_service" data-role="slider">\
                        <option value="off">Off</option>' + option_service + '</select>\
                </div>\
            </div>').trigger("create");
        }
    }

    function put_HM_value(HMs) {
        for (i in HMs) {
            HM = HMs[i];
            $("#host_map_content_ul").append('\
                <li class="ui-li ui-li-static ui-btn-up-c ui-li-last">\
                    <fieldset class="ui-grid-c">\
                        <div class="ui-block-a">\
                            <input type="text" class="ui-disabled" name="input_hostname" id="input_hostname" value="' + HM.hostname + '" placeholder="host_01.domain.com" />\
                        </div>\
                        <div class="ui-block-b">\
                            <input type="text" class="ui-disabled" name="input_ip_addr" id="input_ip_addr" value="' + HM.ip_addr + '" placeholder="192.168.1.20" />\
                        </div>\
                        <div class="ui-block-c">\
                            <input type="text" class="ui-disabled" name="input_mac_addr" id="input_mac_addr" value="' + HM.mac_addr + '" placeholder="00:00:00:AA:AA:01" />\
                        </div>\
                        <div class="ui-block-d">\
                            <a id="button_save_host_map" class="ui-disabled" data-role="button" data-icon="check" data-inline="true" data-theme="b">SAVE</a>\
                            <a id="button_del_host_map" data-role="button" data-icon="delete" data-inline="true" data-theme="a">DEL</a>\
                        </div>\
                    </fieldset>\
                </li>'
            ).trigger("create");
        }
        if (0 == HMs.length) {
            $("#host_map_content_ul").append('\
                <li class="ui-li ui-li-static ui-btn-up-c ui-li-last">\
                    <fieldset class="ui-grid-c">\
                        <div class="ui-block-a">\
                            <input type="text" name="input_hostname" id="input_hostname" value="" placeholder="host_01.' + global_info.domain_name + '" onclick="this.value = this.placeholder" />\
                        </div>\
                        <div class="ui-block-b">\
                            <input type="text" name="input_ip_addr" id="input_ip_addr" value="" placeholder="' + global_info.router + '" onclick="this.value = this.placeholder" />\
                        </div>\
                        <div class="ui-block-c">\
                            <input type="text" name="input_mac_addr" id="input_mac_addr" value="" placeholder="00:00:00:AA:AA:01" onclick="this.value = this.placeholder" />\
                        </div>\
                        <div class="ui-block-d">\
                            <a id="button_save_host_map" data-role="button" data-icon="check" data-inline="true" data-theme="b">SAVE</a>\
                            <a id="button_del_host_map" data-role="button" data-icon="delete" data-inline="true" data-theme="a">DEL</a>\
                        </div>\
                    </fieldset>\
                </li>'
            ).trigger("create");
        }
        else {
            clone_item_host_map();
        }
    }

    function clone_item_host_map() {
            $("#host_map_content_ul li:last-child").clone().appendTo("#host_map_content_ul");
            $("#host_map_content_ul li:last-child").find("#button_save_host_map").removeClass("ui-disabled");
            $("#host_map_content_ul li:last-child").find("input[type=text]").removeClass("ui-disabled");
    }

    function clear_host_map_panel() {
        for (var i = 1; i < $("#host_map_content_ul li").length; i++) {
            $("#host_map_content_ul li:nth-child(i)").remove();
        }
    }

    $("#radio-choice-IP_start").click( function() {
        $("#IP_start_slider").removeClass("ui-hidden-accessible");
        $("#IP_end_slider").addClass("ui-hidden-accessible");
    });
    $("#radio-choice-IP_end").click( function() {
        $("#end_IP").attr("min",parseInt($("#start_IP").val())+1);
        $("#IP_start_slider").addClass("ui-hidden-accessible");
        $("#IP_end_slider").removeClass("ui-hidden-accessible");
    });
    
    $("#button_save").click( function() {
        $.post("handle.php",
        {
            action:'create',
            area_name:$("#area_name").val(),
            network_segment:$("#network_segment").val(),
            start_IP:$("#start_IP").val(),
            end_IP:$("#end_IP").val(),
            change:$("#change").val(),
//--------------------------EXTEND-------------------------
            lease_time:$("#lease_time").val(),
            interface:$("#interface").val(),
            router:$("#router").val(),
            dns:$("#dns").val(),
            mx_host:$("#mx_host").val(),
            ntp:$("#ntp").val(),
//--------------------------EXTEND=>TFTP-------------------------
            tftp_enable:"off",
            tftp_server:$("#tftp_server").val(),
            tftp_root:$("#tftp_root").val(),
            boot_file:$("#boot_file").val(),
        },
        function(data,status){
            if ("success" == status) {
                window.location.href="/";
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("#button_delete").click( function() {
        $.post("handle.php",
        {
            action:'delete',
            area_name:$("#delete_domain").val(),
        },
        function(data,status){
            if ("success" == status) {
                window.location.href="/";
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("[id=AI_service]").die().live("slidestop",function() {});
    $("[id=AI_service]").live("slidestop",function() {
        $.post("handle.php",
        {
            action:'change',
            area_name:this.name,
            service:this.value,
            change:$("#change").val(),
        },
        function(data,status){
            if ("success" == status) {
                window.location.href="/";
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("[id=tftp_enable]").die().live("slidestop",function() {});
    $("[id=tftp_enable]").live("slidestop",function() {
        $.post("handle.php",
        {
            action:'change',
            area_name:this.name,
            tftp_enable:this.value,
            change:$("#change").val(),
        },
        function(data,status){
            if ("success" == status) {
                window.location.href="/";
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("#button_add_item_host_map_bar").click( function() {
        if(1 < $("#host_map_content_ul li").length) {
            clone_item_host_map();
        }
    });

    $("[id=go_host_map_panel]").die().live("click",function() {});
    $("[id=go_host_map_panel]").live("click",function() {
        $("#current_domain").val(this.name);
        clear_host_map_panel();
        $.post(url,
        {
            how: "get_all_HM",
            owner_by: $("#current_domain").val()
        },
        function (data,status) {
            put_HM_value(data)
        },
        "json"
        );
    });

    $("[id=button_save_host_map]").die().live("click",function() {});
    $("[id=button_save_host_map]").live("click",function() {
        cur_but = $(this);
        $.post("handle.php",
        {
            action:'add_host_map',
            owner_by:$("#current_domain").val(),
            hostname:$(this).parent().parents().find("input#input_hostname").val(),
            mac_addr:$(this).parent().parents().find("input#input_mac_addr").val(),
            ip_addr:$(this).parent().parents().find("input#input_ip_addr").val(),
        },
        function(data,status){
            if ("success" == status) {
                if (cur_but.parent().parent().parent().index() == $("#host_map_content_ul li:last-child").index()) {
                    clone_item_host_map();
                }
                cur_but.parent().parent().find("input").addClass("ui-disabled");
                cur_but.addClass("ui-disabled");
            }
            else {
                alert("Not OK!");
            }
        });
    });

    $("[id=button_del_host_map]").die().live("click",function() {});
    $("[id=button_del_host_map]").live("click",function() {
        cur_but = $(this);
        $.post("handle.php",
        {
            action:'delete_host_map',
            host_name:$(this).parent().parents().find("input#input_hostname").val(),
        },
        function(data,status){
            if ("success" == status) {
                if(2 == $("#host_map_content_ul li").length) {
                    $("#host_map_content_ul li input").val("");
                    cur_but.parent().find("#button_save_host_map").removeClass("ui-disabled");
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
