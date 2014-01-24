<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";

//ini_set("display_errors",false);
//echo ini_get("display_errors");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);

//debugerr($_POST);
$KD_Code=getKDCode();
if(isset($_POST[submit]) && $_POST[submit] == 'Save') {
	$k						=	0;
	if(!isset($tgt_edit) && $tgt_edit != 'edit') {
		for($i = 1; $i <=$overall_rowcnt; $i++) {

			$post_prodcode		=	$_POST["productname_".$i];	
			$post_dsrcode		=	$_POST["dsrname_".$i];	
			$post_rsm			=	$_POST["rsmval_".$i];	
			$post_asm			=	$_POST["asmval_".$i];
			$post_brand			=	$_POST["brandval_".$i];	
			$post_units			=	$_POST["tgt_units_".$i];
			$post_naira			=	$_POST["tgt_naira_".$i];
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

			if($post_prodcode == '' && $post_dsrcode == '') {
				$post_prodcode		=	'';	
				$post_dsrcode		=	'';	
				$post_rsm			=	'';	
				$post_asm			=	'';
				$post_brand			=	'';	
				$post_units			=	'';
				$post_naira			=	'';
				$post_tgt_id		=	'';
				continue;
			}

			$sel_tgtcheck				=	"SELECT * from target_setting WHERE id = '$post_tgt_id'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE target_setting SET KD_Code= '$KD_Code',rsm_id = '$post_rsm', asm_id = '$post_asm', brand_id = '$post_brand', target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), SR_Code = '$post_dsrcode', Product_id = '$post_prodcode' WHERE id = '$post_tgt_id'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO target_setting (KD_Code,rsm_id,asm_id,SR_Code,brand_id,Product_id,target_units,target_naira,insertdatetime) VALUES ('$KD_Code','$post_rsm','$post_asm','$post_dsrcode','$post_brand','$post_prodcode','$post_units','$post_naira',NOW())";
				
				mysql_query($insert_tgt) or die(mysql_error());
			}	
		}
		header("location:targetentry.php?no=1");

	} else {
		for($i = 1; $i <=$overall_rowcnt; $i++) {

			$post_prodcode		=	$_POST["productname_".$i];	
			$post_dsrcode		=	$_POST["dsrname_".$i];	
			$post_rsm			=	$_POST["rsmval_".$i];	
			$post_asm			=	$_POST["asmval_".$i];
			$post_brand			=	$_POST["brandval_".$i];	
			$post_units			=	$_POST["tgt_units_".$i];
			$post_naira			=	$_POST["tgt_naira_".$i];
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];
			

			if($post_prodcode == '' && $post_dsrcode == '') {
				$post_prodcode		=	'';	
				$post_dsrcode		=	'';	
				$post_rsm			=	'';	
				$post_asm			=	'';
				$post_brand			=	'';	
				$post_units			=	'';
				$post_naira			=	'';
				$post_tgt_id		=	'';
				continue;
			}

			$sel_tgtcheck				=	"SELECT * from target_setting WHERE id = '$post_tgt_id'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE target_setting SET KD_Code = '$KD_Code',rsm_id = '$post_rsm', asm_id = '$post_asm', brand_id = '$post_brand', target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), SR_Code = '$post_dsrcode', Product_id = '$post_prodcode' WHERE id = '$post_tgt_id'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO target_setting (KD_Code,rsm_id,asm_id,SR_Code,brand_id,Product_id,target_units,target_naira,insertdatetime) VALUES ('$KD_Code','$post_rsm','$post_asm','$post_dsrcode','$post_brand','$post_prodcode','$post_units','$post_naira',NOW())";
				
				mysql_query($insert_tgt) or die(mysql_error());
			}
		}
		header("location:targetentry.php?no=2");
	}
}

