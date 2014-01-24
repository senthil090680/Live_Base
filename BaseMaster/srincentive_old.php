<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])) {
	session_destroy();
	header("Location:../index.php");
}
require_once "../include/ajax_pagination.php";
extract($_REQUEST);

//pre($_POST);
//exit;
$KD_Code=getKDCode();
if(isset($_POST[submit]) && $_POST[submit] == 'Save') {
	$k						=	0;
	$monthval				=	trim(date('m'),0);
	$yearval				=	date('Y');

	if(!isset($tgt_edit) && $tgt_edit != 'edit') {
		$post_dsrcode				=	$_POST["srcode"];
		$customer_count		=	$_POST["customer_count"];
		$customer_naira		=	$_POST["customer_naira"];
		for($i = 0; $i < $overall_rowcnt; $i++) {

			$post_prodcode		=	$_POST["productname_".$i];	
			$post_units			=	$_POST["tgt_units_".$i];
			$post_naira			=	$_POST["tgt_naira_".$i];
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

			if($post_prodcode == '' && $post_dsrcode == '') {
				$post_prodcode		=	'';	
				$post_units			=	'';
				$post_naira			=	'';
				$post_tgt_id		=	'';
				continue;
			}

			$sel_tgtcheck				=	"SELECT * from sr_incentive WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE sr_incentive SET KD_Code= '$KD_Code',DSR_Code = '$post_dsrcode',customer_count = '$customer_count', customer_naira = '$customer_naira',  target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), Product_id = '$post_prodcode' WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO sr_incentive (KD_Code,DSR_Code,customer_count,customer_naira,monthval,yearval,target_units,target_naira,Product_id,insertdatetime) VALUES ('$KD_Code','$post_dsrcode','$customer_count','$customer_naira','$monthval','$yearval','$post_units','$post_naira','$post_prodcode',NOW())";
				
				mysql_query($insert_tgt) or die(mysql_error());
			}	
		}
		header("location:srincentiveview.php?no=1");

	} else {
		$post_dsrcode				=	$_POST["srcode"];
		$customer_count		=	$_POST["customer_count"];
		$customer_naira		=	$_POST["customer_naira"];
		for($i = 0; $i < $overall_rowcnt; $i++) {

			$post_prodcode		=	$_POST["productname_".$i];	
			$post_units			=	$_POST["tgt_units_".$i];
			$post_naira			=	$_POST["tgt_naira_".$i];
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

			if($post_prodcode == '' && $post_dsrcode == '') {
				$post_prodcode		=	'';	
				$post_units			=	'';
				$post_naira			=	'';
				$post_tgt_id		=	'';
				continue;
			}

			$sel_tgtcheck				=	"SELECT * from sr_incentive WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE sr_incentive SET KD_Code= '$KD_Code',DSR_Code = '$post_dsrcode',customer_count = '$customer_count', customer_naira = '$customer_naira',  target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), Product_id = '$post_prodcode' WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO sr_incentive (KD_Code,DSR_Code,customer_count,customer_naira,monthval,yearval,target_units,target_naira,Product_id,insertdatetime) VALUES ('$KD_Code','$post_dsrcode','$customer_count','$customer_naira','$monthval','$yearval','$post_units','$post_naira','$post_prodcode',NOW())";
				
				mysql_query($insert_tgt) or die(mysql_error());
			}	
		}
		header("location:srincentiveview.php?no=2");
	}
}

$query_prod 						=	"select id,Product_code,Product_description1,brand from product";
$res_prod 							=	mysql_query($query_prod) or die(mysql_error());
$rowcnt_prod 						=	mysql_num_rows($res_prod);
while($row_prod 					=	mysql_fetch_array($res_prod)) {
	$product_codearr[]				=	$row_prod[Product_code];
}

$sel_tgtsr							=	"SELECT * from sr_incentive WHERE id = '$id'";
$res_tgtsr							=	mysql_query($sel_tgtsr) or die(mysql_error());
$rowcnt_tgtsr						=	mysql_num_rows($res_tgtsr);
$rowcnt_tgtsel						=	0;	
if($rowcnt_tgtsr	> 0) {
	$row_tgtsr						=	mysql_fetch_array($res_tgtsr);
	$tgtsrmonth						=	$row_tgtsr[monthval];
	$tgtsryear						=	$row_tgtsr[yearval];
	$tgtsrDSR_Code					=	$row_tgtsr[DSR_Code];
	$tgtsrcustomer_count			=	$row_tgtsr[customer_count];
	$tgtsrcustomer_naira			=	$row_tgtsr[customer_naira];
	
	$sel_tgtsel						=	"SELECT * from sr_incentive WHERE monthval = '$tgtsrmonth' AND yearval = '$tgtsryear' AND DSR_Code = '$tgtsrDSR_Code'";
	$res_tgtsel						=	mysql_query($sel_tgtsel) or die(mysql_error());
	$rowcnt_tgtsel					=	mysql_num_rows($res_tgtsel);
	if($rowcnt_tgtsel	> 0) {
		while($row_tgtsel			=	mysql_fetch_array($res_tgtsel)) {
			$tgtsel[]				=	$row_tgtsel;
		}
	}
}
//pre($tgtsel);
?>
<style type="text/css">
.headingscredit{
	background:#a09e9e;
	width:95%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}
