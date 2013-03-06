$(document).ready(function(){
    var url="http://172.26.3.2/gaveme.php";
    $.post(url,
    {
        how: "get_gi"
    },
    function (data,status) {
        put_gi_value(data)
    },
    "json"
    );

    $.post(url,
    {
        how: "get_AIs"
    },
    function (data,status) {
        put_AI_value(data)
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
                      <p><a href="#host_map_panel" data-role="button" data-transition="slidefade" data-inline="false">Host Map</a></p>\
                    </div>\
                </div>\
                <div class="ui-block-b">\
                    <select name="' + AI.area_name + '" id="AI_service" data-role="slider">\
                        <option value="off">Off</option>' + option_service + '</select>\
                </div>\
            </div>').trigger("create");
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
        if(2 > $("#host_map_content_ul li").length) {
        }
        else {
            $("#host_map_content_ul li:last-child").clone().appendTo("#host_map_content_ul");
        }
    });

    $("[id=button_save_host_map]").die().live("click",function() {});
    $("[id=button_save_host_map]").live("click",function() {
    });

    $("[id=button_del_host_map]").die().live("click",function() {});
    $("[id=button_del_host_map]").live("click",function() {
        if(2 == $("#host_map_content_ul li").length) {
            $("#host_map_content_ul li input").val("");
        }
        else {
            $(this).parent().parent().parent().remove();
        }
    });
});
