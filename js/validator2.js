$(document).ready(function() {

//Fadeout for error message
setTimeout(function() {
	$('.mydiv').remove();
	$('#errormsgcus').hide();
	$('#errormsgdev').hide();
	$('#errormsgopen').hide();
	$('#errormsgstock').hide();
	$('#errormsgmetrics').hide();
	$('#errormsgcustra').hide();
	$('#errormsgcol').hide();
	$('#errormsgveh').hide();
	$('#errormsgcycle').hide();
	$('#errormsgcycleajx').hide();
	$('#errormsgcystart').hide();
	$('#errormsgcuscon').hide();
	$('#errormsgsalcol').hide();
	$('#errormsgindopen').hide();
	$('#errormsgsrperf').hide();	
},5000);


/* SETUP PARAM  */
// hide or show by default
if($("#Data_Transfer").val()==='0') {
	$('#Data_Transfer').selectedIndex = '0';
	$("#textbox1").attr("disabled", "disabled");
	$("#start").attr("disabled", "disabled"); 
	$("#end").attr("disabled", "disabled"); 
 }
if($("#Data_Transfer").val()==='1') {
	$('#Data_Transfer').selectedIndex = '1';
	$("#textbox1").attr("enabled", "enabled"); 
	$("#start").attr("enabled", "enabled"); 
	$("#end").attr("enabled", "enabled"); 
 } 
/* SETUP PARAM  END */

//Fancy Box
$('a[rel*=facebox]').facebox(); 
						   
$('.ask').jConfirmAction();

$(function(){
	$('#closebutton').button({
		icons: {
			primary : "../images/close_pop.png",
		},
		text:false
	});

	$('#closebutton_cus').button({
		icons: {
			primary : "../images/close_pop.png",
		},
		text:false
	});

	$('#closebutton_blue').button({
		icons : {
			primary : "..images/close_pop.png"
		},
		text:false

	});

	$('#closebutton_blue').click(function(event) {
		$('#errormsg').hide();
		$('#errorbigmsg').hide();
		$('#errormsgmetrics').hide();
		$('#errormsgcustra').hide();
		$('#errormsgveh').hide();
		$('#errormsgcycle').hide();
		$('#errormsgcuscon').hide();
		$('#errormsgsalcol').hide();
		return false;
	});

	$('#closebutton').click(function(event) {
		//alert('232');
		$('#errormsg').hide();
		$('#errorbigmsg').hide();
		$('#errormsgcus').hide();
		$('#errormsgdev').hide();
		$('#errormsgopen').hide();
		$('#errormsgstock').hide();
		$('#errormsgcol').hide();
		$('#errormsgcycleajx').hide();
		$('#errormsgcystart').hide();
		$('#errormsgindopen').hide();
		$('#errormsgdevreg').hide();
		$('#errormsgpopupprod').hide();
		$("#errormsgrouteplan").hide();
		$("#errormsgmonplan").hide();
		$("#errormsgtgt").hide();
		$('#errormsgcredit').hide();
		$('#errormsgcovtgt').hide();
		$('#errormsgsrinc').hide();
		$('#errormsgsrpercent').hide();
		$('#errormsgposmtgt').hide();
		$('#errormsgposmcov').hide();
		$('#errormsgmetrep').hide();
		$('#errormsgsrperf').hide();
		return false;
	});
	$('#closebutton_cus').click(function(event) {
		//alert('232');
		$('#errormsgcus').hide();
		$('#errormsgsrinc').hide();
		$('#errormsgsrpercent').hide();
		return false;
	});
	$('#closebutton_cre').click(function(event) {
		//alert('232');
		$('#errormsgcredit').hide();
		return false;
	});
});


$(function() {
	  if ($.browser.msie && $.browser.version.substr(0,1)<7)
	  {
		$('li').has('ul').mouseover(function(){
			$(this).children('ul').show();
			}).mouseout(function(){
			$(this).children('ul').hide();
			})
	  }
	}); 	



//Sorting MYSQL Result SET
$("#rounded-corner").tablesorter({sortList: [[0,0], [1,0]]}); 
$("#sort").tablesorter({sortList: [[1,1], [0,0]]});

//popup
$(function() {
	$( "#datepicker" ).datepicker();
});	

//popup
$(function() {
	$( ".datepicker" ).datepicker();
});	
//open popup
$(".popup").click(function(){
  $("#overlay_form").fadeIn(1000);
  positionPopup();
});

//close popup
$("#close").click(function(){
	$("#overlay_form").fadeOut(500);
});


//position the popup at the center of the page
function positionPopup(){
  if(!$("#overlay_form").is(':visible')){
    return;
  } 
  $("#overlay_form").css({
      left: ($(window).width() - $('#overlay_form').width()) / 2,
      top: ($(window).width() - $('#overlay_form').width()) / 7,
      position:'absolute'
  });
}

//maintain the popup at center of the page when browser resized
$(window).bind('resize',positionPopup);


//product Validation
$("#validation").validate({
    invalidHandler: function(form, validator) {
      var errors = validator.numberOfInvalids();
	  if (errors) {
        var message ='&nbsp;&nbsp;Please Enter ALL Mandatory Fields';
        $("#messageBox").html(message);
        $("#messageBox").show();
      } else {
        $("#messageBox").hide();
      }
    },
    showErrors: function(errorMap, errorList) {
    },
submitHandler: function() {
	 $("#messageBox").hide(); 
	//alert("Submit!") 
	 if ($(form).valid()) 
     form.submit(); 
     return false; // prevent normal form posting
}
});


//Receipts Validation
$("#stockvalidation").validate({
	onkeyup:false,
	onkeydown:false,
    invalidHandler: function(form, validator) {
      var errors = validator.numberOfInvalids();
	  if (errors) {
        var message ='&nbsp;&nbsp;Please Enter ALL Mandatory Fields';
        $("#messageBox").html(message);
        $("#messageBox").show();
      } else {
        $("#messageBox").hide();
      }
    },
    showErrors: function(errorMap, errorList) {
    },
	submitHandler: function() {
	 $("#messageBox").hide(); 
	//alert("Submit!") 
	 if ($(form).valid()) 
     form.submit(); 
     return false; // prevent normal form posting
}
});


$('#product_names').on("change",function() {
	if($(this).val() == '') {
		$('#showerr').show();
	} else {
		$('#showerr').hide();
	}
});


$('select[name="supplier_category"]').live("change", function() {		
	if($(this).val() == '') {
		//$('#supcaterr').show();
	} else {
		var sup_cat		=	$('select[name="supplier_category"]').val();
		//alert(sup_cat);
		//alert($('select[name="supplier_category"]').val());
		if(sup_cat == 'Fareast' || sup_cat == 'FAREAST' || sup_cat == 'fareast' || sup_cat == 'FMCL' || sup_cat == 'fmcl' || sup_cat == 'Fmcl') {
			$('input[name="supplier_name"]').val('Fareast');
			$('input[name="supplier_name"]').attr('readonly',true);			
		} else if (sup_cat == 'DSR Return' || sup_cat == 'DSR return' || sup_cat == 'DSR RETURN' || sup_cat == 'DSRReturn' || sup_cat == 'DSRreturn' || sup_cat == 'DSRRETURN' || sup_cat == 'Dsrreturn' || sup_cat == 'DsrReturn' || sup_cat == 'DsrRETURN') {				
			$.ajax({
				url : "changeCategoryContent.php",
				type: "get",
				dataType:"text",
				data: { "sup_cat" : sup_cat  },
				success: function(data) {
					var dataCat	=	$.trim(data);
					$('#mainareastockstatic').html(dataCat);
					$('input[name="supplier_name"]').removeAttr('readonly');
					$('input[name="supplier_name"]').val('');
				}				
			});

		} else if (sup_cat == 'CUSTOMER Return' || sup_cat == 'CUSTOMER return' || sup_cat == 'CUSTOMER RETURN' || sup_cat == 'Customer Return' || sup_cat == 'Customer return' || sup_cat == 'Customer RETURN' || sup_cat == 'CUSTOMERReturn' || sup_cat == 'CUSTOMERreturn' || sup_cat == 'CUSTOMERRETURN' || sup_cat == 'Customerreturn' || sup_cat == 'CustomerReturn' || sup_cat == 'CustomerRETURN') {
			$.ajax({
				url : "changeCategoryCusContent.php",
				type: "get",
				dataType:"text",
				data: { "sup_cat" : sup_cat  },
				success: function(data) {
					var dataCat	=	$.trim(data);
					$('#mainareastockstatic').html(dataCat);
					$('input[name="supplier_name"]').removeAttr('readonly');
					$('input[name="supplier_name"]').val('');
				}				
			});

		} else {
			$('input[name="supplier_name"]').removeAttr('readonly');
			$('input[name="supplier_name"]').val('');
			/*$.ajax({
				url : "changeOldCategoryContent.php",
				type: "get",
				dataType:"text",
				data: { "sup_cat" : sup_cat  },
				success: function(data) {
					var dataCat	=	$.trim(data);
					$('#mainareastock').html(dataCat);
					$('input[name="supplier_name"]').removeAttr('disabled');
					$('input[name="supplier_name"]').val('');

				}				
			});*/
		}
		$('#supcaterr').hide();
	}
});


});

var codevalue					=		'';

//Clear Field Value For ALL Forms
function valClear()
{
	document.location.href='register.php';
}
function forgot_clear()
{
	document.location.href='forgotPassword.php';
}

function changePWD_clear()
{
	document.location.href='changePassword.php';
}

function deviceclr()
{
	document.location.href='devicemaster.php';
}

function dsrclear()
{
	document.location.href='dsr.php';
}

function vehicleclr()
{
	document.location.href='vehicle.php';
}

function cusclr()
{
	document.location.href='customer.php';
}

function deviceregclr()
{
	document.location.href='deviceReg.php';
}

function kdclr()
{
	document.location.href='KD_information.php';
}

function routeclr()
{
	document.location.href='route.php';
}

function systemParam()
{
	document.location.href='setupParam.php';
}

function saleclr()
{
	document.location.href='sales_collection.php';
}

function cycleclr()
{
	document.location.href='cycle_assignment.php';
}



//Change Password
function changePwd(val)
{
        $.ajax({
            url: 'get_changePassword.php?val=' + val,
            success: function(data) {
				var value=$.trim(data);//To Remove White Space in string
				var value1=data.substring(0,value.length-1);//To return part of the string
				var list= value1.split("|"); 
				var newHTML = [];
				var newHTML1 = [];
				newHTML.push("<option value='' selected='selected'>Select email</option>");
				newHTML1.push("<option value='' selected='selected'>Select password</option>");
				for (var i=0; i<list.length; i++) {
					var arr_i= list[i].split("^");
					$(".email").val(arr_i[1]);
					$(".old_pass").val(arr_i[2]);
					}

			}
        });
}	//End Of Change Password

//Set Up Parameters
function param()
{
	//alert("dsf");
	var val1=$('#masterName option:selected').text();
	if(val1=='Product'){
	$(".pdt").show();
	$(".scheme").hide();
	$(".productScheme").hide();
	}
	else if(val1=='Scheme')
	{
	$(".scheme").show();
	$(".pdt").hide();
	$(".productScheme").hide();
	}
	else if(val1=='KD')
	{
	$(".KD").show();
	$(".pdt").hide();
	$(".scheme").hide();
	}
}	//End Of Set Up Parameters

//Download Upload configuration
function DUConfig()
{
var val=$('#uploaddownload option:selected').text();
if(val=='Download'){
$(".folderName").val('Download From Channel Partner');
$(".username").val('Admin');
$(".password").val('Admin');
$(".servername").val('Host.com');
}
else
{
$(".folderName").val('Upload to Channel Partner');
$(".username").val('Admin');
$(".password").val('Admin');
$(".servername").val('Host.com');
}
}	//End Of Download Upload configuration


//Download Upload Status
function DUStatus()
{
var val=$('#uploaddownload option:selected').text();
if(val=='Download'){
$(".status").show();
$(".statusU").hide();
}
else
{
$(".statusU").show();
$(".status").hide();
}
}	//End Of Download Upload Status

function changeTxn(dateval) {
	$.ajax({ 
		url : "showTransNumAjax.php?dateval="+dateval,
		success: function(data) {
			var dataAajx	=	$.trim(data);
			$('#TransDetails').html(data);
		}
	})
}

/*function getAjaxReceipts() //Start of Ajax for Stock Receipts
{
	//alert('232');
	//var TransNum	=	$("#TxnNum").val();
	if($('select[name="TxnNum"]').val() !='') {
		var TransNum	=	$('select[name="TxnNum"]').val();
	}
	
	
		
	//return false;
	//alert(TransNum);
	//if(TransNum != ''){
		$.ajax({
			url: 'StockReceiptsAjax.php?val=' + TransNum,
			success: function(data) {
				var value	=	$.trim(data); //To Remove White Space in String
				$('#containerpr').html(data);
			}
		});
	//}
	return false;
	
}	//End of Ajax for Stock Receipts
*/

function addproduct() {
	if($('#product_names').val() == ''){
		//$('#showerr').show();
		$('.myaligndev').html('ERR : Choose Product');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}

	var rowcnt			=	$('#prodcnt').val();
	var rowcntcal;
	if(rowcnt == '') {
		rowcntcal		=	1;
	} else {
		rowcntcal		=	parseInt(rowcnt) + 1;
	}
	$('#prodcnt').val(rowcntcal);
	var product_code	=	$('#product_names').val();
	var product_name	=	$('#product_names option:selected').text();
	$('#showerr').hide();
	$('#productsadd').show();
	if(rowcnt == '') {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='pcode_"+rowcntcal+"' />"+product_code+"</td><td align='left'><input type='hidden' value='"+product_name+"' readonly name='pname_"+rowcntcal+"' />"+product_name+"</td><td align='left'><select name='mcs_"+rowcntcal+"' id='mcs_"+rowcntcal+"' ><option value='' >--Select--</option><option value='carton' >Carton</option><option value='PCS'>PCS</option></select></td><td align='center'><input type='text' value='' autocomplete='off' name='qty_"+rowcntcal+"' id='qty_"+rowcntcal+"' /></td></tr>");

		/*<td align='center'><input type='hidden' value='PCS' readonly name='uom_"+rowcntcal+"' />PCS</td>*/
	} else {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='pcode_"+rowcntcal+"' />"+product_code+"</td><td align='left'><input type='hidden' value='"+product_name+"' readonly name='pname_"+rowcntcal+"' />"+product_name+"</td><td align='left'><select name='mcs_"+rowcntcal+"' id='mcs_"+rowcntcal+"' ><option value='' >--Select--</option><option value='carton' >Carton</option><option value='PCS'>PCS</option></select></td><td align='center'><input type='text' value='' autocomplete='off' name='qty_"+rowcntcal+"' id='qty_"+rowcntcal+"' /></td></tr>");

		/*<td align='center'><input type='hidden' value='PCS' readonly name='uom_"+rowcntcal+"' />PCS</td>*/
	}
	return false;
}

function nextrecord(nextId,urlpath) {
	//alert(nextId);
	//alert(urlpath);
	$.ajax({
		url : urlpath,
		dataType:	"text",
		type: "get",
		data : { "nextrec" : nextId },
		success : function(datval) {
			datvalue	=	$.trim(datval);
			//alert(datvalue);
			$('#mainareastockcat').html(datvalue);
		}
	});
}
function prvrecord(prvId,urlpath) {
	$.ajax({
		url					:	urlpath,
		dataType			:	"text",
		type				:	"get",
		data				:	{ "nextrec" :  prvId},
		success				:	function(datval) {
			datvalue		=	$.trim(datval);
			//alert(datvalue);
			$('#mainareastockcat').html(datvalue);
		}
	});
}

function loadDSRCol(DSRCode){
	if(DSRCode == '') {
		$('.myaligncol').html('ERR : Select SR Name');
		$('#errormsgcol').css('display','block');		
		$('input[name="DSR_Code"]').val('');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;		
	} else {
		$('input[name="DSR_Code"]').val(DSRCode);
	}
}


function loadDSR(DSRCode){
	if(DSRCode == '') {
		$('.myalign').html('ERR : Select SR Name');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		$('input[name="DSR_Code"]').val('');
		$('input[name="vehicle_name"]').val('');
		$('input[name="vehicle_code"]').val('');
		$('#suggestedqtyid').html('');
		$('#loadseqno').val('');
		return false;
	} else {
		$.ajax({
			url				:	"checkdsrcycleajax.php",
			dataType		:	"text",
			data			:	{ "DSR_Code":DSRCode },
			type			:	"get",
			success			:	function(dataval) {
				trimval		=	$.trim(dataval);

				if(trimval == "ASSNO") {
					$('.myalign').html('ERR : No Assignment for this DSR');
					$('#errormsgcus').css('display','block');
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('input[name="DSR_Code"]').val('');
					$('input[name="vehicle_name"]').val('');
					$('input[name="vehicle_code"]').val('');
					$('#suggestedqtyid').html('');
					$('#loadseqno').val('');
					return false;
				} else {
					splitval	=	trimval.split("~");
					$('input[name="DSR_Code"]').val(DSRCode);
					$('input[name="vehicle_name"]').val(splitval[1]);
					$('input[name="vehicle_code"]').val(splitval[4]);
					var splitproductwise	=	splitval[6].split("&");
					var splitprodlen		=	splitproductwise.length;
					for(var q = 1; q<=splitprodlen; q++) {
						$('#suggestedqtyid_'+q).html(splitproductwise[q-1]);
						$('#Loaded_Qty_'+q).val(splitproductwise[q-1]);
					}
					$('#loadseqno').val(splitval[7]);
				}
			}
		});		
	}
}

function loadDSRAdj(DSRCode) {
	if(DSRCode == '') {
		$('.myaligndev').html('ERR : Select SR Name');
		$('#errormsgdev').css('display','block');
		$('input[name="DSR_Code"]').val('');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} else {		
		$('input[name="DSR_Code"]').val(DSRCode);	
	}

}

function curPageName() {
	alert("fgg");
//location.reload(true); 
}

