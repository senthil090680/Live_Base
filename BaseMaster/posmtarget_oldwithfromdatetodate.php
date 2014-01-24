<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";

extract($_REQUEST);
//pre($_REQUEST);
//exit;
$KD_Code=getKDCode();
if(isset($_POST[submit]) && $_POST[submit] == 'Save') {	
	if(!isset($tgt_edit) && $tgt_edit != 'edit') {
		//pre($_REQUEST);
		//exit;
		//echo $overall_rowcnt;
		//exit;
		for($i = 1; $i <= $overall_rowcnt; $i++) {		
			//pre($_REQUEST);
			
			//echo "334";
			//exit;
			$post_posmval		=	$_POST["posmval_".$i];	
			$post_princval		=	$_POST["princval_".$i];
			$post_branval		=	$_POST["branval_".$i];
			$post_ctval			=	$_POST["ctval_".$i];
			$post_noofcus		=	$_POST["noofcus_".$i];			
			$post_units			=	$_POST["unitstgt_".$i];
			$post_fromdate		=	$_POST["fromdate_".$i];
			$post_todate		=	$_POST["todate_".$i];			
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];
			//exit;
			if($post_posmval == '') {
				$post_posmval		=	'';	
				$post_princval		=	'';
				$post_branval		=	'';
				$post_ctval			=	'';
				$post_noofcus		=	'';
				$post_units			=	'';
				$post_fromdate		=	'';
				$post_todate		=	'';
				$post_tgt_id		=	'';
				//pre($_REQUEST);
				//exit;
				continue;
			}

			//pre($_REQUEST);
			//echo "==".$i."<br/>";
			$sel_tgtcheck				=	"SELECT * from posmtarget WHERE productId = '$post_posmval' AND fromdate = '$post_fromdate' AND todate = '$post_todate'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE posmtarget SET KD_Code= '$KD_Code',productId = '$post_posmval', principalId = '$post_princval', brandId = '$post_branval', custypeId = '$post_ctval', noofcus = '$post_noofcus', unitval = '$post_units', fromdate = '$post_fromdate',todate='$post_todate',monthval = '',yearval='',updatedatetime = NOW() WHERE productId = '$post_posmval' AND fromdate = '$post_fromdate' AND todate = '$post_todate'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO posmtarget (KD_Code,principalId,brandId,productId,custypeId,noofcus,unitval,fromdate,todate,monthval,yearval,insertdatetime) VALUES ('$KD_Code','$post_princval','$post_branval','$post_posmval','$post_ctval','$post_noofcus','$post_units','$post_fromdate','$post_todate','','',NOW())";
				//exit;
				mysql_query($insert_tgt) or die(mysql_error());
				//exit;
			}
		}
		header("location:posmtargetview.php?no=1");

	} else {
		for($i = 1; $i <= $overall_rowcnt; $i++) {		
			//pre($_REQUEST);
			
			//echo "334";
			//exit;
			$post_posmval		=	$_POST["posmval_".$i];	
			$post_princval		=	$_POST["princval_".$i];
			$post_branval		=	$_POST["branval_".$i];
			$post_ctval			=	$_POST["ctval_".$i];
			$post_noofcus		=	$_POST["noofcus_".$i];			
			$post_units			=	$_POST["unitstgt_".$i];
			$post_fromdate		=	$_POST["fromdate_".$i];
			$post_todate		=	$_POST["todate_".$i];			
			$post_tgt_id		=	$_POST["tgt_qry_id_".$i];
			//exit;
			if($post_posmval == '') {
				$post_posmval		=	'';	
				$post_princval		=	'';
				$post_branval		=	'';
				$post_ctval			=	'';
				$post_noofcus		=	'';
				$post_units			=	'';
				$post_fromdate		=	'';
				$post_todate		=	'';
				$post_tgt_id		=	'';
				//pre($_REQUEST);
				//exit;
				continue;
			}

			//pre($_REQUEST);
			//echo "==".$i."<br/>";
			$sel_tgtcheck				=	"SELECT * from posmtarget WHERE productId = '$post_posmval' AND fromdate = '$post_fromdate' AND todate = '$post_todate'";
			$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
			$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
			if($rowcnt_tgtcheck	> 0) {
				$update_tgt		=	"UPDATE posmtarget SET KD_Code= '$KD_Code',productId = '$post_posmval', principalId = '$post_princval', brandId = '$post_branval', custypeId = '$post_ctval', noofcus = '$post_noofcus', unitval = '$post_units', fromdate = '$post_fromdate',todate='$post_todate',monthval = '',yearval='',updatedatetime = NOW() WHERE productId = '$post_posmval' AND fromdate = '$post_fromdate' AND todate = '$post_todate'";
				$res_tgt		=	mysql_query($update_tgt);
			} else {
				$insert_tgt		=	"INSERT INTO posmtarget (KD_Code,principalId,brandId,productId,custypeId,noofcus,unitval,fromdate,todate,monthval,yearval,insertdatetime) VALUES ('$KD_Code','$post_princval','$post_branval','$post_posmval','$post_ctval','$post_noofcus','$post_units','$post_fromdate','$post_todate','','',NOW())";
				//exit;
				mysql_query($insert_tgt) or die(mysql_error());
				//exit;
			}
		}
		header("location:posmtargetview.php?no=2");
	}
}

