$(document).ready(function (e) {
	
    // function general
	$("#addressbox").hide();
	$('#datatable-buttons').dataTable({dom: 'T<"clear">lfrtip', tableTools: {"sSwfPath": site}});
	 
	// // date time picker
	$('#d1,#d2,#d3,#d4,#d5').daterangepicker({
		 locale: {format: 'YYYY/MM/DD'}
    }); 


	$('#ds1,#ds2').daterangepicker({
        locale: {format: 'YYYY-MM-DD hh:mm A'},
		singleDatePicker: true,
		timePicker:true,
        showDropdowns: true
	 });
	 
	 $('#ds3,#ds4,#ds3_update,#ds4_update').daterangepicker({
        locale: {format: 'YYYY-MM-DD hh:mm A'},
		singleDatePicker: true,
		timePicker:true,
        showDropdowns: true
	 });

	load_data();  
	
	// batas dtatable

	// fungsi jquery update transaction item
	$(document).on('click','.text-update',function(e)
	{	e.preventDefault();
		var element = $(this);
		var del_id = element.attr("id");
		var url = sites_get_item+"/"+ del_id;
		
		$("#myModal").modal('show');

		$.ajax({
			type: 'POST',
			url: url,
    	    cache: false,
			headers: { "cache-control": "no-cache" },
			success: function(result) {

				res = result.split("|");

			// 0$acc->id.'|'.
			// 1 $acc->sales_id.'|'.
			// 2 $acc->passenger.'|'.
			// 3 $acc->idcard.'|'.
			// 4 $acc->source.'|'.
			// 5  $acc->dates.'|'.
			// 6 $acc->source_desc.'|'.
			// 7  $acc->destination.'|'.
			// 8  $acc->destination_desc.'|'.
			// 9  $acc->return_dates.'|'.
			// 10  $acc->ticketno.'|'.
			// 11  $acc->bookcode.'|'.
			// 12 $acc->airline.'|'.
			// 13 $acc->vendor.'|'.
			// 14 $acc->price.'|'.
			// 15 $acc->amount.'|'.
			// 16 $acc->hpp.'|'.
			// 17 $acc->discount.'|'.
			// 18 $acc->tax.'|'.
			// 19 $acc->returns;

			    // console.log(res[13]);

				$("#tid").val(res[0]);
				$("#tsid").val(res[1]);
				$("#tpassenger_update").val(res[2]);
				$("#tidcard_update").val(res[3]);
				$("#cdepart_update").val(res[4]);
				$("#ds3_update").val(res[5]);
				$("#tdepartdesc_update").val(res[6]);
				$("#carrived_update").val(res[7]);
				$("#tarriveddesc_update").val(res[8]);
				$("#ds4_update").val(res[9]);
				$("#tticketno_update").val(res[10]);
				$("#tbook_update").val(res[11]);
				$("#cairline_update").val(res[12]);
				$("#tprice_update").val(res[14]);
				$("#cvendor_update").val(res[13]).change();
				$("#tcapital_update").val(res[16]);
				$("#tdiscount_update").val(res[17]);
				$("#ctax_update").val(res[18]);
				$("#ttax").val(res[18]);
				if (res[19] == 'TRUE'){ $("#ckreturn_update").prop('checked', true);
				}else{ $("#ckreturn_update").prop('checked', false); }
			}
		})
		return false;	
		
	});
	
		// fungsi jquery update
	$(document).on('click','.text-print',function(e)
	{	e.preventDefault();
		var element = $(this);
		var del_id = element.attr("id");
		var url = sites_print_invoice +"/"+ del_id +"/invoice";
		
		// window.location.href = url;
		window.open(url, "_blank", "scrollbars=1,resizable=0,height=600,width=800");
		
	});
	
	// publish status
	$(document).on('click','.primary_status',function(e)
	{	
		e.preventDefault();
		var element = $(this);
		var del_id = element.attr("id");
		var url = sites_primary +"/"+ del_id;
		$(".error").fadeOut();
		
		// $("#myModal2").modal('show');
		// batas
		$.ajax({
			type: 'POST',
			url: url,
    	    cache: false,
			headers: { "cache-control": "no-cache" },
			success: function(result) {
				
				res = result.split("|");
				if (res[0] == "true")
				{   
			        error_mess(1,res[1],0);
					load_data();
				}
				else if (res[0] == 'warning'){ error_mess(2,res[1],0); }
				else{ error_mess(3,res[1],0); }
			}
		})
		return false;
	});
	
   // fungsi ajax form sales
	$('#salesformdata').submit(function() {

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data) {
				
				res = data.split("|");
				if (res[0] == "true")
				{   
					error_mess(1,res[1],0);
					$("#noticket").val(res[3]);
					$("#buttonsave").prop('disabled', true);

					setTimeout(function(){ location.reload(); }, 45000);
				}
				else if (res[0] == 'warning'){ error_mess(2,res[1],0); }
				else{ error_mess(3,res[1],0); }
			},
			error: function(e) 
	    	{
				$("#error").html(e).fadeIn();
				console.log(e.responseText);	
	    	} 
		})
		return false;
	});

	// ajax transaction data 
	$('#ajaxtransform,#ajaxtransform1').submit(function() {

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data) {
				
				res = data.split("|");
				if (res[0] == "true")
				{   
			        error_mess(1,res[1],0);
					location.reload(true);
				}
				else if (res[0] == 'warning'){ error_mess(2,res[1],0); }
				else{ error_mess(3,res[1],0); }
			},
			error: function(e) 
	    	{
				$("#error").html(e).fadeIn();
				console.log(e.responseText);	
	    	} 
		})
		return false;
	});

		// fungsi ajax get customer
	$(document).on('click','#brequest',function(e)
	{	
		e.preventDefault();
		
		var nama = $("#nama").val();
		var meter = $("#meter").val();
		var no = $("#no").val();
		var id = $("#id").val();
		var url = sites+'/request';

		if (nama != '' || no != '' || id != '' || meter != ''){

			// batas
			$.ajax({
				type: 'POST',
				url: url,
				data: "nama="+nama+"&no="+no+"&id="+id+"&meter="+meter,
				cache: false,
				headers: { "cache-control": "no-cache" },
				success: function(result) {
				if (result){

				//	console.log(result);

				   var res = result.split('|');
				   $("#no").val(res[0]);
				   $("#nama").val(res[1]);
				   $("#id,#hcust").val(res[2]);
				   $("#meter").val(res[3]);
				   $("#alamat,#taddress").val(res[4]);
				   $("#no,#nama,#id,#meter").prop('disabled', true);
				}
				
				// $("#temail").val(res[0]);
				// $("#tshipadd,#tshipaddkurir").val(res[1]);

				}
			})
			return false;

		}else{ swal('Parameter Required...!', "", "error"); }

	});

	// fungsi get damage type
	$(document).on('change','#ccategoryform',function(e)
	{	
		e.preventDefault();
		
		var value = $(this).val();
		var url = sites+'/combo_damage/'+value;
		$("#addressbox").val('');
		$("#addressbox").hide();
		if (value != 0){

			// batas
			$.ajax({
				type: 'POST',
				url: url,
				cache: false,
				headers: { "cache-control": "no-cache" },
				success: function(result) {
				  if (result){ $("#damagebox").html(result);}
				}
			})
			return false;
		}else{ $("#damagebox").html(""); }

	});

	$(document).on('change','#cdamage',function(e)
	{	
		e.preventDefault();
		
		var value = $(this).val();
		var url = sites+'/get_address/'+value;

		if (value != 0){

			// batas
			$.ajax({
				type: 'POST',
				url: url,
				cache: false,
				headers: { "cache-control": "no-cache" },
				success: function(result) {
				  if (result){
					   $("#addressbox").show();
					   $("#addressbox").val(result);
				  }
				}
			})
			return false;
		}else{ $("#damagebox").html(""); }

	});


	$(document).on('click','#breset',function(e)
	{	
		e.preventDefault();
		$("#no").val('');
		$("#nama").val('');
		$("#meter").val('');
		$("#alamat,#taddress").val('');
		$("#id,#hcust").val('');
		$("#no,#nama,#id,#meter").prop('disabled', false);
	});


	$('#searchform').submit(function() {
		
		var ticket = $("#tticket").val();
		var customer = $("#tcustomer").val();
		var category = $("#ccategory").val();
		var param = ['searching',ticket,customer,category];
		
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data) {
				
				if (!param[1]){ param[1] = 'null'; }
				if (!param[2]){ param[2] = 'null'; }
				if (!param[3]){ param[3] = 'null'; }
				load_data_search(param);
			}
		});
		return false;
		swal('Error Load Data...!', "", "error");
		
	});	


		