function addbank(defcur) {
	if($('#bank_names').val() == ''){
		$('.myaligncol').html('ERR : Choose Bank');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}

	var rowcnt			=	$('#bankcnt').val();
	var rowcntcal;
	if(rowcnt == '') {
		rowcntcal		=	1;
	} else {
		rowcntcal		=	parseInt(rowcnt) + 1;
	}

	$('#bankcnt').val(rowcntcal);
	var bank_name	=	$('#bank_names option:selected').text();
	$('#showerr').hide();
	$('#banksadd').show();
	$('#bankid_'+rowcntcal).show();
	$('#Bank_Name_'+rowcntcal).val(bank_name);
	$('#Bank_Name_Show_'+rowcntcal).html(bank_name);

	//if($('#bank_names').val() == 'Cash' || $('#bank_names').val() == 'CASH' || $('#bank_names').val() == 'cash') {
	if($('#bank_names').val() == 'Cash') {
		$("#Challan_Number_"+rowcntcal).get(0).type = 'hidden';
		$("#Challan_Date_"+rowcntcal).get(0).type = 'hidden';
		
		/*$("#Challan_Number_"+rowcntcal).css({'background-color':'#BFBFBF'});
		$("#Challan_Date_"+rowcntcal).css({'background-color':'#BFBFBF'});	
		$("#Challan_Number_"+rowcntcal).attr('readonly','readonly');
		$("#Challan_Date_"+rowcntcal).removeClass();
		$("#Challan_Date_"+rowcntcal).removeAttr('class');*/

		//alert("233");
	}
	return;
	if(rowcnt == '') {
		var appenedItems = "<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+bank_name+"' readonly name='Bank_Name_"+rowcntcal+"' />"+bank_name+"</td><td align='center'><input type='text' value='' name='Challan_Number_"+rowcntcal+"' id='Challan_Number_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' autocomplete='off' class='datepicker' readonly name='Challan_Date_"+rowcntcal+"' id='Challan_Date_"+rowcntcal+"'  autocomplete='off' /></td><td align='center'><input type='text' value='"+defcur+"' readonly name='Currency_"+rowcntcal+"' /></td><td align='center'><input type='text' value='' onblur='addamount(this.value);' id='Amount_Deposited_"+rowcntcal+"' name='Amount_Deposited_"+rowcntcal+"' id='Amount_Deposited_"+rowcntcal+"' autocomplete='off' /></td></tr>";
		$(appenedItems).appendTo('#banksadded');
	} else {
		$('#banksadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+bank_name+"' readonly name='Bank_Name_"+rowcntcal+"' />"+bank_name+"</td><td align='center'><input type='text' value='' name='Challan_Number_"+rowcntcal+"' id='Challan_Number_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' autocomplete='off' class='datepicker' readonly name='Challan_Date_"+rowcntcal+"' id='Challan_Date_"+rowcntcal+"' /></td><td align='center'><input type='text' value='"+defcur+"' readonly name='Currency_"+rowcntcal+"' /></td><td align='center'><input type='text' value='' onblur='addamount(this.value);' name='Amount_Deposited_"+rowcntcal+"' id='Amount_Deposited_"+rowcntcal+"' autocomplete='off' /></td></tr>");
	}
	return false;
}

function addamount(amtval) {
	//var previous_val = $('input[name="Total_Amount"]').val();
	var rowcnt			=	$('#bankcnt').val();
	//alert(rowcnt);
	var amt = 0;
	for(var k=1; k <= rowcnt; k++) {
		//alert($("#Amount_Deposited_"+k).val());
		var strvalue = $("#Amount_Deposited_"+k).val(); 
		amt += parseInt(strvalue.replace(/,/g,''));
	}	
	//alert(amt);
	amt	=	amt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	$('input[name="Total_Amount"]').val(amt);
	return;
}

function addeditamt(amtval) {
	var balAmount		=	$('#balAmount').val();
	//alert(balAmount);
	//alert(amtval);
	var newAmt			=	parseInt(amtval.replace(/,/g,'')) + parseInt(balAmount.replace(/,/g,''));
	newAmt	=	newAmt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	$('#Total_Amount').val(newAmt);
}
 
function checkdate(input) {
    var validformat = /^\d{4}\-\d{2}\-\d{2}$/ //Basic check for format validity
    var returnval = false
    if (!validformat.test(input.value))
        alert("Invalid Date Format. Please correct and submit again.")
    else { //Detailed check for valid date ranges
        var monthfield = input.value.split("-")[1]
        var dayfield = input.value.split("-")[2]
        var yearfield = input.value.split("-")[0]
        var dayobj = new Date(yearfield, monthfield - 1, dayfield)
        if ((dayobj.getMonth() + 1 != monthfield) || (dayobj.getDate() != dayfield) || (dayobj.getFullYear() != yearfield))
            alert("Invalid Day, Month, or Year range detected. Please correct and submit again.")
        else
            returnval = true
    }
    return returnval
}

/*function getStock() {
	var fromDate	=	$('input[name="fromDate"]').val();
	var toDate		=	$('input[name="toDate"]').val();

	if(fromDate == ''){
		$('#fromerr').html('Choose From Date');
		$('#toerr').html('');
		$('#fromerr').css('color','#FF0000');
		return false;
	}
	if(toDate == ''){
		$('#fromerr').html('');
		$('#toerr').html('Choose To Date');
		$('#toerr').css('color','#FF0000');
		return false;
	}
	
	$.ajax({
		type: "get",
		url : "showstockcontent.php",
		data : { "fromDate" : fromDate, "toDate" : toDate },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			alert(actdata);
		}
	});
}*/

function bringPopup(fromDate,toDate,Product_code,openstock,recqty,issqty,adjqty,errorid,pname) {
	
	if(openstock == ''){
		openstock	=	0;
	}
	if(recqty == ''){
		recqty	=	0;
	}
	if(issqty == ''){
		issqty	=	0;
	}
	if(adjqty == ''){
		adjqty	=	0;
	}	

	var errid	=	errorid+"norecmsg";
	//alert(recqty);
	var totalcnt		=	parseInt(openstock)+parseInt(recqty)+parseInt(issqty)+parseInt(adjqty);
	var recqtynoneg		=	Math.abs(recqty);
	//alert(recqtynoneg);
	var adjqtynoneg		=	(adjqty);
	//alert(adjqtynoneg);
	var issqtynoneg		=	Math.abs(issqty);
	var insertmsg		=	'<table id="sort" border="1" class="tablesorter" width="100%"><thead><tr><th colspan="5" align="left"><h2>Stock Status</h2></th></tr><tr><th colspan="5" align="left">Product Code :   '+Product_code+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product Name :   ' +pname+ ' </th></tr><tr><td align="center"><strong>Opening Stock</strong></td><td align="center"><strong>Receipt</strong></td><td align="center"><strong>Issue</strong></td><td align="center"><strong>Adjustment</strong></td><td align="center"><strong>Closing Stock</strong></td></tr></tr></thead><tbody><tr><td align="center">'+qtyformatreturn(openstock)+'</td><td align="center"><span style="cursor:hand;cursor:pointer;color:#4285F4;" onclick="showrecords(this,\''+fromDate+'\',\''+toDate+'\',\''+Product_code+'\',\'stock_receipts\',\''+errid+'\')"> '+qtyformatreturn(recqtynoneg)+'</span></td><td align="center"><span style="cursor:hand;cursor:pointer;color:#4285F4;" onclick="showrecords(this,\''+fromDate+'\',\''+toDate+'\',\''+Product_code+'\',\'stock_issue\',\''+errid+'\')">'+qtyformatreturn(issqtynoneg)+'</span></td><td align="center"><span style="cursor:hand;cursor:pointer;color:#4285F4;" onclick="showrecords(this,\''+fromDate+'\',\''+toDate+'\',\''+Product_code+'\',\'stock_adjustment\',\''+errid+'\')">'+qtyformatreturn(adjqtynoneg)+'</span></td><td align="center">'+qtyformatreturn(totalcnt)+'</td></tr><tr><td colspan="6" align="center"><span id="'+errid+'" style="display:none;color:#ED2700;"><b>No Records Found.</b></span></td></tr></tbody></table>';		
		//$("#confirmFirstMessage"+postid).css("display","none");
		$("#backgroundChatPopup").css("display","none");
		$(" <div />" ).attr("id","FirstEnq"+Product_code).addClass("confirmShared").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeFirstEnquiry(this,\''+Product_code+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="FirstEnqMsg'+Product_code+'" class="con"></div></p>').appendTo($( "body" ));	
		$("#FirstEnq"+Product_code).css("display","block");
		$("#FirstEnq"+Product_code).css("z-index","100");
		$('#FirstEnqMsg'+Product_code).html(insertmsg);
		$("#backgroundChatPopup").css({"opacity": "0.7"});
		$("#backgroundChatPopup").fadeIn("slow");
		return false;
}

function closeFirstEnquiry(atr,PCode){
	$('#FirstEnq'+PCode).remove();
	$('#FirstEnq'+PCode).css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function closeSecondEnquiry(atr,PCode){
	$('#SecondEnq'+PCode).remove();
	$('#SecondEnq'+PCode).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
}

function showrecords(atyr,fromDate,toDate,Product_code,tblname,errid) {	
	$.ajax({
		type: "get",
		url : "pullindivstock.php",
		data : { "fromDate" : fromDate, "toDate" : toDate, "Pcode" : Product_code, "tblname" : tblname },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			if(actdata == '') {
				//alert(actdata);
				$("#"+errid).css("display","block");
				return;
			}
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","SecondEnq"+Product_code).addClass("confirmSecondSharedStock").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeSecondEnquiry(this,\''+Product_code+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="SecondEnqMsg'+Product_code+'"></div></p>').appendTo($( "body" ));	
			$("#SecondEnq"+Product_code).css("display","block");
			$('#SecondEnqMsg'+Product_code).html(insertmsg);
			return false;
		}
	});
}

function loadvehicle(vhc){
	if(vhc == '') {
		$('input[name="vehicle_code"]').val('');
	} else {
		$('input[name="vehicle_code"]').val(vhc);
	}
}

/* DAILY STOCK LOADING STARTS HERE */

function checkdailystock() {

	var DSRName					=	$('select[name="DSRName"]').val();
	var Dateval					=	$('input[name="Date"]').val();
	var vehicle_name			=	$('select[name="vehicle_name"]').val();
	var prodcnt					=	$('input[name="prodcnt"]').val();

	var currentdate				=	new Date();

	var dte2					=	parseInt(Dateval.substring(8,10),10);
	var mont2					=	(parseInt(Dateval.substring(5,7), 10)) -1;
	var year2					=	parseInt(Dateval.substring(0,4),10);
	var date2					=	new Date(year2,mont2,dte2);
	var y						=	0;
	//alert(prodcnt);
	/*alert(dte2);
	alert(mont2);
	alert(year2);*/

	/*var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);*/

	if(Dateval == ''){
		//$('.myalign').html('ERR : Select Date');
		//$('#errormsgcus').css('display','block');
		$('.myalignprod').html('ERR : Select Date');
		$('#errormsgpopupprod').css('display','block');
		setTimeout(function() {
			$('#errormsgpopupprod').hide();
		},5000);
		return false;
	} else if (date2 > currentdate){
		$('.myalignprod').html('ERR : Date greater than today!');
		$('#errormsgpopupprod').css('display','block');
		setTimeout(function() {
			$('#errormsgpopupprod').hide();
		},5000);
		return false;
	}else if(DSRName == ''){
		$('.myalignprod').html('ERR : Select SR Name');
		$('#errormsgpopupprod').css('display','block');
		setTimeout(function() {
			$('#errormsgpopupprod').hide();
		},5000);
		return false;
	} else if(vehicle_name == ''){
		$('.myalignprod').html('ERR : Select Vehicle Name');
		$('#errormsgpopupprod').css('display','block');
		setTimeout(function() {
			$('#errormsgpopupprod').hide();
		},5000);
		return false;
	} else if(prodcnt == ''){
		$('.myalignprod').html('ERR : Add Product');
		$('#errormsgpopupprod').css('display','block');
		setTimeout(function() {
			$('#errormsgpopupprod').hide();
		},5000);
		return false;
	} if(prodcnt > 0) {
		//alert(prodcnt);
		for(var f=1; f <= prodcnt; f++) {
			if($.trim($("#Loaded_Qty_"+f).val()) == '') {
			
			} else {
				y++;
			}
			/*if($("#cbox_"+f).is(":checked")){
				y++;
			}*/
		}
		if(y == 0) {
			$(".myalignprod").html("Enter One Product");
			$("#errormsgpopupprod").css('display','block');
			setTimeout(function() {
				$("#errormsgpopupprod").hide();
			},5000);
			return false;
		}
		
		var w=0;
		var qtypat	= /^[0-9]+$/;
		for(var k=1; k <= prodcnt; k++) {
			//if($('#cbox_'+k).is(":checked")) {
			var actual_qty			=	parseInt($.trim($('#actual_qty_'+k).val()));
			var Loaded_Qty			=	$('#Loaded_Qty_'+k).val();
			var Loaded_Qty_check	=	parseInt($.trim($("#Loaded_Qty_"+k).val()));
			var product_code_val	=	$('#product_code_'+k).val();

			if(Loaded_Qty != '') {
				if(Loaded_Qty ==''){
					$('.myalignprod').html('ERR : Enter Quantity for '+product_code_val);
					$('#errormsgpopupprod').css('display','block');
					setTimeout(function() {
						$('#errormsgpopupprod').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}

				//alert($("#qty_"+k).val());
				if(isNaN(Loaded_Qty)){
					$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
					$('#errormsgpopupprod').css('display','block');
					//alert(Loaded_Qty);
					setTimeout(function() {
						$('#errormsgpopupprod').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(!qtypat.test(Loaded_Qty)){
					$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
					$('#errormsgpopupprod').css('display','block');
					setTimeout(function() {
						$('#errormsgpopupprod').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(Loaded_Qty == 0){
					$('.myalignprod').html('ERR : No Zero for '+product_code_val);
					$('#errormsgpopupprod').css('display','block');
					setTimeout(function() {
						$('#errormsgpopupprod').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(Loaded_Qty_check > actual_qty) {
					$('.myalignprod').html('ERR : Available quantity is '+actual_qty+' for '+product_code_val);
					$('#errormsgpopupprod').css('display','block');
					setTimeout(function() {
						$('#errormsgpopupprod').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
			}
		}
	}
	//alert('ere');
	$("#dailystockvalidation").removeAttr("target");
	$("#dailystockvalidation").attr("action","");
	$("#dailystockvalidation").submit();
}

function addproduct_field() {
	//alert($('#DSR_Code').val());

	var DSR_Code	=	$("#DSR_Code").val();
	if($('#DSR_Code').val() == '' && $('#DSRName').val() == ''){
		//$('#showerr').show();
		$('.myalign').html('ERR : Select SR');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}
	if($('#DSR_Code').val() == '' && $('#DSRName').val() != ''){
		//$('#showerr').show();
		$('.myalign').html('ERR : No Assignment for this DSR');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}

	$.ajax({
		url			:	"dailystockproductajax.php",
		type		:	"post",
		dataType	:	"text",
		data		:	{ "DSR_Code" : DSR_Code },
		success		:	function(ajaxdata) { 
			var trimdata	=	$.trim(ajaxdata);
			//alert(trimdata);
			$("#totval").html();
			$("#productshow").html(trimdata);
			$("#productshow").css({"display":"block"});
			$("#backgroundChatPopup").fadeIn("slow");
			$("#backgroundChatPopup").css({"opacity" : "0.7"});
		}
	});			
	return false;
	$.ajax({
		url			:	"dailystockprodajax.php",
		type		:	"get",
		dataType	:	"text",
		data		:	{  },
		success		:	function(ajaxdata) { 
			var trimdata	=	$.trim(ajaxdata);
			alert(trimdata);
		}
	});

	return false;
	if($('#product_names').val() == ''){
		//$('#showerr').show();
		$('.myalign').html('ERR : Select Product');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}
	
	var product_name	=	$('#product_names').val();
	var product_code	=	$('#product_names option:selected').text();
		

	//ADDED TO FIND DUPLICATE PRODUCTS STARTS HERE 

	//alert(product_code);
	var w				=	0;
	var addedProduct	=	'';
	//alert(selectedVal);
	
	var rowcntchk			=	$('#prodcnt').val();
	var rowcntcalchk;
	if(rowcntchk == '') {
		rowcntcalchk		=	1;
	} else {
		rowcntcalchk		=	rowcntchk;
	}
	//alert(rowcntcalchk);
	for(var k=1; k<=rowcntcalchk; k++) {
		addedProduct	=	$.trim($('#product_codename_'+k).val());
		product_codechk	=	$.trim(product_code);
		//alert(addedProduct);
		if(addedProduct == product_codechk) {
			w++;
		}
	}
	if(w > 0){
		$('.myalign').html('ERR : Product Duplicate');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}
	var rowcnt			=	$('#prodcnt').val();
	var rowcntcal;
	if(rowcnt == '') {
		rowcntcal		=	1;
	} else {
		rowcntcal		=	parseInt(rowcnt) + 1;
	}
	$('#prodcnt').val(rowcntcal);

	// ADDED TO FIND DUPLICATE PRODUCTS ENDS HERE 

	var split_data			=	product_name.split('~');
	var product_codename	=	split_data[0];
	var product_UOM			=	split_data[1];
	var product_type		=	split_data[2];
	var product_focus		=	split_data[3];
	var product_scheme		=	split_data[4];

	$('#showerr').hide();
	$('#productsadd').show();
	if(rowcnt == '') {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_codename+"' readonly name='product_code_"+rowcntcal+"' id='product_code_"+rowcntcal+"' />"+product_codename+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='product_codename_"+rowcntcal+"'  id='product_codename_"+rowcntcal+"' />"+product_code+"</td><td align='center'><input type='hidden' value='"+product_UOM+"' readonly name='product_UOM_"+rowcntcal+"' />"+product_UOM+"</td><td align='center'><input type='text' value='' name='Loaded_Qty_"+rowcntcal+"' id='Loaded_Qty_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' readonly name='Confirmed_Qty_"+rowcntcal+"' /></td><td align='center'><input type='hidden' value='"+product_focus+"' name='focus_Flag_"+rowcntcal+"' />"+product_focus+"</td><td align='center'><input type='hidden' value='"+product_scheme+"' name='scheme_Flag_"+rowcntcal+"' />"+product_scheme+"</td><td align='center'><input type='hidden' value='"+product_type+"' name='ProductType_"+rowcntcal+"' />"+product_type+"</td></tr>");
	} else {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_codename+"' readonly name='product_code_"+rowcntcal+"' id='product_code_"+rowcntcal+"' />"+product_codename+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='product_codename_"+rowcntcal+"' id='product_codename_"+rowcntcal+"' />"+product_code+"</td><td align='center'><input type='hidden' value='"+product_UOM+"' readonly name='product_UOM_"+rowcntcal+"' />"+product_UOM+"</td><td align='center'><input type='text' value='' name='Loaded_Qty_"+rowcntcal+"' id='Loaded_Qty_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' readonly name='Confirmed_Qty_"+rowcntcal+"' /></td><td align='center'><input type='hidden' value='"+product_focus+"' name='focus_Flag_"+rowcntcal+"' />"+product_focus+"</td><td align='center'><input type='hidden' value='"+product_scheme+"' name='scheme_Flag_"+rowcntcal+"' />"+product_scheme+"</td><td align='center'><input type='hidden' value='"+product_type+"' name='ProductType_"+rowcntcal+"' />"+product_type+"</td></tr>");
	}
	return false;
}


/*function addproduct_field() {
	if($('#product_names').val() == ''){
		//$('#showerr').show();
		$('.myalign').html('ERR : Select Product');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}
	
	var product_name	=	$('#product_names').val();
	var product_code	=	$('#product_names option:selected').text();
		

	//ADDED TO FIND DUPLICATE PRODUCTS STARTS HERE 

	//alert(product_code);
	var w				=	0;
	var addedProduct	=	'';
	//alert(selectedVal);
	
	var rowcntchk			=	$('#prodcnt').val();
	var rowcntcalchk;
	if(rowcntchk == '') {
		rowcntcalchk		=	1;
	} else {
		rowcntcalchk		=	rowcntchk;
	}
	//alert(rowcntcalchk);
	for(var k=1; k<=rowcntcalchk; k++) {
		addedProduct	=	$.trim($('#product_codename_'+k).val());
		product_codechk	=	$.trim(product_code);
		//alert(addedProduct);
		if(addedProduct == product_codechk) {
			w++;
		}
	}
	if(w > 0){
		$('.myalign').html('ERR : Product Duplicate');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}
	var rowcnt			=	$('#prodcnt').val();
	var rowcntcal;
	if(rowcnt == '') {
		rowcntcal		=	1;
	} else {
		rowcntcal		=	parseInt(rowcnt) + 1;
	}
	$('#prodcnt').val(rowcntcal);

	// ADDED TO FIND DUPLICATE PRODUCTS ENDS HERE 

	var split_data			=	product_name.split('~');
	var product_codename	=	split_data[0];
	var product_UOM			=	split_data[1];
	var product_type		=	split_data[2];
	var product_focus		=	split_data[3];
	var product_scheme		=	split_data[4];

	$('#showerr').hide();
	$('#productsadd').show();
	if(rowcnt == '') {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_codename+"' readonly name='product_code_"+rowcntcal+"' id='product_code_"+rowcntcal+"' />"+product_codename+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='product_codename_"+rowcntcal+"'  id='product_codename_"+rowcntcal+"' />"+product_code+"</td><td align='center'><input type='hidden' value='"+product_UOM+"' readonly name='product_UOM_"+rowcntcal+"' />"+product_UOM+"</td><td align='center'><input type='text' value='' name='Loaded_Qty_"+rowcntcal+"' id='Loaded_Qty_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' readonly name='Confirmed_Qty_"+rowcntcal+"' /></td><td align='center'><input type='hidden' value='"+product_focus+"' name='focus_Flag_"+rowcntcal+"' />"+product_focus+"</td><td align='center'><input type='hidden' value='"+product_scheme+"' name='scheme_Flag_"+rowcntcal+"' />"+product_scheme+"</td><td align='center'><input type='hidden' value='"+product_type+"' name='ProductType_"+rowcntcal+"' />"+product_type+"</td></tr>");
	} else {
		$('#productsadded').append("<tr><td align='center'><input type='hidden' value='"+rowcntcal+"' readonly name='sno_"+rowcntcal+"' />"+rowcntcal+"</td><td align='center'><input type='hidden' value='"+product_codename+"' readonly name='product_code_"+rowcntcal+"' id='product_code_"+rowcntcal+"' />"+product_codename+"</td><td align='center'><input type='hidden' value='"+product_code+"' readonly name='product_codename_"+rowcntcal+"' id='product_codename_"+rowcntcal+"' />"+product_code+"</td><td align='center'><input type='hidden' value='"+product_UOM+"' readonly name='product_UOM_"+rowcntcal+"' />"+product_UOM+"</td><td align='center'><input type='text' value='' name='Loaded_Qty_"+rowcntcal+"' id='Loaded_Qty_"+rowcntcal+"' autocomplete='off' /></td><td align='center'><input type='text' value='' readonly name='Confirmed_Qty_"+rowcntcal+"' /></td><td align='center'><input type='hidden' value='"+product_focus+"' name='focus_Flag_"+rowcntcal+"' />"+product_focus+"</td><td align='center'><input type='hidden' value='"+product_scheme+"' name='scheme_Flag_"+rowcntcal+"' />"+product_scheme+"</td><td align='center'><input type='hidden' value='"+product_type+"' name='ProductType_"+rowcntcal+"' />"+product_type+"</td></tr>");
	}
	return false;
}*/

/* DAILY STOCK LOADING ENDS HERE */

function loadDSRVehicle(vhc) {
	var DateVal			=	$('input[name="Date"]').val();
	var DSRName		=	$('select[name="DSRName"]').val();

	if(DateVal == ''){
		$('.myalignveh').html('MSG : Select Date');
		$('#errormsgveh').css('display','block');
		setTimeout(function() {
			$('#errormsgveh').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		//alert(DSRName);
		$('.myalignveh').html('MSG : Select SR Name');
		$('#errormsgveh').css('display','block');
		$('#DSR_Code').val('');
		$('#vehicleName').val('');
		$('#deviceName').val('');
		$('#nodatetab').css('display','none');
		setTimeout(function() {
			$('#errormsgveh').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		var DSR_Code		=	$('input[name="DSR_Code"]').val();
		$('#errormsgveh').css('display','none');
		$.ajax({
			url : "getvehiclestock.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				$('#DSR_Code').val(splitval[0]);
				$('#vehicleName').val(splitval[1]);
				$('#deviceName').val(splitval[2]);
				$('#nodatetab').css('display','block');
				$('#nodatetab').html(splitval[3]);
			}
		});
	}
}

function loadDSRVehicleDate() {
	var DateVal			=	$('input[name="Date"]').val();
	var DSRName		=	$('select[name="DSRName"]').val();	

	if(DateVal == ''){
		$('.myalignveh').html('MSG : Select Date');
		$('#errormsgveh').css('display','block');
		setTimeout(function() {
			$('#errormsgveh').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myalignveh').html('MSG : Select SR Name');
		$('#errormsgveh').css('display','block');
		$('#DSR_Code').val('');
		$('#vehicleName').val('');
		$('#deviceName').val('');
		$('#nodatetab').css('display','none');

		setTimeout(function() {
			$('#errormsgveh').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		$('#errormsgveh').css('display','none');
		$.ajax({
			url : "getvehiclestock.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				$('#DSR_Code').val(splitval[0]);
				$('#vehicleName').val(splitval[1]);
				$('#deviceName').val(splitval[2]);
				$('#nodatetab').css('display','block');
				$('#nodatetab').html(splitval[3]);
			}
		});
	}
}

function showBatch(Pcode,Pname,UOM,BatchNum,ExpDate,StockQty) {
	var insertmsg		=	'<table id="sort" border="1" class="tablesorter" width="100%"><thead><tr><th colspan="6" align="left">Batch Screen</th><tr><td align="center"><strong>Product Code</strong></td><td align="center"><strong>Product Name</strong></td><td align="center"><strong>UOM</strong></td><td align="center"><strong>Batch No.</strong></td><td align="center"><strong>Expiry Date</strong></td><td align="center"><strong>Stock Quantity</strong></td></tr></tr></thead><tbody><tr><td align="center">'+Pcode+'</td><td align="center"><span > '+Pname+'</span></td><td align="center"><span  >'+UOM+'</span</td><td align="center"><span  >'+BatchNum+'</td><td align="center">'+ExpDate+'</td><td align="center">'+StockQty+'</td></tr></tbody></table>';
	$('<div/>').attr("id","ShowBatch"+Pcode).addClass("ShowBatchDis").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0);" onclick="javascript:return closeShowBatchDis(this, \''+Pcode+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="ShowBatchDisMsg'+Pcode+'" class="con"></div></p>').appendTo($("body"));
	$('#ShowBatch'+Pcode).css("display","block");
	$("#ShowBatchDisMsg"+Pcode).html(insertmsg);
	$('#backgroundChatPopup').css({"opacity":"0.7"});
	$('#backgroundChatPopup').fadeIn("slow");
}

function closeShowBatchDis(srcd,Pcode) {
	$('#ShowBatch'+Pcode).remove();
	$('#ShowBatch'+Pcode).css("display","none");
	$("#backgroundChatPopup").fadeOut("slow");
}

function getdevtrans(){
	var kd_id			=	$('input[name="kd_id"]').val();
	var dsr_id			=	$('select[name="dsr_id"]').val();
	var fromdate		=	$('input[name="fromdate"]').val();
	var todate			=	$('input[name="todate"]').val();
	/*var Salesperson_id	=	$('input[name="Salesperson_id"]').val();
	var KDCode			=	$('select[name="KDCode"]').val();*/

	var fromdate, todate, dt1, dt2, mon1, mon2, yr1, yr2, date1, date2;
	var chkFrom = fromdate;
	var chkTo = todate;				
	dt1 = parseInt(fromdate.substring(8, 10), 10);
	mon1 = (parseInt(fromdate.substring(5, 7), 10)) - 1;
	yr1 = parseInt(fromdate.substring(0, 4), 10);

	dt2 = parseInt(todate.substring(8, 10), 10);
	mon2 = (parseInt(todate.substring(5, 7), 10)) - 1;
	yr2 = parseInt(todate.substring(0, 4), 10);
	date1 = new Date(yr1, mon1, dt1);
	date2 = new Date(yr2, mon2, dt2);

	//alert(KDCode);

	if(kd_id == ''){
		$('.myaligndev').html('ERR : Select KD');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} else if(dsr_id == ''){
		$('.myaligndev').html('ERR : Select SR Name');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	
	if(dsr_id != ''){
		var dsr_split		=	dsr_id.split('~');
		var dsr_act_id		=	dsr_split[0];
		var dsr_sales_id	=	dsr_split[1];
		$('#errormsgdev').css('display','none');
		//$('input[name="Salesperson_id"]').val(dsr_sales_id);
	}
	
	if(fromdate == ''){
		$('.myaligndev').html('ERR : Select From Date');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} else if(todate == ''){
		$('.myaligndev').html('ERR : Select To Date');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} 
	
	//alert(date2);
	//alert(date1);

	if (date2 < date1) {		
		$('.myaligndev').html('ERR : To date Should be greater than From date!');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	var currentdate = new Date();

	//alert(date2);
	//alert(currentdate);
	if (date1 <= currentdate) {				
	} else {
		$('.myaligndev').html('ERR : From Date greater than Today!');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(date2 <= currentdate)
	{
		//alert('Date greater than Today');
	} else {
		$('.myaligndev').html('ERR : To Date greater than Today!');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	
	$.ajax({
		url : "devtransajax.php",
		type: "get",
		dataType: "text",
		//data : { "kd_id" : kd_id,"dsr_id": dsr_act_id,"fromdate" : fromdate,"todate": todate,"Salesperson_id" : dsr_sales_id,"KDCode": KDCode },
		data : { "kd_id" : kd_id,"dsr_id": dsr_act_id,"fromdate" : fromdate,"todate": todate },
		success : function (dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$('#tablestr_dev').html(trimval);
		}
	});
}

function getsalesid(salesid) {
	var dsr_split		=	salesid.split('~');
	var dsr_act_id		=	dsr_split[0];
	var dsr_sales_id	=	dsr_split[1];

	//alert(dsr_act_id);alert(dsr_sales_id);
	$('input[name="Salesperson_id"]').val(dsr_sales_id);

}

function checkFile(){
	var Dateval			=	$('input[name="Date"]').val();
	var excelfile		=	$('input[name="excelfile"]').val();
	
	var currentdate = new Date();
	var dt2 = parseInt(Dateval.substring(8, 10), 10);
	var mon2 = (parseInt(Dateval.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(Dateval.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);
	//alert(date2);
	//alert(currentdate);

	if(Dateval == '') {
		$('.myalignopen').html('ERR : Please select Date');
		$('#errormsgopen').css('display','block');
		setTimeout(function() {
			$('#errormsgopen').hide();
		},5000);
		return false;
	} else if(date2 > currentdate) {
		$('.myalignopen').html('ERR : Date greater than Today!');
		$('#errormsgopen').css('display','block');
		setTimeout(function() {
			$('#errormsgopen').hide();
		},5000);
		return false;
	} else if (excelfile == '') {
		$('.myalignopen').html('ERR : Choose CSV File');
		$('#errormsgopen').css('display','block');
		/*setTimeout(function() {
			$('#errormsgopen').hide();
		},5000);*/
		return false;
	} else if (excelfile != '') {
		var pattern				=	/\.csv$|\.CSV$/i;
		var matchpattern		=	excelfile.match(pattern);
		if(matchpattern == null) {
			$('.myalignopen').html('ERR : Upload .csv File Only');
			$('#errormsgopen').css('display','block');
			setTimeout(function() {
				$('#errormsgopen').hide();
			},5000);
			return false;
		}
	}
	$('#errormsgopen').css('display','none');
}


//KD Information Default

function kdinform()
{
	var val=$('.KD_Name option:selected').text();
	 $.ajax({
            url: 'get_kdcode.php?val=' + val,
            success: function(data) {
				//alert(data);
				var value=$.trim(data);//To Remove White Space in string
				var value1=data.substring(0,value.length-1);//To return part of the string
				var list= value1.split("|"); 
				for (var i=0; i<list.length; i++) {
					var arr_i= list[i].split("^");
					$(".KD_Name").val(arr_i[0]);
					$(".KD_Code").val(arr_i[1]);
					$(".Address_Line_1").val(arr_i[2]);
					$(".Address_Line_2").val(arr_i[3]);
					$(".Address_Line_3").val(arr_i[4]);
					$(".City").val(arr_i[5]);
					$(".Pin").val(arr_i[6]);
					$(".Contact_Person").val(arr_i[7]);
					$(".Contact_Number").val(arr_i[8]);
					$(".Email_ID").val(arr_i[9]);
					$(".kd_category").val(arr_i[10]);
				}

			}
        });
	
}




//setup param kd
$(function(){
  // hide or show by default
 if( $('.data').val() === 'Numeric'){
  $('.alpha').css('display', 'none');  
 }
else if( $('.data').val() === 'AlphaNumeric'){
  $('.alpha').css('display', 'block');   	
}		   
//Value Selected Hide show		   
  $('.data').change(function(){
   if ($(this).val() === 'Numeric') {
	 $('.alpha').css('display', 'none');  
     
   }
  else if($(this).val() === 'AlphaNumeric') {
	  $('.alpha').css('display', 'block'); 
      }
 });
});	


//setup param scheme
$(function(){

 if( $('.datasc').val() === 'Numeric'){
  $('.alphasc').css('display', 'none');  
 }
else if( $('.datasc').val() === 'AlphaNumeric'){
  $('.alphasc').css('display', 'block');   	
}

  $('.datasc').change(function(){
   if ($(this).val() === 'Numeric') {
	 $('.alphasc').css('display', 'none');  
     
   }
  else if($(this).val() === 'AlphaNumeric') {
	  $('.alphasc').css('display', 'block'); 
     }
 });
});	



//setup param SR
$(function(){
  // hide or show by default
 if( $('.datasr').val() === 'Numeric'){
  $('.alphasr').css('display', 'none'); 
 }
else if( $('.datasr').val() === 'AlphaNumeric'){
  $('.alphasr').css('display', 'block');   	
}

  $('.datasr').change(function(){
   if ($(this).val() === 'Numeric') {
	 $('.alphasr').css('display', 'none'); 
   }
  else if($(this).val() === 'AlphaNumeric') {
	  $('.alphasr').css('display', 'block'); 
     }
 });
});	


//Cycle End Reconcilization
/* STARTS ON WRITTEN ON 05-24-2013 */

function loadDSRCycle(DSRName) {
	var DateVal		=	$('input[name="cycleEndDate"]').val();
	
	var currentdate = new Date();
	var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);
	//alert(date2);
	//alert(currentdate);
	
	if(DateVal == ''){
		$('.myaligncycle').html('MSG : Select Cycle End Date');
		$('#errormsgcycle').css('display','block');
		setTimeout(function () {
			$('#errormsgcycle').hide();
		},5000);

		return false;
	} else if(date2 > currentdate) {
		/*$('.myaligncycleajx').html('ERR : Date greater than Today!');
		$('#errormsgcycleajx').css('display','block');
		setTimeout(function () {
			$('#errormsgcycleajx').hide();
		},5000);
		return false;*/
	} else if(DSRName == ''){
		$('.myaligncycle').html('MSG : Select SR Name');
		$('#errormsgcycle').css('display','block');
		setTimeout(function () {
			$('#errormsgcycle').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		var DSR_Code		=	$('input[name="DSR_Code"]').val();
		$('#errormsgcycle').css('display','none');
		$.ajax({
			url : "cycleendajax.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				var splitval		=	trimval.split('~');
				//alert(trimval);
				var fromdate		=	splitval[0];
				var todate = DateVal;
				/*dt1 = parseInt(fromdate.substring(0, 2), 10);
				mon1 = parseInt(fromdate.substring(3, 5), 10);
				yr1 = parseInt(fromdate.substring(6, 10), 10);*/
					
				dt1 = parseInt(fromdate.substring(8, 10), 10);
				mon1 = (parseInt(fromdate.substring(5, 7), 10)) - 1;
				yr1 = parseInt(fromdate.substring(0, 4), 10);

				dt2 = parseInt(todate.substring(8, 10), 10);
				mon2 = (parseInt(todate.substring(5, 7), 10)) - 1;
				yr2 = parseInt(todate.substring(0, 4), 10);
				date1 = new Date(yr1, mon1, dt1);
				date2 = new Date(yr2, mon2, dt2);
				//alert(date2);
				//alert(date1);
				$('#cycleStartDate').val(splitval[0]);

				if (date2 <= date1) {
					/*$('.myaligncycleajx').html('ERR : To date Should be greater than From date!');
					$('#errormsgcycleajx').css('display','block');
					setTimeout(function () {
						$('#errormsgcycleajx').hide();
					},5000);
					return false;*/
				}
							
				$('#DSR_Val').val(splitval[1]);
				$('#deviceName').val(splitval[2]);
				$('#deviceCode').val(splitval[3]);
				$('#vehicleName').val(splitval[4]);
				$('#vehicleCode').val(splitval[5]);
				$('#currency').val(splitval[6]);
				$('#UOM').val(splitval[7]);
	
				$('#containercycle').html(splitval[8]);
				$('#netSaleValue').val(splitval[9]);
				$('#depositValue').val(splitval[10]);
				$('#shortageVal').val(splitval[11]);
				$('#allproddetails').val(splitval[12]);
				$('#lastweekshort').val(splitval[14]);
				if(splitval[13] > 0) {
					$('#printoption').css("display","block");
					$('#printoption').css("display","inline");
				}
			}
		});
	}
}

function loadDSRCycleDate(DateVal) {
	var DSRName		=	$('select[name="DSR_Code"]').val();	

	var currentdate = new Date();
	var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);
	//alert(date2);
	//alert(currentdate);
	
	if(DateVal == ''){
		$('.myaligncycle').html('MSG : Select Cycle End Date');
		$('#errormsgcycle').css('display','block');
		setTimeout(function () {
			$('#errormsgcycle').hide();
		},5000);
		return false;
	} else if(date2 > currentdate) {
		/*$('.myaligncycleajx').html('ERR : Date greater than Today!');
		$('#errormsgcycleajx').css('display','block');
		setTimeout(function () {
			$('#errormsgcycleajx').hide();
		},5000);
		return false;*/
	} else if(DSRName == ''){
		$('.myaligncycle').html('MSG : Select SR Name');
		$('#errormsgcycle').css('display','block');
		setTimeout(function () {
			$('#errormsgcycle').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		$('#errormsgcycle').css('display','none');
		$.ajax({
			url : "cycleendajax.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				var splitval		=	trimval.split('~');
				//alert(trimval);

				var fromdate		=	splitval[0];
				var todate = DateVal;
				/*dt1 = parseInt(fromdate.substring(0, 2), 10);
				mon1 = parseInt(fromdate.substring(3, 5), 10);
				yr1 = parseInt(fromdate.substring(6, 10), 10);*/
					
				dt1 = parseInt(fromdate.substring(8, 10), 10);
				mon1 = (parseInt(fromdate.substring(5, 7), 10)) - 1;
				yr1 = parseInt(fromdate.substring(0, 4), 10);

				dt2 = parseInt(todate.substring(8, 10), 10);
				mon2 = (parseInt(todate.substring(5, 7), 10)) - 1;
				yr2 = parseInt(todate.substring(0, 4), 10);
				date1 = new Date(yr1, mon1, dt1);
				date2 = new Date(yr2, mon2, dt2);
				//alert(date2);
				//alert(date1);
				
				$('#cycleStartDate').val(splitval[0]);

				if (date2 <= date1) {
					/*$('.myaligncycleajx').html('ERR : To date Should be greater than From date!');
					$('#errormsgcycleajx').css('display','block');
					setTimeout(function () {
						$('#errormsgcycleajx').hide();
					},5000);
					return false;*/
				}
				$('#DSR_Val').val(splitval[1]);
				$('#deviceName').val(splitval[2]);
				$('#deviceCode').val(splitval[3]);
				$('#vehicleName').val(splitval[4]);
				$('#vehicleCode').val(splitval[5]);
				$('#currency').val(splitval[6]);
				$('#UOM').val(splitval[7]);
	
				$('#containercycle').html(splitval[8]);
				$('#netSaleValue').val(splitval[9]);
				$('#depositValue').val(splitval[10]);
				$('#shortageVal').val(splitval[11]);
				$('#allproddetails').val(splitval[12]);
				$('#lastweekshort').val(splitval[14]);
				if(splitval[13] > 0) {
					$('#printoption').css("display","block");
					$('#printoption').css("display","inline");
				}
			}
		});
	}
}

function confirmcycle() {

	var allproddetails				=	$('#allproddetails').val();
	var alladjreason				=	$('#alladjreason').val();
	//alert(allproddetails);
	//return;
	var splitprod					=	allproddetails.split(",");
	var stockadjval					=	alladjreason.split("&");
	var prodlen						=	splitprod.length;
	//alert(splitprod);
	//alert(prodlen);
	var KD_returned_qty				=	0;
	var DSR_returned_qty			=	0;
	var shortage_val				=	0;
	var shortage_val_found			=	0;
	var	shortage_reason				=	0;	// shortage reason id stored from cycle end page
	var qty_shortage				=	0;
	var qty_shortage_neg;
	var new_shortage;
	var selectedvar;
	for(var k=0; k < prodlen; k++) {

		//amt += parseInt(strvalue.replace(/,/g,''));
		//amt	=	amt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");

		KD_returned_qty					=	parseInt($('#KD_returned_qty_'+splitprod[k]).val().replace(/,/g,''));
		DSR_returned_qty				=	$('#DSR_returned_qty_'+splitprod[k]).val();
		//alert(KD_returned_qty);
		//alert(DSR_returned_qty);
		shortage_val					=	DSR_returned_qty - KD_returned_qty;
		//alert(shortage_val);
		$("#qtyshotspan_"+splitprod[k]).html(qtyformatreturn(shortage_val));
		$('#quantity_shortage_'+splitprod[k]).val(shortage_val);				
		/*var cycleStartDate				=	$('#cycleStartDate').val();
		var cycleEndDate				=	$('#cycleEndDate').val();
		var deviceCode					=	$('#deviceCode').val();
		var vehicleCode					=	$('#vehicleCode').val();
		var UOM							=	$('#UOM').val();
		var DSR_Code					=	$('#DSR_Code').val();
		var currency					=	$('#currency').val();
		var netSaleValue				=	$('#netSaleValue').val();
		var depositValue				=	$('#depositValue').val();
		var shortageVal					=	$('#shortageVal').val();
		var line_number					=	$('#line_number').val();
		var Product_code				=	$('#Product_code').val();
		var cycle_start_loaded_qty		=	$('#cycle_start_loaded_qty').val();
		var daily_total_loaded_qty		=	$('#daily_total_loaded_qty').val();
		var total_sold_qty				=	$('#total_sold_qty').val();
		var total_sales_returned_qty	=	$('#total_sales_returned_qty').val();
		var DSR_returned_qty			=	$('#DSR_returned_qty').val();
		var quantity_shortage			=	$('#quantity_shortage').val();*/
		
		//alert(KD_returned_qty);
		qty_shortage					=	$('#quantity_shortage_'+splitprod[k]).val();
		qty_shortage_neg				=	qty_shortage.charAt(0);
		if(qty_shortage_neg == '-') {
			new_shortage	=	qty_shortage.substring(1);
			//alert("edwerwer");
			if(new_shortage > 0) {
				if($('#short_reason_'+splitprod[k]).val() != '' && typeof($('#short_reason_'+splitprod[k]).val()) != 'undefined') {
					shortage_reason++;
				}
				shortage_val_found++;
			}
		}

		if($('#quantity_shortage_'+splitprod[k]).val() > 0) {			
			//alert('2323');
			if($('#short_reason_'+splitprod[k]).val() != '' && typeof($('#short_reason_'+splitprod[k]).val()) != 'undefined') {
				shortage_reason++;
			}
			shortage_val_found++;
		}
		var checkqty	= /^[0-9]+$/;		
		if(KD_returned_qty == ''){
			$('.myaligncycleajx').html('ERR : Enter DSR Returned Quantity');
			$('#errormsgcycleajx').css('display','block');
			setTimeout(function () {
				$('#errormsgcycleajx').hide();
			},5000);
			return false;
		} if(!checkqty.test(KD_returned_qty)) {
			$('.myaligncycleajx').html('ERR : Only Numeric!');
			$('#errormsgcycleajx').css('display','block');
			setTimeout(function() {
				$('#errormsgcycleajx').hide();
			},5000);
			return false;
		}
		qty_shortage	=	0;
	}
	
	//alert(shortage_val_found);
	if(shortage_val_found > 0){

		//if(shortage_val_found != shortage_reason) {
			var tableform		=	'';
					
			var curDateObj			=	new Date();
			var curDate;

			var dd					=	curDateObj.getDate();
			var mm					=	curDateObj.getMonth()+1; //January is 0!		
			var yyyy				=	curDateObj.getFullYear();
			
			dd		=	10;
			if(dd	< 10) {
				dd		=	"0"+dd;
			}
			curDate					=	yyyy+"-"+mm+"-"+dd;
					
			tableform				+=	"<table><tr><th align='center'>Date</th><th align='center' style='width:100px;'>Product Code</th><th align='center' style='width:600px;'>Product Name</th><th align='center'>Reason</th><th align='center'>Shortage Value</th></tr>";
					
			var	product_value;				// product code from cycle end
			var	shortage_value;				//	shortage value from the cycle end screen text box
			var stockadjreason		=	''; // select box
			var	shortinc			=	0;  // id increment value for shortage text box
			for(var k=0; k < prodlen; k++) {

				qty_shortage					=	$('#quantity_shortage_'+splitprod[k]).val();
				qty_shortage_neg				=	qty_shortage.charAt(0);
				if(qty_shortage_neg == '-') {
					new_shortage	=	qty_shortage.substring(1);
					if(new_shortage > 0) {
						shortage_value		=	$('#quantity_shortage_'+splitprod[k]).val();
						product_value		=	splitprod[k];
						prod_name			=	$('#prod_name_'+splitprod[k]).val();
						//stockadjreason		=	"<select name='stockreason_"+shortinc+"' id='stockreason_"+shortinc+"' ><option value=''>--Select--</option>";
						stockadjreason		=	"<select name='stockreason_"+k+"' id='stockreason_"+k+"' ><option value=''>--Select--</option>";
						var splitstockvalues	=	new Array();
						for(var u=0; u < stockadjval.length; u++) {				
							splitstockvalues	=	stockadjval[u].split("^");

							if(splitstockvalues[1] == $('#short_reason_'+splitprod[k]).val()) {
								selectedvar		=	"selected";
							} else {
								selectedvar			=	"";
							}
							stockadjreason		+=	"<option value='"+splitstockvalues[0]+"' "+selectedvar+" >"+splitstockvalues[1]+"</option>";
						}
						stockadjreason			+=	"</select>";

						tableform				+=	"<tr><td>"+curDate+"</td><td>"+product_value+"</td><td>"+prod_name+"</td><td>"+stockadjreason+"</td><td align='right'><input type='hidden' name='prodcode_"+k+"' id='prodcode_"+k+"' value='"+product_value+"' /><input type='hidden' name='shortageinadj_"+k+"' id='shortageinadj_"+k+"' value='"+shortage_value+"' />"+qtyformatreturn(shortage_value)+"</td></tr>";
						/*<input type='text' name='shortageinadj_"+shortinc+"' id='shortageinadj_"+shortinc+"' value='"+shortage_value+"' />*/	shortinc++;
					}
				}

				if($('#quantity_shortage_'+splitprod[k]).val() > 0) {				
					shortage_value				=	$('#quantity_shortage_'+splitprod[k]).val();
					product_value				=	splitprod[k];
					prod_name					=	$('#prod_name_'+splitprod[k]).val();
					//stockadjreason		=	"<select name='stockreason_"+shortinc+"' id='stockreason_"+shortinc+"' ><option value=''>--Select--</option>";
					stockadjreason				=	"<select name='stockreason_"+k+"' id='stockreason_"+k+"' ><option value=''>--Select--</option>";
					var splitstockvalues		=	new Array();
					
					for(var u=0; u < stockadjval.length; u++) {				
						splitstockvalues		=	stockadjval[u].split("^");
						//alert($('#short_reason_'+splitprod[k]).val());
						if(splitstockvalues[1] == $('#short_reason_'+splitprod[k]).val()) {
							selectedvar			=	"selected";
						} else {
							selectedvar			=	"";
						}
						stockadjreason			+=	"<option value='"+splitstockvalues[0]+"' "+selectedvar+" >"+splitstockvalues[1]+"</option>";
					}
					stockadjreason				+=	"</select>";

					tableform					+=	"<tr><td>"+curDate+"</td><td>"+product_value+"</td><td>"+prod_name+"</td><td>"+stockadjreason+"</td><td align='right'><input type='hidden' name='prodcode_"+k+"' id='prodcode_"+k+"' value='"+product_value+"' /><input type='hidden' name='shortageinadj_"+k+"' id='shortageinadj_"+k+"' value='"+shortage_value+"' />"+shortage_value+"</td></tr>";
					/*<input type='text' name='shortageinadj_"+shortinc+"' id='shortageinadj_"+shortinc+"' value='"+shortage_value+"' />*/					
					shortinc++;
				}
				selectedvar					=	'';
			}		

			//alert(tableform);
			transno				=	1;

			tableform			+=	"</table>";
			var insertmsg		=	tableform;
			$(" <div />" ).attr("id","StockAdj"+transno).addClass("stockAdjustPopup").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeStockAdjEnquiry(this,\''+transno+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="StockAdjustMsg'+transno+'" class="condaily_prod"></div></p><br/><div style="text-align:center;"><input type="button" class="buttons" value="Save" onclick="javascript:return saveStockAdjEnquiry(this,\''+transno+'\',\''+prodlen+'\');" />&nbsp;&nbsp;&nbsp;<input type="button" class="buttons" value="Cancel" onclick="javascript:return closeStockAdjEnquiry(this,\''+transno+'\');" /></div><br/><div class="mcf"></div><div id="errorcyclestoadj" ><h3 align="center" class="mycyclestoadj"></h3><button id="closebutton" style="cursor:pointer;cursor:hand;" onClick="javascript:return buttoncloseforstoadj();" >Close</button></div>').appendTo($( "body" ));
			$("#StockAdj"+transno).css("display","block");
			$('#StockAdjustMsg'+transno).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		//}
	}

	//return false;
	var orig = $('#validationcycle').serialize();

	//alert(orig);
	//return false;

	//else {

	//alert(DSR_Code);
	//return false;

	if(DSR_Code != '' && cycleEndDate != '' && cycleStartDate != '' && KD_returned_qty != '') {
		var confirmQty	=	confirm("Do you want to confirm this Cycle End Date?");
		if(confirmQty) {
			$.ajax({
				url : "storecycleendajx.php",
				type: "POST",
				dataType: "text",
				//data:{  "KD_returned_qty": KD_returned_qty, "cycleStartDate" : cycleStartDate, "DSR_Code": DSR_Code, "cycleEndDate": cycleEndDate, "deviceCode" : deviceCode, "vehicleCode":	vehicleCode, "UOM" : UOM, "currency" : currency, "netSaleValue": netSaleValue, "depositValue" : depositValue, "shortageVal" : shortageVal, "line_number" : line_number, "Product_code" : Product_code, "cycle_start_loaded_qty" :cycle_start_loaded_qty, "daily_total_loaded_qty" : daily_total_loaded_qty, "total_sold_qty" : total_sold_qty, "total_sales_returned_qty" : total_sales_returned_qty, "DSR_returned_qty" : DSR_returned_qty, "quantity_shortage" : quantity_shortage },
				data	:	{ "orig" : orig },
				success: function(eData) {
					var trimData	=	$.trim(eData);
					//alert(trimData);
					//return;
					if(trimData != 'INVALID') {
						//alert("Data Inserted Successfully!");
						
						var innerdivmsg						=	"Data Inserted Successfully";

						popupboxdiv("cesuccess","commonPopupClass","ceinnerid",innerdivmsg,"backgroundChatPopup","commonPopupInner",trimData);	// ce is for cycle end

						return false;

						var confirmprint		=	confirm("Do You Want to Print this Cycle End Reconciliation?");

						if(confirmprint) {
							$('#printval').val(trimData);							
							$('#printpage').submit();							
						} else {
							window.location = "cycleendreconciliation.php"
						}
					}
				}
			});
		}
	}
	//}
}

function closeStockAdjEnquiry(atr,PCode){
	$('#StockAdj'+PCode).remove();
	$('#StockAdj'+PCode).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
}

function buttoncloseforstoadj(){
	$('#errorcyclestoadj').hide();
	return false;
}

function saveStockAdjEnquiry(obj,dummytransno,noofrows) {
	//alert(noofrows);
	for(var k = 0; k < noofrows; k++) {
		
		//alert($("#shortageinadj_"+k).val());

		if($("#shortageinadj_"+k).val() != '' && typeof($("#shortageinadj_"+k).val()) != 'undefined') {
			
			//alert($("#stockreason_"+k).val());
			//return false;
			if($("#stockreason_"+k).val() == '') {
				$('.mycyclestoadj').html('ERR : Select Reason');
				$('#errorcyclestoadj').css('display','block');
				setTimeout(function () {
					$('#errorcyclestoadj').hide();
				}, 5000);
				$("#stockreason_"+k).focus();
				return false;
			}
		
			//alert($("#prodcode_"+k).val());
			var prodcode =	$("#prodcode_"+k).val();
			//alert($("#shortageinadj_"+k).val());

			//alert($("#stockreason_"+k+" option:selected").text());
			//return false;
			$("#short_reason_"+prodcode).val($("#stockreason_"+k+" option:selected").text());	
			//alert($("#stockreason_"+k).val());

			/*if($("#shortageinadj_"+k).val() == '') {
				$('.mycyclestoadj').html('ERR : Enter Shortage Quantity');
				$('#errorcyclestoadj').css('display','block');
				setTimeout(function () {
					$('#errorcyclestoadj').hide();
				}, 5000);
				$("#shortageinadj_"+k).focus();
				return false;
			}
			var checkqty	= /^[0-9]+$/;		
			if(!checkqty.test($("#shortageinadj_"+k).val())) {
				$('.mycyclestoadj').html('ERR : Only Numeric!');
				$('#errorcyclestoadj').css('display','block');
				setTimeout(function() {
					$('#errorcyclestoadj').hide();
				},5000);
				$("#shortageinadj_"+k).focus();
				return false;
			}*/
		}
	}
	$("#StockAdj"+transno).hide();
	$('#backgroundChatPopup').css({"display":"none"});

	var orig = $('#validationcycle').serialize();

	//alert(orig);
	//return false;

	//else {
	//if(DSR_Code != '' && cycleEndDate != '' && cycleStartDate != '' && KD_returned_qty != '') {
		var confirmQty	=	confirm("Do you want to confirm this Cycle End Date?");
		//return false;
		if(confirmQty) {
			//$("#").remove();

			//var prodnameval		=	$("[id^='prod_name_']").remove();
			//alert(prodnameval.length);
			$.ajax({
				url : "storecycleendajx.php",
				type: "POST",
				dataType: "text",
				//data:{  "KD_returned_qty": KD_returned_qty, "cycleStartDate" : cycleStartDate, "DSR_Code": DSR_Code, "cycleEndDate": cycleEndDate, "deviceCode" : deviceCode, "vehicleCode":	vehicleCode, "UOM" : UOM, "currency" : currency, "netSaleValue": netSaleValue, "depositValue" : depositValue, "shortageVal" : shortageVal, "line_number" : line_number, "Product_code" : Product_code, "cycle_start_loaded_qty" :cycle_start_loaded_qty, "daily_total_loaded_qty" : daily_total_loaded_qty, "total_sold_qty" : total_sold_qty, "total_sales_returned_qty" : total_sales_returned_qty, "DSR_returned_qty" : DSR_returned_qty, "quantity_shortage" : quantity_shortage },
				data	:	{ "orig" : orig },
				success: function(eData) {
					var trimData	=	$.trim(eData);
					//alert(trimData);
					//return;
					if(trimData != 'INVALID') {
						//alert("Data Inserted Successfully!");					
						
						var innerdivmsg						=	"Data Inserted Successfully";

						popupboxdiv("cesuccess","commonPopupClass","ceinnerid",innerdivmsg,"backgroundChatPopup","commonPopupInner",trimData);	// ce is for cycle end

						return false;

						var confirmprint		=	confirm("Do You Want to Print this Cycle End Reconciliation?");

						if(confirmprint) {
							$('#printval').val(trimData);							
							$('#printpage').submit();							
						} else {
							window.location = "cycleendreconciliation.php"
						}
					}
				}
			});
		}
	//}
}

function closePrintCyclePopup(atr,printpopup){
	//alert(printpopup);
	$('#'+printpopup).remove();
	$('#'+printpopup).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
	window.location = "cycleendreconciliation.php"
}

function printCycleEndScreen(trimData) {
	$('#printval').val(trimData);							
	$('#printpage').submit();
}

function popupboxdiv(popupid,popupboxclass,innerdivid,innerdivmsg,backgrdid,innerdivclass,trimData) {
	$('<div />').attr('id',popupid).addClass(popupboxclass).html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeceSuccess(this,\''+popupid+'\',\''+backgrdid+'\',\''+trimData+'\');" ><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><br/><div id="'+innerdivid+'" class="'+innerdivclass+'"></div></p><br/><div style="text-align:center;"><input type="button" class="buttons" value="OK" onclick="javascript:return closeceSuccess(this,\''+popupid+'\',\''+backgrdid+'\',\''+trimData+'\');" />&nbsp;&nbsp;&nbsp;</div>').appendTo($("body"));
	$("#"+popupid).css({"display":"block"});
	$("#"+innerdivid).html(innerdivmsg);
	$("#"+backgrdid).css({"opacity":"0.7"});
	$("#"+backgrdid).fadeIn("slow");
}

function closeceSuccess(attrid,popupId,backgrdid,trimData) {
	//alert(popupId);
	$('#'+popupId).remove();
	$('#'+popupId).css('display','none');
	$('#'+backgrdid).css({"display":"none"});

	var insertmsg		=	"Do You Want to Print this Cycle End Reconciliation?";
	$('<div/>').attr('id',"printpopup").addClass("printcyclePopup").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closePrintCyclePopup(this,\'printpopup\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><br/><div id="printpopupbutton" class="printcheck"></div></p><br/><div style="text-align:center;"><input type="button" class="buttons" value="OK" onclick="javascript:return printCycleEndScreen(\''+trimData+'\');" />&nbsp;&nbsp;&nbsp;<input type="button" class="buttons" value="Cancel" onclick="javascript:return closePrintCyclePopup(this,\'printpopup\');" /></div><br/><div class="mcf"></div><div id="errorcyclestoadj" ><h3 align="center" class="mycyclestoadj"></h3><button id="closebutton" style="cursor:pointer;cursor:hand;" onClick="javascript:return closePrintCyclePopup(this,"printpopup");" >Close</button></div>').appendTo($( "body" ));
	$("#printpopup").css("display","block");
	$('#printpopupbutton').html(insertmsg);
	$('#backgroundChatPopup').css({"opacity":"0.7"});
	$('#backgroundChatPopup').fadeIn("slow");
	return false;
}

function updateShortQty(P_code){
	var DSR_returned_qty = $('#DSR_returned_qty_'+P_code).val();
	var KD_returned_qty = $('#KD_returned_qty_'+P_code).val();

	if(KD_returned_qty == ''){
		KD_returned_qty			=	0;
		var quantity_shortage = parseInt(DSR_returned_qty) - parseInt(KD_returned_qty);
		$('#quantity_shortage_'+P_code).val('');
		$('#qtyshotspan_'+P_code).html('');
		
		$('.myaligndev').html('ERR : Enter DSR Returned Quantity');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		}, 5000);
		return false;
	} else {
		var quantity_shortage = parseInt(DSR_returned_qty) - parseInt(KD_returned_qty.replace(/,/g,''));
		$('#quantity_shortage_'+P_code).val(quantity_shortage);
		$('#qtyshotspan_'+P_code).html(qtyformatreturn(quantity_shortage));
		//alert(quantity_shortage);
		//alert(DSR_returned_qty)
		//alert(KD_returned_qty);
	}

}


//End Cycle End Reconcilisation




//DSr Metrics

function loadDSRMetrics(DSRName) {
	var DateVal		=	$('input[name="DSRDate"]').val();

	if(DateVal == ''){
		$('.myalignmetrics').html('MSG : Select Date');
		$('#errormsgmetrics').css('display','block');
		setTimeout(function () {
			$('#errormsgmetrics').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myalignmetrics').html('MSG : Select SR Name');
		$('#errormsgmetrics').css('display','block');
		setTimeout(function () {
			$('#errormsgmetrics').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		var DSR_Code		=	$('select[name="DSR_Code"]').val();
		//return;
		$('#errormsgmetrics').css('display','none');
		$.ajax({
			url : "day_metrics_request.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				//alert(dataval);
				var trimval		=	$.trim(dataval);
				var splitval		=	trimval.split('~');

				//alert(DSR_Code);
				$("#dsrcode").val(DSR_Code);
				$("#targetvisit").html(splitval[0]);
				$("#totalvisit").html(splitval[1]);
				$("#percoverage").html(splitval[2]);				
				
				$("#totalsalesinvoice").html(splitval[3]);
				$("#procoverage").html(splitval[4]);

				$("#totallineitems").html(splitval[5]);
				$("#basketsize").html(splitval[6]);
				$("#effskucover").html(splitval[7]);

				$("#focuslineitems").html(splitval[8]);
				$("#focusitemsstock").html(splitval[9]);
				$("#focuscover").html(splitval[10]);

				$("#totalfocusitemssold").html(splitval[11]);
				$("#totalfocusitemsstock").html(splitval[12]);
				$("#zerostock").html(splitval[13]);

				$("#totalsalevalue").html(splitval[14]);
				$("#dropsize").html(splitval[15]);
			}
		});
	}
}

function loadDSRMetricsDate(DateVal) {
	var DSRName		=	$('select[name="DSR_Code"]').val();	

	if(DateVal == ''){
		$('.myalignmetrics').html('MSG : Select Date');
		$('#errormsgmetrics').css('display','block');
		setTimeout(function () {
			$('#errormsgmetrics').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myalignmetrics').html('MSG : Select SR Name');
		$('#errormsgmetrics').css('display','block');
		setTimeout(function () {
			$('#errormsgmetrics').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		$('#errormsgmetrics').css('display','none');
		$.ajax({
			url : "day_metrics_request.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				var splitval		=	trimval.split('~');
				$("#dsrcode").val(DSRName);
				$("#targetvisit").html(splitval[0]);
				$("#totalvisit").html(splitval[1]);
				$("#percoverage").html(splitval[2]);				
				
				$("#totalsalesinvoice").html(splitval[3]);
				$("#procoverage").html(splitval[4]);

				$("#totallineitems").html(splitval[5]);
				$("#basketsize").html(splitval[6]);
				$("#effskucover").html(splitval[7]);

				$("#focuslineitems").html(splitval[8]);
				$("#focusitemsstock").html(splitval[9]);
				$("#focuscover").html(splitval[10]);

				$("#totalfocusitemssold").html(splitval[11]);
				$("#totalfocusitemsstock").html(splitval[12]);
				$("#zerostock").html(splitval[13]);

				$("#totalsalevalue").html(splitval[14]);
				$("#dropsize").html(splitval[15]);
			}
		});
	}
}


/* STARTS FOR CUSTOMER VISIT TRACKING ON 05-28-2013 */

function loadCustomerTrack(DSRName) {
	var DateVal		=	$('input[name="DSRDate"]').val();

	if(DateVal == ''){
		$('.myaligncustra').html('MSG : Select Date');
		$('#errormsgcustra').css('display','block');
		setTimeout(function () {
			$('#errormsgcustra').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myaligncustra').html('MSG : Select SR Name');
		$('#errormsgcustra').css('display','block');
		setTimeout(function () {
			$('#errormsgcustra').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		var DSR_Code		=	$('select[name="DSR_Code"]').val();
		//return;
		$('#errormsgcustra').css('display','none');
		$.ajax({
			url : "customervisitajax.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				//alert(dataval);
				var trimval		=	$.trim(dataval);
				
				$("#nodatetab").html(trimval);
				$("#nodatetab").css('display','block');
				$("#nodatetab").fadeIn('slow');
			}
		});
	}
}

function loadCustomerTrackDate(DateVal) {
	var DSRName		=	$('select[name="DSR_Code"]').val();

	//var Date12	=	new Date('2013-05-13');
	//var Date13	=	new Date('2013-05-01');

	//alert(Date12.getDate() - Date13.getDate());

	if(DateVal == ''){
		$('.myaligncustra').html('MSG : Select Date');
		$('#errormsgcustra').css('display','block');
		setTimeout(function () {
			$('#errormsgcustra').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myaligncustra').html('MSG : Select SR Name');
		$('#errormsgcustra').css('display','block');
		setTimeout(function () {
			$('#errormsgcustra').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRName != ''){
		$('#errormsgcustra').css('display','none');
		$.ajax({
			url : "customervisitajax.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRName },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);									
				$("#nodatetab").html(trimval);
				$("#nodatetab").css('display','block');
				$("#nodatetab").fadeIn('slow');
			}
		});
	}
}

/* ENDS FOR CUSTOMER VISIT TRACKING ON 05-28-2013 */

/* STOCK RECEIPTS STARTS HERE */

function checkReceipts() {	
	var DateVal						=	$('#Date').val();
	var Transaction_number			=	$('#Transaction_number').val();
	var supplier_category			=	$('#supplier_category').val();
	var supplier_name				=	$('#supplier_name').val();
	var supplier_inv_no				=	$('#supplier_inv_no').val();
	var prodcnt						=	$('#prodcnt').val();

	var currentdate = new Date();
	var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);
	//alert(date2);
	//alert(currentdate);

	if(DateVal == ''){
		$('.myaligndev').html('ERR : Select Date');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} else if(date2 > currentdate) {
		$('.myaligndev').html('ERR : Date greater than Today!');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} if(Transaction_number == ''){
		$('.myaligndev').html('ERR : Enter Receipt Number');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(Transaction_number != ''){
		var tnumpat	= /^[A-Za-z0-9]+$/;
		if(!tnumpat.test(Transaction_number)) {
			$('.myaligndev').html('ERR : Only Alphanumeric!');
			$('#errormsgdev').css('display','block');
			setTimeout(function () {
				$('#errormsgdev').hide();
			},5000);
			return false;
		}
	}
	if(supplier_category == ''){
		$('.myaligndev').html('ERR : Select Supplier Category');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(supplier_name == ''){
		$('.myaligndev').html('ERR : Enter Supplier Name');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(supplier_name != ''){
		var suppat		= /^[A-Za-z0-9 ]+$/;
		if(!suppat.test(supplier_name)) {
			$('.myaligndev').html('ERR : Only Alphanumeric!');
			$('#errormsgdev').css('display','block');
			setTimeout(function () {
				$('#errormsgdev').hide();
			},5000);
			return false;
		}
	}
	if(supplier_inv_no == ''){
		$('.myaligndev').html('ERR : Enter Supplier Invoice Number');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(supplier_inv_no != ''){
		var suppat		= /^[A-Za-z0-9 ]+$/;
		if(!suppat.test(supplier_inv_no23-08-2013)) {
			$('.myaligndev').html('ERR : Only Alphanumeric!');
			$('#errormsgdev').css('display','block');
			setTimeout(function () {
				$('#errormsgdev').hide();
			},5000);
			return false;
		}
	}
	if(prodcnt == '') {
		$('.myaligndev').html('ERR : Add Products!');
		$('#errormsgdev').css('display','block');
		setTimeout(function () {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(prodcnt != '') {
		for(var k=1; k <=prodcnt; k++) {
			var qtypat	= /^[0-9]+$/;
			if($("#qty_"+k).val() ==''){
				$('.myaligndev').html('ERR : Enter Quantity!');
				$('#errormsgdev').css('display','block');
				setTimeout(function () {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			if(isNaN($("#qty_"+k).val())){
				$('.myaligndev').html('ERR : Only Numerals!');
				$('#errormsgdev').css('display','block');
				setTimeout(function () {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			if(!qtypat.test($("#qty_"+k).val())){
				$('.myaligndev').html('ERR : Only Numerals!');
				$('#errormsgdev').css('display','block');
				setTimeout(function () {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			if($("#qty_"+k).val() == 0){
				$('.myaligndev').html('ERR : No Zero!');
				$('#errormsgdev').css('display','block');
				setTimeout(function () {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
		}
	}
	$('#errormsgdev').css('display','none');
	//return false;
}

/* STOCK RECEIPTS ENDS HERE */


/* STOCK ADJUSTMENT STARTS HERE*/

function checkAdjustment() {	
	var DateVal						=	$('#Date').val();
	var Transaction_number			=	$('#Transaction_number').val();
	var DSR_Name					=	$('#DSRName').val();
	var reason						=	$('#reason').val();
	var prodcnt						=	$('#prodcnt').val();

	var currentdate = new Date();
	var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);
	//alert(date2);
	//alert(currentdate);

	if(DateVal == ''){
		$('.myaligndev').html('ERR : Select Date');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} else if(date2 > currentdate) {
		$('.myaligndev').html('ERR : Date greater than Today!');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	} 
	if(Transaction_number == ''){
		$('.myaligndev').html('ERR : Enter Adjustment Number');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(Transaction_number != ''){
		var tnumpat	= /^[A-Za-z0-9]+$/;
		if(!tnumpat.test(Transaction_number)) {
			$('.myaligndev').html('ERR : Only Alphanumeric!');
			$('#errormsgdev').css('display','block');
			setTimeout(function() {
				$('#errormsgdev').hide();
			},5000);
			return false;
		}
	}
	if(DSR_Name == ''){
		$('.myaligndev').html('ERR : Select SR Name');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(reason == ''){
		$('.myaligndev').html('ERR : Select reason');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(prodcnt == '') {
		$('.myaligndev').html('ERR : Add Products!');
		$('#errormsgdev').css('display','block');
		setTimeout(function() {
			$('#errormsgdev').hide();
		},5000);
		return false;
	}
	if(prodcnt != '') {
		for(var k=1; k <=prodcnt; k++) {
			var qtypat	= /^[0-9-]+$/;
			if($("#qty_"+k).val() ==''){
				$('.myaligndev').html('ERR : Enter Quantity!');
				$('#errormsgdev').css('display','block');
				setTimeout(function() {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}

			//alert($("#qty_"+k).val());
			if(isNaN($("#qty_"+k).val())){
				$('.myaligndev').html('ERR : Only Numerals with Negative!');
				$('#errormsgdev').css('display','block');
				setTimeout(function() {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			if(!qtypat.test($("#qty_"+k).val())){
				$('.myaligndev').html('ERR : Only Numerals with Negative!');
				$('#errormsgdev').css('display','block');
				setTimeout(function() {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			if($("#qty_"+k).val() == 0){
				$('.myaligndev').html('ERR : No Zero!');
				$('#errormsgdev').css('display','block');
				setTimeout(function () {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}
			/*if($("#qty_"+k).val() == 0){
				$('.myaligndev').html('ERR : No Zero!');
				$('#errormsgdev').css('display','block');
				setTimeout(function() {
					$('#errormsgdev').hide();
				},5000);
				return false;
			}*/
		}
	}
	$('#errormsgdev').css('display','none');
	//return false;
}

/* STOCK ADJUSTMENT ENDS HERE */

/* OPENING STOCK UPDATE STARTS HERE */

function checkOpening() {
	var Dateval			=	$('input[name="Date"]').val();
	var qtypat	= /^[0-9]+$/;
	if(Dateval == '') {
		$('.myalignopen').html('ERR : Select Date');
		$('#errormsgopen').css('display','block');
		setTimeout(function(){
			$('#errormsgopen').hide();
		},5000);
		return false;
	}
	if($("#qty_1").val() ==''){
		$('.myalignopen').html('ERR : Enter Quantity!');
		$('#errormsgopen').css('display','block');
		setTimeout(function(){
			$('#errormsgopen').hide();
		},5000);
		return false;
	}

	//alert($("#qty_"+k).val());
	if(isNaN($("#qty_1").val())){
		$('.myalignopen').html('ERR : Only Numerals!');
		$('#errormsgopen').css('display','block');
		setTimeout(function(){
			$('#errormsgopen').hide();
		},5000);
		return false;
	}
	if(!qtypat.test($("#qty_1").val())){
		$('.myalignopen').html('ERR : Only Numerals!');
		$('#errormsgopen').css('display','block');
		setTimeout(function(){
			$('#errormsgopen').hide();
		},5000);
		return false;
	}
	if($("#qty_1").val() == 0){
		$('.myalignopen').html('ERR : No Zero!');
		$('#errormsgopen').css('display','block');
		setTimeout(function(){
			$('#errormsgopen').hide();
		},5000);
		return false;
	}
	$('#errormsgopen').css('display','none');
}

/* OPENING STOCK UPDATE ENDS HERE */


/* STOCK STATUS STARTS HERE */

function isValidDate(varFrom, varTo) {
	//alert('2323');
	var fromdate, todate, dt1, dt2, mon1, mon2, yr1, yr2, date1, date2;
	var chkFrom = document.getElementById(varFrom);
	var chkTo = document.getElementById(varTo);
	if (document.getElementById(varFrom).value == '') {
		$('.myalignstock').html('ERR : Choose From Date!');
		$('#errormsgstock').css('display','block');
		setTimeout(function() {
			$('#errormsgstock').hide();
		},5000);
		return false;
	}
	else if(document.getElementById(varTo).value == '') {
		$('.myalignstock').html('ERR : Choose To Date!');
		$('#errormsgstock').css('display','block');
		setTimeout(function() {
			$('#errormsgstock').hide();
		},5000);
		return false;
	}
	if (varFrom != null && document.getElementById(varFrom).value != '' && varTo != null && document.getElementById(varTo).value != '') {
		if (checkdate(chkFrom) != true) {
			document.getElementById(varFrom).value = '';
			document.getElementById(varFrom).focus();
			return false;
		}
		else if (checkdate(chkTo) != true) {
			document.getElementById(varTo).value = '';
			document.getElementById(varTo).focus();
			return false;
		}
		else {
			fromdate = document.getElementById(varFrom).value;
			todate = document.getElementById(varTo).value;
			/*dt1 = parseInt(fromdate.substring(0, 2), 10);
			mon1 = parseInt(fromdate.substring(3, 5), 10);
			yr1 = parseInt(fromdate.substring(6, 10), 10);*/
				
			dt1 = parseInt(fromdate.substring(8, 10), 10);
			mon1 = (parseInt(fromdate.substring(5, 7), 10)) - 1;
			yr1 = parseInt(fromdate.substring(0, 4), 10);

			dt2 = parseInt(todate.substring(8, 10), 10);
			mon2 = (parseInt(todate.substring(5, 7), 10)) - 1;
			yr2 = parseInt(todate.substring(0, 4), 10);
			date1 = new Date(yr1, mon1, dt1);
			date2 = new Date(yr2, mon2, dt2);
			//alert(date2);
			//alert(date1);

			if (date2 < date1) {
				$('.myalignstock').html('ERR : To date Should be greater than From date!');
				$('#errormsgstock').css('display','block');
				setTimeout(function() {
					$('#errormsgstock').hide();
				},5000);
				return false;
			}
			var currentdate = new Date();

			//alert(date2);
			//alert(currentdate);

			if(date2 <= currentdate)
			{
				//alert('Date greater than Today');
			} else {
				/*$('.myalignstock').html('ERR : To Date greater than Today!');
				$('#errormsgstock').css('display','block');
				setTimeout(function() {
					$('#errormsgstock').hide();
				},5000);
				return false;*/
			}
		}
	}
	$('#fromerr').html('');
	$('#toerr').html('');
	$('#errormsgstock').css('display','none');
	$.ajax({
		url : "showstockcontent.php",
		type: "get",
		dataType: "text",
		data : { "fromDate" : fromdate, "toDate" : todate,  },
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			//alert(actdata);

			var splitval	=	actdata.split('~');
			$('#containerdailysto').html(splitval[0]);
			$('input[name="totalval"]').val(splitval[2]);
			$('input[name="numprod"]').val(splitval[1]);
			$('#containerdailysto').css('display','block');
		}
	});
}

/* STOCK STATUS ENDS HERE */


/* CODE ADDED ON 31-05-2013 STARTS HERE */

function checkCollection() {
	//alert(232);
	var DateVal						=	$('#Date').val();
	var Transaction_number			=	$('#Transaction_number').val();
	var DSRName						=	$('#DSRName').val();
	var bankcnt						=	$('#bankcnt').val();
	if(DateVal == ''){
		$('.myaligncol').html('ERR : Select Date');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(Transaction_number == ''){
		$('.myaligncol').html('ERR : Enter Transaction Number');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(Transaction_number != ''){
		var tnumpat	= /^[A-Za-z0-9]+$/;
		if(!tnumpat.test(Transaction_number)) {
			$('.myaligncol').html('ERR : Only Alphanumeric!');
			$('#errormsgcol').css('display','block');
			setTimeout(function() {
				$('#errormsgcol').hide();
			},5000);
			return false;
		}
	}
	if(DSRName == ''){
		$('.myaligncol').html('ERR : Select SR Name');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(bankcnt == '') {
		$('.myaligncol').html('ERR : Add Bank!');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(bankcnt != '') {
		for(var k=1; k <=bankcnt; k++) {
			var chlpat	= /^[A-Za-z0-9]+$/;
			var amtpat	= /^[0-9.,]+$/;
			
			//alert($('#Bank_Name_'+k).val());
			//if($('#Bank_Name_'+k).val() != 'Cash' || $('#Bank_Name_'+k).val() != 'cash' || $('#Bank_Name_'+k).val() != 'CASH') {
			if($('#Bank_Name_'+k).val() != 'Cash') {
				if($("#Challan_Number_"+k).val() ==''){
					$('.myaligncol').html('ERR : Enter Challan Number!');
					$('#errormsgcol').css('display','block');
					$("#Challan_Number_"+k).focus();
					setTimeout(function() {
						$('#errormsgcol').hide();
					},5000);
					return false;
				}
				if(!chlpat.test($("#Challan_Number_"+k).val())){
					$('.myaligncol').html('ERR : Only Alphanumeric!');
					$('#errormsgcol').css('display','block');
					$("#Challan_Number_"+k).focus();
					setTimeout(function() {
						$('#errormsgcol').hide();
					},5000);
					return false;
				}

				var currentdate				=	new Date();
				var Dateval					=	$("#Challan_Date_"+k).val()
				
				if(Dateval ==''){
					$('.myaligncol').html('ERR : Select Challan Date!');
					$('#errormsgcol').css('display','block');
					$("#Challan_Date_"+k).focus();
					setTimeout(function() {
						$('#errormsgcol').hide();
					},5000);
					return false;
				}
				var dte2					=	parseInt(Dateval.substring(8,10),10);
				var mont2					=	(parseInt(Dateval.substring(5,7), 10)) -1;
				var year2					=	parseInt(Dateval.substring(0,4),10);
				var date2					=	new Date(year2,mont2,dte2);

				/*alert(dte2);
				alert(mont2);
				alert(year2);*/

				/*var dt2 = parseInt(DateVal.substring(8, 10), 10);
				var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
				var yr2 = parseInt(DateVal.substring(0, 4), 10);
				var date2 = new Date(yr2, mon2, dt2);*/

				if (date2 > currentdate){
					$('.myaligncol').html('ERR : Date greater than today!');
					$('#errormsgcol').css('display','block');
					$("#Challan_Date_"+k).focus();
					setTimeout(function() {
						$('#errormsgcol').hide();
					},5000);
					return false;
				}
			}
			

			if($("#Amount_Deposited_"+k).val() ==''){
				$('.myaligncol').html('ERR : Enter Amount!');
				$('#errormsgcol').css('display','block');
				$("#Amount_Deposited_"+k).focus();
				setTimeout(function() {
					$('#errormsgcol').hide();
				},5000);
				return false;
			}
			if($("#Amount_Deposited_"+k).val() == 0){
				$('.myaligncol').html('ERR : No Zero!');
				$('#errormsgcol').css('display','block');
				$("#Amount_Deposited_"+k).focus();
				setTimeout(function() {
					$('#errormsgcol').hide();
				},5000);
				return false;
			}

			if(!amtpat.test($("#Amount_Deposited_"+k).val())){
				$('.myaligncol').html('ERR : Only Numerals and Decimals!');
				$('#errormsgcol').css('display','block');
				$("#Amount_Deposited_"+k).focus();
				setTimeout(function() {
					$('#errormsgcol').hide();
				},5000);
				return false;
			}
		}
	}
	$('#errormsgcol').css('display','none');
	//return false;
}

/* CODE ADDED ON 31-05-2013 ENDS HERE */


/* CODE ADDED ON 31-05-2013 STARTS HERE */

function clock() {
	//alert('2323');
	var now			=	new Date();
	var showmin		=	now.getMinutes();
	var showmincal	=	'';
	var showhour	=	now.getHours();
	var showhourcal	=	'';
	var showsec		=	now.getSeconds();
	var showseccal	=	'';
	var showmon		=	now.getMonth();
	var showdate	=	now.getDate();
	var showyear	=	now.getFullYear();

	var m_names = new Array("Jan", "Feb", "Mar", 
"Apr", "May", "Jun", "Jul", "Aug", "Sep", 
"Oct", "Nov", "Dec");

	//var hours = date.getHours();
	var ampm = showhour >= 12 ? 'PM' : 'AM';
	showhour = showhour % 12;
	showhour = showhour ? showhour : 12; // the hour '0' should be '12'

   if(showmin < 10) {
		showmincal = "0"+showmin;
   } else {
		showmincal = showmin;
   }

   if(showsec < 10) {
		showseccal = "0"+showsec;
   } else {
		showseccal = showsec;
   }
   if(showhour < 10) {
		showhourcal = "0"+showhour;
   } else {
		showhourcal = showhour;
   }
   if(showdate < 10) {
		showdatecal = "0"+showdate;
   } else {
		showdatecal = showdate;
   }
   var outStr = showdatecal+'-'+m_names[showmon]+'-'+showyear+" /"+showhourcal+':'+showmincal+':'+showseccal+" "+ampm;
    //alert(outStr);
	$('#showtime').html(outStr);
	//alert($('#showtime').html());
   //document.getElementById('showtime').innerHTML=;
   setTimeout('clock()',1000);
}
clock();

/* CODE ADDED ON 31-05-2013 ENDS HERE */


/* CODE ADDED ON 06-06-2013 STARTS HERE */
function cycleStartDSR(DSRCodeName) {
	var splitDSR		=	DSRCodeName.split("~");
	var splitDSRCode	=	splitDSR[0];
	if(DSRCodeName == '') {
		$('.myaligncystart').html('ERR : Select SR Name');
		$('#errormsgcystart').css('display','block');
		setTimeout(function() {
			$('#errormsgcystart').hide();
		},5000);
		$('input[name="dsr_code"]').val('');
		return false;
	} else {				
		$('input[name="dsr_code"]').val(splitDSRCode);
		//alert(splitDSR[2]);
		//alert(splitDSR[3]);
		$('input[name="route"]').val(splitDSR[2]);
		$('input[name="location_val"]').val(splitDSR[3]);
	}
}

function loadLocation(Locaval) {
	var splitLoc		=	Locaval.split("~");
	var Locationval		=	splitLoc[1];
	if(Locaval == '') {
		$('.myaligncystart').html('ERR : Select Route');
		$('#errormsgcystart').css('display','block');
		setTimeout(function() {
			$('#errormsgcystart').hide();
		},5000);
		$('input[name="location_val"]').val('');
		return false;
	} else {
		$('input[name="location_val"]').val(Locationval);
	}
}

function checkcyclestart() {
	var cycle_code				=	$('select[name="cycle_code"]').val();
	var DSRName					=	$('select[name="dsrname"]').val();
	var device_name				=	$('select[name="devicename"]').val();
	var route					=	$('select[name="route"]').val();
	var vehicle_name			=	$('select[name="vehicle"]').val();
	
	if(cycle_code == ''){
		$('.myaligncystart').html('ERR : Select Cycle Start Flag');
		$('#errormsgcystart').css('display','block');
		setTimeout(function () {
			$('#errormsgcystart').hide();
		},5000);
		return false;
	} else if(DSRName == ''){
		$('.myaligncystart').html('ERR : Select SR');
		$('#errormsgcystart').css('display','block');
		setTimeout(function () {
			$('#errormsgcystart').hide();
		},5000);
		return false;
	} else if(device_name == ''){
		$('.myaligncystart').html('ERR : Select Device');
		$('#errormsgcystart').css('display','block');
		setTimeout(function () {
			$('#errormsgcystart').hide();
		},5000);
		return false;
	} else if(route == ''){
		$('.myaligncystart').html('ERR : Select Route');
		$('#errormsgcystart').css('display','block');
		setTimeout(function () {
			$('#errormsgcystart').hide();
		},5000);
		return false;
	} else if(vehicle_name == ''){
		$('.myaligncystart').html('ERR : Select Vehicle');
		$('#errormsgcystart').css('display','block');
		setTimeout(function () {
			$('#errormsgcystart').hide();
		},5000);
		return false;
	} else if(vehicle_name == 'others' || vehicle_name == 'Others'){
		var vehicleother	=	$('#vehicleother').val();
		if(vehicleother == '') {
			$('.myaligncystart').html('ERR : Enter Vehicle Reg. No.');
			$('#errormsgcystart').css('display','block');
			setTimeout(function () {
				$('#errormsgcystart').hide();
			},5000);
			return false;
		}
	}
}

function loadCustConfirmation(DSRCode) {
	var DateVal		=	$('input[name="Date"]').val();
	if(DSRCode == ''){
		$('.myaligncuscon').html('MSG : Select SR Name');
		$('#errormsgcuscon').css('display','block');
		$('#cdsrcode').val('');
		$('#route_desc').val('');
		$('#clocation').val('');
		$('#croute_code').val('');
		$('#containerpr').html('');
		$('#maplocation').val('');
		$('#printopen').hide();
		setTimeout(function () {
			$('#errormsgcuscon').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRCode != ''){
		$('#errormsgcuscon').css('display','none');
		$.ajax({
			url : "getcustomerconfirm.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRCode },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				//return false;
				$('#cdsrcode').val(splitval[0]);
				$('#route_desc').val(splitval[1]);
				$('#clocation').val(splitval[2]);
				$('#croute_code').val(splitval[3]);
				$('#containerpr_cus').html(splitval[4]);
				var longlat		=	splitval[5]+"~"+splitval[6];				
				var printvalue	=	splitval[7];
				if(printvalue == 1) {
					$('#maplocation').val(longlat);
					//$('#printopen').show();
				} else {
					//$('#printopen').hide();
				}
			}
		});
	}
}

function loadCustConfirmationDate(DateVal) {
	var DSRCode		=	$('select[name="dsrname"]').val();
	if(DSRCode == ''){
		$('.myaligncuscon').html('MSG : Select SR Name');
		$('#errormsgcuscon').css('display','block');
		$('#cdsrcode').val('');
		$('#route_desc').val('');
		$('#clocation').val('');
		$('#croute_code').val('');
		$('#containerpr').html('');
		$('#maplocation').val('');
		$('#printopen').hide();
		setTimeout(function () {
			$('#errormsgcuscon').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRCode != ''){
		$('#errormsgcuscon').css('display','none');
		$.ajax({
			url : "getcustomerconfirm.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRCode },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				$('#cdsrcode').val(splitval[0]);
				$('#route_desc').val(splitval[1]);
				$('#clocation').val(splitval[2]);
				$('#croute_code').val(splitval[3]);
				$('#containerpr_cus').html(splitval[4]);
				var longlat		=	splitval[5]+"~"+splitval[6];				
				var printvalue	=	splitval[7];
				if(printvalue == 1) {
					$('#maplocation').val(longlat);
					//$('#printopen').show();
				} else {
					//$('#printopen').hide();
				}
			}
		});
	}
}

function loadsalescol(DSRCode) {
	var DateVal		=	$('input[name="Date"]').val();

	if(DSRCode == ''){
		$('.myalignsalcol').html('MSG : Select SR Name');
		$('#errormsgsalcol').css('display','block');
		setTimeout(function () {
			$('#errormsgsalcol').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRCode != ''){
		$('#errormsgsalcol').css('display','none');
		$.ajax({
			url : "getsalescollection.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRCode },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				$('#cdsrcode').val(DSRCode);
				$('#vehicle_name').val(splitval[0]);
				$('#device_code').val(splitval[1]);
				$('#containerpr').html(splitval[2]);
			}
		});
	}
}

function loadsalescolDate(DateVal) {
	var DSRCode		=	$('select[name="dsrname"]').val();

	if(DSRCode == ''){
		$('.myalignsalcol').html('MSG : Select SR Name');
		$('#errormsgsalcol').css('display','block');
		setTimeout(function () {
			$('#errormsgsalcol').hide();
		},5000);
		return false;
	} else if(DateVal != '' && DSRCode != ''){
		$('#errormsgsalcol').css('display','none');
		$.ajax({
			url : "getsalescollection.php",
			type: "get",
			dataType: "text",
			data : { "DateVal" : DateVal,"DSR_Code": DSRCode },
			success : function (dataval) {
				var trimval		=	$.trim(dataval);
				splitval		=	trimval.split("~");
				//alert(trimval);
				$('#cdsrcode').val(DSRCode);
				$('#vehicle_name').val(splitval[0]);
				$('#device_code').val(splitval[1]);
				$('#containerpr').html(splitval[2]);
			}
		});
	}
}

function showmap() {
	var maplocation		=	$('input[name="maplocation"]').val();
	if(maplocation	== '') {
		$('.myaligncuscon').html('MSG : No Location Found');
		$('#errormsgcuscon').css('display','block');
		setTimeout(function () {
			$('#errormsgcuscon').hide();
		},5000);
		return false;
	}
	var showingmapval	=	'showingmap';
	$(" <div />" ).attr("id","showingmap").addClass("confirmMAp").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeMapEnquiry();"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="map_canvas" class="ShowMap" align="left" valin="top"></div></p>').appendTo($( "body" ));	

	var splitval		=	maplocation.split("~");
	var lat				=	splitval[0];
	var longval			=	splitval[1];
	//alert(lat);
	//alert(longval);
	var latlng = new google.maps.LatLng(lat,longval);
	var settings = {
		zoom: 15,
		center: latlng,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl: true,
		navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
		mapTypeId: google.maps.MapTypeId.ROADMAP};
	var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
		 google.maps.event.trigger(map, 'resize'); 
	var companyImage = new google.maps.MarkerImage('../images/marker.png',
		new google.maps.Size(100,50),
		new google.maps.Point(0,0),
		new google.maps.Point(50,50)
	);
	//var companyPos = new google.maps.LatLng(25.2894045564648903, 51.49017333985673);
	var companyPos = new google.maps.LatLng(lat,longval);
	var companyMarker = new google.maps.Marker({
		position: companyPos,
		map: map,
		icon: companyImage,
		title:"Location Name",
		zIndex: 3});

	google.maps.event.addListener(companyMarker, 'click', function() {
		infowindow.open(map,companyMarker);
	});

	//$("#backgroundChatPopup").fadeIn("slow");
		
	$("#backgroundChatPopup").css({"opacity": "0.7"});
	$("#backgroundChatPopup").fadeIn("slow");

	$('#showingmap').css("display","block");
	$('.ShowMap').css("display","block");
}

function stockAddPopup() {
	$("#TransactionQty").val('');
	$("#indStockUpdate").css("display","block");
	$("#backgroundChatPopup").css({"opacity": "0.7"});
	$("#backgroundChatPopup").fadeIn("slow");
	return false;
}

function closeMapEnquiry() {
	$('#showingmap').remove();
	$('#showingmap').css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function closeProductShow() {
	//$('#productshow').remove();
	$('#productshow').css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function closeShowingMap() {
	$('#showingmap').remove();
	$('#showingmap').css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function closeindstock(){
	//$('#indStockUpdate').remove();
	$('#indStockUpdate').css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
	$('#TransactionQty').val('');
}


function checkstockqty(pval,pcode,kdcode) {
	//alert(pindex);
	var pindex			=	$("#"+pval+" option:selected").index();

	if("product_code" == pval) {
		$("#product_name").get(0).selectedIndex = pindex;
		//$('#TransactionQty').val(qty);
	}
	if("product_name" == pval) {
		$("#product_code").get(0).selectedIndex = pindex;
		//$('#TransactionQty').val(qty);
	}

	var pcodeqty		=	$('#product_code').val();
	var splitpcodeqty	=	pcodeqty.split("~");
	var product_code	=	splitpcodeqty[0];
	var qty				=	parseInt(splitpcodeqty[1]);
	var product_name	=	$('#product_name').val();
	var TransactionQty	=	$('#TransactionQty').val();
	var DateVal			=	$('#Date').val();
	var UOM				=	$('#UOM').val();

	if("product_code" == pval) {
		$('#TransactionQty').val(qty);
	}
	if("product_name" == pval) {
		$('#TransactionQty').val(qty);
	}


	if(pcode == ''){
		$('.myalignindopen').html('ERR : Select Product');
		$('#errormsgindopen').css('display','block');
		setTimeout(function () {
			$('#errormsgindopen').hide();
		},5000);
		return false;
	} 
}

function checkindstock() {
	var pcodeqty		=	$('#product_code').val();
	var splitpcodeqty	=	pcodeqty.split("~");
	var product_code	=	splitpcodeqty[0];
	var product_name	=	$('#product_name').val();
	var transqty		=	$('#TransactionQty').val();
	var transpat		=	/^[0-9]+$/;
	var umo				=	parseInt($('#umo').val());
	var DateVal			=	$('#Date').val();
	var UOM				=	$('#UOM').val();
	//alert(umo);

	if(umo == 0){
		$('.myalignindopen').html('ERR : No Stocks for Update');
		$('#errormsgindopen').css('display','block');
		setTimeout(function () {
			$('#errormsgindopen').hide();
		},5000);
		return false;
	}

	if(product_code == '' && product_name == '') {
		$('.myalignindopen').html('ERR : Select Product');
		$('#errormsgindopen').css('display','block');
		setTimeout(function () {
			$('#errormsgindopen').hide();
		},5000);
		return false;
	}
	if(transqty == '') {
		$('.myalignindopen').html('ERR : Enter Quantity');
		$('#errormsgindopen').css('display','block');
		setTimeout(function () {
			$('#errormsgindopen').hide();
		},5000);
		return false;
	} if(!transpat.test(transqty)) {
		$('.myalignindopen').html('ERR : Only Numerals');
		$('#errormsgindopen').css('display','block');
		setTimeout(function () {
			$('#errormsgindopen').hide();
		},5000);
		return false;
	}
	$('#errormsgindopen').css('display','none');
	$.ajax({
		url : "getopeningstockdata.php",
		type: "get",
		dataType: "text",
		data : { "pcode" : product_code,"TransactionQty": transqty, "DateVal" : DateVal, "UOM" : UOM },
		success : function (dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			if(trimval == "success") {
				alert('Stock Updated Successfully!');
				window.location = "openingstockins.php";
			}
		}
	});

}

/* CODE ADDED ON 06-06-2013 ENDS HERE */


/* CODE ADDED ON 08-06-2013 STARTS HERE */

function pagination_ajax(page,params) {
	var splitparam		=	params.split("&");
	
	$.ajax({
		url : "showstockcontent.php",
		type: "get",
		dataType: "text",
		data : { "fromDate" : splitparam[0], "toDate" : splitparam[1], "sortorder" : splitparam[2], "ordercol" : splitparam[3], "page" : page },
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			//alert(actdata);
			var splitval	=	actdata.split('~');
			$('#containerdailysto').html(splitval[0]);
			$('input[name="totalval"]').val(splitval[2]);
			$('input[name="numprod"]').val(splitval[1]);
			$('#containerdailysto').css('display','block');
		}
	});
}

function pag_ajax_stosta(page,params) {
	var splitparam		=	params.split("&");
	var Product_code		=	splitparam[2];
	
	$.ajax({
		url : "pullindivstock.php",
		type: "get",
		dataType: "text",
		data : { "fromDate" : splitparam[0], "toDate" : splitparam[1], "Pcode" : splitparam[2], "tblname" : splitparam[3], "page" : page },
		success : function(dataval) {
			var insertmsg		=	$.trim(dataval);					
			$('#SecondEnqMsg'+Product_code).html(insertmsg);
			return false;
		}
	});
}

function pag_ajax_cuscon(page,params) {
	var splitparam		=	params.split("&");
	
	$.ajax({
		url : "getcustomerconfirm.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : splitparam[0], "DSR_Code": splitparam[1], "sortorder": splitparam[2], "ordercol": splitparam[3], "page": page  },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			splitval		=	trimval.split("~");
			//alert(trimval);
			$('#containerpr_cus').html(splitval[4]);
			return false;
		}
	});
}

function loadDeviceDefault(device_val) {
	var splitval	=	device_val.split("~");
	$("input[name='device_description']").val(splitval[3]);
	$("input[name='device_serial_number']").val(splitval[1]);
	$("input[name='device_call_no']").val(splitval[2]);
}

function deviceregister() {
	var uid				=	$("input[name='uid']").val();
	var pwd				=	$("input[name='pwd']").val();
	var url				=	$("input[name='url']").val();
	var device_code		=	$("select[name='device_code']").val();
	var urlpat			=	/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

	if(device_code == '') {
		$('.myaligndevreg').html('ERR : Select Device');
		$('#errormsgdevreg').css('display','block');
		setTimeout(function () {
			$('#errormsgdevreg').hide();
		},5000);
		return false;
	} else if (url == '') {
		$('.myaligndevreg').html('ERR : Enter URL');
		$('#errormsgdevreg').css('display','block');
		setTimeout(function () {
			$('#errormsgdevreg').hide();
		},5000);
		return false;
	} if(!urlpat.test(url)) {
		$('.myaligndevreg').html('ERR : Enter Valid URL');
		$('#errormsgdevreg').css('display','block');
		setTimeout(function () {
			$('#errormsgdevreg').hide();
		},5000);
		return false;		
	} else if (uid == '') {
		$('.myaligndevreg').html('ERR : Enter User Name');
		$('#errormsgdevreg').css('display','block');
		setTimeout(function () {
			$('#errormsgdevreg').hide();
		},5000);
		return false;
	} else if (pwd == '') {
		$('.myaligndevreg').html('ERR : Enter Password');
		$('#errormsgdevreg').css('display','block');
		setTimeout(function () {
			$('#errormsgdevreg').hide();
		},5000);
		return false;
	} 
}

/* CODE ADDED ON 08-06-2013 STARTS HERE */


/* CODE ADDED ON 14-06-2013 STARTS HERE */

function pag_devajax(page,params) { // For pagination of the device transaction ajax result page
	var splitparam		=	params.split("&");	
	$.ajax({
		url : "devtransajax.php",
		type: "get",
		dataType: "text",
		data : { "fromdate" : splitparam[0], "todate" : splitparam[1], "kd_id" : splitparam[2],"dsr_id": splitparam[3], "sortorder" : splitparam[4],"ordercol": splitparam[5], "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			//alert(page);
			//$('#page').val(page);
			$('#tablestr_dev').html(trimval);
		}
	});
}

function print_pages(formid) { // For pagination of the device transaction ajax result page
	//alert(formid);
	//document.formid.submit();
	//document.getElementById(formid).submit();
	document.forms[formid].submit();
}

function getlineitems(transno,transid,transtype,fromdate,todate,kd_id,dsr_id){
	$.ajax({
		type: "get",
		url : "getdevicelineitems.php",
		data : { "transno" : transno, "transtype" : transtype, "fromdate" : fromdate, "todate" : todate,"kd_id" : kd_id,"dsr_id" : dsr_id },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			if(actdata == '') {
				//alert(actdata);
				//$("#"+errid).css("display","block");
				//return;
			}
			//alert(actdata);
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","SecondEnq"+transno).addClass("confirmFirstDeviceTrans").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeSecondEnquiry(this,\''+transno+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="SecondEnqMsg'+transno+'"></div></p>').appendTo($( "body" ));
			$("#SecondEnq"+transno).css("display","block");
			$('#SecondEnqMsg'+transno).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		}
	});
}

function pag_devlineitemajax(page,params) { // For pagination of the device transaction ajax result page
	var splitparam		=	params.split("&");
	var transno			=	splitparam[0];
	var transtype		=	splitparam[1];
	var fromdate		=	splitparam[2];
	var todate			=	splitparam[3];
	var kd_id			=	splitparam[4];
	var dsr_id			=	splitparam[5];
	var sortorder		=	splitparam[6];
	var ordercol		=	splitparam[7];
	$.ajax({
		url : "getdevicelineitems.php",
		type: "get",
		dataType: "text",
		data : { "transno" : transno, "transtype" : transtype, "fromdate" : fromdate, "todate" : todate,"kd_id" : kd_id,"dsr_id" : dsr_id, "sortorder" : sortorder, "ordercol" : ordercol,    "page" : page },
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$('#SecondEnqMsg'+transno).html(insertmsg);
			return false;			
		}
	});
}

function getcustomerimage(transno) {
	$.ajax({
		type: "get",
		url : "getdevicecusimage.php",
		data : { "transno" : transno },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","CustImageEnq"+transno).addClass("confirmFirstDeviceImage").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeCustomerImage(this,\''+transno+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="CustImageEnqMsg'+transno+'"></div></p>').appendTo($( "body" ));
			$("#CustImageEnq"+transno).css("display","block");
			$('#CustImageEnqMsg'+transno).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		}
	});
}

function getcustomersig(transno){
	$.ajax({
		type: "get",
		url : "getdevicesignature.php",
		data : { "transno" : transno },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","CustSignatureEnq"+transno).addClass("confirmFirstDeviceSig").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeCustomerSignature(this,\''+transno+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="CustSignatureEnqMsg'+transno+'"></div></p>').appendTo($( "body" ));
			$("#CustSignatureEnq"+transno).css("display","block");
			$('#CustSignatureEnqMsg'+transno).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		}
	});
}

function getfeedback(transno){
	$.ajax({
		type: "get",
		url : "getdevicefeedback.php",
		data : { "transno" : transno },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","CustFeedbackEnq"+transno).addClass("confirmFirstDeviceFeed").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeCustomerFeedback(this,\''+transno+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="CustFeedbackEnqMsg'+transno+'"></div></p>').appendTo($( "body" ));
			$("#CustFeedbackEnq"+transno).css("display","block");
			$('#CustFeedbackEnqMsg'+transno).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		}
	});
}

function getbatchcontrol(transid,transline){
	$.ajax({
		type: "get",
		url : "getbatchcontrol.php",
		data : { "transno" : transid, "transline" : transline },
		dataType: "text",
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$(" <div />" ).attr("id","ProductBatchControlEnq"+transid).addClass("confirmBatchControl").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeBatchControl(this,\''+transid+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="ProductBatchControlMsg'+transid+'"></div></p>').appendTo($( "body" ));
			$("#ProductBatchControlEnq"+transid).css("display","block");
			$('#ProductBatchControlMsg'+transid).html(insertmsg);
			$('#backgroundChatPopup').css({"opacity":"0.7"});
			$('#backgroundChatPopup').fadeIn("slow");
			return false;
		}
	});
}

function closeCustomerImage(atr,PCode){
	$('#CustImageEnq'+PCode).remove();
	$('#CustImageEnq'+PCode).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
}

function closeCustomerSignature(atr,PCode){
	$('#CustSignatureEnq'+PCode).remove();
	$('#CustSignatureEnq'+PCode).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
}

function closeCustomerFeedback(atr,PCode){
	$('#CustFeedbackEnq'+PCode).remove();
	$('#CustFeedbackEnq'+PCode).css('display','none');
	$('#backgroundChatPopup').css({"display":"none"});
}

function closeBatchControl(atr,Pcode) {
	$("#ProductBatchControlEnq"+Pcode).remove();
	$("#ProductBatchControlEnq"+Pcode).css({"display":"none"});
	$("#backgroundChatPopup").css({"display":"none"});

}


function pag_devbatchajax(page,params) { // For pagination of the device transaction ajax result page
	var splitparam		=	params.split("&");
	var transno			=	splitparam[0];
	var transline		=	splitparam[1];
	$.ajax({
		url : "getbatchcontrol.php",
		type: "get",
		dataType: "text",
		data : { "transno" : transno, "transline" : transline, "page" : page },
		success : function(dataval) {
			var actdata		=	$.trim(dataval);
			var insertmsg		=	actdata;		
			$('#ProductBatchControlEnq'+transid).html(insertmsg);
			return false;			
		}
	});
}

function pag_vehstockajax(page,params) { // For pagination of the device transaction ajax result page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	$.ajax({
		url : "getvehiclestock.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "sortorder" : splitparam[2], "ordercol" : splitparam[3], "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			splitval		=	trimval.split("~");
			//alert(trimval);
			$('#nodatetab').html(splitval[3]);	
		}
	});
}

function pag_salcolajax(page,params) { // For pagination of the device transaction ajax result page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	$.ajax({
		url : "getsalescollection.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			splitval		=	trimval.split("~");
			$('#containerpr').html(splitval[2]);	
		}
	});
}
function pag_cusvisitajax(page,params) { // For pagination of the Customer visit tracking ajax result page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	//alert(splitparam[2]);
	var Kval			=	parseInt(splitparam[2]) + 1;
	var sortorder		=	splitparam[3];
	var ordercol		=	splitparam[4];
	$.ajax({
		url : "customervisitajax.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "k" : Kval, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			$("#nodatetab").html(trimval);	
		}
	});
}

function hideprintbutton() {
	$("#printopen").css({"display":"none"});
	$("#showviewbutton").css({"display":"none"});
	//$(".paginationfile").css({"display":"none"});
	window.print();
}

function printcusconfirm() {
	var dsrid		=	$('#dsrname').val();
	var dsrdate		=	$('#Date').val();
	if(dsrid != ''){
		window.location = "printcustomerconfirm.php?DateVal="+dsrdate+"&DSR_Code="+dsrid;
	}
}


/* CODE ADDED ON 14-06-2013 ENDS HERE */


/* CODE ADDED ON 26-06-2013 STARTS HERE */

function vehiclenewreg(otherval) {
	if(otherval == 'Others' || otherval == 'others') {
		$('#vehother').show();
		$('#vehicleother').val('');
	} else {
		$('#vehother').hide();
		$('#vehicleother').val('');
	}
}

/* CODE ADDED ON 26-06-2013 ENDS HERE */


//Get DSR CODE
function DSRCODE() {
	var val=$('#DSRName option:selected').text();
	$.ajax({
		url: 'get_dsr.php?val=' + val,
		success: function(data) {
			//alert(data);
			var value=$.trim(data);//To Remove White Space in string
			var value1=data.substring(0,value.length-1);//To return part of the string
			var list= value1.split("|"); 
			for (var i=0; i<list.length; i++) {
				var arr_i= list[i].split("^");
				//alert(arr_i[6]);
				$("#DSR_Code").val(arr_i[0]);					
			}
		}
	});	
}


function selectall() {
	var prodcnt		=	$("#prodcnt").val();
	for(var k = 1; k <=prodcnt; k++) {
		$("#cbox_"+k).attr("checked",true);
	}
}
function selectnone() {
	var prodcnt		=	$("#prodcnt").val();
	for(var k = 1; k <=prodcnt; k++) {
		$("#cbox_"+k).attr("checked",false);
	}
}

function checkprodconfirm() {
	var prodcnt		=	$("#prodcnt").val();
	var w=0;
	var y=0;
	var qtypat	= /^[0-9]+$/;

	for(var f=1; f <= prodcnt; f++) {
		if($("#cbox_"+f).is(":checked")){
			y++;
		}
	}
	if(y == 0) {
		$(".myalignprod").html("Select Products");
		$("#errormsgpopupprod").css('display','block');
		setTimeout(function() {
			$("#errormsgpopupprod").hide();
		},5000);
		return false;
	}
	for(var k=1; k <= prodcnt; k++) {
		if($('#cbox_'+k).is(":checked")) {
			//alert(k);
			var actual_qty			=	parseInt($.trim($("#actual_qty_"+k).val()));
			var Loaded_Qty			=	$("#Loaded_Qty_"+k).val();
			var Loaded_Qty_check	=	parseInt($.trim($("#Loaded_Qty_"+k).val()));
			var product_code_val	=	$("#product_code_"+k).val();
			//alert(actual_qty);
			//alert(Loaded_Qty);
			if(Loaded_Qty == ''){
				$('.myalignprod').html('ERR : Enter Quantity for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}

			//alert($("#qty_"+k).val());
			if(isNaN(Loaded_Qty)){
				$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				//alert(Loaded_Qty);
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}
			if(!qtypat.test(Loaded_Qty)){
				$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}
			if(Loaded_Qty == 0){
				$('.myalignprod').html('ERR : No Zero for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}
			//alert(Loaded_Qty);
			//alert(actual_qty);
			//var actval	= (actual_qty > Loaded_Qty) ? 4 : 6;
			//alert(actval);
			//alert(actual_qty > Loaded_Qty);
			if(actual_qty < Loaded_Qty_check) {
				$(".myalignprod").html("ERR : Available quantity is "+actual_qty+" for "+product_code_val);
				$("#errormsgpopupprod").css("display","block");
				setTimeout(function() {
					$("#errormsgpopupprod").hide();
				},5000);
				$("#Loaded_Qty_"+k).focus();
				return false;
			} else {
				//alert(Loaded_Qty);
			}
		}	
		Loaded_Qty = '';
		actual_qty = '';
	}
	$('#productshow').css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function includeweekend(){ 
	if($("#includesatsun").is(":checked")) {
		$("#route_sat").attr("disabled",false);
	} else {
		$("#route_sat").attr("disabled",true);
		$("#route_sat").val('');
		for(var t=1; t<26; t++) {
			$('#sat_'+t).html('');
		}
	}
}

function bringcustomers(routeval,dayval,elename) {
	var dsrval			=	$("#dsrname").val();
	var	route_sat		=	'';
	if(dsrval == '') {
		$(".myalignrouteplan").html("ERR : Select SR");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		$("#"+elename).val('');
		return false;
	}
	if(routeval == '') {
		$(".myalignrouteplan").html("ERR : Select Route");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		for(var r=1; r<26; r++) {
			$('#'+dayval+'_'+r).html('');
		}
		return false;
	} else {
		if(!$("#repeatroute").is(":checked")) {
			var route_mon	=	$("#route_mon").val();
			var route_tue	=	$("#route_tue").val();
			var route_wed	=	$("#route_wed").val();
			var route_thu	=	$("#route_thu").val();
			var route_fri	=	$("#route_fri").val();
			if($("#includesatsun").is(":checked")) {
				route_sat	=	$("#route_sat").val();
			}

			if(dayval == 'mon') {
				if(route_mon == route_tue || route_mon == route_wed || route_mon == route_thu || route_mon == route_fri || route_mon == route_sat) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#mon_'+t).html('');
					}
					return false;
				} 
			} 
			if(dayval == 'tue') {
				if(route_tue == route_mon || route_tue == route_wed || route_tue == route_thu || route_tue == route_fri || route_tue == route_sat) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#tue_'+t).html('');
					}
					return false;
				}
			} 
			if(dayval == 'wed') {
				if(route_wed == route_mon || route_wed == route_tue || route_wed == route_thu || route_wed == route_fri || route_wed == route_sat) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#wed_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'thu') {
				if(route_thu == route_mon || route_thu == route_tue || route_thu == route_wed || route_thu == route_fri || route_thu == route_sat) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#thu_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'fri') {
				if(route_fri == route_mon || route_fri == route_tue || route_fri == route_wed || route_fri == route_thu || route_fri == route_sat) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#fri_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'sat') {
				if(route_sat == route_mon || route_sat == route_tue || route_sat == route_wed || route_sat == route_thu || route_sat == route_fri) {
					$(".myalignrouteplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgrouteplan").css("display","block");
					setTimeout(function () {
						$("#errormsgrouteplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#sat_'+t).html('');
					}
					return false;
				} 
			}
		}
		$.ajax({
			type		:	"get",
			url			:	"routeplancurajax.php",
			data		:	{ "DSR_Code" : dsrval, "routeval" : routeval  },
			dataType	:	"text",
			success		:	function(ajaxval) {
				var trimval		=	$.trim(ajaxval);
				var splitval	=	trimval.split("&");
				var splitlen		=	splitval.length;
				for(var t=1; t<26; t++) {
					if(t<=splitlen){
						if(dayval == 'mon') {
							$('#mon_'+t).html(splitval[t-1]);
						} else if(dayval == 'tue') {
							$('#tue_'+t).html(splitval[t-1]);
						} else if(dayval == 'wed') {
							$('#wed_'+t).html(splitval[t-1]);
						} else if(dayval == 'thu') {
							$('#thu_'+t).html(splitval[t-1]);
						} else if(dayval == 'fri') {
							$('#fri_'+t).html(splitval[t-1]);
						} else if(dayval == 'sat') {
							$('#sat_'+t).html(splitval[t-1]);
						} 						
					} else {
						if(dayval == 'mon') {
							$('#mon_'+t).html('');
						} else if(dayval == 'tue') {
							$('#tue_'+t).html('');
						} else if(dayval == 'wed') {
							$('#wed_'+t).html('');
						} else if(dayval == 'thu') {
							$('#thu_'+t).html('');
						} else if(dayval == 'fri') {
							$('#fri_'+t).html('');
						} else if(dayval == 'sat') {
							$('#sat_'+t).html('');
						}
					}
				}
			}		
		});
	}
}

function repeatrouteweek() {
	var route_sat	=	'';
	var moncnt		=	0;
	var tuecnt		=	0;
	var wedcnt		=	0;
	var thucnt		=	0;
	var fricnt		=	0;
	var satcnt		=	0;
	if(!$("#repeatroute").is(":checked")) {
		var route_mon	=	$("#route_mon").val();
		var route_tue	=	$("#route_tue").val();
		var route_wed	=	$("#route_wed").val();
		var route_thu	=	$("#route_thu").val();
		var route_fri	=	$("#route_fri").val();
		if($("#includesatsun").is(":checked")) {
			route_sat	=	$("#route_sat").val();
		}
		var confirmremove	=	confirm("It will remove all repetition for the week");
		if(confirmremove) {
			if(route_tue == route_mon) {				
				tuecnt++;
			}
			if(tuecnt > 0 ) {
				$("#route_tue").val('');
				for(var t=1; t<26; t++) {
					$('#tue_'+t).html('');
				}
			}
			if(route_wed == route_mon) {
				wedcnt++;
			}
			if(route_wed == route_tue) {
				wedcnt++;
			}
			if(wedcnt > 0) {
				$("#route_wed").val('');
				for(var t=1; t<26; t++) {
					$('#wed_'+t).html('');
				}
			}
			if(route_thu == route_mon) {
				thucnt++;
			}
			if(route_thu == route_tue) {
				thucnt++;
			}
			if(route_thu == route_wed) {
				thucnt++;
			}
			if(thucnt > 0){
				$("#route_thu").val('');
				for(var t=1; t<26; t++) {
					$('#thu_'+t).html('');
				}
			}
			if(route_fri == route_mon) {
				fricnt++;
			}
			if(route_fri == route_tue) {
				fricnt++;
			}
			if(route_fri == route_wed) {
				fricnt++;
			}
			if(route_fri == route_thu) {
				fricnt++;
			}
			if(fricnt > 0) {
				$("#route_fri").val('');
				for(var t=1; t<26; t++) {
					$('#fri_'+t).html('');
				}
			}
			if(route_sat == route_mon) {
				satcnt++;	
			}
			if(route_sat == route_tue) {
				satcnt++;
			}
			if(route_sat == route_wed) {
				satcnt++;
			}
			if(route_sat == route_thu) {
				satcnt++;
			}
			if(route_sat == route_fri) {
				satcnt++;
			}
			if(satcnt > 0) {
				$("#route_sat").val('');
				for(var t=1; t<26; t++) {
					$('#sat_'+t).html('');
				}
			}
		} else {
			$("#repeatroute").attr("checked",true);
		}
	}
}

function routemasterpl() {
	var dsrval			=	$("#dsrname").val();
	var route_mon		=	$("#route_mon").val();
	var route_tue		=	$("#route_tue").val();
	var route_wed		=	$("#route_wed").val();
	var route_thu		=	$("#route_thu").val();
	var route_fri		=	$("#route_fri").val();
	var route_sat		=	'';
		
	if(dsrval == '') {
		$(".myalignrouteplan").html("ERR : Select SR");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if(route_mon == '') {
		$(".myalignrouteplan").html("ERR : Select Route for Monday");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if(route_tue == '') {
		$(".myalignrouteplan").html("ERR : Select Route for Tuesday");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if(route_wed == '') {
		$(".myalignrouteplan").html("ERR : Select Route for Wednesday");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if(route_thu == '') {
		$(".myalignrouteplan").html("ERR : Select Route for Thursday");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if(route_fri == '') {
		$(".myalignrouteplan").html("ERR : Select Route for Friday");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	if($("#includesatsun").is(":checked")) {
		route_sat		=	$("#route_sat").val();
		if(route_sat == '') {
			$(".myalignrouteplan").html("ERR : Select Route for Saturday");
			$("#errormsgrouteplan").css("display","block");
			setTimeout(function () {
				$("#errormsgrouteplan").hide();
			},5000);
			return false;
		}
	}
	$.ajax({
		url : "savemasterrouteplan.php",
		type: "get",
		dataType:"text",
		data: { "DSR_Code" : dsrval, "route_mon" : route_mon, "route_tue" : route_tue, "route_wed" : route_wed, "route_thu" : route_thu, "route_fri" : route_fri, "route_sat" : route_sat },
		success: function(data) {
			var dataCat	=	$.trim(data);
			if(dataCat == 'insert') {
				alert("Route Plan Created Succesfully");
				window.location="routemasterplview.php"
			} else if(dataCat == 'update') {
				alert("Route Plan Updated Succesfully");
				window.location="routemasterplview.php"
			}
		}						
	});
}
function getAllRoutes(DSR_Code) {		
	if(DSR_Code == '') {
		$(".myalignrouteplan").html("ERR : Select SR");
		$("#errormsgrouteplan").css("display","block");
		setTimeout(function () {
			$("#errormsgrouteplan").hide();
		},5000);
		return false;
	}
	$.ajax({
		url : "getallroutes.php",
		type: "get",
		dataType:"text",
		data: { "DSR_Code" : DSR_Code },
		success: function(ajaxdata) {
			var trimval		=	$.trim(ajaxdata);
			var splitval	=	trimval.split("~");
			if(splitval[0] == 'NOROUTE') {
				$(".myalignrouteplan").html("ERR : No Routes for this DSR");
				$("#errormsgrouteplan").css("display","block");
				setTimeout(function () {
					$("#errormsgrouteplan").hide();
				},5000);
				$("#monrouteselect").html(splitval[1]);
				$("#tuerouteselect").html(splitval[2]);
				$("#wedrouteselect").html(splitval[3]);
				$("#thurouteselect").html(splitval[4]);
				$("#frirouteselect").html(splitval[5]);
				$("#satrouteselect").html(splitval[6]);
				
				for(var f=1; f<26; f++) {					
					$('#mon_'+f).html('');
					$('#tue_'+f).html('');
					$('#wed_'+f).html('');
					$('#thu_'+f).html('');
					$('#fri_'+f).html('');
					$('#sat_'+f).html('');
				} //for loop

				return false;
			} else {				
				$("#monrouteselect").html(splitval[0]);
				$("#tuerouteselect").html(splitval[1]);
				$("#wedrouteselect").html(splitval[2]);
				$("#thurouteselect").html(splitval[3]);
				$("#frirouteselect").html(splitval[4]);
				$("#satrouteselect").html(splitval[5]);
				if(parseInt(splitval[6]) == 1) {
					$("#includesatsun").attr("checked",true);
				} else {
					$("#includesatsun").attr("checked",false);
				}
				if(parseInt(splitval[7]) == 1) {
					$("#repeatroute").attr("checked",true);
				} else {
					$("#repeatroute").attr("checked",false);
				} 
				for(var q=8; q<=13; q++) {
					if(splitval[q] != '') {
						showcustvalues(DSR_Code,splitval[q],q);
					} else {
						for(var w=1; w<26;w++) {
							if(q == 8) {
								//alert(splitvalroute[f-1]);
								$('#mon_'+w).html('');
							} else if(q == 9) {
								$('#tue_'+w).html('');
							} else if(q == 10) {
								$('#wed_'+w).html('');
							} else if(q == 11) {
								$('#thu_'+w).html('');
							} else if(q == 12) {
								$('#fri_'+w).html('');
							} else if(q == 13) {
								$('#sat_'+w).html('');
							}
						}
					}
				}
			}
		}						
	});
}

function showcustvalues(DSR_Code,routeval,q) {
	$.ajax({
		type		:	"get",
		url			:	"routeplancurajax.php",
		data		:	{ "DSR_Code" : DSR_Code, "routeval" : routeval  },
		dataType	:	"text",
		success		:	function(ajaxvalroute) {
			var trimvalroute		=	$.trim(ajaxvalroute);
			var splitvalroute		=	trimvalroute.split("&");
			var splitlenroute		=	splitvalroute.length;
			//alert(splitlenroute);
			for(var f=1; f<26; f++) {
				if(f<=splitlenroute){
					if(q == 8) {
						//alert(splitvalroute[f-1]);
						$('#mon_'+f).html(splitvalroute[f-1]);
					} else if(q == 9) {
						$('#tue_'+f).html(splitvalroute[f-1]);
					} else if(q == 10) {
						$('#wed_'+f).html(splitvalroute[f-1]);
					} else if(q == 11) {
						$('#thu_'+f).html(splitvalroute[f-1]);
					} else if(q == 12) {
						$('#fri_'+f).html(splitvalroute[f-1]);
					} else if(q == 13) {
						$('#sat_'+f).html(splitvalroute[f-1]);
					} 						
				} else {
					if(q == 8) {
						$('#mon_'+f).html('');
					} else if(q == 9) {
						$('#tue_'+f).html('');
					} else if(q == 10) {
						$('#wed_'+f).html('');
					} else if(q == 11) {
						$('#thu_'+f).html('');
					} else if(q == 12) {
						$('#fri_'+f).html('');
					} else if(q == 13) {
						$('#sat_'+f).html('');
					}
				}
			}
		}	
	});
	return true;
}

function getOldOrMasterRoutes(copyval) {
	var DSR_Code	=	$("#dsrname").val();

	if(DSR_Code == '') {
		$(".myalignmonplan").html("ERR : Select SR");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(copyval == '') {
		$(".myalignmonplan").html("ERR : Select Copy Options");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
}

function copyfromold() {
	var DSR_Code	=	$("#dsrname").val();
	var copyval		=	$("#monthplan").val();

	if(DSR_Code == '') {
		$(".myalignmonplan").html("ERR : Select SR");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(copyval == '') {
		$(".myalignmonplan").html("ERR : Select Copy Options");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	$.ajax({
		type			:	"get",
		url				:	"copymonthrouteplan.php",
		dataType		:	"text",
		data			:	{ "DSR_Code" : DSR_Code, "copyval" : copyval  } ,
		success			:	function(ajaxval) {
			var trimval		=	$.trim(ajaxval);
			//alert(trimval);
			if(trimval	!= '') {
				window.location="routemonthplview.php";
			}
		}
	});
}
function getDSRRoutes(DSR_Code) {
	if(DSR_Code == '') {
		$(".myalignmonplan").html("ERR : Select SR");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	$.ajax({
		url : "getDSRroutes.php",
		type: "get",
		dataType:"text",
		data: { "DSR_Code" : DSR_Code },
		success: function(ajaxdata) {
			var trimval		=	$.trim(ajaxdata);
			var splitval	=	trimval.split("~");
			if(splitval[0] == 'NOROUTE') {
				
				//alert(2323);
				$("#copyfromspan").css("display","block");
				$("#copyfromselectspan").css("display","block");
				
				$(".myalignmonplan").html("ERR : No Routes for this DSR");
				$("#errormsgmonplan").css("display","block");

				$("#includesatsun").attr("checked",false);
				$("#repeatroute").attr("checked",false);

				setTimeout(function () {
					$("#errormsgmonplan").hide();
				},5000);
				$("#monrouteselect").html(splitval[1]);
				$("#tuerouteselect").html(splitval[2]);
				$("#wedrouteselect").html(splitval[3]);
				$("#thurouteselect").html(splitval[4]);
				$("#frirouteselect").html(splitval[5]);
				$("#satrouteselect").html(splitval[6]);
				
				for(var f=1; f<26; f++) {					
					$('#mon_'+f).html('');
					$('#tue_'+f).html('');
					$('#wed_'+f).html('');
					$('#thu_'+f).html('');
					$('#fri_'+f).html('');
					$('#sat_'+f).html('');
				} //for loop

				return false;
			} else {
				$("#errormsgmonplan").hide();
				if(parseInt(splitval[14]) == 1) {
					$("#copyfromspan").css("display","none");
					$("#copyfromselectspan").css("display","none");
				}
				$("#monrouteselect").html(splitval[0]);
				$("#tuerouteselect").html(splitval[1]);
				$("#wedrouteselect").html(splitval[2]);
				$("#thurouteselect").html(splitval[3]);
				$("#frirouteselect").html(splitval[4]);
				$("#satrouteselect").html(splitval[5]);
				//alert(splitval[6]);
				//alert(splitval[7]);
				if(parseInt(splitval[6]) == 1) {
					$("#includesatsun").attr("checked",true);
				} else {
					$("#includesatsun").attr("checked",false);
				}
				if(parseInt(splitval[7]) == 1) {
					$("#repeatroute").attr("checked",true);
				} else {
					$("#repeatroute").attr("checked",false);
				}
				for(var q=8; q<=13; q++) {
					if(splitval[q] != '') {
						showcustomervalues(DSR_Code,splitval[q],q);
					} else {
						for(var w=1; w<26;w++) {
							if(q == 8) {
								//alert(splitvalroute[f-1]);
								$('#mon_'+w).html('');
							} else if(q == 9) {
								$('#tue_'+w).html('');
							} else if(q == 10) {
								$('#wed_'+w).html('');
							} else if(q == 11) {
								$('#thu_'+w).html('');
							} else if(q == 12) {
								$('#fri_'+w).html('');
							} else if(q == 13) {
								$('#sat_'+w).html('');
							}
						}
					}
				}
			}
		}						
	});
}

function showcustomervalues(DSR_Code,routeval,q) {
	$.ajax({
		type		:	"get",
		url			:	"routeplmonthajax.php",
		data		:	{ "DSR_Code" : DSR_Code, "routeval" : routeval  },
		dataType	:	"text",
		success		:	function(ajaxvalroute) {
			var trimvalroute		=	$.trim(ajaxvalroute);
			var splitvalroute		=	trimvalroute.split("~");
			var splitlenroute		=	splitvalroute.length;
			//alert(splitlenroute);
			for(var f=1; f<26; f++) {
				if(f<=splitlenroute){
					if(q == 8) {
						//alert(splitvalroute[f-1]);
						$('#mon_'+f).html(splitvalroute[f-1]);
					} else if(q == 9) {
						$('#tue_'+f).html(splitvalroute[f-1]);
					} else if(q == 10) {
						$('#wed_'+f).html(splitvalroute[f-1]);
					} else if(q == 11) {
						$('#thu_'+f).html(splitvalroute[f-1]);
					} else if(q == 12) {
						$('#fri_'+f).html(splitvalroute[f-1]);
					} else if(q == 13) {
						$('#sat_'+f).html(splitvalroute[f-1]);
					} 						
				} else {
					if(q == 8) {
						$('#mon_'+f).html('');
					} else if(q == 9) {
						$('#tue_'+f).html('');
					} else if(q == 10) {
						$('#wed_'+f).html('');
					} else if(q == 11) {
						$('#thu_'+f).html('');
					} else if(q == 12) {
						$('#fri_'+f).html('');
					} else if(q == 13) {
						$('#sat_'+f).html('');
					}
				}
			}
		}	
	});
	return true;
}


function bringcust(routeval,dayval,elename) {
	var dsrval			=	$("#dsrname").val();
	var	route_sat		=	'';
	if(dsrval == '') {
		$(".myalignmonplan").html("ERR : Select SR");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		$("#"+elename).val('');
		return false;
	}
	if(routeval == '') {
		$(".myalignmonplan").html("ERR : Select Route");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		for(var r=1; r<26; r++) {
			$('#'+dayval+'_'+r).html('');
		}
		return false;
	} else {
		if(!$("#repeatroute").is(":checked")) {
			var route_mon	=	$("#route_mon").val();
			var route_tue	=	$("#route_tue").val();
			var route_wed	=	$("#route_wed").val();
			var route_thu	=	$("#route_thu").val();
			var route_fri	=	$("#route_fri").val();
			if($("#includesatsun").is(":checked")) {
				route_sat	=	$("#route_sat").val();
			}

			if(dayval == 'mon') {
				if(route_mon == route_tue || route_mon == route_wed || route_mon == route_thu || route_mon == route_fri || route_mon == route_sat) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#mon_'+t).html('');
					}
					return false;
				} 
			} 
			if(dayval == 'tue') {
				if(route_tue == route_mon || route_tue == route_wed || route_tue == route_thu || route_tue == route_fri || route_tue == route_sat) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#tue_'+t).html('');
					}
					return false;
				}
			} 
			if(dayval == 'wed') {
				if(route_wed == route_mon || route_wed == route_tue || route_wed == route_thu || route_wed == route_fri || route_wed == route_sat) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#wed_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'thu') {
				if(route_thu == route_mon || route_thu == route_tue || route_thu == route_wed || route_thu == route_fri || route_thu == route_sat) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#thu_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'fri') {
				if(route_fri == route_mon || route_fri == route_tue || route_fri == route_wed || route_fri == route_thu || route_fri == route_sat) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#fri_'+t).html('');
					}
					return false;
				}
			}
			if(dayval == 'sat') {
				if(route_sat == route_mon || route_sat == route_tue || route_sat == route_wed || route_sat == route_thu || route_sat == route_fri) {
					$(".myalignmonplan").html("ERR : Repeat Route only after using above checkbox");
					$("#errormsgmonplan").css("display","block");
					setTimeout(function () {
						$("#errormsgmonplan").hide();
					},5000);
					$("#"+elename).val('');
					for(var t=1; t<26; t++) {
						$('#sat_'+t).html('');
					}
					return false;
				} 
			}
		}
		$.ajax({
			type		:	"get",
			url			:	"routeplmonthajax.php",
			data		:	{ "DSR_Code" : dsrval, "routeval" : routeval  },
			dataType	:	"text",
			success		:	function(ajaxval) {
				var trimval		=	$.trim(ajaxval);
				var splitval	=	trimval.split("~");
				var splitlen		=	splitval.length;
				for(var t=1; t<26; t++) {
					if(t<=splitlen){
						if(dayval == 'mon') {
							$('#mon_'+t).html(splitval[t-1]);
						} else if(dayval == 'tue') {
							$('#tue_'+t).html(splitval[t-1]);
						} else if(dayval == 'wed') {
							$('#wed_'+t).html(splitval[t-1]);
						} else if(dayval == 'thu') {
							$('#thu_'+t).html(splitval[t-1]);
						} else if(dayval == 'fri') {
							$('#fri_'+t).html(splitval[t-1]);
						} else if(dayval == 'sat') {
							$('#sat_'+t).html(splitval[t-1]);
						} 						
					} else {
						if(dayval == 'mon') {
							$('#mon_'+t).html('');
						} else if(dayval == 'tue') {
							$('#tue_'+t).html('');
						} else if(dayval == 'wed') {
							$('#wed_'+t).html('');
						} else if(dayval == 'thu') {
							$('#thu_'+t).html('');
						} else if(dayval == 'fri') {
							$('#fri_'+t).html('');
						} else if(dayval == 'sat') {
							$('#sat_'+t).html('');
						}
					}
				}
			}		
		});
	}
}

function routemonthpl() {
	var dsrval			=	$("#dsrname").val();
	var route_mon		=	$("#route_mon").val();
	var route_tue		=	$("#route_tue").val();
	var route_wed		=	$("#route_wed").val();
	var route_thu		=	$("#route_thu").val();
	var route_fri		=	$("#route_fri").val();
	var route_sat		=	'';
		
	if(dsrval == '') {
		$(".myalignmonplan").html("ERR : Select SR");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(route_mon == '') {
		$(".myalignmonplan").html("ERR : Select Route for Monday");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(route_tue == '') {
		$(".myalignmonplan").html("ERR : Select Route for Tuesday");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(route_wed == '') {
		$(".myalignmonplan").html("ERR : Select Route for Wednesday");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(route_thu == '') {
		$(".myalignmonplan").html("ERR : Select Route for Thursday");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if(route_fri == '') {
		$(".myalignmonplan").html("ERR : Select Route for Friday");
		$("#errormsgmonplan").css("display","block");
		setTimeout(function () {
			$("#errormsgmonplan").hide();
		},5000);
		return false;
	}
	if($("#includesatsun").is(":checked")) {
		route_sat		=	$("#route_sat").val();
		if(route_sat == '') {
			$(".myalignmonplan").html("ERR : Select Route for Saturday");
			$("#errormsgmonplan").css("display","block");
			setTimeout(function () {
				$("#errormsgmonplan").hide();
			},5000);
			return false;
		}
	}
	$.ajax({
		url : "savemonthrouteplan.php",
		type: "get",
		dataType:"text",
		data: { "DSR_Code" : dsrval, "route_mon" : route_mon, "route_tue" : route_tue, "route_wed" : route_wed, "route_thu" : route_thu, "route_fri" : route_fri, "route_sat" : route_sat },
		success: function(data) {
			var dataCat	=	$.trim(data);
			if(dataCat == 'insert') {
				alert("Monthly Route Plan Created Succesfully");
				window.location="routemonthplview.php"
			} else if(dataCat == 'update') {
				alert("Monthly Route Plan Updated Succesfully");
				window.location="routemonthplview.php"
			}
		}						
	});
}

function openviewajax(page,params){   // For pagination and sorting of the opening stock view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "openingstockviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#oviewajax").html(trimval);	
		}
	});
}

function searchopenviewajax(page){
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "openingstockviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#oviewajax").html(trimval);	
		}
	});
}

function receiptsviewajax(page,params){   // For pagination and sorting of the stock receipts view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "StockReceiptsviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srviewajax").html(trimval);	
		}
	});
}

function searchreceiptsviewajax(page){  // For pagination and sorting of the stock receipts search in view page
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "StockReceiptsviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srviewajax").html(trimval);	
		}
	});
}

function adjviewajax(page,params){   // For pagination and sorting of the stock adjustment view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "StockAdjustmentviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#adjviewajax").html(trimval);	
		}
	});
}

function searchadjviewajax(page){  // For pagination and sorting of the stock adjustment search in view page
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "StockAdjustmentviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#adjviewajax").html(trimval);	
		}
	});
}

function cyasviewajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var DSR_name		=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "cycleassignviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cycleassignid").html(trimval);	
		}
	});
}

function searchcyasviewajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var DSR_name	=	$("input[name='DSR_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "cycleassignviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#cycleassignid").html(trimval);	
		}
	});
}

function dailyviewajax(page,params){   // For pagination and sorting of the daily stock loading view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "DailyStockLoadingviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#dailyviewajaxid").html(trimval);
		}
	});
}

function searchdailyviewajax(page){  // For pagination and sorting of the daily stock loading search in view page
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "DailyStockLoadingviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#dailyviewajaxid").html(trimval);	
		}
	});
}

function colviewajax(page,params){   // For pagination and sorting of the Collection Deposited view page
	var splitparam		=	params.split("&");
	var Challan_Number	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "CollectionDepositedviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Challan_Number" : Challan_Number, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#colviewajaxid").html(trimval);
		}
	});
}

function searchcolviewajax(page){  // For pagination and sorting of the Collection Deposited search in view page
	var Challan_Number	=	$("input[name='Challan_Number']").val();
	//alert(Product_name);
	$.ajax({
		url : "CollectionDepositedviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Challan_Number" : Challan_Number, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#colviewajaxid").html(trimval);
		}
	});
}

function srbasedkd(codeval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsrbasedkd.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(kdrsm);
		}
	});
	return codevalue;
}

function getLoadedQuantity(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	//alert(loadedqty + rowvalue + Dateval + DSR_Code + Prod_Code);
	var allstocks		=	getStockValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code);
	//alert(allstocks);
	var popupId			=	Prod_Code+DSR_Code;
	openpopup(allstocks,popupId,0,0);
}

function getSoldQuantity(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	//alert(loadedqty + rowvalue + Dateval + DSR_Code + Prod_Code);
	var allstocks		=	getsalesValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code);
	//alert(allstocks);
	var popupId			=	Prod_Code+DSR_Code;
	openpopup(allstocks,popupId,0,0);
}

function getReturnedQuantity(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	//alert(loadedqty + rowvalue + Dateval + DSR_Code + Prod_Code);
	var allstocks		=	getreturnValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code);
	//alert(allstocks);
	var popupId			=	Prod_Code+DSR_Code;
	openpopup(allstocks,popupId,0,0);
}

function getreturnValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	$.ajax({
		url : "getreturnloadingvalues.php",
		type: "get",
		dataType: "text",
		async	: false,
		data : { "DSR_Code" : DSR_Code, "DateVal" : Dateval, "Prod_Code" : Prod_Code },
		success : function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	//alert(codevalue);
	return codevalue;
}

function getspecificreturnajax(page,params){   // For pagination and sorting of the vehicle in depth stock loading view page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	var Prod_Code		=	splitparam[2];
	var sortorder		=	splitparam[3];
	var ordercol		=	splitparam[4];
	//alert(DateVal);
	//alert(DSR_Code);
	//alert(Prod_Code);
	$.ajax({
		url : "getreturnloadingvalues.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "Prod_Code" : Prod_Code, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			codevalues		=	$.trim(dataval);
			//alert(codevalues);
			var popupId			=	Prod_Code+DSR_Code;
			$('#showPopupMsg'+popupId).html(codevalues);
		}
	});
}



function getsalesValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	$.ajax({
		url : "getsalesvaluesajax.php",
		type: "get",
		dataType: "text",
		async	: false,
		data : { "DSR_Code" : DSR_Code, "DateVal" : Dateval, "Prod_Code" : Prod_Code },
		success : function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	//alert(codevalue);
	return codevalue;
}

function getspecificsalesajax(page,params){   // For pagination and sorting of the vehicle in depth stock loading view page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	var Prod_Code		=	splitparam[2];
	var sortorder		=	splitparam[3];
	var ordercol		=	splitparam[4];
	//alert(DateVal);
	//alert(DSR_Code);
	//alert(Prod_Code);
	$.ajax({
		url : "getsalesvaluesajax.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "Prod_Code" : Prod_Code, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			codevalues		=	$.trim(dataval);
			//alert(codevalues);
			var popupId			=	Prod_Code+DSR_Code;
			$('#showPopupMsg'+popupId).html(codevalues);
		}
	});
}

function getStockValues(loadedqty,rowvalue,Dateval,DSR_Code,Prod_Code){ 
	$.ajax({
		url : "getstockloadingvalues.php",
		type: "get",
		dataType: "text",
		async	: false,
		data : { "DSR_Code" : DSR_Code, "DateVal" : Dateval, "Prod_Code" : Prod_Code },
		success : function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	//alert(codevalue);
	return codevalue;
}

function getspecificstockajax(page,params){   // For pagination and sorting of the vehicle in depth stock loading view page
	var splitparam		=	params.split("&");
	var DateVal			=	splitparam[0];
	var DSR_Code		=	splitparam[1];
	var Prod_Code		=	splitparam[2];
	var sortorder		=	splitparam[3];
	var ordercol		=	splitparam[4];
	//alert(DateVal);
	//alert(DSR_Code);
	//alert(Prod_Code);
	$.ajax({
		url : "getstockloadingvalues.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code, "Prod_Code" : Prod_Code, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			codevalues		=	$.trim(dataval);
			//alert(codevalues);
			var popupId			=	Prod_Code+DSR_Code;
			$('#showPopupMsg'+popupId).html(codevalues);
		}
	});
}


function openpopup(insertmsg,popupId,redirurl,formsubmit) {
	var showMsgId		=	'showPopupMsg'+popupId;
	$(" <div />" ).attr("id","showPopup"+popupId).addClass("popupOpenVehicle").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closePopupBox(this,\''+popupId+'\',\''+redirurl+'\',\''+formsubmit+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;" id='+showMsgId+' class="addcolor"></p>').appendTo($( "body" ));	
	$("#showPopup"+popupId).css("display","block");
	$('#showPopupMsg'+popupId).html(insertmsg);
	$('#backgroundPopup').css({"opacity":"0.7"});
	$("#backgroundPopup").fadeIn("slow");
	return false;
}

function closePopupBox(thisid,popId,reurl,formsubmit){	
	$('#showPopup'+popId).remove();
	$('#backgroundPopup').fadeOut('slow');

	if(reurl !='' && reurl != 0){
		window.location = reurl;
	} 
	if(formsubmit !='' && formsubmit != 0){
		document.forms[formsubmit].submit();
	}
}

function checkdailystockedit() {
	var DSRName					=	$('select[name="DSRName"]').val();
	var Dateval					=	$('input[name="Date"]').val();
	var vehicle_name			=	$('select[name="vehicle_name"]').val();
	var prodcnt					=	$('input[name="prodcnt"]').val();

	var currentdate				=	new Date();

	var dte2					=	parseInt(Dateval.substring(8,10),10);
	var mont2					=	(parseInt(Dateval.substring(5,7), 10)) -1;
	var year2					=	parseInt(Dateval.substring(0,4),10);
	var date2					=	new Date(year2,mont2,dte2);
	var y						=	0;
	//alert(prodcnt);
	/*alert(dte2);
	alert(mont2);
	alert(year2);*/

	/*var dt2 = parseInt(DateVal.substring(8, 10), 10);
	var mon2 = (parseInt(DateVal.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(DateVal.substring(0, 4), 10);
	var date2 = new Date(yr2, mon2, dt2);*/


	if(Dateval == ''){
		$('.myalign').html('ERR : Select Date');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	} else if (date2 > currentdate){
		$('.myalign').html('ERR : Date greater than today!');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	}else if(DSRName == ''){
		$('.myalign').html('ERR : Select SR Name');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	} else if(vehicle_name == ''){
		$('.myalign').html('ERR : Select Vehicle Name');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	} else if(prodcnt == ''){
		$('.myalign').html('ERR : Add Product');
		$('#errormsgcus').css('display','block');
		setTimeout(function() {
			$('#errormsgcus').hide();
		},5000);
		return false;
	} if(prodcnt > 0) {
		//alert(prodcnt);
		for(var f=1; f <= prodcnt; f++) {
			if($("#cbox_"+f).is(":checked")){
				y++;
			}
		}
		if(y == 0) {
			/*$(".myalign").html("Select Products");
			$("#errormsgcus").css('display','block');
			setTimeout(function() {
				$("#errormsgcus").hide();
			},5000);
			return false;*/
		}

		var w=0;
		var qtypat	= /^[0-9]+$/;
		for(var k=1; k <= prodcnt; k++) {
			if($('#cbox_'+k).is(":checked")) {
				var actual_qty			=	parseInt($('#actual_qty_'+k).val());
				var Loaded_Qty			=	parseInt($('#Loaded_Qty_'+k).val());
				var product_code_val	=	$('#product_code_'+k).val();
				if(Loaded_Qty ==''){
					$('.myalign').html('ERR : Enter Quantity for '+product_code_val);
					$('#errormsgcus').css('display','block');
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}

				//alert($("#qty_"+k).val());
				if(isNaN(Loaded_Qty)){
					$('.myalign').html('ERR : Only Numerals for '+product_code_val);
					$('#errormsgcus').css('display','block');
					//alert(Loaded_Qty);
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(!qtypat.test(Loaded_Qty)){
					$('.myalign').html('ERR : Only Numerals for '+product_code_val);
					$('#errormsgcus').css('display','block');
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(Loaded_Qty == 0){
					$('.myalign').html('ERR : No Zero for '+product_code_val);
					$('#errormsgcus').css('display','block');
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
				if(Loaded_Qty > actual_qty) {
					$('.myalign').html('ERR : Available quantity is '+actual_qty+' for '+product_code_val);
					$('#errormsgcus').css('display','block');
					setTimeout(function() {
						$('#errormsgcus').hide();
					},5000);
					$('#Loaded_Qty_'+k).focus();
					return false;
				}
			}
		}
	}
	$("#dailystockvalidation").submit();
}


//getting asm and rsm
function gettgtasmrsm(srid,allid,rowid) {
	var overallcount		=	$("#overall_rowcnt").val();
	var prodcode			=	$("#productname_"+rowid).val();
	
	//alert(srid);
	//alert(prodcode);

	if(srid == '') {
		$('.myaligntgt').html("ERR : Select SR");
		$('#errormsgtgt').css('display','block');
		$("#asm_"+rowid).html("");
		$("#rsm_"+rowid).html("");
		$("#asmval_"+rowid).val("");
		$("#rsmval_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	} /*else if (prodcode == '') {
		$('.myaligntgt').html("ERR : Select Product");
		$('#errormsgtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	}*/
	
	var t 			=	0;
	for(var k = 1; k <= allid; k++) {
		
		var dsrcode			=	$("#dsrname_"+k).val();
		var prodcodechk		=	$("#productname_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);

		if(dsrcode == srid && dsrcode != '' && prodcodechk == prodcode && prodcodechk != '') {
			t++;
		}
		dsrcode			=	'';
		prodcodechk		=	'';
	}

	if(t > 1) {
		$('.myaligntgt').html("ERR : This Combination already Selected");
		$('#errormsgtgt').css('display','block');
		$("#asm_"+rowid).html("");
		$("#rsm_"+rowid).html("");
		$("#asmval_"+rowid).val("");
		$("#rsmval_"+rowid).val("");
		$("#dsrname_"+rowid).val("");
		$("#tgt_cov_percent_"+rowid).val("");
		$("#tgt_eff_percent_"+rowid).val("");

		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	}

	//alert(overallcount);
	//alert(srid);
	//return false;
	var asmrsm			=	getasmrsm(srid);
	//alert(asmrsm);
	var splitval		=	asmrsm.split("~");
	$("#asm_"+rowid).html(splitval[1]);
	$("#rsm_"+rowid).html(splitval[3]);
	$("#asmval_"+rowid).val(splitval[0]);
	$("#rsmval_"+rowid).val(splitval[2]);
}


function gettgtcovasmrsm(srid,allid,rowid) {
	var overallcount		=	$("#overall_rowcnt").val();
	
	//alert(srid);
	//alert(prodcode);

	if(srid == '') {
		$('.myaligncovtgt').html("ERR : Select SR");
		$('#errormsgcovtgt').css('display','block');
		$("#asm_"+rowid).html("");
		$("#rsm_"+rowid).html("");
		$("#asmval_"+rowid).val("");
		$("#rsmval_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgcovtgt').hide();
		},5000);
		return false;
	}
	
	var t 			=	0;
	for(var k = 1; k <= allid; k++) {
		
		var dsrcode			=	$("#dsrname_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);

		if(dsrcode == srid && dsrcode != '') {
			t++;
		}
		dsrcode			=	'';
	}

	if(t > 1) {
		$('.myaligncovtgt').html("ERR : This SR already Selected");
		$('#errormsgcovtgt').css('display','block');
		$("#asm_"+rowid).html("");
		$("#rsm_"+rowid).html("");
		$("#asmval_"+rowid).val("");
		$("#rsmval_"+rowid).val("");
		$("#dsrname_"+rowid).val("");
		$("#tgt_cov_percent_"+rowid).val("");
		$("#tgt_eff_percent_"+rowid).val("");

		setTimeout(function() {
			$('#errormsgcovtgt').hide();
		},5000);
		return false;
	}

	//alert(overallcount);
	//alert(srid);
	//return false;
	var asmrsm			=	getasmrsm(srid);
	//alert(asmrsm);
	var splitval		=	asmrsm.split("~");
	$("#asm_"+rowid).html(splitval[1]);
	$("#rsm_"+rowid).html(splitval[3]);
	$("#asmval_"+rowid).val(splitval[0]);
	$("#rsmval_"+rowid).val(splitval[2]);
}


//getting brands
function gettgtbrand(prodcode,allid,rowid) {
	var overallcount		=	$("#overall_rowcnt").val();
	var srid				=	$("#dsrname_"+rowid).val();
	
	//alert(srid); alert(prodcode);
	//alert(srid);
	//alert(prodcode);

	if(srid == '') {
		$('.myaligntgt').html("ERR : Select SR");
		$("#productname_"+rowid).val("");
		$('#errormsgtgt').css('display','block');
		$("#asm_"+rowid).html("");
		$("#rsm_"+rowid).html("");
		$("#asmval_"+rowid).val("");
		$("#rsmval_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	} else if (prodcode == '') {
		$('.myaligntgt').html("ERR : Select Product");
		$('#errormsgtgt').css('display','block');
		$("#brand_"+rowid).html("");
		$("#brandval_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	}
	
	var t 			=	0;
	//alert(rowid);
	for(var k = 1; k <= allid; k++) {
		
		var dsrcode			=	$("#dsrname_"+k).val();
		var prodcodechk		=	$("#productname_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);

		if(dsrcode == srid && dsrcode != '' && prodcodechk == prodcode && prodcodechk != '') {
			t++;
			//alert(t);
			//alert(k);
			//alert(t);
		}
		dsrcode			=	'';
		prodcodechk		=	'';
	}
	
	//alert(k);
	//alert(t);
	if(t > 1) {
		$('.myaligntgt').html("ERR : This Combination already Selected");
		$('#errormsgtgt').css('display','block');
		$("#brand_"+rowid).html("");
		$("#brandval_"+rowid).val("");
		$("#tgt_units_"+rowid).val("");
		$("#tgt_naira_"+rowid).val("");
		$("#productname_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgtgt').hide();
		},5000);
		return false;
	}

	//alert(overallcount);
	//alert(srid);
	//return false;
	var brandval			=	getbrand(prodcode);
	//alert(brandval);
	var splitval		=	brandval.split("~");
	$("#brand_"+rowid).html(splitval[1]);
	$("#brandval_"+rowid).val(splitval[0]);
}



//saving target coverage and effective setting
function savetgtcov(rowid) {
	//alert(rowid);
	var t 			=	0;
	var m 			=	0;
	for(var k = 1; k <= rowid; k++) {
		
		var dsrcode					=	$("#dsrname_"+k).val();
		var tgt_cov_percent			=	$("#tgt_cov_percent_"+k).val();
		var tgt_eff_percent			=	$("#tgt_eff_percent_"+k).val();
		var tgt_protive_percent		=	$("#tgt_protive_percent_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);
		
		if(dsrcode == '') {
			m++;
		}

		if(dsrcode != '') {
			//alert(dsrcode);
			//alert(prodcodechk);

			if(tgt_cov_percent == '') {
				$('.myaligncovtgt').html("ERR : Enter Coverage Percentage");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_cov_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(isNaN(tgt_cov_percent)) {
				$('.myaligncovtgt').html("ERR : Only Numerals");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_cov_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(tgt_cov_percent > 100) {
				$('.myaligncovtgt').html("ERR : Not more than 100");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_cov_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} if(tgt_eff_percent == '') {
				$('.myaligncovtgt').html("ERR : Enter Effective Coverage Percentage");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_eff_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(isNaN(tgt_eff_percent)) {
				$('.myaligncovtgt').html("ERR : Only Numerals");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_eff_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(tgt_eff_percent > 100) {
				$('.myaligncovtgt').html("ERR : Not more than 100");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_eff_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} if(tgt_protive_percent == '') {
				$('.myaligncovtgt').html("ERR : Enter Productive Coverage Percentage");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_protive_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(isNaN(tgt_protive_percent)) {
				$('.myaligncovtgt').html("ERR : Only Numerals");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_protive_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} else if(tgt_protive_percent > 100) {
				$('.myaligncovtgt').html("ERR : Not more than 100");
				$('#errormsgcovtgt').css('display','block');
				$("#tgt_protive_percent_"+k).focus();
				setTimeout(function() {
					$('#errormsgcovtgt').hide();
				},5000);
				return false;
			} 
		}
		dsrcode					=	'';
		tgt_cov_percent			=	'';
		tgt_eff_percent			=	'';
		tgt_protive_percent		=	'';
	}

	//alert(m);
	//return false;
	if(m > 0 && m == rowid) {
		$('.myaligncovtgt').html("ERR : Select Atleast One SR");
		$('#errormsgcovtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgcovtgt').hide();
		},5000);
		return false;
	}
	//alert(overallcount);
	//alert(srid);
	//return false;
}


//saving target setting
function savetgt(rowid) {
	//alert(rowid);
	var t 			=	0;
	for(var k = 1; k <= rowid; k++) {
		
		var dsrcode			=	$("#dsrname_"+k).val();
		var prodcodechk		=	$("#productname_"+k).val();
		var tgt_uts			=	$("#tgt_units_"+k).val();
		var tgt_naira		=	$("#tgt_naira_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);
		
		if(dsrcode == '' && prodcodechk != '') {
			$('.myaligntgt').html("ERR : Select SR");
			$('#errormsgtgt').css('display','block');
			setTimeout(function() {
				$('#errormsgtgt').hide();
			},5000);
			return false;
		}

		if(dsrcode != '' && prodcodechk == '') {
			$('.myaligntgt').html("ERR : Select Product");
			$('#errormsgtgt').css('display','block');
			setTimeout(function() {
				$('#errormsgtgt').hide();
			},5000);
			return false;
		}

		if(dsrcode != '' &&	prodcodechk != '') {
			//alert(dsrcode);
			//alert(prodcodechk);

			if(tgt_uts == '') {
				$('.myaligntgt').html("ERR : Enter Units");
				$('#errormsgtgt').css('display','block');
				$("#tgt_units_"+k).focus();
				setTimeout(function() {
					$('#errormsgtgt').hide();
				},5000);
				return false;
			} else if(isNaN(tgt_uts)) {
				$('.myaligntgt').html("ERR : Only Numerals");
				$('#errormsgtgt').css('display','block');
				$("#tgt_units_"+k).focus();
				setTimeout(function() {
					$('#errormsgtgt').hide();
				},5000);
				return false;
			} if(tgt_naira == '') {
				$('.myaligntgt').html("ERR : Enter Naira");
				$('#errormsgtgt').css('display','block');
				$("#tgt_naira_"+k).focus();
				setTimeout(function() {
					$('#errormsgtgt').hide();
				},5000);
				return false;
			} else if(isNaN(tgt_naira)) {
				$('.myaligntgt').html("ERR : Only Numerals");
				$('#errormsgtgt').css('display','block');
				$("#tgt_naira_"+k).focus();
				setTimeout(function() {
					$('#errormsgtgt').hide();
				},5000);
				return false;
			} 
		}
		dsrcode			=	'';
		prodcodechk		=	'';
		tgt_uts			=	'';
		tgt_naira		=	'';
	}
	//alert(overallcount);
	//alert(srid);
	//return false;
}

var trimval			=	'';
var trimvalue		=	'';
function getasmrsm(DSR_Code) {
	trimval			=	'';
	$.ajax({
		url			:	"getasmrsm.php",
		type		:	"get",
		data		:	{ "DSR_Code" : DSR_Code },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			trimval			=	$.trim(ajaxval);
			//alert(trimval);			
		}
	});
	//alert(trimval);
	return trimval;
}

function getbrand(prodcode) {
	trimvalue		=	'';
	$.ajax({
		url			:	"getbrand.php",
		type		:	"get",
		data		:	{ "prodcode" : prodcode },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			trimvalue		=	$.trim(ajaxval);
			//alert(trimval);
		}
	});
	//alert(trimvalue);
	return trimvalue;
}

function bringreturntrans(return_id) {
	if(return_id == '') {
		$('.myaligncredit').html("ERR : Select Transaction No.");
			$('#errormsgcredit').css('display','block');
			setTimeout(function() {
				$('#errormsgcredit').hide();
			},5000);
			return false;
	}
	
	$.ajax({
		url			:	"creditnoteajax.php",
		type		:	"get",
		data		:	{ "return_id" : return_id },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			trimvalue		=	$.trim(ajaxval);
			splitvalue		=	trimvalue.split("~");
			$("#creditreturnid").html(splitvalue[0]);
			//alert(splitvalue[1]);
			$("#salesdatespan").html(splitvalue[1]);
			$("#salesdate").val(splitvalue[1]);
			$("#cusname").html(splitvalue[2]);
			$("#cusaddress").val(splitvalue[2]);
			$("#salestransno").val(return_id);
			//$("#pagspan").html(splitvalue[3]);
			//totvalspan
			//totval

			//creditreturnid tbody
			//cusaddress - hid
			//cusname - span
			//salesdatespan
			//salesdate - hid

			//alert(trimval);
		}
	});
}

function bringreturntransview(return_id) {
	if(return_id == '') {
		$('.myaligncredit').html("ERR : Select Transaction No.");
			$('#errormsgcredit').css('display','block');
			setTimeout(function() {
				$('#errormsgcredit').hide();
			},5000);
			return false;
	}
	
	$.ajax({
		url			:	"creditnoteviewajax.php",
		type		:	"get",
		data		:	{ "return_id" : return_id },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			trimvalue		=	$.trim(ajaxval);
			splitvalue		=	trimvalue.split("~");
			$("#creditreturnid").html(splitvalue[0]);
			//alert(splitvalue[1]);
			$("#salesdatespan").html(splitvalue[1]);
			$("#salesdate").val(splitvalue[1]);
			$("#cusname").html(splitvalue[2]);
			$("#cusaddress").val(splitvalue[2]);
			$("#salestransno").val(return_id).attr('selected',true);
			//$("#pagspan").html(splitvalue[3]);
			//totvalspan
			//totval

			//creditreturnid tbody
			//cusaddress - hid
			//cusname - span
			//salesdatespan
			//salesdate - hid

			//alert(trimval);
		}
	});
}

function bringreturntransprint(return_id) {
	if(return_id == '') {
		$('.myaligncredit').html("ERR : Select Transaction No.");
			$('#errormsgcredit').css('display','block');
			setTimeout(function() {
				$('#errormsgcredit').hide();
			},5000);
			return false;
	}
	
	$.ajax({
		url			:	"creditnoteprintajax.php",
		type		:	"get",
		data		:	{ "return_id" : return_id },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			trimvalue		=	$.trim(ajaxval);
			splitvalue		=	trimvalue.split("~");
			$("#creditreturnid").html(splitvalue[0]);
			//alert(splitvalue[1]);
			$("#salesdatespan").html(splitvalue[1]);
			$("#salesdate").val(splitvalue[1]);
			$("#cusname").html(splitvalue[2]);
			$("#cusaddress").val(splitvalue[2]);
			$("#salestransno").val(return_id).attr('selected',true);
			//$("#pagspan").html(splitvalue[3]);
			//totvalspan
			//totval

			//creditreturnid tbody
			//cusaddress - hid
			//cusname - span
			//salesdatespan
			//salesdate - hid

			//alert(trimval);
		}
	});
}

function changetotalval(rowcnt) {
	var valueval	=	0;
	var amtval	=	0;
	for(var k=1; k<=rowcnt; k++ ) {
	
		amtval		=	parseInt($("#priceval_"+k).val().replace(/,./g,''));

		//amt += parseInt(strvalue.replace(/,/g,''));

		//amtval	=	amtval.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");

		valuevalue	=	parseInt(amtval) * parseInt($("#returnqty_"+k).val());
		//alert(valuevalue);
		$("#valueval_"+k).val(valuevalue);
		$("#valuevalspan_"+k).html(valuevalue.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
		valueval	+=	valuevalue;
	}
	//alert(valueval);
	$("#totvalspan").html(valueval.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
	$("#totval").val(valueval);
	
}

function creditnoteajax(page,params){   // For pagination and sorting of the daily stock loading view page
	var splitparam		=	params.split("&");
	var return_id		=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "creditnoteajax.php",
		type: "get",
		dataType: "text",
		data : { "return_id" : return_id, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			splitvalue		=	trimval.split("~");
			//alert(trimval);
			$("#creditreturnid").html(splitvalue[0]);
			//$("#pagspan").html(splitvalue[3]);
		}
	});
}

function creditnotesave() {
	var salestransno		=	$("#salestransno").val();
	if(salestransno == '') {
		$('.myaligncredit').html("ERR : Select Transaction No.");
			$('#errormsgcredit').css('display','block');
			setTimeout(function() {
				$('#errormsgcredit').hide();
			},5000);
			return false;
	}
}

function getsrspecific() {
	var srcodes		=	$("#srcode").val();
	if(srcodes == '' || srcodes == null) {
		$('.myalignsrperf').html("ERR : Select SR");
		$('#errormsgsrperf').css('display','block');
		$("#srcode option:selected").attr("selected",false);
		var srasm			=	srbasedsm(srcodes,'asm_sp','SRIN');
		var srrsm			=	srbasedsm(srcodes,'rsm_sp','SRIN');
		var srbranch		=	srbasedsm(srcodes,'branch','SRIN');
		$("#asmspan").html(srasm);
		$("#rsmspan").html(srrsm);
		$("#branchspan").html(srbranch);
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);	
	var srasm				=	srbasedsm(srcodes,'asm_sp','SRIN');
	var srrsm				=	srbasedsm(srcodes,'rsm_sp','SRIN');
	var srbranch			=	srbasedsm(srcodes,'branch','SRIN');
	$("#asmspan").html(srasm);
	$("#rsmspan").html(srrsm);
	$("#branchspan").html(srbranch);
	//alert(kdprod);	
}

function getasmspecificwithsr() {
	var asmcodes		=	$("#asmcode").val();
	if(asmcodes == '' || asmcodes == null) {
		$('.myalignsrperf').html("ERR : Select ASM");
		$('#errormsgsrperf').css('display','block');
		$("#asmcode option:selected").attr("selected",false);
		var asmrsm			=	asmbasedrsm(asmcodes,'SRIN');
		var asmsr			=	smbasedsr(asmcodes,'asm_sp','SRIN');
		var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRIN');
		$("#rsmspan").html(asmrsm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(asmbranch);

		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);
	var asmrsm					=	asmbasedrsm(asmcodes,'SRIN');
	var asmsr					=	smbasedsr(asmcodes,'asm_sp','SRIN');
	var asmbranch				=	smbasedbranch(asmcodes,'asm_sp','SRIN');
	$("#rsmspan").html(asmrsm);
	$("#srspan").html(asmsr);
	$("#branchspan").html(asmbranch);
	//alert(kdprod);	
}

function getrsmspecificwithsr() {
	var rsmcodes		=	$("#rsmcode").val();
	if(rsmcodes == '' || rsmcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrperf').html("ERR : Select RSM");
		$('#errormsgsrperf').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var rsmasm			=	rsmbasedasm(rsmcodes,'SRIN');
		var asmsr			=	smbasedsr(rsmcodes,'rsm_sp','SRIN');
		var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRIN');
		$("#asmspan").html(rsmasm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(rsmbranch);

		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var rsmasm		=	rsmbasedasm(rsmcodes,'SRIN');
	var rsmsr		=	smbasedsr(rsmcodes,'rsm_sp','SRIN');
	var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRIN');
	$("#asmspan").html(rsmasm);
	$("#srspan").html(rsmsr);
	$("#branchspan").html(rsmbranch);
	//alert(kdprod);	
}

function srbasedsm(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsrbasedsm.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function smbasedsr(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsmbasedsr.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function asmbasedrsm(codeval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getasmbasedrsm.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(trimval);
		}
	});
	return codevalue;
}

function rsmbasedasm(codeval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getrsmbasedasm.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(trimval);
		}
	});
	return codevalue;	
}
function srperformreport() {
	var	reportby		=	$("#reportby").val();
	var	asmcode			=	$("#asmcode").val();
	var	rsmcode			=	$("#rsmcode").val();
	var	srcode			=	$("#srcode").val();
	var	custype			=	$("#custype").val();

	var fromdateval	=	$("#fromdates").val();
	var todateval	=	$("#todates").val();

	var dt1		=	parseInt(fromdateval.substring(8, 10), 10);
	var mon1	=	(parseInt(fromdateval.substring(5, 7), 10)) - 1;
	var yr1		=	parseInt(fromdateval.substring(0, 4), 10);
	var date1	=	new Date(yr1, mon1, dt1);

	var dt2 = parseInt(todateval.substring(8, 10), 10);
	var mon2 = (parseInt(todateval.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(todateval.substring(0, 4), 10);
	var date2		=	new Date(yr2, mon2, dt2);

	var currdate	=	new Date();
	if(reportby	==	'') {
		//alert(reportby);
		$('.myalignsrperf').html("ERR : Select Report By");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	if(fromdateval == '') {
		$('.myalignsrperf').html("ERR : Select From Date");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	} if(todateval == '') {
		$('.myalignsrperf').html("ERR : Select To Date");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	if(date1 > currdate) {
		$('.myalignsrperf').html("ERR : From Date is greater than today date");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	} if(date2 > currdate) {
		$('.myalignsrperf').html("ERR : To Date is greater than today date");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	} if(date1 > date2) {
		$('.myalignsrperf').html("ERR : From Date greater than To Date");
		$('#errormsgsrperf').css('display','block');
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}	
	var ajaxData		=	{ "reportby" : reportby, "fromdatevalue" : fromdateval, "todatevalue" : todateval, "srcode" : srcode, "asmcode" : asmcode, "rsmcode" : rsmcode, "custype" : custype };

	$.ajax({
		url			:	"getsrperformancereport.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			$("#ajaxresultpage").html(codevalue);
			//alert(codevalue);
		}
	});
}

function getasmrsmvalues(srcode) {
	if(srcode == '') {
		$('.myalignsrinc').html("ERR : Select SR");
		$('#errormsgsrinc').css('display','block');
		$("#asmid").html('');
		$("#rsmid").html('');

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	var ajaxData		=	{ "DSR_Code" : srcode };
	$.ajax({
		url			:	"getasmrsm.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			codeval			=	codevalue.split("~");
			var srasm		=	codeval[1];
			var srrsm		=	codeval[3];
			$("#asmid").html(srasm);
			$("#rsmid").html(srrsm);
			//alert(codevalue);
		}
	});
}

function getasmrsmvalueswithbranch(srcode) {
	if(srcode == '') {
		$('.myalignsrinc').html("ERR : Select SR");
		$('#errormsgsrinc').css('display','block');
		$("#asmid").html('');
		$("#rsmid").html('');

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	var ajaxData		=	{ "DSR_Code" : srcode };
	$.ajax({
		url			:	"getasmrsmbranch.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			codeval			=	codevalue.split("~");
			var srasm		=	codeval[1];
			var srrsm		=	codeval[3];
			var srbranch	=	codeval[4];
			$("#asmid").html(srasm);
			$("#rsmid").html(srrsm);
			$("#branchid").html(srbranch);
			//alert(codevalue);
		}
	});
}

function checksrincentry(rowcnt,curmonthval,curyearval) {
	var srcode					=	$("#srcode").val();
	var brandcode					=	$("#brandcode").val();
	var frommonth				=	$("#frommonth").val();
	var tomonth					=	$("#tomonth").val();
	var fromyear				=	$("#fromyear").val();
	var toyear					=	$("#toyear").val();
	//var targetflaglen			=	$("input[name='targetflag']").length;
	curmonthval					=	curmonthval;
	curyearval					=	curyearval;
	rowcnt						=	$("#overall_rowcnt").val();

	//alert(srcode);
	//alert(targetflaglen);
	//alert($("input[name='targetflag'] :checked").length);
	if(srcode == '' || srcode == null) {
		$('.myalignsrinc').html("ERR : Select SR");
		$('#errormsgsrinc').css('display','block');
		$("#asmid").html('');
		$("#rsmid").html('');

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(brandcode == '' || brandcode == null) {
		$('.myalignsrinc').html("ERR : Select Brand");
		$('#errormsgsrinc').css('display','block');
		$("#asmid").html('');
		$("#rsmid").html('');

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(rowcnt == 0) {
		//if(targetflag	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Add Products");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if($("input[name='targetflag']:checked").length <= 0) {
		//if(targetflag	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Select Target Type");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	if(frommonth	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Select From Month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(fromyear == '') {
		$('.myalignsrinc').html("ERR : Select From Year");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(parseInt(frommonth) < parseInt(curmonthval) && parseInt(curyearval) == parseInt(fromyear)) {
		$('.myalignsrinc').html("ERR : From Month is below the Current month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(tomonth	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Select To Month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(toyear == '') {
		$('.myalignsrinc').html("ERR : Select To Year");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	
	//alert(frommonth+"+++"+fromyear+"+++"+tomonth+"+++"+toyear);
	if(parseInt(frommonth) > parseInt(tomonth) && parseInt(fromyear) == parseInt(toyear)) {
		$('.myalignsrinc').html("ERR : This is not allowed");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(parseInt(fromyear) > parseInt(toyear)) {
		$('.myalignsrinc').html("ERR : From Year is Greater than To Year");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}

	var t=0;
	var r=0;
	var p=0;
	var q=0;
	var tgt_editval	=	$("#tgt_edit").val();
	var amtsrincpat	=	/^[0-9.,]+$/;	
	var qtysrincpat	=	/^[0-9,]+$/;	
	//alert(tgt_editval);
	for(var j=0; j<rowcnt; j++) {
		
			//alert($('#tgt_units_'+j).val());
			//alert('2332');
			//return false;
			if($('#tgt_units_'+j).val() == '') {
				$('.myalignsrinc').html("ERR : Enter Target Units");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_units_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}
			
			if(!qtysrincpat.test($('#tgt_units_'+j).val())) {
				//alert($('#tgt_units_'+j).val());
				$('.myalignsrinc').html("ERR : Only Numerals");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_units_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			} 
			
			if(parseInt($('#tgt_units_'+j).val()) == 0) {
				$('.myalignsrinc').html("ERR : No Zero");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_units_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}

			if($('#tgt_naira_'+j).val() == '') {
				$('.myalignsrinc').html("ERR : Enter Naira");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_naira_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}
			if(!amtsrincpat.test($('#tgt_naira_'+j).val())) {
				$('.myalignsrinc').html("ERR : Only Numerals");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_naira_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}

			if(parseInt($('#tgt_naira_'+j).val()) == 0) {
				$('.myalignsrinc').html("ERR : No Zero");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_naira_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}

		/*if($('#tgt_units_'+j).val() == '') {
			t++;
		} else if($('#tgt_units_'+j).val() == 0) {
			p++;
		} else {

			if($("input[name='targetflag']:checked").val() == 0) {
				if(isNaN($('#tgt_units_'+j).val())) {
					$('.myalignsrinc').html("ERR : Only Numerals");
					$('#errormsgsrinc').css('display','block');
					$('#tgt_units_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrinc').hide();
					},5000);
					return false;
				} 
				
				if(tgt_editval != 'edit') {
					//alert("er");
					if(parseInt($('#tgt_units_'+j).val()) == 0) {
						$('.myalignsrinc').html("ERR : No Zero");
						$('#errormsgsrinc').css('display','block');
						$('#tgt_units_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrinc').hide();
						},5000);
						return false;
					}
				}
			}
		}
		if($('#tgt_naira_'+j).val() == '') {
			r++;
		} else if($('#tgt_naira_'+j).val() == 0) {
			q++;
		} else {
			if(isNaN($('#tgt_naira_'+j).val())) {
				$('.myalignsrinc').html("ERR : Only Numerals");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_naira_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}
		} 
		if(tgt_editval != 'edit') {
			if(parseInt($('#tgt_naira_'+j).val()) == 0) {
				$('.myalignsrinc').html("ERR : No Zero");
				$('#errormsgsrinc').css('display','block');
				$('#tgt_naira_'+j).focus();
				setTimeout(function() {
					$('#errormsgsrinc').hide();
				},5000);
				return false;
			}
		}*/
	}

	//return false;
	
	/*if(t == rowcnt) {
		if($("input[name='targetflag']:checked").val() == 0) {
			$('.myalignsrinc').html("ERR : Enter Target Units");
			$('#errormsgsrinc').css('display','block');
			setTimeout(function() {
				$('#errormsgsrinc').hide();
			},5000);
			return false;
		}
	}
	if(r == rowcnt) {
		$('.myalignsrinc').html("ERR : Enter Target Naira");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}

	if(p == rowcnt) {
		if($("input[name='targetflag']:checked").val() == 0) {
			$('.myalignsrinc').html("ERR : Enter Target Units With No Zero");
			$('#errormsgsrinc').css('display','block');
			setTimeout(function() {
				$('#errormsgsrinc').hide();
			},5000);
			return false;
		}
	}
	if(q == rowcnt) {
		$('.myalignsrinc').html("ERR : Enter Target Naira With No Zero");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}  */
}

function srincviewajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var DSR_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "srincentiveviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srincid").html(trimval);	
		}
	});
}

function searchsrincviewajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var DSR_name	=	$("input[name='DSR_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "srincentiveviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srincid").html(trimval);	
		}
	});
}

function getasmrsmsrsta() {
	var srcodes		=	$("#srcode").val();
	if(srcodes == '' || srcodes == null) {
		$('.myalignsrsta').html("ERR : Select SR");
		$('#errormsgsrsta').css('display','block');
		$("#srcode option:selected").attr("selected",false);
		var srasm			=	srbasedsm(srcodes,'asm_sp','SRINC');
		var srrsm			=	srbasedsm(srcodes,'rsm_sp','SRINC');
		var srbranch		=	srbasedsm(srcodes,'branch','SRINC');
		$("#asmspan").html(srasm);
		$("#rsmspan").html(srrsm);
		$("#branchspan").html(srbranch);
		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);
	var srasm			=	srbasedsm(srcodes,'asm_sp','SRINC');
	var srrsm			=	srbasedsm(srcodes,'rsm_sp','SRINC');
	var srbranch		=	srbasedsm(srcodes,'branch','SRINC');
	$("#asmspan").html(srasm);
	$("#rsmspan").html(srrsm);
	$("#branchspan").html(srbranch);
	//alert(kdprod);	
}

function getasmspecificsrsta() {
	var asmcodes		=	$("#asmcode").val();
	if(asmcodes == '' || asmcodes == null) {
		$('.myalignsrsta').html("ERR : Select ASM");
		$('#errormsgsrsta').css('display','block');
		$("#asmcode option:selected").attr("selected",false);
		var asmrsm				=	asmbasedrsm(asmcodes,'SRINC');
		var asmsr				=	smbasedsr(asmcodes,'asm_sp','SRINC');
		var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRINC');
		$("#rsmspan").html(asmrsm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(asmbranch);

		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);
	var asmrsm		=	asmbasedrsm(asmcodes,'SRINC');
	var asmsr		=	smbasedsr(asmcodes,'asm_sp','SRINC');
	var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRINC');
	$("#rsmspan").html(asmrsm);
	$("#srspan").html(asmsr);
	$("#branchspan").html(asmbranch);
	//alert(kdprod);	
}

function getrsmspecificsrsta() {
	var rsmcodes		=	$("#rsmcode").val();
	if(rsmcodes == '' || rsmcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrsta').html("ERR : Select RSM");
		$('#errormsgsrsta').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var rsmasm				=	rsmbasedasm(rsmcodes,'SRINC');
		var asmsr				=	smbasedsr(rsmcodes,'rsm_sp','SRINC');
		var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRINC');
		$("#asmspan").html(rsmasm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(rsmbranch);

		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var rsmasm				=	rsmbasedasm(rsmcodes,'SRINC');
	var rsmsr				=	smbasedsr(rsmcodes,'rsm_sp','SRINC');
	var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRINC');
	$("#asmspan").html(rsmasm);
	$("#srspan").html(rsmsr);
	$("#branchspan").html(rsmbranch);
	//alert(kdprod);	
}

function getbranchspecificsrsta() {
	var branchcodes		=	$("#branchcode").val();
	if(branchcodes == '' || branchcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrsta').html("ERR : Select Branch");
		$('#errormsgsrsta').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRINC');
		var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRINC');
		var branchsr			=	branchbasedsm(branchcodes,'dsr','SRINC');
		$("#rsmspan").html(branchrsm);
		$("#asmspan").html(branchasm);
		$("#srspan").html(branchsr);

		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRINC');
	var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRINC');
	var branchsr			=	branchbasedsm(branchcodes,'dsr','SRINC');
	$("#rsmspan").html(branchrsm);
	$("#asmspan").html(branchasm);
	$("#srspan").html(branchsr);
	//alert(kdprod);	
}

function srincstatus() {
	var	srcode				=	$("#srcode").val();
	var	propmonth			=	$("#propmonth").val();
	var	propyear			=	$("#propyear").val();

	if(propmonth	==	'') {
		//alert(reportby);
		$('.myalignsrsta').html("ERR : Select Month");
		$('#errormsgsrsta').css('display','block');
		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	if(propyear == '') {
		$('.myalignsrsta').html("ERR : Select Year");
		$('#errormsgsrsta').css('display','block');
		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	} 
	var ajaxData		=	{ "srcode" : srcode, "propmonths" : propmonth, "propyears" : propyear };

	$.ajax({
		url			:	"getsrincstatusreport.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
			splitval		=	codevalue.split("~");
			$("#ecosta").html(splitval[0]);
			$("#covsta").html(splitval[1]);
			$("#prosta").html(splitval[2]);
			$("#allecosta").html(splitval[3]);
			$("#alldetsta").html(splitval[4]);
			//alert(codevalue);
		}
	});
}

function getasmrsmsrinc() {
	var srcodes		=	$("#srcode").val();

	//alert(srcodes);
	if(srcodes == '' || srcodes == null) {
		//alert('2342');
		$('.myalignsrinc').html("ERR : Select SR");
		$('#errormsgsrinc').css('display','block');
		$("#srcode option:selected").attr("selected",false);
		
		var srasm			=	srbasedsm(srcodes,'asm_sp','SRINCE');
		var srrsm			=	srbasedsm(srcodes,'rsm_sp','SRINCE');
		var srbranch		=	srbasedsm(srcodes,'branch','SRINCE');
		$("#asmspan").html(srasm);
		$("#rsmspan").html(srrsm);
		$("#branchspan").html(srbranch);
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}

	/*if($.isArray(srcodes)) {

		var myArray = $("#srcode option:selected").map(function() {
			 return $(this).text();
		  }).get();

		  

		//alert(myArray);
		if(myArray.indexOf(" ALL") != -1) {			
			//alert('2323');

			var srlength	=	$('#srcode option').length;
			$("#srcode").get(0).selectedIndex = 1;

			for(var c = 0; c <= srlength; c++) {
				if(c != 1) {
					$("#ddcl-srcode-i"+c).attr("checked",false);
				}
			}

			//srcodes			=	$("#srcode").val();
			//alert(srcodes);
		}
	}*/
	
	srcodes		=	$("#srcode").val();
	//alert(srcodes);
	var srasm		=	srbasedsm(srcodes,'asm_sp','SRINCE');
	var srrsm		=	srbasedsm(srcodes,'rsm_sp','SRINCE');
	var srbranch	=	srbasedsm(srcodes,'branch','SRINCE');
	$("#asmspan").html(srasm);
	$("#rsmspan").html(srrsm);
	$("#branchspan").html(srbranch);
	//alert(kdprod);	
}

function getasmspecificsrinc() {
	var asmcodes		=	$("#asmcode").val();
	if(asmcodes == '' || asmcodes == null) {
		//alert('53');
		$('.myalignsrinc').html("ERR : Select ASM");
		$('#errormsgsrinc').css('display','block');
		$("#asmcode option:selected").attr("selected",false);
		var asmrsm				=	asmbasedrsm(asmcodes,'SRINCE');
		var asmsr				=	smbasedsr(asmcodes,'asm_sp','SRINCE');
		var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRINCE');
		$("#rsmspan").html(asmrsm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(asmbranch);

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);
	var asmrsm				=	asmbasedrsm(asmcodes,'SRINCE');
	var asmsr				=	smbasedsr(asmcodes,'asm_sp','SRINCE');
	var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRINCE');
	$("#rsmspan").html(asmrsm);
	$("#srspan").html(asmsr);
	$("#branchspan").html(asmbranch);
	//alert(kdprod);	
}

function getrsmspecificsrinc() {
	var rsmcodes		=	$("#rsmcode").val();
	if(rsmcodes == '' || rsmcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrinc').html("ERR : Select RSM");
		$('#errormsgsrinc').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var rsmasm				=	rsmbasedasm(rsmcodes,'SRINCE');
		var rsmsr				=	smbasedsr(rsmcodes,'rsm_sp','SRINCE');
		var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRINCE');
		$("#asmspan").html(rsmasm);
		$("#srspan").html(rsmsr);
		$("#branchspan").html(rsmbranch);

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var rsmasm				=	rsmbasedasm(rsmcodes,'SRINCE');
	var rsmsr				=	smbasedsr(rsmcodes,'rsm_sp','SRINCE');
	var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRINCE');
	$("#asmspan").html(rsmasm);
	$("#srspan").html(rsmsr);
	$("#branchspan").html(rsmbranch);
	//alert(kdprod);	
}

function getbranchspecificsrinc() {
	var branchcodes		=	$("#branchcode").val();
	if(branchcodes == '' || branchcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrinc').html("ERR : Select Branch");
		$('#errormsgsrinc').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRINCE');
		var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRINCE');
		var branchsr			=	branchbasedsm(branchcodes,'dsr','SRINCE');
		$("#rsmspan").html(branchrsm);
		$("#asmspan").html(branchasm);
		$("#srspan").html(branchsr);

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRINCE');
	var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRINCE');
	var branchsr			=	branchbasedsm(branchcodes,'dsr','SRINCE');
	$("#rsmspan").html(branchrsm);
	$("#asmspan").html(branchasm);
	$("#srspan").html(branchsr);
	//alert(kdprod);	
}

function smbasedbranch(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsmbasedbranch.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function branchbasedsm(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getbranchbasedsm.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function getbranchspecificwithsr() {
	var branchcodes		=	$("#branchcode").val();
	if(branchcodes == '' || branchcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrsta').html("ERR : Select Branch");
		$('#errormsgsrsta').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRIN');
		var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRIN');
		var branchsr			=	branchbasedsm(branchcodes,'dsr','SRIN');
		$("#rsmspan").html(branchrsm);
		$("#asmspan").html(branchasm);
		$("#srspan").html(branchsr);

		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRIN');
	var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRIN');
	var branchsr			=	branchbasedsm(branchcodes,'dsr','SRIN');
	$("#rsmspan").html(branchrsm);
	$("#asmspan").html(branchasm);
	$("#srspan").html(branchsr);
	//alert(kdprod);	
}


//NOT USING THIS FUNCTION, WE CAN REMOVE IT
function srtargetentrycheck(curmonthval,curyearval) {
	var	srcode				=	$("#srcode").val();
	var	frommonth			=	parseInt($("#frommonth").val());
	var	fromyear			=	parseInt($("#fromyear").val());
	var	tomonth				=	parseInt($("#tomonth").val());
	var	toyear				=	parseInt($("#toyear").val());
	var	curmonthval			=	parseInt(curmonthval);
	var	curyearval			=	parseInt(curyearval);
	//alert(srcode);
	if(srcode	==	'' || srcode ==	null) {
		//alert(reportby);
		errormsgsrinc
		$('.myalignsrinc').html("ERR : Select SR");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	if(frommonth	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Select From Month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(frommonth < curmonthval && curyearval == fromyear) {
		$('.myalignsrinc').html("ERR : From Month is below the Current month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(fromyear == '') {
		$('.myalignsrinc').html("ERR : Select From Year");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	} if(tomonth	==	'') {
		//alert(reportby);
		$('.myalignsrinc').html("ERR : Select To Month");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	if(toyear == '') {
		$('.myalignsrinc').html("ERR : Select To Year");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}

	//alert(frommonth+"+++"+fromyear+"+++"+tomonth+"+++"+toyear);
	if(frommonth > tomonth && fromyear == toyear) {
		$('.myalignsrinc').html("ERR : This is not allowed");
		$('#errormsgsrinc').css('display','block');
		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	var ajaxData		=	{ "srcode" : srcode, "frommonth" : frommonth, "tomonth" : tomonth, "fromyear" : fromyear, "toyear" : toyear };

	$.ajax({
		url			:	"getsrincstoredrecords.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			splitval		=	codevalue.split("~");
			$("#ecosta").html(splitval[0]);
			$("#allecosta").html(splitval[1]);
			$("#alldetsta").html(splitval[2]);
			//alert(codevalue);
		}
	});
}

function checkpercententry(curmonthval,curyearval) {	
	var srcode					=	$("#srcode").val();
	var	rowcnt					=	$("#overall_rowcnt").val();
	var frommonth				=	$("#frommonth").val();
	var tomonth					=	$("#tomonth").val();
	var fromyear				=	$("#fromyear").val();
	var toyear					=	$("#toyear").val();
	var targetflag				=	$("#targetflag").val();
	//var targetflag				=	$("select[name='targetflag']").val();
	curmonthval					=	curmonthval;
	curyearval					=	curyearval;

	/*alert(curmonthval);
	alert(curyearval);
	alert(frommonth);
	alert(fromyear);*/


	//alert(srcode);
	//return false;
	if(srcode == '' || srcode == null) {
		$('.myalignsrpercent').html("ERR : Select SR");
		$('#errormsgsrpercent').css('display','block');
		$("#asmid").html('');
		$("#rsmid").html('');

		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	//alert(frommonth);
	
	//alert(targetflag);
	if(frommonth	==	'') {
		//alert(reportby);
		$('.myalignsrpercent').html("ERR : Select From Month");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(fromyear == '') {
		$('.myalignsrpercent').html("ERR : Select From Year");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(parseInt(frommonth) < parseInt(curmonthval) && parseInt(curyearval) == parseInt(fromyear)) {
		$('.myalignsrpercent').html("ERR : From Month is below the Current month");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(tomonth	==	'') {
		//alert(reportby);
		$('.myalignsrpercent').html("ERR : Select To Month");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(toyear == '') {
		$('.myalignsrpercent').html("ERR : Select To Year");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	
	//alert(frommonth+"+++"+fromyear+"+++"+tomonth+"+++"+toyear);
	if(parseInt(frommonth) > parseInt(tomonth) && parseInt(fromyear) == parseInt(toyear)) {
		$('.myalignsrpercent').html("ERR : This is not allowed");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(parseInt(fromyear) > parseInt(toyear)) {
		$('.myalignsrpercent').html("ERR : From Year is Greater than To Year");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	//alert($('input[name="visitcov"]:checked').length);

	/* if($('input[name="tgtTypeCov"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Target Type For Coverage");
		$('#errormsgsrpercent').css('display','block');
		$('#tgtTypeCov').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if($('input[name="tgtTypeProd"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Target Type for Productivity Coverage");
		$('#errormsgsrpercent').css('display','block');
		$('#tgtTypeProd').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if($('input[name="tgtTypeEff"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Target Type for Effective Coverage");
		$('#errormsgsrpercent').css('display','block');
		$('#tgtTypeEff').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} */
	
	if($('input[name="tgtTypeCov"]:checked').length == 0 && $('input[name="tgtTypeEff"]:checked').length == 0 && $('input[name="tgtTypeProd"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Atleast One Target Type");
		$('#errormsgsrpercent').css('display','block');
		$('#tgtTypeEff').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}

	if($('input[name="tgtTypeCov"]:checked').length != 0) {
		if($('input[name="visitcov"]:checked').length == 0) {
			$('.myalignsrpercent').html("ERR : Select Coverage Option");
			$('#errormsgsrpercent').css('display','block');
			$('#visitcov').focus();
			setTimeout(function() {
				$('#errormsgsrpercent').hide();
			},5000);
			return false;
		}
	} if($('input[name="tgtTypeProd"]:checked').length != 0) {
		if($('input[name="visitprod"]:checked').length == 0) {
			$('.myalignsrpercent').html("ERR : Select Productivity Option");
			$('#errormsgsrpercent').css('display','block');
			$('#visitprod').focus();
			setTimeout(function() {
				$('#errormsgsrpercent').hide();
			},5000);
			return false;
		} 
	} if($('input[name="tgtTypeEff"]:checked').length != 0) {
		if($('input[name="visiteff"]:checked').length == 0) {
			$('.myalignsrpercent').html("ERR : Select Effective Option");
			$('#errormsgsrpercent').css('display','block');
			$('#visiteff').focus();
			setTimeout(function() {
				$('#errormsgsrpercent').hide();
			},5000);
			return false;
		}
	} 
		
	//alert($('input[name="tgtTypeCov"]:checked').val());
	
	/*if($('input[name="visitcov"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Coverage Option");
		$('#errormsgsrpercent').css('display','block');
		$('#visitcov').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} 

	if($('input[name="visitprod"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Productivity Option");
		$('#errormsgsrpercent').css('display','block');
		$('#visitprod').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} 

	if($('input[name="visiteff"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Effective Option");
		$('#errormsgsrpercent').css('display','block');
		$('#visiteff').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}*/

	if($('input[name="visitcov"]:checked').length == 0 && $('input[name="visitprod"]:checked').length == 0 && $('input[name="visiteff"]:checked').length == 0) {
		$('.myalignsrpercent').html("ERR : Select Atleast One Option");
		$('#errormsgsrpercent').css('display','block');
		$('#visitcov').focus();
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}

	//return false;
	var t=0;
	var r=0;
	var amtcovcheck = /^[0-9.,]+$/;
	for(var j=0; j<rowcnt; j++) {
		//alert(j);
		//alert(rowcnt);
		//if($('input[name="tgtTypeCov"]:checked').val() == 0) {
			if($('input[name="visitcov"]:checked').val() == 5) {
				if($('#cov_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Coverage %");
					$('#errormsgsrpercent').css('display','block');
					$('#cov_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#cov_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#cov_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#cov_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					}
				}
			}  // IF LOOP FOR % OR VISIT OPTION
		//}

		//if($('input[name="tgtTypeCov"]:checked').val() == 1) {
			if($('input[name="visitcov"]:checked').val() == 10) {
				if($('#cov_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Coverage Visit");
					$('#errormsgsrpercent').css('display','block');
					$('#cov_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#cov_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#cov_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} /* if(parseInt($('#cov_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#cov_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} */
				}
			}
		//}


		//}  // IF LOOP FOR TARGET TYPE 

		//if($('input[name="tgtTypeProd"]:checked').val() == 0) {
			if($('input[name="visitprod"]:checked').val() == 5) {
				if($('#prod_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Productivity %");
					$('#errormsgsrpercent').css('display','block');
					$('#prod_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#prod_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#prod_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#prod_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					}
				}
			} 
		//}
		
		//if($('input[name="tgtTypeProd"]:checked').val() == 1) {
			if($('input[name="visitprod"]:checked').val() == 10) {
				if($('#prod_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Productivity Visit");
					$('#errormsgsrpercent').css('display','block');
					$('#prod_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#prod_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#prod_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} /* if(parseInt($('#prod_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#prod_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} */
				}
			}
		//}
			
		//if($('input[name="tgtTypeEff"]:checked').val() == 0) {
			if($('input[name="visiteff"]:checked').val() == 5) {
				if($('#eff_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Effective %");
					$('#errormsgsrpercent').css('display','block');
					$('#eff_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#eff_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#eff_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					 } if(parseInt($('#eff_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					 } 
				}
			} 
		//}		

		//if($('input[name="tgtTypeEff"]:checked').val() == 1) {
			if($('input[name="visiteff"]:checked').val() == 10) {
				if($('#eff_per_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Effective Visit");
					$('#errormsgsrpercent').css('display','block');
					$('#eff_per_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} else {
					if(isNaN($('#eff_per_'+j).val())) {
						$('.myalignsrpercent').html("ERR : Only Numerals");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					} if(parseInt($('#eff_per_'+j).val()) == 0) {
						$('.myalignsrpercent').html("ERR : No Zero");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					 } /*if(parseInt($('#eff_per_'+j).val()) > 100) {
						$('.myalignsrpercent').html("ERR : 100 or below");
						$('#errormsgsrpercent').css('display','block');
						$('#eff_per_'+j).focus();
						setTimeout(function() {
							$('#errormsgsrpercent').hide();
						},5000);
						return false;
					 } */
				}
			}
		//}
				
		if($('input[name="visitcov"]:checked').val() == 5 || $('input[name="visitcov"]:checked').val() == 10) {
			if($('#cov_visit_'+j).val() == '') {
					$('.myalignsrpercent').html("ERR : Enter Coverage Incentive");
					$('#errormsgsrpercent').css('display','block');
					$('#cov_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
			} else {
				//if(isNaN($('#cov_visit_'+j).val())) {
				if(!amtcovcheck.test($('#cov_visit_'+j).val())) {					
					$('.myalignsrpercent').html("ERR : Only Numerals");
					$('#errormsgsrpercent').css('display','block');
					$('#cov_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} if(parseInt($('#cov_visit_'+j).val()) == 0) {
					$('.myalignsrpercent').html("ERR : No Zero");
					$('#errormsgsrpercent').css('display','block');
					$('#cov_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} 
			}
		}
		
		//alert("334");
		
		if($('input[name="visitprod"]:checked').val() == 5 || $('input[name="visitprod"]:checked').val() == 10) {
			if($('#prod_visit_'+j).val() == '') {
				$('.myalignsrpercent').html("ERR : Enter Productivity Incentive");
					$('#errormsgsrpercent').css('display','block');
					$('#prod_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
			} else {
				//if(isNaN($('#prod_visit_'+j).val())) {
				if(!amtcovcheck.test($('#prod_visit_'+j).val())) {
					$('.myalignsrpercent').html("ERR : Only Numerals");
					$('#errormsgsrpercent').css('display','block');
					$('#prod_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} if(parseInt($('#prod_visit_'+j).val()) == 0) {
					$('.myalignsrpercent').html("ERR : No Zero");
					$('#errormsgsrpercent').css('display','block');
					$('#prod_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} 
			}
		}
		
		if($('input[name="visiteff"]:checked').val() == 5 || $('input[name="visiteff"]:checked').val() == 10) {
			if($('#eff_visit_'+j).val() == '') {
				$('.myalignsrpercent').html("ERR : Enter Effective Incentive");
					$('#errormsgsrpercent').css('display','block');
					$('#eff_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
			} else {
				//if(isNaN($('#eff_visit_'+j).val())) {
				if(!amtcovcheck.test($('#eff_visit_'+j).val())) {
					$('.myalignsrpercent').html("ERR : Only Numerals");
					$('#errormsgsrpercent').css('display','block');
					$('#eff_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				} if(parseInt($('#eff_visit_'+j).val()) == 0) {
					$('.myalignsrpercent').html("ERR : No Zero");
					$('#errormsgsrpercent').css('display','block');
					$('#eff_visit_'+j).focus();
					setTimeout(function() {
						$('#errormsgsrpercent').hide();
					},5000);
					return false;
				}
			}
		}
		//alert(3343);
	}

	

	/*if(t == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Coverage %");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;

	} if(r == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Productivity %");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(s == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Effective %");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(u == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Coverage Visit");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;

	} if(v == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Productivity Visit");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	} if(w == rowcnt) {
		$('.myalignsrpercent').html("ERR : Enter Effective Visit");
		$('#errormsgsrpercent').css('display','block');
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}*/
	//alert(3343);
}

function getasmrsmsrpercent() {
	var srcodes		=	$("#srcode").val();
	//alert(srcodes);
	if(srcodes == '' || srcodes == null) {
		$('.myalignsrpercent').html("ERR : Select SR");
		$('#errormsgsrpercent').css('display','block');
		$("#srcode option:selected").attr("selected",false);
		var srasm			=	srbasedsm(srcodes,'asm_sp','SRCOV');
		var srrsm			=	srbasedsm(srcodes,'rsm_sp','SRCOV');
		var srbranch		=	srbasedsm(srcodes,'branch','SRCOV');
		var srspan			=	getsrforcov(srcodes,'dsr');
		$("#coventryspan").html(srspan);
		$("#coventryspan").css("display","block");
		$("#asmspan").html(srasm);
		$("#rsmspan").html(srrsm);
		$("#branchspan").html(srbranch);
		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	
	//alert(typeof(srcodes));
	
	/*if($.isArray(srcodes)) {
		var myArray = $("#srcode option:selected").map(function() {
			 return $(this).text();
		  }).get();

		//alert(myArray);
		if(myArray.indexOf("ALL") != -1) {
			
			$("#dsrselecttext").val(myArray);
			$("#srcode").get(0).selectedIndex = 1;
			srcodes			=	$("#srcode").val();
			var srspan		=	getsrforcov(srcodes);
			$("#coventryspan").html(srspan);
			$("#coventryspan").css("display","block");
			var srasm		=	srbasedsm(srcodes,'asm_sp','SRCOV');
			var srrsm		=	srbasedsm(srcodes,'rsm_sp','SRCOV');
			var srbranch	=	srbasedsm(srcodes,'branch','SRCOV');
			$("#asmspan").html(srasm);
			$("#rsmspan").html(srrsm);
			$("#branchspan").html(srbranch);
			return false;
		}
	}*/
	
	//alert(srcodes);
	var srspan		=	getsrforcov(srcodes,'dsr');
	$("#coventryspan").html(srspan);
	$("#coventryspan").css("display","block");

	//alert(srcodes);
	var srasm		=	srbasedsm(srcodes,'asm_sp','SRCOV');
	var srrsm		=	srbasedsm(srcodes,'rsm_sp','SRCOV');
	var srbranch	=	srbasedsm(srcodes,'branch','SRCOV');
	$("#asmspan").html(srasm);
	$("#rsmspan").html(srrsm);
	$("#branchspan").html(srbranch);
	//alert(kdprod);	
}

function getsrforcov(codeval,smval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsrforcov.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function getasmspecificsrpercent() {
	var asmcodes		=	$("#asmcode").val();
	if(asmcodes == '' || asmcodes == null) {
		$('.myalignsrpercent').html("ERR : Select ASM");
		$('#errormsgsrpercent').css('display','block');
		$("#asmcode option:selected").attr("selected",false);
		var asmrsm				=	asmbasedrsm(asmcodes,'SRCOV');
		var asmsr				=	smbasedsrall(asmcodes,'asm_sp','SRCOV');
		var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRCOV');

		var asmspan				=	getsrforcov(asmcodes,'asm_sp');
		$("#coventryspan").html(asmspan);
		$("#coventryspan").css("display","block");

		$("#rsmspan").html(asmrsm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(asmbranch);

		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);

	var asmspan		=	getsrforcov(asmcodes,'asm_sp');
	$("#coventryspan").html(asmspan);
	$("#coventryspan").css("display","block");

	var asmrsm				=	asmbasedrsm(asmcodes,'SRCOV');
	var asmsr				=	smbasedsrall(asmcodes,'asm_sp','SRCOV');
	var asmbranch			=	smbasedbranch(asmcodes,'asm_sp','SRCOV');
	$("#rsmspan").html(asmrsm);
	$("#srspan").html(asmsr);
	$("#branchspan").html(asmbranch);
	//alert(kdprod);	
}

function getrsmspecificsrpercent() {
	var rsmcodes		=	$("#rsmcode").val();
	if(rsmcodes == '' || rsmcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrpercent').html("ERR : Select RSM");
		$('#errormsgsrpercent').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var rsmasm				=	rsmbasedasm(rsmcodes,'SRCOV');
		var rsmsr				=	smbasedsrall(rsmcodes,'rsm_sp','SRCOV');
		var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRCOV');

		var rsmspan				=	getsrforcov(rsmcodes,'rsm_sp');
		$("#coventryspan").html(rsmspan);
		$("#coventryspan").css("display","block");

		$("#asmspan").html(rsmasm);
		$("#srspan").html(rsmsr);
		$("#branchspan").html(rsmbranch);

		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var rsmasm				=	rsmbasedasm(rsmcodes,'SRCOV');
	var rsmsr				=	smbasedsrall(rsmcodes,'rsm_sp','SRCOV');
	var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','SRCOV');

	var rsmspan				=	getsrforcov(rsmcodes,'rsm_sp');
	$("#coventryspan").html(rsmspan);
	$("#coventryspan").css("display","block");

	$("#asmspan").html(rsmasm);
	$("#srspan").html(rsmsr);
	$("#branchspan").html(rsmbranch);
	//alert(kdprod);	
}

function getbranchspecificsrpercent() {
	var branchcodes		=	$("#branchcode").val();
	if(branchcodes == '' || branchcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrpercent').html("ERR : Select Branch");
		$('#errormsgsrpercent').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRCOV');
		var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRCOV');
		var branchsr			=	branchbasedsmall(branchcodes,'dsr','SRCOV');

		var branchspan				=	getsrforcov(branchcodes,'branch');
		$("#coventryspan").html(branchspan);
		$("#coventryspan").css("display","block");

		$("#rsmspan").html(branchrsm);
		$("#asmspan").html(branchasm);
		$("#srspan").html(branchsr);

		setTimeout(function() {
			$('#errormsgsrpercent').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','SRCOV');
	var branchasm			=	branchbasedsm(branchcodes,'asm_sp','SRCOV');
	var branchsr			=	branchbasedsmall(branchcodes,'dsr','SRCOV');

	var branchspan				=	getsrforcov(branchcodes,'branch');
	$("#coventryspan").html(branchspan);
	$("#coventryspan").css("display","block");

	$("#rsmspan").html(branchrsm);
	$("#asmspan").html(branchasm);
	$("#srspan").html(branchsr);
	//alert(kdprod);	
}


function smbasedsrall(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getsmbasedsrall.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function branchbasedsmall(codeval,smval,srval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getbranchbasedsmall.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval, "smval" : smval, "srval" : srval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}


function srcovviewajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var DSR_name		=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "covtargetentryviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srcovid").html(trimval);	
		}
	});
}

function searchsrcovviewajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var DSR_name	=	$("input[name='DSR_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "covtargetentryviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srcovid").html(trimval);	
		}
	});
}

function getallforposm(prodcode,allid,rowid) {
	var overallcount		=	$("#overall_rowcnt").val();
	//var srid				=	$("#dsrname_"+rowid).val();
	
	//alert(srid); alert(prodcode);
	//alert(srid);
	//alert(prodcode);

	if (prodcode == '') {
		$('.myalignposmtgt').html("ERR : Select Product");
		$('#errormsgposmtgt').css('display','block');
		$("#princ_"+rowid).html("");
		$("#princval_"+rowid).val("");
		$("#bran_"+rowid).html("");
		$("#branval_"+rowid).val("");
		$("#posmval_"+rowid).val("");
		$("#ct_"+rowid).html("");
		$("#ctval_"+rowid).val("");
		$("#noofcus_"+rowid).val("");
		$("#unitstgt_"+rowid).val("");
		$("#fromdate_"+rowid).val("");
		$("#todate_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	}
	
	var t 			=	0;
	//alert(rowid);
	for(var k = 1; k <= allid; k++) {
		
		//var dsrcode			=	$("#dsrname_"+k).val();
		var prodcodechk		=	$("#posmval_"+k).val();
		//alert(dsrcode);
		//alert(prodcodechk);

		if(prodcodechk == prodcode && prodcodechk != '') {
			t++;
			//alert(t);
			//alert(k);
			//alert(t);
		}
		//dsrcode			=	'';
		prodcodechk		=	'';
	}
	
	//alert(k);
	//alert(t);
	if(t > 1) {
		$('.myalignposmtgt').html("ERR : This Product already Selected");
		$('#errormsgposmtgt').css('display','block');
		$("#princ_"+rowid).html("");
		$("#princval_"+rowid).val("");
		$("#bran_"+rowid).html("");
		$("#branval_"+rowid).val("");
		$("#posmval_"+rowid).val("");
		$("#ct_"+rowid).html("");
		$("#ctval_"+rowid).val("");
		$("#noofcus_"+rowid).val("");
		$("#unitstgt_"+rowid).val("");
		$("#fromdate_"+rowid).val("");
		$("#todate_"+rowid).val("");
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	}

	//alert(overallcount);
	//alert(srid);
	//return false;
	var brandval			=	getposmdetails(prodcode);
	//alert(brandval);
	var splitval		=	brandval.split("~");
	$("#branval_"+rowid).val(splitval[0]);
	$("#bran_"+rowid).html(splitval[1]);
	$("#princval_"+rowid).val(splitval[2]);
	$("#princ_"+rowid).html(splitval[3]);
	$("#ctval_"+rowid).val(splitval[4]);
	$("#ct_"+rowid).html(splitval[5]);
	$("#noofcus_"+rowid).val(splitval[6]);
	$("#noofcusval_"+rowid).val(splitval[6]);	
}

function getposmdetails(prodid) {
	codevalue		=	'';
	$.ajax({
		url			:	"getposmdet.php",
		type		:	"get",
		data		:	{ "prodid" : prodid },
		dataType	:	"text",
		async		:	false,
		success		:	function(ajaxval) {
			codevalue		=	$.trim(ajaxval);
			//alert(trimval);
		}
	});
	//alert(trimvalue);
	return codevalue;
}

//saving posm target setting
function saveposmtgt(rowid,curmonthval,curyearval) {
	var frommonth			=	$("#frommonth").val();
	var tomonth				=	$("#tomonth").val();
	var fromyear			=	$("#fromyear").val();
	var toyear				=	$("#toyear").val();

	if(frommonth	==	'') {
	//alert(reportby);
	$('.myalignposmtgt').html("ERR : Select From Month");
	$('#errormsgposmtgt').css('display','block');
	setTimeout(function() {
		$('#errormsgposmtgt').hide();
	},5000);
	return false;
	} if(fromyear == '') {
		$('.myalignposmtgt').html("ERR : Select From Year");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	} if(parseInt(frommonth) < parseInt(curmonthval) && parseInt(curyearval) == parseInt(fromyear)) {
		$('.myalignposmtgt').html("ERR : From Month is below the Current month");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	} if(tomonth	==	'') {
		//alert(reportby);
		$('.myalignposmtgt').html("ERR : Select To Month");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	} if(toyear == '') {
		$('.myalignposmtgt').html("ERR : Select To Year");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	}
	
	//alert(frommonth+"+++"+fromyear+"+++"+tomonth+"+++"+toyear);
	if(parseInt(frommonth) > parseInt(tomonth) && parseInt(fromyear) == parseInt(toyear)) {
		$('.myalignposmtgt').html("ERR : This is not allowed");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	} if(parseInt(fromyear) > parseInt(toyear)) {
		$('.myalignposmtgt').html("ERR : From Year is Greater than To Year");
		$('#errormsgposmtgt').css('display','block');
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	}
	
	//alert(rowid);
	var t 			=	0;
	for(var k = 1; k <= rowid; k++) {
		
		var posmval				=	$("#posmval_"+k).val();
		var unitstgt			=	$("#unitstgt_"+k).val();
		var cusval				=	$("#noofcus_"+k).val();
		var actcusval			=	$("#noofcusval_"+k).val();
		var qtypat				=	/^[0-9,]+$/; // this is javascript regular expression pattern
		
		/*var fromdate		=	$("#fromdate_"+k).val();
		var todate			=	$("#todate_"+k).val();

		var fromdate, todate, dt1, dt2, mon1, mon2, yr1, yr2, date1, date2;
		var chkFrom			=	fromdate;
		var chkTo			=	todate;				
		dt1					=	parseInt(fromdate.substring(8, 10), 10);
		mon1				=	(parseInt(fromdate.substring(5, 7), 10)) - 1;
		yr1					=	parseInt(fromdate.substring(0, 4), 10);

		dt2					=	parseInt(todate.substring(8, 10), 10);
		mon2				=	(parseInt(todate.substring(5, 7), 10)) - 1;
		yr2					=	parseInt(todate.substring(0, 4), 10);
		date1				=	new Date(yr1, mon1, dt1);
		date2				=	new Date(yr2, mon2, dt2);
		*/

		//alert(date2);
		//alert(date1);

		//alert(posmval);
		
		if(posmval == '') {
			t++;
		}

		if(posmval != '') {
			//alert(dsrcode);
			//alert(prodcodechk);
			actcusval
			
			if(cusval == '') {
				$('.myalignposmtgt').html("ERR : Enter Customer Count");
				$('#errormsgposmtgt').css('display','block');
				$("#noofcus_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} else if(isNaN(cusval)) {
				$('.myalignposmtgt').html("ERR : Only Numerals");
				$('#errormsgposmtgt').css('display','block');
				$("#noofcus_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} 
			
			if(actcusval	!= 0) {
				if(cusval == 0) {
				$('.myalignposmtgt').html("ERR : No Zero");
				$('#errormsgposmtgt').css('display','block');
				$("#noofcus_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} else if(cusval > actcusval) {
				$('.myalignposmtgt').html("ERR : Customer Count Exceeded");
				$('#errormsgposmtgt').css('display','block');
				$("#noofcus_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} 
			}

			if(unitstgt == '') {
				$('.myalignposmtgt').html("ERR : Enter Units");
				$('#errormsgposmtgt').css('display','block');
				$("#unitstgt_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} //else if(isNaN(unitstgt)) {
				else if(!qtypat.test(unitstgt)) {
				$('.myalignposmtgt').html("ERR : Only Numerals");
				$('#errormsgposmtgt').css('display','block');
				$("#unitstgt_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} else if(unitstgt == 0) {
				$('.myalignposmtgt').html("ERR : No Zero");
				$('#errormsgposmtgt').css('display','block');
				$("#unitstgt_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} 			
			
			/*if(fromdate == '') {
				$('.myalignposmtgt').html("ERR : Select From Date");
				$('#errormsgposmtgt').css('display','block');
				$("#fromdate_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} 

			var currentdate = new Date();
			var curdt1					=	currentdate.getDate();
			var curmon1					=	currentdate.getMonth();
			var curyr1					=	currentdate.getFullYear();
			var curval					=	new Date(curyr1,curmon1,curdt1);

			//alert(curval);
			//alert(date1);
			if (date1 < curval) {				
				$('.myalignposmtgt').html('ERR : From Date Below the Today Date!');
				$('#errormsgposmtgt').css('display','block');
				$("#fromdate_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			}

			if(todate == '') {
				$('.myalignposmtgt').html("ERR : Select To Date");
				$('#errormsgposmtgt').css('display','block');
				$("#todate_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			} 

			if (date2 < date1) {		
				$('.myalignposmtgt').html('ERR : To date Should be greater than From date!');
				$('#errormsgposmtgt').css('display','block');
				$("#todate_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			}


			if(date2 < curval) {
				$('.myalignposmtgt').html('ERR : To Date below the Today Date!');
				$('#errormsgposmtgt').css('display','block');
				$("#todate_"+k).focus();
				setTimeout(function() {
					$('#errormsgposmtgt').hide();
				},5000);
				return false;
			}*/
		}
		posmval			=	'';
		unitstgt		=	'';
		frommonth		=	'';
		tomonth			=	'';
		fromyear		=	'';
		toyear			=	'';
	}

	//alert(t);
	if(t == rowid) {
		$('.myalignposmtgt').html("ERR : Select Product");
		$('#errormsgposmtgt').css('display','block');
		$("#posmval_"+k).focus();
		setTimeout(function() {
			$('#errormsgposmtgt').hide();
		},5000);
		return false;
	}
	//alert(overallcount);
	//alert(srid);
	//return false;
}

function posmtgtviewajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "posmtargetviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#posmtgtid").html(trimval);	
		}
	});
}

function searchposmtgtviewajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "posmtargetviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#posmtgtid").html(trimval);	
		}
	});
}

function getsrspecificposm() {
	var srcodes		=	$("#srcode").val();
	if(srcodes == '' || srcodes == null) {
		$('.myalignsrperf').html("ERR : Select SR");
		$('#errormsgsrperf').css('display','block');
		$("#srcode option:selected").attr("selected",false);
		var srasm			=	srbasedsm(srcodes,'asm_sp','POSM');
		var srrsm			=	srbasedsm(srcodes,'rsm_sp','POSM');
		var srbranch		=	srbasedsm(srcodes,'branch','POSM');
		$("#asmspan").html(srasm);
		$("#rsmspan").html(srrsm);
		$("#branchspan").html(srbranch);
		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);	
	var srasm				=	srbasedsm(srcodes,'asm_sp','POSM');
	var srrsm				=	srbasedsm(srcodes,'rsm_sp','POSM');
	var srbranch			=	srbasedsm(srcodes,'branch','POSM');
	$("#asmspan").html(srasm);
	$("#rsmspan").html(srrsm);
	$("#branchspan").html(srbranch);
	//alert(kdprod);	
}

function getasmspecificposm() {
	var asmcodes		=	$("#asmcode").val();
	if(asmcodes == '' || asmcodes == null) {
		$('.myalignsrperf').html("ERR : Select ASM");
		$('#errormsgsrperf').css('display','block');
		$("#asmcode option:selected").attr("selected",false);
		var asmrsm			=	asmbasedrsm(asmcodes,'POSM');
		var asmsr			=	smbasedsr(asmcodes,'asm_sp','POSM');
		var asmbranch		=	smbasedbranch(asmcodes,'asm_sp','POSM');
		$("#rsmspan").html(asmrsm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(asmbranch);

		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(kdcodes);
	var asmrsm					=	asmbasedrsm(asmcodes,'POSM');
	var asmsr					=	smbasedsr(asmcodes,'asm_sp','POSM');
	var asmbranch				=	smbasedbranch(asmcodes,'asm_sp','POSM');
	$("#rsmspan").html(asmrsm);
	$("#srspan").html(asmsr);
	$("#branchspan").html(asmbranch);
	//alert(kdprod);	
}

function getrsmspecificposm() {
	var rsmcodes		=	$("#rsmcode").val();
	if(rsmcodes == '' || rsmcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrperf').html("ERR : Select RSM");
		$('#errormsgsrperf').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var rsmasm				=	rsmbasedasm(rsmcodes,'POSM');
		var asmsr				=	smbasedsr(rsmcodes,'rsm_sp','POSM');
		var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','POSM');
		$("#asmspan").html(rsmasm);
		$("#srspan").html(asmsr);
		$("#branchspan").html(rsmbranch);

		setTimeout(function() {
			$('#errormsgsrperf').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var rsmasm				=	rsmbasedasm(rsmcodes,'POSM');
	var rsmsr				=	smbasedsr(rsmcodes,'rsm_sp','POSM');
	var rsmbranch			=	smbasedbranch(rsmcodes,'rsm_sp','POSM');
	$("#asmspan").html(rsmasm);
	$("#srspan").html(rsmsr);
	$("#branchspan").html(rsmbranch);
	//alert(kdprod);	
}

function getbranchspecificposm() {
	var branchcodes		=	$("#branchcode").val();
	if(branchcodes == '' || branchcodes == null) {
		//alert(rsmcodes);
		$('.myalignsrsta').html("ERR : Select Branch");
		$('#errormsgsrsta').css('display','block');
		$("#rsmcode option:selected").attr("selected",false);
		var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','POSM');
		var branchasm			=	branchbasedsm(branchcodes,'asm_sp','POSM');
		var branchsr			=	branchbasedsm(branchcodes,'dsr','POSM');
		$("#rsmspan").html(branchrsm);
		$("#asmspan").html(branchasm);
		$("#srspan").html(branchsr);

		setTimeout(function() {
			$('#errormsgsrsta').hide();
		},5000);
		return false;
	}
	//alert(rsmcodes);
	var branchrsm			=	branchbasedsm(branchcodes,'rsm_sp','POSM');
	var branchasm			=	branchbasedsm(branchcodes,'asm_sp','POSM');
	var branchsr			=	branchbasedsm(branchcodes,'dsr','POSM');
	$("#rsmspan").html(branchrsm);
	$("#asmspan").html(branchasm);
	$("#srspan").html(branchsr);
	//alert(kdprod);	
}

function posmreport() {
	var	reportby		=	$("#reportby").val();
	var	asmcode			=	$("#asmcode").val();
	var	rsmcode			=	$("#rsmcode").val();
	var	srcode			=	$("#srcode").val();
	var	custype			=	$("#custype").val();
	var	propmonth		=	$("#propmonth").val();
	var	propyear		=	$("#propyear").val();

	/*var fromdateval	=	$("#fromdates").val();
	var todateval	=	$("#todates").val();

	var dt1		=	parseInt(fromdateval.substring(8, 10), 10);
	var mon1	=	(parseInt(fromdateval.substring(5, 7), 10)) - 1;
	var yr1		=	parseInt(fromdateval.substring(0, 4), 10);
	var date1	=	new Date(yr1, mon1, dt1);

	var dt2 = parseInt(todateval.substring(8, 10), 10);
	var mon2 = (parseInt(todateval.substring(5, 7), 10)) - 1;
	var yr2 = parseInt(todateval.substring(0, 4), 10);
	var date2		=	new Date(yr2, mon2, dt2);

	var currdate	=	new Date();
	if(reportby	==	'') {
		//alert(reportby);
		$('.myalignposmcov').html("ERR : Select Report By");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
	if(fromdateval == '') {
		$('.myalignposmcov').html("ERR : Select From Date");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	} if(todateval == '') {
		$('.myalignposmcov').html("ERR : Select To Date");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
	if(date1 > currdate) {
		$('.myalignposmcov').html("ERR : From Date is greater than today date");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	} if(date2 > currdate) {
		$('.myalignposmcov').html("ERR : To Date is greater than today date");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	} if(date1 > date2) {
		$('.myalignposmcov').html("ERR : From Date greater than To Date");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}*/
	if(reportby	==	'') {
		//alert(reportby);
		$('.myalignposmcov').html("ERR : Select Report By");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
	if(propmonth == '') {
		$('.myalignposmcov').html("ERR : Select Month");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
	if(propyear == '') {
		$('.myalignposmcov').html("ERR : Select Year");
		$('#errormsgposmcov').css('display','block');
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
	var ajaxData		=	{ "reportby" : reportby, "propmonths" : propmonth, "propyears" : propyear, "srcode" : srcode, "asmcode" : asmcode, "rsmcode" : rsmcode, "custype" : custype };

	$.ajax({
		url			:	"getposmreport.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			$("#ajaxresultpage").html(codevalue);
			//alert(codevalue);
		}
	});
}

function checkcustypeval(val) {
	var custypecodes		=	$("#custype").val();
	if(custypecodes == '' || custypecodes == null){
		//alert(val);
		$('.myalignposmcov').html("ERR : Select Customer Type");
		$('#errormsgposmcov').css('display','block');
		$("#custype").get(0).selectedIndex = -1;
		setTimeout(function() {
			$('#errormsgposmcov').hide();
		},5000);
		return false;
	}
}

function srmetricsreport() {
	var	srcode			=	$("#srcode").val();
	var	propmonth		=	$("#propmonth").val();
	var	propyear		=	$("#propyear").val();

	if(srcode == ''){
		//alert(val);
		$('.myalignmetrep').html("ERR : Select SR");
		$('#errormsgmetrep').css('display','block');
		$("#custype").get(0).selectedIndex = -1;
		setTimeout(function() {
			$('#errormsgmetrep').hide();
		},5000);
		return false;
	} if(propmonth == '') {
		$('.myalignmetrep').html("ERR : Select Month");
		$('#errormsgmetrep').css('display','block');
		setTimeout(function() {
			$('#errormsgmetrep').hide();
		},5000);
		return false;
	}
	if(propyear == '') {
		$('.myalignmetrep').html("ERR : Select Year");
		$('#errormsgmetrep').css('display','block');
		setTimeout(function() {
			$('#errormsgmetrep').hide();
		},5000);
		return false;
	}
	var ajaxData		=	{ "srcode" : srcode, "propmonths" : propmonth, "propyears" : propyear };

	$.ajax({
		url			:	"getmetricsreport.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,		
		data		:	ajaxData,
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			$("#ajaxresultpage").html(codevalue);
			//alert(codevalue);
		}
	});
}

function checkSRTgtUnits(noofrows,fieldval,disval) {
	//alert(43);
	for(var k = 0; k < noofrows; k++) {
		if(disval == 'notoff') {
			//alert('2334');
			$("#"+fieldval+k).attr("disabled",true);
			$("#"+fieldval+k).val("");
		} else if(disval == 'noton') {
			//alert('23');
			$("#"+fieldval+k).val("");
			$("#"+fieldval+k).removeAttr("disabled");			
		}
	}
}

function checkCovVisit(noofrows,fieldval,disval) {
	//alert(43);
	for(var k = 0; k < noofrows; k++) {
		if(disval == 'notoff') {
			//alert('2334');
			$("#"+fieldval+k).attr("disabled",true);
			$("#"+fieldval+k).val("");
		} else if(disval == 'noton') {
			//alert('23');
			$("#"+fieldval+k).val("");
			$("#"+fieldval+k).removeAttr("disabled");			
		}
	}
}

function checkCovTgt(noofrows,textid,fieldid,disval) {
	if(disval == 'notoff') {
		$("input[name='"+fieldid+"']").each(function() {
			$(this).attr("disabled",true);
			$(this).removeAttr("checked");
		});		
	} else if(disval == 'noton') {
		$("input[name='"+fieldid+"']").each(function() {
			$(this).removeAttr("disabled");
			$(this).removeAttr("checked");
		});		
	} 

	for(var k = 0; k < noofrows; k++) {
		if(disval == 'notoff') {
			$("#"+textid+k).attr("disabled",true);
			$("#"+textid+k).val("");
		} else if(disval == 'noton') {
			//alert('23');
			$("#"+textid+k).val("");
			$("#"+textid+k).removeAttr("disabled");			
		}
	}
}

function changeDateFormat(DateVal,dateelement) {
	//alert(DateVal);
	var datePart	=	DateVal.split('/');

	var dateyear	=	datePart[2];
	var dateday		=	datePart[1];
	var datemon		=	datePart[0];
	
	var DateOrgVal		=	dateyear+"-"+datemon+"-"+dateday;
	//alert(DateOrgVal);
	$('#'+dateelement).val(DateOrgVal);
}

function devstatusajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var DSR_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "devicestatusajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#devicestatusid").html(trimval);	
		}
	});
}

function searchdevstatusajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var DSR_name	=	$("input[name='DSR_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "devicestatusajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#devicestatusid").html(trimval);	
		}
	});
}

function changeIndianDateFormat(DateVal,dateelement) {
	//alert(DateVal);
	var datePart	=	DateVal.split('-');

	var dateyear	=	datePart[2];
	var dateday		=	datePart[1];
	var datemon		=	datePart[0];
	
	var DateOrgVal		=	dateday+"-"+datemon+"-"+dateyear;
	//alert(DateOrgVal);
	$('#'+dateelement).val(DateOrgVal);
}

function getprodforbrand() {
	var brandcodes					=	$("#brandcode").val();
	if(brandcodes == '' || brandcodes == null) {
		$('.myalignsrinc').html("ERR : Select BRAND");
		$('#errormsgsrinc').css('display','block');
		$("#brandcode option:selected").attr("selected",false);
		var prodspan				=	getbrandbasedprod(brandcodes);
		$("#brandbasedprodspan").html(prodspan);
		$("#brandbasedprodspan").css("display","block");

		setTimeout(function() {
			$('#errormsgsrinc').hide();
		},5000);
		return false;
	}
	//alert(brandcodes);

	var prodspan				=	getbrandbasedprod(brandcodes);
	$("#brandbasedprodspan").html(prodspan);
	$("#brandbasedprodspan").css("display","block");
	//alert(brandcodes);	
}

function getbrandbasedprod(codeval) {
	codevalue					=		'';
	$.ajax({
		url			:	"getbrandforproduct.php",
		type		:	"get",
		dataType	:	"text",
		async		:	false,
		data		:	{ "codeval" : codeval },
		success		:	function(dataval) {
			codevalue		=	$.trim(dataval);
			//alert(codevalue);
		}
	});
	return codevalue;	
}

function routemonthviewajax(page,params){   // For pagination and sorting of the cycle assignment view page
	var splitparam		=	params.split("&");
	var DSR_name		=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "routemonthplviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#routemonthplid").html(trimval);	
		}
	});
}

function searchroutemonthviewajax(page){  // For pagination and sorting of the cycle assignment search in view page
	var DSR_name	=	$("input[name='DSR_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "routemonthplviewajax.php",
		type: "get",
		dataType: "text",
		data : { "DSR_name" : DSR_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#routemonthplid").html(trimval);	
		}
	});
}

function bringDaySalesTrans(DateVal,DSR_Code) {

	$.ajax({
		url : "bringdaysalestrans.php",
		type: "get",
		dataType: "text",
		data : { "DateVal" : DateVal, "DSR_Code" : DSR_Code },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
						
			//$("#confirmFirstMessage"+postid).css("display","none");
			$("#backgroundChatPopup").css("display","none");
			$(" <div />" ).attr("id","FirstSalesTrans"+DateVal).addClass("SalesTransPop").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeSalesTransPopup(this,\''+DateVal+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="FirstSalTrans'+DateVal+'" ></div></p>').appendTo($( "body" ));	
			$("#FirstSalesTrans"+DateVal).css("display","block");
			$("#FirstSalesTrans"+DateVal).css("z-index","100");
			$('#FirstSalTrans'+DateVal).html(trimval);
			$("#backgroundChatPopup").css({"opacity": "0.7"});
			$("#backgroundChatPopup").fadeIn("slow");
			return false;	
		}
	});
}

function bringCusBalDue(cusCode,TransNo,DSRName,DateVal) { 
	$.ajax({
		url : "bringcusbaldue.php",
		type: "get",
		dataType: "text",
		data : { "cusCode" : cusCode, "TransNo" : TransNo, "DSRName" : DSRName, "DateVal" : DateVal },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
						
			//$("#confirmFirstMessage"+postid).css("display","none");
			$("#backgroundChatPopup").css("display","none");
			$(" <div />" ).attr("id","SecCusBal"+TransNo).addClass("CusBalDuePop").html('<p class="closepboxa"><label class="closexbox"><a class="closelink" href="javascript:void(0)" onclick="javascript:return closeCusBalDuePopup(this,\''+TransNo+'\');"><b><img border="0" src="../images/close_button2.png" /></b></a></label></p><p style="font-size:15px;padding-left:30px;" class="addcolor"><div id="SecBalPop'+TransNo+'" ></div></p>').appendTo($( "body" ));	
			$("#SecCusBal"+TransNo).css("display","block");
			$("#SecCusBal"+TransNo).css("z-index","120");
			$('#SecBalPop'+TransNo).html(trimval);
			$("#backgroundChatPopup").css({"opacity": "0.7"});
			$("#backgroundChatPopup").fadeIn("slow");
			return false;	
		}
	});
}

function closeSalesTransPopup(atr,DateVal){
	$('#FirstSalesTrans'+DateVal).remove();
	$('#FirstSalesTrans'+DateVal).css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function closeCusBalDuePopup(atr,TransNo){
	$('#SecCusBal'+TransNo).remove();
	$('#SecCusBal'+TransNo).css('display','none');
	$('#backgroundChatPopup').fadeOut('slow');
}

function printprodconfirm() {	
	var prodcnt		=	$("#prodcnt").val();
	//alert(prodcnt);
	var w=0;
	var y=0;
	
	var qtypat	= /^[0-9]+$/;

	for(var f=1; f <= prodcnt; f++) {		
		//alert($("#Loaded_Qty_"+f).val());
		if($.trim($("#Loaded_Qty_"+f).val()) == '') {
			
		} else {
			y++;
		}
	}
	if(y == 0) {
		$(".myalignprod").html("Enter One Product");
		$("#errormsgpopupprod").css('display','block');
		setTimeout(function() {
			$("#errormsgpopupprod").hide();
		},5000);
		return false;
	}

	for(var k=1; k <= prodcnt; k++) {
		var actual_qty			=	parseInt($.trim($("#actual_qty_"+k).val()));
		var Loaded_Qty			=	$("#Loaded_Qty_"+k).val();
		var Loaded_Qty_check	=	parseInt($.trim($("#Loaded_Qty_"+k).val()));
		var product_code_val	=	$("#product_code_"+k).val();

		if(Loaded_Qty != '') {
			//alert(k);
			
			//alert(actual_qty);
			//alert(Loaded_Qty);
			if(Loaded_Qty == ''){
				$('.myalignprod').html('ERR : Enter Quantity for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}

			//alert($("#qty_"+k).val());
			if(isNaN(Loaded_Qty)){
				$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				//alert(Loaded_Qty);
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			} 
			if(!qtypat.test(Loaded_Qty)){
				$('.myalignprod').html('ERR : Only Numerals for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}
			if(Loaded_Qty == 0){
				$('.myalignprod').html('ERR : No Zero for '+product_code_val);
				$('#errormsgpopupprod').css('display','block');
				setTimeout(function() {
					$('#errormsgpopupprod').hide();
				},5000);
				$('#Loaded_Qty_'+k).focus();
				return false;
			}
			//alert(Loaded_Qty);
			//alert(actual_qty);
			//var actval	= (actual_qty > Loaded_Qty) ? 4 : 6;
			//alert(actval);
			//alert(actual_qty > Loaded_Qty);
			if(actual_qty < Loaded_Qty_check) {
				$(".myalignprod").html("ERR : Available quantity is "+actual_qty+" for "+product_code_val);
				$("#errormsgpopupprod").css("display","block");
				setTimeout(function() {
					$("#errormsgpopupprod").hide();
				},5000);
				$("#Loaded_Qty_"+k).focus();
				return false;
			} else {
				//alert(Loaded_Qty);
			}
		}	
		Loaded_Qty = '';
		actual_qty = '';
	}
	
	//alert($("#DSR_Code").val());
	//$('#productshow').css('display','none');
	//$('#backgroundChatPopup').fadeOut('slow');
	$("#dailystockvalidation").attr("target","_blank");
	$("#dailystockvalidation").attr("action","printprodconfirm.php");
	$("#dailystockvalidation").submit();

	return false;
	//$('#productshow').css('display','none');
	//$('#backgroundChatPopup').fadeOut('slow');
}
function nothingdo() {
	$("#errormsgpopupprod").hide();
	return false;
}

function getPriceVal(ordernum,priceval,qtyval,uom_coversion) {
	var amt			=	0;		// WE NEED TO INITIALIZE TO 0 WHEN ADDING AND INITIALIZE TO 1 WHEN MULTIPLYING IN JQUERY OR JAVASCRIPT
	var prodcnt		=	$("#prodcnt").val();
	if($("#uomval_"+ordernum).val() == 'PCS') {
		var totalprice	=	parseInt(priceval) * parseInt(qtyval);
	} else if($("#uomval_"+ordernum).val() == 'CARTONS') {
		var totalprice	=	parseInt(priceval) * parseInt(qtyval) * parseInt(uom_coversion);
	}
	var totalval	=	$("#totval").html();
	if(totalval == 'Nil') {
		totalval	= 0;
	} else {
		totalval	= parseInt(totalval);
	}
	//alert(totalval);
	var nowtotalval	=	totalprice + totalval;	//	for total value
	//alert(nowtotalval);
	$("#value_"+ordernum).html(totalprice);
	for(var k=1; k <= prodcnt; k++) {
		//alert($("#value_"+k).html());
		amt += parseInt($("#value_"+k).html());
	}
	$("#totval").html(amt);						//	for total value
}
function changeToPcs(ordernum,priceval,cartonqty,uom_conversion) {
	var amt				=	0;		// WE NEED TO INITIALIZE TO 0 WHEN ADDING AND INITIALIZE TO 1 WHEN MULTIPLYING IN JQUERY OR JAVASCRIPT
	var totalqty		=	parseInt(cartonqty) * parseInt(uom_conversion);
	//alert(totalqty);
	//alert(priceval);
	var prodcnt			=	$("#prodcnt").val();

	if(cartonqty	==	'') {
		totalqty		=	'';
	}
	$("#Loaded_Qty_"+ordernum).val(totalqty);

	var totalprice	=	parseInt(priceval) * (cartonqty);
	$("#total_price_value_"+ordernum).val(totalprice);
	$("#value_"+ordernum).html(totalprice);
	for(var k=1; k <= prodcnt; k++) {
		//alert($("#value_"+k).html());
		amt += parseInt($("#value_"+k).html());
	}
	$("#totval").html(amt);
	$("#finaltotalval").val(amt);
}

function changeToCartons(ordernum,priceval,pieceqty,uom_conversion) {
	//cartonqty
	var amt				=	0;		// WE NEED TO INITIALIZE TO 0 WHEN ADDING AND INITIALIZE TO 1 WHEN MULTIPLYING IN JQUERY OR JAVASCRIPT
	var totalcartonqty	=	parseInt(pieceqty) / parseInt(uom_conversion);
	//alert(totalqty);
	//alert(priceval);
	var prodcnt			=	$("#prodcnt").val();

	if(pieceqty	==	'') {
		totalcartonqty		=	'';
	}

	$("#UOM_cartons_"+ordernum).val(totalcartonqty);
	//$("#Loaded_Qty_"+ordernum).val(totalqty);

	var totalprice	=	parseInt(priceval) * (totalcartonqty);
	$("#total_price_value_"+ordernum).val(totalprice);
	$("#value_"+ordernum).html(totalprice);
	for(var k=1; k <= prodcnt; k++) {
		//alert($("#value_"+k).html());
		amt += parseInt($("#value_"+k).html());
	}
	$("#totval").html(amt);
	$("#finaltotalval").val(amt);
}

function issuesviewajax(page,params){   // For pagination and sorting of the stock receipts view page
	var splitparam		=	params.split("&");
	var Product_name	=	splitparam[0];
	var sortorder		=	splitparam[1];
	var ordercol		=	splitparam[2];
	$.ajax({
		url : "StockIssuesviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srviewajax").html(trimval);	
		}
	});
}

function searchissuesviewajax(page){  // For pagination and sorting of the stock receipts search in view page
	var Product_name	=	$("input[name='Product_name']").val();
	//alert(Product_name);
	$.ajax({
		url : "StockIssuesviewajax.php",
		type: "get",
		dataType: "text",
		data : { "Product_name" : Product_name, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#srviewajax").html(trimval);	
		}
	});
}

function currencyformat(amt,t) {
	//alert(amt);
	amt	=	parseInt(amt);
	var amtval	=	amt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	$("#Amount_Deposited_"+t).val(amtval);
	//return amtval;
}
function curformatreturn(amt) {
	amt	=	parseInt(amt);
	var amtval	=	amt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	return amtval;
}

function curformatreturntoid(amt,idval) {
	if(amt == '') {
		return false;
	}
	if(isNaN(amt)) {
		return false;
	}

	amt	=	parseInt(amt);
	var amtval	=	amt.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	$("#"+idval).val(amtval);
}

function qtyformatreturntoid(qtyval,idval) {
	if(qtyval == '') {
		return false;
	}
	if(isNaN(qtyval)) {
		return false;
	}
	
	var qtyvalue= qtyval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	
	$("#"+idval).val(qtyvalue);
}

function qtyformatreturn(qtyval) {
	if(qtyval == '') {
		return 0;
	}
	if(isNaN(qtyval)) {
		return 0;
	}
	
	return qtyvalue= qtyval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function down_excel() {
	window.location = "downloadstockstatus.php"
}