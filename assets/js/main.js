/*$(document).ready(function() {
    window.history.pushState(null, "", window.location.href);        
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };
});*/

$(window).click(function(e) {
if($(e.target).hasClass("sending_li")){
  var send_value = $(e.target).attr("data-element");  
  $(e.target).parents(".delivery_location").find(".sending_type_class").val(send_value);
  $(e.target).parents(".sending_type").find("li").find("a").removeClass("active");
  $(e.target).addClass("active");
  $(e.target).parents(".delivery_location").find(".sending_type_class").removeClass("error");
  $(e.target).parents(".delivery_location").find("#sending_type-error").hide();  
 }
if($(e.target).hasClass("weight_li")){
    var base_url = $("#base_url").val();
    var kilometer = $(".kilometer_calculater").val();
    var charges = $(e.target).attr("data-element");
    $.ajax({
        url:base_url+'/user/weight_charges',
        type:"post",
        dataType:"json",
        data:{value:charges, kilometer_charges:kilometer},
        success: function(result) {
          $(e.target).parents(".weight_ul").find("li").find("a").removeClass("active");
          $(e.target).addClass("active");
          $(".weight_class").val(result['id']);
          $(".popup_weight_title").html(result['title']);
          $(".popup_kilo_weight").html(result['charges']);
          $(".total_charges").html(result['total_charges']);
        }
      });  
 }

if($(e.target).hasClass("payment_get")){
    var base_url = $("#base_url").val();
    var payment_get = $(e.target).attr("data-element");
    var weight = $(".weight_class").val();
    var kilometer = $(".kilometer_class").val();
    $.ajax({
        url:base_url+'/user/payment_get',
        type:"post",
        dataType:"json",
        data:{value:payment_get, kilometer:kilometer, weight:weight},
        success: function(result) {

          $(".total_charges").val(result['total_charges']);
          $(".total_charges").html(result['total_charges']);
          $(".note_class").html(result['notes']);
          $(".payment_get_class").val(payment_get);
          
        }
      });  
 }

if($(e.target).hasClass("order_status_sms")){
  if($(e.target).is(":checked")){
    $(e.target).val(1)
  }
  else{
    $(e.target).val(0)
  }
}
if($(e.target).hasClass("delivery_details_sms")){
  if($(e.target).is(":checked")){
    $(e.target).val(1)
  }
  else{
    $(e.target).val(0)
  }
}


if($(e.target).hasClass("ignore_class")){  
    var base_url = $("#base_url").val();    
    var id = $(e.target).attr("data-element");  
    
    $.ajax({
        url:base_url+'/deliver_boy/order_reject',
        type:"post",
        dataType:"json",
        data:{id:id},
        success: function(result) {
          $(".pickup_decline").html(result['modal']);
          $(".order_model").modal("show");

        }
      });  
 }

if($(e.target).hasClass("pickup_class")){  
    var base_url = $("#base_url").val();    
    var id = $(e.target).attr("data-element");  
    
    $.ajax({
        url:base_url+'/deliver_boy/order_pickup',
        type:"post",
        dataType:"json",
        data:{id:id},
        success: function(result) {
          $(".pickup_decline").html(result['modal']);
          $(".order_model").modal("show");

        }
      });  
 }





})





$(document).ready(function(){
  $('.menu-link').bigSlide();
  $(window).scroll(function(){
    var position = $(this).scrollTop();
    if(position >= 200) {
      $('.logo').addClass('logo-navbar');
      $('.logo_img').addClass('logo-navbar_img');
    } else {
      $('.logo').removeClass('logo-navbar');
      $('.logo_img').removeClass('logo-navbar_img');
    }
  });
});

$(".order_status_change").change(function(){
  var base_url = $("#base_url").val();
  var status = $(this).val();
  var order_id = $(this).attr("data-element");  
  $.ajax({
    url:base_url+'/deliver_boy/order_status_change',
    type:"post",    
    data:{id:order_id, status:status},
    success: function(result) {
       location.reload(true);
    }
  });
});

/*$(".weight_li").click(function() {
  $("li").removeClass("active");
  $(this).addClass("active");
  var id = $(this).val('');
  console.log(id);
  $(".weight_class").val(id);
});

$(".sending_li").click(function() {
  $(this).parents(".sending_type").find("li").removeClass("active");
  $(this).addClass("active");
});*/




$('#order_proces_form').validate({
    rules: {
      pickup: {required: true},
      password:{required: true},   
      contact_person:{required: true},         
    },
    messages: {              
      pickup:{required : "Enter Pickup Area"},
      delivery:{required:"Enter Delivery Area"},
      contact_person:{required:"Enter Contact Person Name"},

    },
});

$(function(){
    $('#data_table').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: true,
      info: false
    });
    $('#ongoing_table').DataTable({
        
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
    });
});

$(".forgot_password").click(function(){
  $(".forgot_toogle").slideToggle();
});

$('.notify').click( function() {                
    $('.notify_ul').toggle();
    $('.profile_toggle').hide();        
});

$('.profile').click( function() {            
    $('.profile_toggle').toggle();
    $('.notify_ul').hide();        
});



/*$(document).ready(function(){    
    $('.profile').click( function(m) {        
        m.preventDefault();
        m.stopPropagation();
        $('.profile_toggle').toggle();
        $('.notify_ul').hide();        
    });    
    $('.profile_toggle').click( function(m) {       
        m.stopPropagation();        
    }); 
    $('.notify').click( function(m) {        
        m.preventDefault();
        m.stopPropagation();
        $('.notify_ul').toggle();
        $('.profile_toggle').hide();        
    });    
    $('.notify_ul').click( function(m) {       
        m.stopPropagation();        
    });   
    $('body').click( function() {       
        $('.profile_toggle').hide();
        $('.notify_ul').hide();
    });
    
});*/

$(document).ready(function() {
  $('#step2-order-form').validate({
      rules: {

      },
      messages: {              
        

      },
  });
})





$.ajaxSetup({async:false});
$(".place_order").click(function(){
   if($("#step2-order-form").valid()){
    $("#step2-order-form").submit();
   }

})

$.ajaxSetup({async:false});
$(".confirm_order").click(function(){
   if($("#step3-order-form").valid()){
    $("#step3-order-form").submit();
   }

})