// document ready end	
});


	function load_data_search(search)
	{
		$(document).ready(function (e) {
			
			var oTable = $('#datatable-buttons').dataTable();
			var stts = 'btn btn-danger';
			
		    $.ajax({
				type : 'GET',
				url: source+"/"+search[0]+"/"+search[1]+"/"+search[2]+"/"+search[3],
				//force to handle it as text
				contentType: "application/json",
				dataType: "json",
				success: function(s) 
				{   
					console.log(s);
					oTable.fnClearTable();
					$(".chkselect").remove()
	
		$("#chkbox").append('<input type="checkbox" name="newsletter" value="accept1" onclick="cekall('+s.length+')" id="chkselect" class="chkselect">');
							
		for(var i = 0; i < s.length; i++) {
			oTable.fnAddData([
'<input type="checkbox" name="cek[]" value="'+s[i][0]+'" id="cek'+i+'" style="margin:0px"  />',
						  i+1,
						  s[i][1],
						  s[i][2],
						  s[i][3],
						  s[i][4],
						  s[i][5],
						  s[i][7],
'<div class="btn-group" role"group">'+
'<a href="" class="btn btn-success btn-xs text-print" id="' +s[i][0]+ '" title="Invoice Status"> <i class="fa fa-print"> </i> </a> '+
'<a href="#" class="btn btn-danger btn-xs text-danger" id="'+s[i][0]+'" title="delete"> <i class="fa fas-2x fa-trash"> </i> </a>'+
'</div>'
							  ]);										
							  } // End For 
											
				},
				error: function(e){
				   oTable.fnClearTable();  
				   //console.log(e.responseText);	
				}
				
			});  // end document ready	
			
        });
	}

    // fungsi load data
	function load_data()
	{
		$(document).ready(function (e) {
			
			var oTable = $('#datatable-buttons').dataTable();
			var stts = 'btn btn-danger';
			
		    $.ajax({
				type : 'GET',
				url: source,
				//force to handle it as text
				contentType: "application/json",
				dataType: "json",
				success: function(s) 
				{   
				       console.log(s);
					  
						oTable.fnClearTable();
						$(".chkselect").remove()
	
		$("#chkbox").append('<input type="checkbox" name="newsletter" value="accept1" onclick="cekall('+s.length+')" id="chkselect" class="chkselect">');
							
							for(var i = 0; i < s.length; i++) {
						  oTable.fnAddData([
'<input type="checkbox" name="cek[]" value="'+s[i][0]+'" id="cek'+i+'" style="margin:0px"  />',
										i+1,
										s[i][1],
										s[i][2],
										s[i][3],
										s[i][4],
										s[i][5],
										s[i][7],
'<div class="btn-group" role"group">'+
'<a href="" class="btn btn-success btn-xs text-print" id="' +s[i][0]+ '" title="Invoice Status"> <i class="fa fa-print"> </i> </a> '+
'<a href="#" class="btn btn-danger btn-xs text-danger" id="'+s[i][0]+'" title="delete"> <i class="fa fas-2x fa-trash"> </i> </a>'+
'</div>'
										    ]);										
											} // End For 
											
				},
				error: function(e){
				   oTable.fnClearTable();  
				   console.log(e.responseText);	
				}
				
			});  // end document ready	
			
        });
	}
	
	// batas fungsi load data
	function resets()
	{  
	   $(document).ready(function (e) {
		  // reset form
		  $("#tname, #tmodel, #tsku").val("");
		  $("#catimg").attr("src","");
	  });
	}
	
	function load_form()
	{
		$(document).ready(function (e) {
			
		  	$.ajax({
				type : 'GET',
				url: source,
				//force to handle it as text
				contentType: "application/json",
				dataType: "json",
				success: function(data) 
				{   
					// alert(data[0][1]);
					$("#tname").val(data[0][1]);
					$("#taddress").val(data[0][2]);
					$("#ccity").val(data[0][13]).change();
					$("#tzip").val(data[0][9]);
					$("#tphone").val(data[0][3]);
					$("#tphone2").val(data[0][4]);
					$("#tmail").val(data[0][5]);
					$("#tbillmail").val(data[0][6]);
					$("#ttechmail").val(data[0][7]);
					$("#tccmail").val(data[0][8]);
					$("#taccount_name").val(data[0][10]);
					$("#taccount_no").val(data[0][11]);
					$("#tbank").val(data[0][12]);
					$("#tsitename").val(data[0][14]);
					$("#tmetadesc").val(data[0][15]);
					$("#tmetakey").val(data[0][16]);
					$("#catimg_update").attr("src","");
					$("#catimg_update").attr("src",base_url+"images/property/"+data[0][17]);
			   
				},
				error: function(e){
				   //console.log(e.responseText);	
				}
				
			});  
			
	    });  // end document ready	
	}
	