$query_prod 							=	"SELECT id FROM product WHERE product_type = 'POSM'";
$res_prod 								=	mysql_query($query_prod) or die(mysql_error());
$rowcnt_prod 							=	mysql_num_rows($res_prod);

$overall_rowcnt							=	$rowcnt_prod;


$sel_tgtsel							=	"SELECT * from posmtarget WHERE id='$id'";
$res_tgtsel							=	mysql_query($sel_tgtsel) or die(mysql_error());
$rowcnt_tgtsel						=	mysql_num_rows($res_tgtsel);
if($rowcnt_tgtsel	> 0) {
	while($row_tgtsel				=	mysql_fetch_array($res_tgtsel)) {
		$tgtsel[]					=	$row_tgtsel;
	}
}

?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">
.heading_report{
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
#mytableform_report{
	background:#fff;
	width:99%;
	margin-left:auto;
	margin-right:auto;
	height:480px;
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
	height:370px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:scroll;
}
#errormsgposmtgt {
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
.myalignposmtgt {
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

.align2 {
	padding-left:10px;
}

#span1 {
	width: 30px; 
	float:left;
  }
#span2 { 
    width: 30px; 
	float:right;
	}
	
#colors{
	background-color:#CCC;
}
  
</style>
<body >
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="heading_report">POSM TARGET</div>
<div id="mytableform_report" align="center">
<div class="mcf"></div>
<form method="post" action="" id="posmtgtform" onSubmit="return saveposmtgt('<?php echo $overall_rowcnt; ?>');">
<div class="mcf">
	<div class="condaily_routeplan">
		  <table border="1" width="100%">
			<thead>
			  <tr>
               	<th align="center" style="width:10%">Principal</th>
				<th align="center" style="width:10%">Brand</th>
				<th align="center" style="width:70%">POSM ITEM</th>
				<th align="center" style="width:10%" nowrap="nowrap">Customer Type</th>
				<th align="center" style="width:4%">No Of Cus(s)</th>
				<th align="center" style="width:4%">Units</th>
				<th align="center" style="width:4%">From Date</th>
				<th align="center" style="width:4%">To Date</th>
	   	  </tr>
		  </thead>
	     <tbody>		 
		<?php	for($k=1; $k<=$overall_rowcnt; $k++) { 
				$tgt_qry_id			=	$tgtsel[$k-1][0];
				$princ_qry_id		=	$tgtsel[$k-1][2];
				$bran_qry_id		=	$tgtsel[$k-1][3];
				$prod_qry_id		=	$tgtsel[$k-1][4];
				$ct_qry_id			=	$tgtsel[$k-1][5];
				$noofcus_qry_id		=	$tgtsel[$k-1][6];
				$units_qry_id		=	$tgtsel[$k-1][7];
				$fromdate_qry_id	=	$tgtsel[$k-1][8];
				$todate_qry_id		=	$tgtsel[$k-1][9];
		?>
         <tr>
         <td>
			<span id="princ_<?php echo $k; ?>">
				<?php 
				$query_princName 					=	"select principal from principal WHERE id = '$princ_qry_id'";
				$res_princName 						=	mysql_query($query_princName) or die(mysql_error());
				$rowcnt_princName 					=	mysql_num_rows($res_princName);
				if($rowcnt_princName > 0) {
					$row_princName 					=	mysql_fetch_array($res_princName);
					echo $princName 				=	upperstate($row_princName[principal]);
				}
				?>
			</span>
			<input type="hidden" name="princval_<?php echo $k; ?>" id="princval_<?php echo $k; ?>" value="<?php echo $princ_qry_id; ?>" />
			<input type="hidden" name="tgt_qry_id_<?php echo $k; ?>" id="tgt_qry_id_<?php echo $k; ?>" value="<?php echo $tgt_qry_id; ?>" />
		 </td>
		 <td><span id="bran_<?php echo $k; ?>"> 
					<?php
					$query_branName 					=	"select brand FROM brand WHERE id = '$bran_qry_id'";
					$res_branName 						=	mysql_query($query_branName) or die(mysql_error());
					$rowcnt_branName 					=	mysql_num_rows($res_branName);
					if($rowcnt_branName > 0) {
						$row_branName 					=	mysql_fetch_array($res_branName);
						echo $branName 					=	upperstate($row_branName[brand]);
					}
					?>

				</span> <input type="hidden" name="branval_<?php echo $k; ?>" id="branval_<?php echo $k; ?>" value="<?php echo $bran_qry_id; ?>" /> 		 
		</td>
		 <td><select name="posmval_<?php echo $k; ?>" id="posmval_<?php echo $k; ?>" onChange="getallforposm(this.value,'<?php echo $overall_rowcnt; ?>','<?php echo $k; ?>');">
			<option value="">---Select---</option>
		   <?php 
				$query_product 						=	"SELECT id,Product_code,Product_description1 FROM product WHERE product_type = 'POSM'";
				$res_product						=	mysql_query($query_product) or die(mysql_error());
				while($row_product = mysql_fetch_assoc($res_product)){?>
				<option value="<?php echo  $row_product['id']; ?>" <?php if($prod_qry_id == $row_product['id']) { echo "selected"; } ?> > <?php echo  upperstate($row_product['Product_description1']); ?></option>
			<?php }?>
		  </select>
		 </td>         
         <td><span id="ct_<?php echo $k; ?>"> 
					<?php
					$query_ctName 						=	"select customer_type FROM customer_type WHERE id = '$ct_qry_id'";
					$res_ctName 						=	mysql_query($query_ctName) or die(mysql_error());
					$rowcnt_ctName 						=	mysql_num_rows($res_ctName);
					if($rowcnt_ctName > 0) {
						$row_ctName 					=	mysql_fetch_array($res_ctName);
						echo $ctName 					=	upperstate($row_ctName[customer_type]);
					}
					?>

				</span> <input type="hidden" name="ctval_<?php echo $k; ?>" id="ctval_<?php echo $k; ?>" value="<?php echo $ct_qry_id; ?>" /> 	
		</td>
         <td><input readonly type="text" autocomplete="off" name="noofcus_<?php echo $k; ?>" id="noofcus_<?php echo $k; ?>" value="<?php echo $noofcus_qry_id; ?>" size="6"/></td>
		 <td><input type="text" autocomplete="off" name="unitstgt_<?php echo $k; ?>" id="unitstgt_<?php echo $k; ?>" value="<?php echo $units_qry_id; ?>" size="5" maxLength="10" /></td>
         <td><input type="text" autocomplete="off" size="9" readonly class="datepicker" name="fromdate_<?php echo $k; ?>" id="fromdate_<?php echo $k; ?>" value="<?php echo $fromdate_qry_id; ?>" /></td>
         <td><input type="text" autocomplete="off" size="9" readonly class="datepicker" name="todate_<?php echo $k; ?>" id="todate_<?php echo $k; ?>" value="<?php echo $todate_qry_id; ?>" /></td>
		</tr>
		<?php	
				$tgt_qry_id			=	'';
				$princ_qry_id		=	'';
				$bran_qry_id		=	'';
				$ct_qry_id			=	'';
				$prod_qry_id		=	'';
				$noofcus_qry_id		=	'';
				$units_qry_id		=	'';
				$fromdate_qry_id	=	'';
				$todate_qry_id		=	'';				
			} ?>
			<span><input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $overall_rowcnt; ?>" />
			<?php if($rowcnt_tgtsel  > 0) { ?>
			<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
			if($rowcnt_tgtsel  > 0) {
				echo "edit";
			}
			?>" />
			<?php } ?>
			</span>	
        </tbody>
		</table>	
		</div>        
</div>
<div class="mcf"></div>
	 <table width="50%" style="clear:both">
		 <tr align="center" height="10px;">
			 <td ><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
			<!--<input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;-->
			<input type="button" name="cancel" value="Cancel" class="buttons" onClick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="View" value="View" class="buttons" onClick="window.location='posmtargetview.php'"/>
			</td>
			 </td>
		 </tr>
	 </table>
</form>
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
	  <div id="errormsgposmtgt" style="display:none;"><h3 align="center" class="myalignposmtgt"></h3><button id="closebutton">Close</button></div>
    
     </div>
  </div>
</div>
<?php include('../include/footer.php'); ?>