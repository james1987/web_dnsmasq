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
});