$sel_tgtsel							=	"SELECT * from target_setting";
$res_tgtsel							=	mysql_query($sel_tgtsel) or die(mysql_error());
$rowcnt_tgtsel						=	mysql_num_rows($res_tgtsel);
if($rowcnt_tgtsel	> 0) {
	while($row_tgtsel				=	mysql_fetch_array($res_tgtsel)) {
		$tgtsel[]					=	$row_tgtsel;
	}
}
//debugerr($tgtsel);
//echo $tgtsel[0][3];
?>
<style type="text/css">
.heading_report {
	background:#a09e9e;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
	clear:both;
}
#mytableform_report {
	background:#fff;
	width:99%;
	margin-left:auto;
	margin-right:auto;
	height:500px;
}
.alignment_report{
width:96%;
padding-left:20px;
margin-left:10px;
font-size:16px;
}
.condaily_routeplan th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}
.condaily_routeplan td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}
.condaily_routeplan tbody tr:hover td {
	background: #c1c1c1;
}
.condaily_routeplan{
	width:100%;
	text-align:left;
	height:400px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
}
#errormsgtgt {
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
.myaligntgt {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}
.buttons_new{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#31859C;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:160px;
	height:15px;
}
.buttons_gray {
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#A09E9E;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:240px;
	height:15px;
}
</style>
<body>
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="heading_report">TARGET ENTRY</div>
<div id="mytableform_report" align="center">
<?php
$query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
$res_DSR 							=	mysql_query($query_DSR) or die(mysql_error());
$rowcnt_DSR 						=	mysql_num_rows($res_DSR);

$query_prod 						=	"select id,Product_code,Product_description1,brand from product";
$res_prod 							=	mysql_query($query_prod) or die(mysql_error());
$rowcnt_prod 						=	mysql_num_rows($res_prod);

