<div class="modal-dialog">
        
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title"> Complain Confirmation </h4>
</div>
<div class="modal-body">

 <!-- error div -->
 <div class="alert alert-success success"> </div>
 <div class="alert alert-warning warning"> </div>
 <div class="alert alert-error error"> </div>
 
 <!-- form add -->
<div class="x_panel" >
<div class="x_title">
  
  <div class="clearfix"></div> 
</div>
<div class="x_content">

 <form id="edit_form_non" data-parsley-validate class="form-horizontal form-label-left" method="POST" 
 action="<?php echo site_url('complain/confirmation'); ?>" enctype="multipart/form-data">
   
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
<input type="text" class="form-control has-feedback-left" id="treporter" readonly>
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span> 
                    <input type="hidden" id="tid" name="tid">
                  </div>
                    
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <input type="text" class="form-control" id="tphone_confirm" readonly>
                    <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span> 
                  </div>
                  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
     <input type="text" class="form-control has-feedback-left" readonly id="tticket_confirm" readonly>
     <span class="fa fa-book form-control-feedback left" aria-hidden="true"></span> 
                  </div>
                  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
     <input type="text" class="form-control" id="tdatestime" readonly>
                    <span class="fa fa-book form-control-feedback right" aria-hidden="true"></span> 
                  </div>
     
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
     <input type="text" class="form-control has-feedback-left" readonly id="tcustid" readonly>
     <span class="fa fa-book form-control-feedback left" aria-hidden="true"></span> 
                  </div>
                  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <input type="tel" name="tphone" class="form-control" id="tcustname" readonly>
                    <span class="fa fa-book form-control-feedback right" aria-hidden="true"></span> 
                  </div>
                  
                  <!-- pembatas div -->
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">  
                  </div> 
                   <!-- pembatas div -->
                  
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Category </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
<?php $js = "class='form-control' id='ccategoryform' tabindex='-1' style='width:100%;' "; 
echo form_dropdown('ccategory', $category_child, isset($default['category']) ? $default['category'] : '', $js); ?>
                    </div>
                  </div>
     
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Damage </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
<table style="width:100%">
    <tr> <td> <div id="damagebox"></div> </td> </tr>
    <tr> <td> <textarea id="addressbox" class="form-control" readonly></textarea> </td> </tr>
</table>
                    </div>
                  </div>
                  
                 
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Address </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
<textarea name="taddress" id="tcustaddress" class="form-control" rows="3"><?php echo set_value('taddress', isset($default['address']) ? $default['address'] : ''); ?></textarea>
                    </div>
                  </div>
     
                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Description </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
<textarea name="tdescription" id="tdescription" class="form-control" rows="3" readonly></textarea>
                    </div>
                  </div>
                  
                  
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
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