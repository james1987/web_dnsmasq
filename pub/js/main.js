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
        area_name:$("#area_name").val(),
        network_segment:$("#network_segment").val(),
        start_IP:$("#start_IP").val(),
        end_IP:$("#end_IP").val(),
    },
    function(data,status){
        if ("success" == status) {
//            window.location.href="/";
        }
        else {
            alert("Not OK!");
        }
    });
  });

});
