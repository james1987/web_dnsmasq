$(document).ready(function(){

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

    $("[id=AI_service]").on('slidestop', function() {
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

    $("[id=tftp_enable]").on('slidestop', function() {
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
});
