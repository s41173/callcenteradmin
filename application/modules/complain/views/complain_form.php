 <!-- Datatables CSS -->
<link href="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/icheck/flat/green.css" rel="stylesheet" type="text/css">

<!-- Date time picker -->
 <script type="text/javascript" src="http://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
 <!-- Include Date Range Picker -->
<script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


<style type="text/css">
  a:hover { text-decoration:none;}
</style>

<script src="<?php echo base_url(); ?>js/moduljs/complain.js"></script>
<script src="<?php echo base_url(); ?>js-old/register.js"></script>

<script type="text/javascript">

	var sites_add  = "<?php echo site_url('complain/add_process/');?>";
	var sites_edit = "<?php echo site_url('complain/update_process/');?>";
	var sites_del  = "<?php echo site_url('complain/delete/');?>";
	var sites_get  = "<?php echo site_url('complain/update/');?>";
    var sites_get_item  = "<?php echo site_url('complain/update_item/');?>";
    var sites  = "<?php echo site_url('complain');?>";
	var source = "<?php echo $source;?>";
    var url  = "<?php echo $graph;?>";
	
</script>

          <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel" >
              
              <!-- xtitle -->
              <div class="x_title">
              
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                </ul>
                
                <div class="clearfix"></div>
              </div>
              <!-- xtitle -->
                
                <div class="x_content">
                    
<!--
  <div id="errors" class="alert alert-danger alert-dismissible fade in" role="alert"> 
     <?php // $flashmessage = $this->session->flashdata('message'); ?> 
	 <?php // echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> 
  </div>
-->
  
  <div id="step-1">
    <!-- form -->
    <form id="salesformdata" data-parsley-validate class="form-horizontal form-label-left" method="POST" 
    action="<?php echo $form_action; ?>" 
      enctype="multipart/form-data">
		
    <style type="text/css">
       .xborder{ border: 1px solid red;}
       #custtitlebox{ height: 205px; background-color: #E0F7FF; border-top: 3px solid #2A3F54; margin-bottom: 10px; }
        #amt{ color: #000; margin-top: 35px; text-align: right; font-weight: bold;}
        #amt span{ color: blue;}
        .labelx{ font-weight: bold; color: #000;}
        #table_summary{ font-size: 16px; color: #000;}
        .amt{ text-align: right;}
        .custtext{ margin: 3px; float: left; width: 210px;}
    </style>

<!-- form atas   -->
    <div class="row">
       
<!-- div untuk customer place  -->
       <div id="custtitlebox" class="col-md-12 col-sm-12 col-xs-12">
            
           <div class="form-group">
               
               <div class="col-md-4 col-sm-12 col-xs-12">
                   <label class="control-label labelx"> * Pelanggan </label>
                   <table>
<tr> <td> <input type="text" name="no" id="no" class="form-control custtext" placeholder="No Pelanggan"> </td> </tr>
<tr> <td> <input type="text" name="id" id="id" class="form-control custtext" placeholder="ID Pelanggan"> </td> 
     <td> 
     <button type="button" class="btn btn-success" id="brequest" style="float:left; margin:0 0 0 10px;"> GET </button> 
     </td>
<td> <button type="button" class="btn btn-warning" id="breset" style="float:left; margin:0 0 0 3px;"> RESET </button> </td>
</tr>
<tr> <td> <input type="text" name="nama" id="nama" class="form-control custtext" placeholder="Nama Pelanggan"> 
          <input type="hidden" name="hcust" id="hcust" >
     </td> </tr>
<tr> <td> <input type="number" name="meter" id="meter" class="form-control custtext" placeholder="No Meter"> </td> </tr>
                   </table>
                 
               </div>
               
               <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
    <label class="control-label labelx"> Jenis Keluhan </label>
    <table>
<tr> <td> <?php $js = "class='form-control' id='ccategoryform' tabindex='-1' style='min-width:260px;' "; 
     echo form_dropdown('ccategory', $category, isset($default['category']) ? $default['category'] : '', $js); ?> 
</td> 
<td>
   <button type="button" class="btn btn-primary" data-toggle="modal" style="float:left; margin:0 0 0 3px;" data-target="#myModal"> New Damage </button>
</td>
</tr>
<tr> <td> <div id="damagebox"></div> </td> </tr>

    </table>
               </div>
                 
           </div>
           
       </div>
<!-- div untuk customer place  -->

<!-- div tgl transaksi -->
    <div class="col-md-9 col-sm-12 col-xs-12">
       
       <div class="col-md-12 col-sm-12 col-xs-12">
          <label class="control-label labelx"> Description </label>
          <textarea class="form-control" name="tdescription" id="tdescription" rows="5"></textarea>
       </div>
           
    </div>
<!-- div tgl transaksi -->        

</div>
<!-- form atas   -->
      
      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-9">
          <div class="btn-group">    
          <button type="submit" class="btn btn-success" id="button"> Save </button>
          <a class="btn btn-danger" href="<?php echo site_url('complain'); ?>"> Cancel </a> 
          <a class="btn btn-primary" href="<?php echo site_url('sales/add/'); ?>"> New Transaction </a> 
          </div>
        </div>
      </div>
      
	</form>
      
    <!-- end div layer 1 -->
      
<!-- form transaction table  -->
             
<?php
                        
$atts2 = array(
	  'class'      => 'btn btn-primary button_inline',
	  'title'      => 'Product',
	  'width'      => '800',
	  'height'     => '600',
	  'scrollbars' => 'yes',
	  'status'     => 'yes',
	  'resizable'  => 'yes',
	  'screenx'    =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
	  'screeny'    =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
);

?>      
        
  </div>
                  
     </div>
       
       <!-- links -->
       <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?>
       <!-- links -->
               
    </div>
  </div>
     
  <!-- Modal - Add Form -->
  <div class="modal fade" id="myModal" role="dialog">
     <?php $this->load->view('damage_form'); ?>      
  </div>
      <!-- Modal - Add Form -->
      
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
    
<!-- jQuery Smart Wizard -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/wizard/jquery.smartWizard.js"></script>

    <!-- jQuery Smart Wizard -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#wizard').smartWizard();

    $('#wizard_verticle').smartWizard({
      transitionEffect: 'slide'
    });

  });
</script>
<!-- /jQuery Smart Wizard -->
    