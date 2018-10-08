
 <!-- Datatables CSS -->
<link href="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/icheck/flat/green.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url(); ?>js/moduljs/damage.js"></script>
<script src="<?php echo base_url(); ?>js-old/register.js"></script>

<!-- Date time picker -->
 <script type="text/javascript" src="http://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
 <!-- Include Date Range Picker -->
<script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


<script type="text/javascript">

	var sites_add  = "<?php echo site_url('damage/add_process/');?>";
	var sites_edit = "<?php echo site_url('damage/update_process/');?>";
	var sites_del  = "<?php echo site_url('damage/delete/');?>";
	var sites_get  = "<?php echo site_url('damage/update/');?>";
    var sites_ajax  = "<?php echo site_url('damage/');?>";
	var source = "<?php echo $source;?>";
	
</script>
          
<!-- map function -->
    <!-- Place this tag in your head or just before your close body tag. -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIyA_tbgcPHkf0NaVCgJZ3KtiCbYRaD0I"></script>
<script type="text/javascript">

$(document).ready(function (e) {
    
var lat = 2.926884;
var long = 99.041911;
    
var sites_ajax  = "<?php echo site_url('damage/');?>";
var iconkurir = "<?php echo base_url().'images/kurir.png';?>";
var iconperson = "<?php echo base_url().'images/person.png';?>"; 
var pos = setInterval(getposinterval, 10000);
getpos();   
function getpos() {
    
    $.get(sites_ajax+"/get_loc_all/", function(data, status){
        
        var markerx = [];  
        var obj = JSON.parse(data);
        
        if (obj){
            for (var i=0;i<obj.length;i++){
                var cor = obj[i].coordinate.split(',');
                markerx[i] = [obj[i].userid]; 
                markerx[i][1] = cor[0];
                markerx[i][2] = cor[1];
            }
            initMap(markerx);
        }
    });
}
    
function getposinterval() {
      
    $.get(sites_ajax+"/get_loc_all/", function(data, status){
        
        var markerx = [];  
        var obj = JSON.parse(data);
        
        for (var i=0;i<obj.length;i++){
            var cor = obj[i].coordinate.split(',');
            markerx[i] = [obj[i].description]; 
            markerx[i][1] = cor[0];
            markerx[i][2] = cor[1];
        }
        addMarker(markerx);
    });
}
    
var map;
var markers = [];

function initMap(locations) {
        var haightAshbury = {lat: lat, lng: long};

        map = new google.maps.Map(document.getElementById('mapCanvas'), {
          zoom: 10,
          center: haightAshbury,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Adds a marker at the center of the map.
        addMarker(locations);
}

      // Adds a marker to the map and push to the array.
function addMarker(locations) {
          
        deleteMarkers();
        for (count = 0; count < locations.length; count++) {
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[count][1], locations[count][2]),
          map: map,
          title: locations[count][0]
        }); markers.push(marker);
      }  
}

      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }

    
// document ready end	
});    
    
</script>
          
<!-- map function -->

          <div class="row"> 
          
            <div class="col-md-12 col-sm-12 col-xs-12">
             
              <div class="x_panel" >
              
              <!-- xtitle -->
              <div class="x_title">
                
               <h2> Filter </h2>
                
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                </ul>
                
                <div class="clearfix"></div>
              </div>
              <!-- xtitle -->
                
                <div class="x_content">
                
<!--
    <style type="text/css">
        #mapCanvas{ width: 100%; height: 400px; }  
    </style>
            
    <div class="x_content" id="mapCanvas"> </div>
-->
           
           <!-- searching form -->
           
           <form id="searchform" class="form-inline">
             
       <div class="form-group">
           <label> Category : </label> <br>
           <?php $js = "class='select2_single form-control' id='ccategory_search' tabindex='-1' style='width:210px;' "; 
           echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?>
       </div>
             
      <div class="form-group">
           <label> Status : </label> <br>
           <select name="cstatus" class="select2_single form-control" id="cstatus_search" style="width:170px;">
               <option value="0"> Progress </option>
               <option value="1"> Completed </option>
           </select>
       </div>
              
              <div class="btn-group"> 
                <label></label> <br>  
               <button type="submit" class="btn btn-primary button_inline"> Filter </button>
               <button type="reset" onClick="" class="btn btn-success button_inline"> Clear </button>
               <button type="button" onClick="load_data();" class="btn btn-danger button_inline"> Reset </button>
              </div>
          </form> <br>
           
           <!-- searching form -->
           
              
          <form class="form-inline" id="cekallform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
                  <!-- table -->
                  
                  <?php echo ! empty($table) ? $table : ''; ?>            
                  
<!--
                  <div class="form-group" id="chkbox">
                    Check All : 
                    <button type="submit" id="cekallbutton" class="btn btn-danger btn-xs">
                       <span class="glyphicon glyphicon-trash"></span>
                    </button>
                  </div>
-->
                  <!-- Check All Function -->
                  
          </form>       
             </div>

               <!-- Trigger the modal with a button --> 
   <div class="btn-group">
   
   <button type="button" onclick="resets()" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> 
       <i class="fa fa-plus"></i>&nbsp;Add New 
   </button>
   
   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal3"> Report  </button>

   <!-- links -->
   <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?>
   <!-- links -->
   </div>
                             
            </div>
            
<!-- map -->
       
 <div class="x_panel" >
              
              <!-- xtitle -->
              <div class="x_title">
                
               <h2> Map </h2>
                
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                </ul>
                
                <div class="clearfix"></div>
              </div>
              <!-- xtitle -->
                
            <div class="x_content">
                
    <style type="text/css">
        #mapCanvas{ width: 100%; height: 450px; }  
    </style>
            
    <div class="x_content" id="mapCanvas"> </div>
           
             </div>          
</div>
                   
<!-- map -->
            
          </div>  
    
      <!-- Modal - Add Form -->
      <div class="modal fade" id="myModal" role="dialog">
         <?php $this->load->view('damage_form'); ?>      
      </div>
      <!-- Modal - Add Form -->
              
       <!-- Modal - Add Form -->
      <div class="modal fade" id="myModal2" role="dialog">
         <?php $this->load->view('damage_update'); ?>      
      </div>
      <!-- Modal - Add Form -->
      
       <!-- Modal - Add Form -->
      <div class="modal fade" id="myModal4" role="dialog">
         <?php $this->load->view('damage_confirmation'); ?>      
      </div>
      <!-- Modal - Add Form -->
      
      
      <!-- Modal - Report Form -->
      <div class="modal fade" id="myModal3" role="dialog">
         <?php $this->load->view('damage_report_panel'); ?>    
      </div>
      <!-- Modal - Report Form -->
      
      <script src="<?php echo base_url(); ?>js/icheck/icheck.min.js"></script>
      
       <!-- Datatables JS -->
        <script src="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.scroller.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.tableTools.js"></script>
