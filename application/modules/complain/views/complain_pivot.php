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
	.poder{ border-bottom:0px solid #000000; color:#0000FF; font-size:9pt;}
	.red{ border-bottom:0px solid #000000; color:#900; font-size:10pt;}
</style>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'js-old/pivot/' ?>pivot.css">
	  <script type="text/javascript" src="<?php echo base_url().'js-old/pivot/' ?>jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/pivot/' ?>jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/pivot/' ?>jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'js-old/pivot/' ?>pivot.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

			var input = $("#input")
			$("#output").pivotUI(input);
			$("#input").hide();
        });
    </script>

</head>

<body>

<div style="width:100%; border:0px solid blue; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

	<div style="border:0px solid red; float:left;">
		<table border="0">
			<tr> <td> Period </td> <td> : </td> <td> <?php echo $start.' - '.$end; ?> </td> </tr>
			<tr> <td> Run Date </td> <td> : </td> <td> <?php echo $rundate; ?> </td> </tr>
			<tr> <td> Log </td> <td> : </td> <td> <?php echo $log; ?> </td> </tr>
		</table>
	</div>

	<center>
	   <div style="border:0px solid green; width:230px;">
	      <h4> <?php echo isset($company) ? $company : ''; ?> <br> Complain - Report (Pivot Table) </h4>
	   </div>
	</center>

	<div class="clear"></div>

	<div style="width:100%; border:0px solid brown; margin-top:20px; border-bottom:0px dotted #000000; ">

    	<div id='jqxWidget'>
        <div style='margin-top: 10px;' id="output"> </div>
        </div>

		<table id="input" border="0" width="100%">
		   <thead>
           <tr>
<th> No </th> <th> Type </th> <th> District </th> <th> Ticket </th> <th> Date </th> <th> Customer </th>
<th> Category </th> <th> Damage </th> <th> Status </th> <th> Interval </th> <th> Log </th>
           </tr>
           </thead>
		  
          <tbody> 
		  <?php 
              
              function category($val)
              {
                  $res = new Category_lib(); 
                  return strtoupper($res->get_name($val));
              }
              
              function damage($val)
              {
                  $res = new Damage_lib(); 
                  return 'DM-0'.$val.' : '.strtoupper($res->get_name($val));
              }
              
              function damage_status($val)
              {
                  $res = new Damage_lib(); 
                  return strtoupper($res->get_status($val));
              }
              
              function user($val){
                  $res = new Log_lib(); 
                  $user = new Admin_lib(); 
                  return $user->get_username($res->get_user($val));
              }
              
              function interval($val)
              {
                  $res = new Damage_lib(); 
                  return $res->get_interval($val);
              }
              
              function approval($val){ if ($val == 0){ return 'N'; }else{ return 'Y'; } }
              function district($val){ if ($val == 0){ return 'Pusat'; }else{ return 'Cabang'; } }
              function type($val){ if ($val == 0){ return 'Pelanggan'; }else{ return 'Non'; } }
			  		  
		      $i=1; 
			  if ($reports)
			  {
				foreach ($reports as $res)
				{	
				   echo " 
				   <tr> 
				       <td class=\"strongs\">".$i."</td> 
                       <td class=\"strongs\">".type($res->type)."</td>
                       <td class=\"strongs\">".district($res->district)."</td>
                       <td class=\"strongs\">".$res->ticketno."</td> 
                       <td class=\"strongs\">".tglincompletetime($res->dates)."</td> 
                       <td class=\"strongs\">".$res->name.' - '.$res->phone."</td>
                       <td class=\"strongs\">".category($res->category)."</td>
                       <td class=\"strongs\">".damage($res->damage)."</td>
                       <td class=\"strongs\">".damage_status($res->damage)."</td>
                       <td class=\"strongs\">".interval($res->damage)."</td>
                       <td class=\"strongs\">".user($res->log)." / ".$res->log."</td>
				   </tr>";
				   $i++;
				}
			 }  
		  ?>
		</tbody>      
		</table>
	</div>
	
     <a style="float:left; margin:10px;" title="Back" href="<?php echo site_url('complain'); ?>"> 
        <img src="<?php echo base_url().'images/back.png'; ?>"> 
     </a>
    
</div>

</body>
</html>
