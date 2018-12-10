<div class="modal-dialog">
        
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title"> Add - Damage Problem </h4>
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

<form id="upload_form" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
    
      <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Trans Date </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" title="Date" class="form-control" id="ds1" name="tdates" /> 
        </div>
      </div>
      
      <div class="col-md-11 col-sm-9 col-xs-12 form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Category </label>
        <div class="col-md-6 col-sm-6 col-xs-12">    
           <?php $js = "class='select2_single form-control' id='ccategory' tabindex='-1' style='width:210px;' "; 
           echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?>
        </div>
      </div>
      
      <div class="col-md-11 col-sm-9 col-xs-12 form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Coordinate </label>
        <div class="col-md-9 col-sm-9 col-xs-12">    
          <input type="text" class="form-control" name="tccordinate" id="tccordinate">
        </div>
      </div>
       
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <textarea class="form-control" name="tdesc" rows="3" placeholder="Description"></textarea>
      </div>
         
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <textarea class="form-control" name="taddress" id="taddress" rows="4" placeholder="Address"></textarea>
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