$overall_rowcnt						=	$rowcnt_prod * $rowcnt_DSR;
?>
<form method="post" action="" id="routemasterplan" onSubmit="return savetgt('<?php echo $overall_rowcnt; ?>');">
<table width="100%" >
<tr>
<td>
<fieldset class="alignment_report">
  <legend><strong>Target Entry</strong></legend>
  
  <table width="100%"> 
  <tr>
	<td colspan="7">
		<div class="condaily_routeplan">
		  <table border="1">
			<thead>
			  <tr>
				<th align="center" style="width:8%" height="28" nowrap="nowrap">RSM NAME</th>
				<th align="center" style="width:8%" nowrap="nowrap">ASM NAME</th>

				<th align="center" style="width:8%" >SR NAME</th>

				<th align="center" style="width:8%">BRAND</th>

				<th align="center" style="width:58%">PRODUCT</th>

				<th align="center" style="width:10%">
					<table>
						<tr>
							<td align="center" colspan="2">TARGET</td>
						</tr>
						<tr>
							<td>UNITS</td>
							<td>NAIRA</td>
						</tr>				
					</table>				
				</th>
			  </tr>
		  </thead>
		  <tbody>
			<?php 
			
			for($k=1; $k<=$overall_rowcnt; $k++) { 
				$tgt_qry_id			=	$tgtsel[$k-1][0];
				$rsm_qry_id			=	$tgtsel[$k-1][2];
				$asm_qry_id			=	$tgtsel[$k-1][3];
				$sr_qry_id			=	$tgtsel[$k-1][4];
				$brand_qry_id		=	$tgtsel[$k-1][5];
				$prod_qry_id		=	$tgtsel[$k-1][6];
				$units_qry_id		=	$tgtsel[$k-1][7];
				$naira_qry_id		=	$tgtsel[$k-1][8];
				
				?>
			  <tr>
				<td align="center" style="width:8%"><span id="rsm_<?php echo $k; ?>">
					<?php 
					$query_RSMName 						=	"select DSRName from rsm_sp WHERE id = '$rsm_qry_id'";
					$res_RSMName 						=	mysql_query($query_RSMName) or die(mysql_error());
					$rowcnt_RSMName 					=	mysql_num_rows($res_RSMName);
					if($rowcnt_RSMName > 0) {
						$row_RSMName 					=	mysql_fetch_array($res_RSMName);
						echo $RSMName 					=	$row_RSMName[DSRName];
					}
					?>
				</span>
				<input type="hidden" name="rsmval_<?php echo $k; ?>" id="rsmval_<?php echo $k; ?>" value="<?php echo $rsm_qry_id; ?>" />
				<input type="hidden" name="tgt_qry_id_<?php echo $k; ?>" id="tgt_qry_id_<?php echo $k; ?>" value="<?php echo $tgt_qry_id; ?>" />
				
				
				</td>
				<td align="center" style="width:8%"><span id="asm_<?php echo $k; ?>"> 
					<?php
					$query_ASMName 						=	"select DSRName from asm_sp WHERE id = '$asm_qry_id'";
					$res_ASMName 						=	mysql_query($query_ASMName) or die(mysql_error());
					$rowcnt_ASMName 					=	mysql_num_rows($res_ASMName);
					if($rowcnt_ASMName > 0) {
						$row_ASMName 					=	mysql_fetch_array($res_ASMName);
						echo $ASMName 					=	$row_ASMName[DSRName];
					}
					?>

				</span> <input type="hidden" name="asmval_<?php echo $k; ?>" id="asmval_<?php echo $k; ?>" value="<?php echo $asm_qry_id; ?>" /> </td>
				<td align="center" style="width:8%"><span id="sr_<?php echo $k; ?>">
				<select class="dsrname" name="dsrname_<?php echo $k; ?>" id="dsrname_<?php echo $k; ?>" onChange="gettgtasmrsm(this.value,'<?php echo $overall_rowcnt; ?>','<?php echo $k; ?>');">
					<option value="">---Select---</option>
					<?php 
					$query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
					$res_DSR 							=	mysql_query($query_DSR) or die(mysql_error());
					while($row_DSR = mysql_fetch_assoc($res_DSR)){?>
					<option value="<?php echo  $row_DSR['DSR_Code']; ?>" <?php if($sr_qry_id == $row_DSR['DSR_Code']) { echo "selected"; } ?> > <?php echo  $row_DSR['DSRName'] ?></option>
					<?php }?> 
				</select> 
		
				</span></td>

				<td align="center" style="width:8%"> <input type="hidden" name="brandval_<?php echo $k; ?>" id="brandval_<?php echo $k; ?>" value="<?php echo $brand_qry_id; ?>" />
				
				<span id="brand_<?php echo $k; ?>">
					<?php
					$query_BrandName 						=	"select brand from brand WHERE id = '$brand_qry_id'";
					$res_BrandName 							=	mysql_query($query_BrandName) or die(mysql_error());
					$rowcnt_BrandName 						=	mysql_num_rows($res_BrandName);
					if($rowcnt_BrandName > 0) {
						$row_BrandName 						=	mysql_fetch_array($res_BrandName);
						echo $BrandName 					=	$row_BrandName[brand];
					}
					?>

				</span></td>

				<td align="center" style="width:58%"><span id="product_<?php echo $k; ?>">
				<select class="dsrname" name="productname_<?php echo $k; ?>" id="productname_<?php echo $k; ?>" onChange="gettgtbrand(this.value,'<?php echo $overall_rowcnt; ?>','<?php echo $k; ?>');">
					<option value="">---Select---</option>
					<?php 
					$query_prod 						=	"select id,Product_code,Product_description1 from product ORDER BY Product_description1 ASC";
					$res_prod 							=	mysql_query($query_prod) or die(mysql_error());
					while($row_prod = mysql_fetch_assoc($res_prod)){?>
					<option value="<?php echo  $row_prod['id']; ?>" <?php if($prod_qry_id == $row_prod['id']) { echo "selected"; } ?> > <?php echo  $row_prod['Product_description1'] ?></option>
					<?php }?> 
				</select>
				</span></td>

				<td align="center" style="width:10%"><span id="target_<?php echo $k; ?>"> 
						<table border="1">
							<tr>
								<td><input type="text" size="5" maxlength="5" autocomplete="off" name="tgt_units_<?php echo $k; ?>" id="tgt_units_<?php echo $k; ?>" value="<?php echo $units_qry_id; ?>" /></td>
								<td><input type="text" size="5" maxlength="5" autocomplete="off" name="tgt_naira_<?php echo $k; ?>" id="tgt_naira_<?php echo $k; ?>" value="<?php echo $naira_qry_id; ?>" /></td>
							</tr>				
						</table>
					</span>
				</td>
			  </tr>
			<?php 
				$rsm_qry_id			=	'';
				$asm_qry_id			=	'';
				$sr_qry_id			=	'';
				$brand_qry_id		=	'';
				$prod_qry_id		=	'';
				$units_qry_id		=	'';
				$naira_qry_id		=	'';
				
				} ?>
			<span><input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $overall_rowcnt; ?>" />
			<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
			if($rowcnt_tgtsel  > 0) {
				echo "edit";
			}
			?>" />
			</span>
		 </tbody>
		</table>
		</div>
	 </td>
   </tr>
  </table>
</fieldset>
</td>
</tr> 
</table>
	 <table width="50%" style="clear:both">
		 <tr align="center" height="10px;">
			 <td ><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
				 <?php if($rowcnt_tgtsel  == 0) { ?>
				 <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
				<?php } ?>
				 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
				 </td>
			 </td>
		 </tr>
	 </table>
</form>
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
	  <div id="errormsgtgt" style="display:none;"><h3 align="center" class="myaligntgt"></h3><button id="closebutton">Close</button></div>    
     </div>
  </div>
</div>
<?php include('../include/footer.php');?>