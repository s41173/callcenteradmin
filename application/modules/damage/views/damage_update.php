<div class="modal-dialog">
        
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title"> Edit - Damage </h4>
</div>
<div class="modal-body">

 <!-- error div -->
 <div class="alert alert-success success"> </div>
 <div class="alert alert-warning warning"> </div>
 <div class="alert alert-error error"> </div>
 
 <!-- form add -->
<div class="x_panel" >

<div class="x_content">

<?php
    
$atts1 = array(
	  'class'      => 'btn btn-primary button_inline',
	  'title'      => 'COA - List',
	  'width'      => '600',
	  'height'     => '400',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 600)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 400)/2)+\'',
);

?>
 
 
<script type="text/javascript">
    var markers = [];
    window.onload = function () {
        var mapOptions = {
            center: new google.maps.LatLng(2.926884, 99.041911),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMapupdate"), mapOptions);
 
        //Attach click event handler to the map.
        google.maps.event.addListener(map, 'click', function (e) {
 
            DeleteMarkers();
            
            //Determine the location where the user has clicked.
            var location = e.latLng;
            document.getElementById("tccordinate_update").value = location.lat()+","+location.lng();
            getcoor(location.lat(),location.lng());
 
            //Create a marker and placed it on the map.
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
 
            //Attach click event handler to the marker.
            google.maps.event.addListener(marker, "click", function (e) {
                var infoWindow = new google.maps.InfoWindow({
                    content: 'Latitude: ' + location.lat() + '<br />Longitude: ' + location.lng()
                });
                infoWindow.open(map, marker);
            });
 
            //Add marker to the array.
            markers.push(marker);
        });
    };
    function DeleteMarkers() {
        //Loop through all the markers and remove
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    };
    
    // fungsi untuk set address berdasarkan lat, long
  function getcoor(lat,long){

//     var lat = document.getElementById("hlat").value;
//     var long = document.getElementById("hlong").value;    

     var url = "https://maps.googleapis.com/maps/api/geocode/json?address="+lat+","+long+"&key=AIzaSyCIyA_tbgcPHkf0NaVCgJZ3KtiCbYRaD0I&callback";

     $.get(url, function(data, status){
        var add = data.results[0].formatted_address;
        $("#taddress_update").val(add);
     });   
  }
    
</script>

 <form id="edit_form_non" data-parsley-validate class="form-horizontal form-label-left" method="POST" 
 action="<?php echo $form_action_update; ?>" enctype="multipart/form-data">
    
       <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Trans Date </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" id="tid" name="tid">
            <input type="text" title="Date" class="form-control" id="ds2_update" name="tdates" /> 
        </div>
      </div>
      
      <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Due Date </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" title="Due Date" class="form-control" id="ds3_update" name="tduedates" /> 
        </div>
      </div>
      
      <div class="col-md-11 col-sm-9 col-xs-12 form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Category </label>
        <div class="col-md-6 col-sm-6 col-xs-12">    
           <?php $js = "class='form-control' id='ccategory_update' tabindex='-1' style='width:210px;' "; 
           echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?>
        </div>
      </div>
      
       <div class="col-md-11 col-sm-9 col-xs-12 form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Coordinate </label>
        <div class="col-md-9 col-sm-9 col-xs-12">    
          <input type="text" class="form-control" name="tccordinate" id="tccordinate_update">
        </div>
      </div>
       
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <textarea class="form-control" name="tdesc" id="tdesc_update" rows="3" placeholder="Description"></textarea>
      </div>
         
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <textarea class="form-control" name="taddress" id="taddress_update" rows="4" placeholder="Address"></textarea>
        <div id="dvMapupdate" style="width:100%; height: 450px; margin: 10px 0 0 0;"> </div>
      </div>
         
       <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> In Charge </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" title="In Charge Staff" class="form-control" id="tstaff_update" name="tstaff" /> 
        </div>
      </div>
          
      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3 btn-group">
          <button type="submit" class="btn btn-primary" id="button">Save</button>
          <button type="button" id="bclose" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" id="breset" class="btn btn-warning" onClick="reset();">Reset</button>
        </div>
      </div>
</form> 

</div>
</div>
<!-- form add -->

</div>
    <div class="modal-footer"> </div>
</div>
  
</div>