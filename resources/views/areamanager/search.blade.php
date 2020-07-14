@extends('areamanagerheader')
@section('content')
<!-- Content Header (Page header) -->


<div class="col-lg-12 col-md-12 col-sm-12 col-12 areamanager_right_panel">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-12">
			<div class="main_title">Search</div>
		</div>
	</div>
	<div class="row">
        <div class="col-lg-12">
            <?php
              if(Session::has('message')) { ?>
                  <p class="alert alert-info">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo Session::get('message'); ?>
                        
                    </p>
              <?php }
              ?>
        </div>
    <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search by Shop</label>
          <input type="text" class="form-control search_shop" placeholder="Search by Shop"  style="padding-right: 50px;">
          <label id="shop_search-error" class="error" for="area_name" style="display: none;">Please Enter Shop</label>

          <a href="javascript:" class="my-1 search_icon search_shop_icon"><i class="fas fa-search search_shop_icon"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search Account Number</label>
          <input type="number" class="form-control search_account" placeholder="Search Account Number"  style="padding-right: 50px;">
          <label id="account_search-error" class="error" for="area_name" style="display: none;">Please Enter Account Number</label>

          <a href="javascript:" class="my-1 search_icon search_account_icon"><i class="fas fa-search search_account_icon"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label style="display: none;">Search Postcode</label>
          <input type="text" class="form-control search_postcode" placeholder="Search Postcode"  style="padding-right: 50px;">
          <label id="postcode_search-error" class="error" for="area_name" style="display: none;">Please Enter Postcode</label>

          <a href="javascript:" class="my-1 search_icon search_postcode_icon"><i class="fas fa-search search_postcode_icon"></i></a>
        </div>
      </div>
		
		<div class="col-lg-12">
      
			<div class="table-responsive">
        <table class="table table-striped" id="shop_table">
          <thead class="thead-dark">
            <tr>              
              <th>Shop Details</th>
              
            </tr>
          </thead>
          <tbody id="shop_tbody">
            

            


                        
          </tbody>
      </table>
  </div>
		</div>
	</div>
		</div>
	</div>
</div>                
<!-- /.content -->

<script type="text/javascript">




$('#shop_table').DataTable({        
  autoWidth: true,
  scrollX: false,
  fixedColumns: false,
  searching: false,
  paging: false,
  info: false
});

$(window).click(function(e) {

if($(e.target).hasClass("search_shop_icon")){  
  var value = $(".search_shop").val(); 
  var route_id = $(".route_id").val(); 
  if(value == ''){
    $("#shop_search-error").show();
  }
  else{
    $("#shop_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:1},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}

if($(e.target).hasClass("search_account_icon")){  
  var value = $(".search_account").val(); 
  var route_id = $(".route_id").val(); 
  if(value == ''){
    $("#account_search-error").show();
  }
  else{
    $("#account_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:2},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}


if($(e.target).hasClass("search_postcode_icon")){  
  var value = $(".search_postcode").val();
  var route_id = $(".route_id").val();  
  if(value == ''){
    $("#postcode_search-error").show();
  }
  else{
    $("#postcode_search-error").hide();
    $(".loading_css").addClass("loading");
    $("#data_table").dataTable().fnDestroy();
    $.ajax({
    url:"<?php echo URL::to('areamanager/search_common')?>",
    type:"post",
    dataType:"json",
    data:{value:value,route_id:route_id,type:3},
    success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
  }
}

})



$(".search_shop").keypress(function( event ) {
  var value = $(this).val();  
  if(event.which == 13 ) {
    if(value == ''){
      $("#shop_search-error").show();
    }
    else{
      $("#shop_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,type:1},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})

$(".search_account").keypress(function( event ) {
  var value = $(this).val();
  var route_id = $(".route_id").val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#account_search-error").show();
    }
    else{
      $("#account_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,route_id:route_id,type:2},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})



$(".search_postcode").keypress(function( event ) {
  var value = $(this).val();
  var route_id = $(".route_id").val();
  if(event.which == 13 ) {
    if(value == ''){
      $("#postcode_search-error").show();
    }
    else{
      $("#postcode_search-error").hide();
      $(".loading_css").addClass("loading");
      $("#data_table").dataTable().fnDestroy();
      $.ajax({
      url:"<?php echo URL::to('areamanager/search_common')?>",
      type:"post",
      dataType:"json",
      data:{value:value,route_id:route_id,type:3},
      success: function(result) { 
        $("#shop_tbody").html(result['output_shop']); 
        $('#data_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });       
        $(".loading_css").removeClass("loading");        
      } 
    })
    }    
  }  
})

</script>
@stop