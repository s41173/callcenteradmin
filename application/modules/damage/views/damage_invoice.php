<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> Bukti Kerusakan </title>
<style media="all">

	#logo { margin:0 0 0 75px;}
	#logotext{ font-size:12px; text-align:center; margin:0; }
	p { margin:0; padding:0; font-size:11px;}
	#pono{ font-size:18px; padding:0; margin:0 5px 10px 0; text-align:left;}
	
	table.product
	{ border-collapse:collapse; width:100%; }
	
	table.product,table.product th
	{	border: 1px solid black; font-size:13px; font-weight:bold; padding:4px 0 4px 0; }
	
	table.product,table.product td
	{	border: 1px solid black; font-size:12px; font-weight:normal; padding:3px 0 3px 0; text-align:center; }
	
	table.product td.left { text-align:left; padding:3px 5px 3px 10px; }
	table.product td.right { text-align:right; padding:3px 10px 3px 5px; }
	
</style>
</head>

<script type="text/javascript">
    
    function closeWindow() {
        setTimeout(function() {
        window.close();
        }, 300000);
    }
    
</script>    
    
<body onLoad="closeWindow()">

<div style="width:750px; font-family:Arial, Helvetica, sans-serif; font-size:12px;"> 
	
	<h2 style="font-size:18px; font-weight:normal; text-align:center; text-decoration:underline;"> Bukti Kerusakan </h2> <div style="clear:both; "></div> 
	
	<div style="width:350px; border:0px solid #000; float:left;">
		<table style="font-size:11px;">
			<tr> <td> Code </td> <td>:</td> <td> <?php echo $pid; ?> </td> </tr>
            <tr> <td> Category </td> <td>:</td> <td> <?php echo $category; ?> </td> </tr>
            <tr> <td> Periode </td> <td>:</td> <td> <?php echo $date.' - '.$due; ?> </td> </tr>
            <tr> <td> Status </td> <td>:</td> <td> <?php echo $status; ?> </td> </tr>
            <tr> <td> Estimasi </td> <td>:</td> <td> <?php echo $estimate; ?> </td> </tr>
		</table>
	</div>
    
    <div style="border:0px solid; float:right;">
		<table style="font-size:11px;">
			<tr> <td> Pelanggan </td> <td>:</td> <td> <?php echo $custname; ?> </td> </tr>
            <tr> <td> Telp </td> <td>:</td> <td> <?php echo $custphone; ?> </td> </tr>
            <tr> <td> Jenis </td> <td>:</td> <td> <?php echo $custtype; ?> </td> </tr>
		</table>
	</div>
    

	<div style="clear:both; "></div>
	
	<div style="margin:3px 0 0 0; border-bottom:0px dotted #000;">
		
		<table class="product">

		 <tr> <th> Keterangan </th> <th> Alamat </th> <th> Kordinat </th> <th> Staff </th> </tr>
		 
		 <tr> 
         <td class="left"> <?php echo $description; ?> </td>
         <td class="left"> <?php echo $address; ?> </td>
         <td class="left"> <?php echo $coordinate; ?> </td>
         <td class="right"> <?php echo $staff; ?>,- </td> </tr>   
			
		</table>
		
		<div style="clear:both; "></div>
		
		<div style="width:620px; border:0px solid #000; float:right; margin:3px 0px 0 0;">
		<style>
			.sig{ font-size:11px; width:100%; float:right; text-align:center;}
			.sig td{ width:155px;}
		</style>
			<table border="0" class="sig">
				<tr> <td> Disetujui Oleh : </td> <td> Dibuat Oleh : </td> </tr>
			</table> <br> <br> <br> <br> <br> <br>
			
			<table border="0" class="sig">
				<tr> <td> Manager </td> <td> ( <?php echo $user; ?> ) </td> </tr>
			</table>
		</div>
		
		<!--<div style="float:right;">
			
			<table>
				<p> &nbsp; &nbsp; Dipesan Oleh, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh, </p> <br> <br> <br> <br>
				<p style="text-align:right;"> (  ) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (_______________) </p>
				<p> &nbsp; &nbsp; &nbsp; Purchasing  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Direktur Utama </p>
			</table>
			<br>
		</div> -->
		
		<div style="clear:both; ">
		
	</div>	
    </div>
</div>

</body>
</html>
