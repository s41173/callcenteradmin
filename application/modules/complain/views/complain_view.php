<!--<meta http-equiv="Refresh" content="60">-->
 <!-- Datatables CSS -->
<link href="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>js/datatables/dataTables.tableTools.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/icheck/flat/green.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url(); ?>js/moduljs/complain.js"></script>
<script src="<?php echo base_url(); ?>js-old/register.js"></script>

<!--canvas js-->
<script type="text/javascript" src="<?php echo base_url().'js-old/' ?>canvasjs.min.js"></script>

<!-- Date time picker -->
 <script type="text/javascript" src="http://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
 <!-- Include Date Range Picker -->
<script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<style type="text/css">
    
    .normal_p{ text-decoration: line-through; margin: 0;}
    .discount_p { color: red;}
</style>

<!-- bootstrap toogle -->
<!--<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>-->

<script type="text/javascript">

	var sites_add  = "<?php echo site_url('complain/add_process/');?>";
	var sites_edit = "<?php echo site_url('complain/update_process/');?>";
	var sites_del  = "<?php echo site_url('complain/delete/');?>";
	var sites_get  = "<?php echo site_url('complain/update/');?>";
    var sites_confirmation  = "<?php echo site_url('complain/confirmation/');?>";
    var sites_print_invoice  = "<?php echo site_url('complain/invoice/');?>";
    var sites_primary   = "<?php echo site_url('complain/publish/');?>";
	var sites_attribute = "<?php echo site_url('complain/attribute/');?>";
    var sites  = "<?php echo site_url('complain');?>";
	var source = "<?php echo $source;?>";
    
    var url  = "<?php echo $graph;?>";
	
    $(document).ready(function (e) {
    
     //chart render
	
	$.getJSON(url, function (result) {
		
		var chart = new CanvasJS.Chart("chartcontainer", {

			theme: "theme1",//theme1
			axisY:{title: "", },
  		    animationEnabled: true, 
			data: [
				{
					type: "column",
					dataPoints: result
				}
			]
		});

		chart.render();
	});
	
	//chart render
        
    // document ready end	
    });
    
</script>

          <div class="row"> 
          
            <div class="col-md-12 col-sm-12 col-xs-12">
                  
              <!--  batas xtitle 2  -->    
                
              <div class="x_panel" >
                   
              <!-- xtitle -->
              <div class="x_title">
                
               <h2> Order Filter </h2>
                
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                </ul>
                
                <div class="clearfix"></div>
              </div>
              <!-- xtitle -->
                
                <div class="x_content">
           
           <!-- searching form -->
           
           <form id="searchform" class="form-inline">
             
            <div class="form-group">
                <label class="control-label labelx"> Period : </label> <br>  
                <input type="text" title="Date" class="form-control" id="ds1" name="tdates" /> 
    <?php // $js = "class='select2_single form-control' id='ccategory' tabindex='-1' style='min-width:260px;' "; 
    // echo form_dropdown('ccategory', $damage, isset($default['category']) ? $default['category'] : '', $js); ?>
                   &nbsp;
            </div> 
            
             <div class="form-group">
                <label class="control-label labelx"> Ticket No : </label> <br>  
                <input name="tticket" id="tticket" class="form-control" style="width:150px;"> &nbsp;
            </div>  
             
            <div class="form-group">
                <label class="control-label labelx"> Reporter : </label> <br>  
                <input name="tcustomer" id="tcustomer" class="form-control" style="width:150px;"> &nbsp;
            </div>   
               
            <div class="form-group">
                <label class="control-label labelx"> Reporter Phone : </label> <br>  
                <input name="tphone" id="tphone" class="form-control" style="width:150px;"> &nbsp;
            </div>      
              
          <div class="form-group">
           <br>   
           <div class="btn-group">      
           <button type="submit" class="btn btn-primary button_inline"> Filter </button>
           <button type="reset" onClick="" class="btn btn-success button_inline"> Clear </button>
           <button type="button" onClick="load_data();" class="btn btn-danger button_inline"> Reset </button>
           </div>
          </div>
          </form> <br>

         <!-- searching form -->
           
              
<form class="form-inline" id="cekallform" method="post" action="<?php echo ! empty($form_action_del) ? $form_action_del : ''; ?>">
      <!-- table -->

      <div class="table-responsive">
        <?php echo ! empty($table) ? $table : ''; ?>            
      </div>

      <div class="form-group" id="chkbox">
        Check All : 
        <button type="submit" id="cekallbutton" class="btn btn-danger btn-xs">
           <span class="glyphicon glyphicon-trash"></span>
        </button>
      </div>
      <!-- Check All Function -->
</form>       
             </div>

            <div class="btn-group">
<a class="btn btn-primary" href="<?php echo site_url('complain/add'); ?>"> <i class="fa fa-plus"></i>&nbsp;Add New </a>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> Report </button>

               <!-- links -->
               <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?>
               <!-- links -->      
            </div>
            </div>
          </div>  
    
      <!-- Modal - Add Form -->
      <div class="modal fade" id="myModal" role="dialog">
         <?php $this->load->view('complain_report_panel'); ?>   
      </div>
      <!-- Modal - Add Form -->
              
      <!-- Modal - Add Form -->
      <div class="modal fade" id="myModal1" role="dialog">
         <?php $this->load->view('complain_confirm'); ?>   
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
