<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" >
<title> <?php echo isset($title) ? $title : ''; ?>  </title>
<style media="all">
	table{ font-family:"Arial", Times, serif; font-size:11px;}
	h4{ font-family:"Arial", Times, serif; font-size:14px; font-weight:600;}
	.clear{clear:both;}
	table th{ background-color:#EFEFEF; padding:4px 0px 4px 0px; border-top:1px solid #000000; border-bottom:1px solid #000000;}
    p{ font-family:"Arial", Times, serif; font-size:12px; margin:0; padding:0;}
	legend{font-family:"Arial", Times, serif; font-size:13px; margin:0; padding:0; font-weight:600;}
	.tablesum{ font-size:13px;}
	.strongs{ font-weight:normal; font-size:12px; border-top:1px dotted #000000; }
	.poder{ border-bottom:0px solid #000000; color:#0000FF;}
</style>

    <link rel="stylesheet" href="<?php echo base_url().'js-old/jxgrid/' ?>css/jqx.base.css" type="text/css" />
    
	<script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxdata.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.columnsreorder.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.aggregates.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxdata.export.js"></script>
	<script type="text/javascript" src="<?php echo base_url().'js-old/jxgrid/' ?>js/jqxgrid.export.js"></script>
    
     <script type="text/javascript">
	
        $(document).ready(function () {
          
			var rows = $("#table tbody tr");
                // select columns.
                var columns = $("#table thead th");
                var data = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var datarow = {};
                    for (var j = 0; j < columns.length; j++) {
                        // get column's title.
                        var columnName = $.trim($(columns[j]).text());
                        // select cell.
                        var cell = $(row).find('td:eq(' + j + ')');
                        datarow[columnName] = $.trim(cell.text());
                    }
                    data[data.length] = datarow;
                }
                var source = {
                    localdata: data,
                    datatype: "array",
                    datafields:
                    [
                        { name: "No", type: "string" },
						{ name: "Code", type: "string" },
                        { name: "Date", type: "string" },
						{ name: "Due", type: "string" },
                        { name: "Category", type: "string" },
						{ name: "Description", type: "string" },
						{ name: "Address", type: "string" },
                        { name: "Coordinate", type: "string" },
						{ name: "Approval", type: "string" },
                        { name: "Status", type: "string" },
                        { name: "Interval", type: "string" },
                        { name: "Staff", type: "string" },
                        { name: "Log", type: "string" }
                    ]
                };
			
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                width: '100%',
				source: dataAdapter,
				sortable: true,
				filterable: true,
				pageable: true,
				altrows: true,
				enabletooltips: true,
				filtermode: 'excel',
				autoheight: true,
				columnsresize: true,
				columnsreorder: true,
				showstatusbar: true,
				statusbarheight: 30,
				showaggregates: true,
				autoshowfiltericon: false,
                columns: [
                  { text: 'No', dataField: 'No', width: 50 },
   				  { text: 'Code', dataField: 'Code', width : 100 },
                  { text: 'Date', dataField: 'Date', width : 150 },
                  { text: 'Due', dataField: 'Due', width : 150 },
				  { text: 'Category', dataField: 'Category', width : 200 },
				  { text: 'Description', dataField: 'Description', width : 220 },
                  { text: 'Address', dataField: 'Address', width : 250 },
                  { text: 'Coordinate', dataField: 'Coordinate', width : 200 },
				  { text: 'Approval', dataField: 'Approval', width : 110 },
                  { text: 'Status', dataField: 'Status', width : 120 },
                  { text: 'Interval', dataField: 'Interval', width : 100 },
                  { text: 'Staff', dataField: 'Staff', width : 180 },
                  { text: 'Log', dataField: 'Log', width : 120 }
                ]
            });
			
			$('#jqxgrid').jqxGrid({ pagesizeoptions: ['100', '300', '500', '1000', '5000']}); 
			
			$("#bexport").click(function() {
				
				var type = $("#crtype").val();	
				if (type == 0){ $("#jqxgrid").jqxGrid('exportdata', 'html', 'Damage-Summary'); }
				else if (type == 1){ $("#jqxgrid").jqxGrid('exportdata', 'xls', 'Damage-Summary'); }
				else if (type == 2){ $("#jqxgrid").jqxGrid('exportdata', 'pdf', 'Damage-Summary'); }
				else if (type == 3){ $("#jqxgrid").jqxGrid('exportdata', 'csv', 'Damage-Summary'); }
			});
			
			$("#table").hide();
			
		// end jquery	
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	
	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Period </td> <td> : </td> <td> <?php echo tglin($start); ?> to <?php echo tglin($end); ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">	
	       <h4> <?php echo isset($company) ? $company : ''; ?> <br> Damage Report </h4>
	   </div>
	</center>
	
	<div class="clear"></div>
	
	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:1px dotted #000000; ">
		
        <div id='jqxWidget'>
        <div style='margin-top: 10px;' id="jqxgrid"> </div>
        
        <table style="float:right; margin:5px;">
        <tr>
        <td> <input type="button" id="bexport" value="Export"> - </td>
        <td> 
        <select id="crtype"> <option value="0"> HTML </option> <option value="1"> XLS </option>  <option value="2"> PDF </option> 
        <option value="3"> CSV </option> 
        </select>
        </td>
        </tr>
        </table>
        
        </div>
        
		<table id="table" border="0" width="100%">
        
           <thead>
<tr> <th> No </th> <th> Code </th> <th> Date </th> <th> Due </th> <th> Category </th> <th> Description </th>
     <th> Address </th> <th> Coordinate </th> <th> Approval </th> <th> Status </th> <th> Interval </th> <th> Staff </th> <th> Log </th>
</tr>
           </thead>
		   
          <tbody> 		   
		  <?php 
		  	
			  function category($val)
			  {
				  $category = new Category_lib();
				  return strtoupper($category->get_name($val));
			  }
              
              function statusapproval($val){
                  if ($val == 0){ return 'N'; }else{ return 'Y'; }
              }
              
              function statusprogress($val){
                  if ($val == 0){ return 'Progress'; }else{ return 'Completed'; }
              }
              
              function interval($val){
                  $category = new Damage_lib();
				  return $category->get_interval($val);
              }
		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $report)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
                       <td class=\"strongs\"> DM-0".$report->id."</td> 
                       <td class=\"strongs\">".tglincompletetime($report->dates)."</td>
                       <td class=\"strongs\">".tglincompletetime($report->due)."</td>
					   <td class=\"strongs\">".category($report->category)."</td> 
					   <td class=\"strongs\">".$report->description."</td> 
                       <td class=\"strongs\">".$report->address."</td>
                       <td class=\"strongs\">".$report->coordinate."</td>
					   <td class=\"strongs\">".statusapproval($report->approved)."</td>
                       <td class=\"strongs\">".statusprogress($report->status)."</td>
                       <td class=\"strongs\">".interval($report->id)."</td>
                       <td class=\"strongs\">".strtoupper($report->staff)."</td>
                       <td class=\"strongs\">".$report->log."</td>
				   </tr>";
				   $i++;
				}
			  }  
		  ?>
		  </tbody> 
		</table>
	</div>

</div>
<a style="float:left; margin:10px;" title="Back" href="<?php echo site_url('damage'); ?>"> 
  <img src="<?php echo base_url().'images/back.png'; ?>"> 
</a>
</body>
</html>
