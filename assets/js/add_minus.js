$(".add_location").click(function(){		
		$(".dooted_empty").addClass("dotted");
		var html = $(".sending_type").last().html();
    var value_sending = $(".sending_type_class").last().val();
		var count = $(".round_count").length;
		var count_value = count+1;
    $(".location_details").append('<div class="col-lg-12 delivery_location"><div class="row"><div class="col-lg-6 col-md-6 col-sm-12 col-12"><div class="step2_form width_100 margin_top_20"><div class="row"><div class="col-lg-12"><button type="button" class="remove_location">×</button><div class="responsive_count d-xl-none d-lg-none d-md-none d-sm-block d-block"><div class="res_round">'+count_value+'</div></div><input type="hidden" value="'+count_value+'" class="round" name="delivery_point[]"><input type="hidden" class="sending_type_class" name="sending_type[]" value="'+value_sending+'"><div class="form-group"><label>Delivery Area</label><input type="text" class="input deliverey_location_class" id="delivery_location_'+count_value+'" value="" required name="delivery_location[]"  placeholder="Enter Delivery Location"></div></div><div class="col-lg-6"><div class="form-group"><label>Contact Person Name</label><input type="text" class="input" name="contact_person_delivery[]" required  placeholder="Enter Contact Person Name"></div></div><div class="col-lg-6"><div class="form-group"><label>Mobile Number</label><input type="number" class="input" required name="delivery_mobile[]"  placeholder="Enter Contact Mobile Number" minlength="10" maxlength="10" ></div></div><div class="col-lg-12"><div class="form-group"><label>What are you Sending?</label><div class="sending_type">'+html+'</div></div></div></div></div></div><div class="col-lg-1 col-md-1 col-sm-12 col-12 margin_top_20 d-sm-none d-none d-xl-block d-lg-block d-md-block"><div class="round_count"><div class="round">'+count_value+'</div><div class="dooted_empty"></div></div></div><div class="col-lg-5 col-md-5 col-sm-12 col-12"><div class="width_100 margin_top_20"><div id="map-canvas_'+count_value+'" style="height:300px;width:100%;"></div><style>#map-canvas_'+count_value+' img {max-width: none!important;background: none!important}</style><input type="hidden" name="delivery_latitude[]" id="delivery_latitude_'+count_value+'" value=""><input type="hidden" name="delivery_longitude[]" id="delivery_longitude_'+count_value+'" value=""></div></div></div></div></div');    
    init_map_automatic(11.0168,76.9558,count_value)
  });

$("body").on("click",".remove_location",function(e){
       $(this).parents('.delivery_location').remove();
       $(".dooted_empty").last().removeClass("dotted");		
       var count = 1;
       $(".round_count").each(function(){
       	$(this).find(".round").html(count);       	
       	count++;
       })
       var res_round = 1;
       $(".step2_form").each(function(){
        $(this).find(".res_round").html(res_round);         
        res_round++;
       })
       
       var count_filed = 1;
       $(".step2_form").each(function(){        
        $(this).find(".round").val(count_filed);        
        count_filed++;
       })
  });