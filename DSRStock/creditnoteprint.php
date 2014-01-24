<?php
session_start();
ob_start();
include('../include/config.php');
require_once('../include/ajax_pagination.php');
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
include "../include/ps_pagination.php";

extract($_REQUEST);
//debugerr($_REQUEST);


if(isset($_POST[submit]) && $_POST[submit] == 'Save') {
	for($i = 1; $i <=$total_rows; $i++) {
		$post_cuscode				=	$_POST["cuscode"];
		$post_salestransno			=	$_POST["salestransno"];
		$post_salesdate				=	$_POST["salesdate"];
		$post_prodcode				=	$_POST["prodcode_".$i];	
		$post_returnqty				=	$_POST["returnqty_".$i];	
		$post_priceval				=	$_POST["priceval_".$i];	
		$post_valueval				=	$_POST["valueval_".$i];
		$post_totval				=	$_POST["totval"];
		$post_cuscode				=	$_POST["cuscode"];
		$post_cre_id				=	$_POST["post_cre_id".$i];

		$sel_tgtcheck				=	"SELECT * from credit_note WHERE id = '$post_cre_id'";
		$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
		$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
		if($rowcnt_tgtcheck	> 0) {
			$update_cre		=	"UPDATE credit_note SET return_transno = '$post_salestransno', return_date = '$post_salesdate', Product_id = '$post_prodcode', quantity = '$post_returnqty', price_val = '$post_priceval', value_val = '$post_valueval', total_val = '$post_totval', customer_code = '$post_cuscode',updatedatetime = NOW() WHERE id = '$post_cre_id'";
			$res_cre		=	mysql_query($update_cre) or die(mysql_error());
		} else {
			echo $insert_cre		=	"INSERT INTO credit_note (return_transno,return_date,Product_id,quantity,price_val,value_val,total_val,customer_code,insertdatetime) VALUES ('$post_salestransno','$post_salesdate','$post_prodcode','$post_returnqty','$post_priceval','$post_valueval','$post_totval','$post_cuscode',NOW())";	mysql_query($insert_cre) or die(mysql_error());
		}	
	}
	header("location:creditnote.php?no=1");
}
?>
<!------------------------------- Form -------------------------------------------------->

<title>CREDIT NOTE</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>

<style type="text/css">
.headingscredit {
	background:#a09e9e;
	width:90%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}
#mytableformcredit {
	background:#fff;
	width:95%;
	margin-left:auto;
	margin-right:auto;
	height:480px;
}
.concredit {
	width:95%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}

.conscrollc {
	width:95%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	/* overflow:scroll;
	overflow-x:hidden; */
	height:auto;
}

.concredit th,.conscrollc th{
	width:22%;
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.concredit td,.conscrollc td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.concredit tbody tr:hover td,.conscrollc tbody tr:hover td{
	background: #c1c1c1;
}

.myaligncredit {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

#errormsgcredit{
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
#closebutton_cre{
  position:relative;
  top:-32px;
  color:transparent;
  right:-190px;
  border:none;
  clear:both;
  height:100%;
  min-height:100%;
  background:url(../images/close_pop.png) no-repeat;
 }
</style>

<body onload="bringreturntransprint('<?php echo $id; ?>');">
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingscredit">Credit Note</div>
<div id="mytableformcredit" align="center">

<form id="creditform" method="post" action="" onSubmit="return creditnotesave();">
<div class="mcf"></div>
<div class="concredit">
<!--Top Customer Name & Address-->
<table align="center" width="100%">
        <thead>
        <tr>
        <th align="center">Customer Name & Address</th>
        <th align="center">Date</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td align="left"><span id="cusname"></span><input type="hidden" name="cusaddress" id="cusaddress" value="" /></td>
        <td align="center"><?php echo date('Y-m-d'); ?></td>	
        </td>
        </tr>
        
        <tr>
        <td align="center"><strong>SALES RETURN TRANSACTION NO : </strong><?php echo $id; ?></td>
		<td align="center"><strong>DATED : </strong><span id="salesdatespan"></span><input type="hidden" readonly name="salesdate" id="salesdate" value=""/> </td>
        </tr>    
            
        </tbody>
       
</table>
</div> <!--con ending-->
<div class="clearfix"></div>
<div class="conscrollc">
<table align="center" width="100%">
        <thead>
        <tr>
        <th align="center" width="600px">Product</th>
        <th align="center">Quantity</th>
        <th align="center">Price</th>
        <th align="center">Value</th>
        </tr>
        </thead>
        <tbody id="creditreturnid">
        <tr>
        <td width="600px" colspan="4" align="center"><strong>No Records Found.</strong></td>
		
		<!--<td width="600px"></td>
			<td align="center">10</td>	
			<td align="center">200</td>
			<td align="center">value</td> -->
        </td>
        </tr>
        
        <tr>
        <td colspan="10"><span><strong style="margin-left:700px">Total Value: <img src='../images/currency.gif' style="vertical-align:bottom;" width="15px" height="15px" /></strong> <span id="totvalspan"> </span> <input type="hidden" name="totval" id="totval"  value="" /> <span> </td>
        </tr>        
        </tbody>
       
</table>
     </div> <!--con ending-->
	<span id="pagspan" ></span>


 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <!-- <input type="button" name="submit" id="submit" class="buttons" value="Save" onClick="return creditnotesave();" />&nbsp;&nbsp;&nbsp;&nbsp; -->
	  <span id="printopen" style="padding-left:0px;padding-top:10px;display:block;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
	  </td>
      </tr>
 </table> 
 <?php include("../include/error.php"); ?>
<div class="mcf"></div>
  <div id="errormsgcredit" style="display:none;" ><h3 align="center" class="myaligncredit"></h3><button id="closebutton_cre">Close</button></div> 
  </form>
 
  </div> <!--mytableform ending-->
</div><!-- mainarea ending-->
<?php include('../include/footer.php'); ?>