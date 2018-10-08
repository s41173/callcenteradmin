<div class="modal-dialog">
        
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title"> Edit - Damage Confirmation </h4>
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
 
 <form id="edit_form_non" data-parsley-validate class="form-horizontal form-label-left" method="POST" 
 action="<?php echo site_url('damage/confirmation_process'); ?>" enctype="multipart/form-data">

      <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> Due Date </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" id="tid_confirmation" name="tid">
            <input type="text" title="Due Date" class="form-control" id="ds4_update" name="tduedates" /> 
        </div>
      </div>
         
       <div class="col-md-11 col-sm-9 col-xs-12 form-group"> <br>
        <label class="control-label col-md-3 col-sm-3 col-xs-12"> In Charge </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" title="In Charge Staff" class="form-control" id="tstaff_update_confirm" name="tstaff" /> 
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