#top{
background:#fff;
width:95%;
margin-left:auto;
margin-right:auto;
height:110px;	
	
}
#mytableformcredit{
background:#fff;
width:95%;
margin-left:auto;
margin-right:auto;
height:480px;
}

.concredit {
	width:60%;
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
	overflow:scroll;
	overflow-x:hidden;
	height:280px;
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

#errormsgsrinc {
	display:none;
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
.myalignsrinc {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}
</style>
<body onLoad="getasmrsmvalues('<?php echo $tgtsrDSR_Code; ?>');">
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingscredit">SR INCENTIVE</div>
<div id="mytableformcredit" align="center">

<form id="srincentry" method="post" action="" onSubmit="return checksrincentry('<?php echo $rowcnt_prod; ?>');">
<div class="mcf"></div>
<div id="top">
<div style="float:left">
<table align="center" width="100%">

<tr height="20">
      <td>SR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
     <td>
		<?php if($rowcnt_tgtsel == 0) { ?>
        <select name="srcode" id="srcode" style="width:200px" onChange="getasmrsmvalues(this.value);">
        <option value="">----------------- Select -----------------</option>
        <?php 
        $query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
		$res_DSR 							=	mysql_query($query_DSR) or die(mysql_error());
		while($row_DSR = mysql_fetch_assoc($res_DSR)){?>
		<option value="<?php echo  $row_DSR['DSR_Code']; ?>" <?php if($tgtsrDSR_Code == $row_DSR['DSR_Code']) { echo "selected"; } ?> > <?php echo  $row_DSR['DSRName'] ?></option>
		<?php } ?>
        </select>
		<?php } else {  ?>
			<span id="srid"><input type="hidden" autocomplete="off" name="srcode" id="srcode" value="<?php echo $tgtsrDSR_Code; ?>" /><?php echo getdbval($tgtsrDSR_Code,'DSRName','DSR_Code','dsr'); ?></span>						
		<?php } ?>
       </td>
     </tr>
	 <tr>
       <td>ASM : </td>
       <td><span id="asmid">&nbsp;</span></td>
     </tr>     
	 <tr>
       <td>RSM : </td>
	   <td><span id="rsmid">&nbsp;</span></td>
     </tr>
     
</table>


</div>

<div class="concredit" style="float:right">
<!--Top Customer Name & Address-->
<table align="center" width="100%">
	<thead>
	<tr>
	<th align="center" colspan="10" style="background-color:#FFF">Effective Coverage/Month of <?php echo date('F'). " & ".date('Y'); ?> </th>
	</tr>
	<tr>
	<th align="center">Customer Count</th>
	<th align="center">Naira</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="customer_count" id="customer_count" value="<?php echo $tgtsrcustomer_count; ?>" /></td>
	<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="customer_naira" id="customer_naira" value="<?php echo $tgtsrcustomer_naira; ?>" /></td>	
	</td>
	</tr>
	</tbody>       
</table>
</div> <!--con ending-->
</div> <!--top ending-->

<div class="conscrollc">
<table align="center" width="100%">
        <thead>
        <tr>
        <th align="center" style="width:80%">Product</th>
        <th align="center">Target<br/>Units</th>
        <th  align="center">Naira/Unit</th>
        </tr>
        </thead>
        <tbody>
        <?php
			//pre($product_codearr);
			//echo $rowcnt_prod;
		if($rowcnt_prod > 0) {
			for($k=0; $k<$rowcnt_prod; $k++) {			
			?>
			<tr>
			<td width="731" style="width:80%"><input type="hidden" autocomplete="off" name="productname_<?php echo $k; ?>" id="productname_<?php echo $k; ?>" value="<?php echo getdbval($product_codearr[$k],'id','Product_code','product'); ?>" /><span><?php echo getdbval($product_codearr[$k],'Product_description1','Product_code','product'); //PARAMETERS ARE TABLE QUERY COLUMN VALUE, TABLE RESULT COLUMN, TABLE QUERY COLUMN NAME, TABLE NAME ?></span></td>
			<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="tgt_units_<?php echo $k; ?>" id="tgt_units_<?php echo $k; ?>" value="<?php echo $tgtsel[$k][8];?>" /></td>	
			<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="tgt_naira_<?php echo $k; ?>" id="tgt_naira_<?php echo $k; ?>" value="<?php echo $tgtsel[$k][9]; ?>" /></td>
			</tr>
			<?php 
			} 	//for loop ?>
			<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_prod; ?>" />
			<?php if($rowcnt_tgtsel > 0) { ?>
			<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
			if($rowcnt_tgtsel > 0) {
				echo "edit";
			} ?>" />
		<?php  } //edit if loop
		
			} // if loop ?>
        </tbody>
       
</table>
     </div> <!--con ending-->
<?php require_once "../include/error.php"; ?>
<div id="errormsgsrinc" style="display:none;"><h3 align="center" class="myalignsrinc"></h3><button id="closebutton">Close</button></div>
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" class="buttons" value="Clear" id="clear"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="button" name="View" value="View" class="buttons" onclick="window.location='srincentiveview.php'"/>
	  </td>
      </tr>
 </table>       
 </form>
  </div> <!--mytableform ending-->
</div><!-- mainarea ending-->
<?php include('../include/footer.php'); ?>