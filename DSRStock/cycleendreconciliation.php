<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
include "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
EXTRACT($_POST);
$id=$_REQUEST['id'];

$sel_stoadj	=	"select id,stock_adjustment_reason from stock_adjustment_reason";
$res_stoadj	=	mysql_query($sel_stoadj) or die(mysql_error());
if(mysql_num_rows($res_stoadj) > 0) {
	while($row_stoadj	=	mysql_fetch_assoc($res_stoadj)) {
		$stock_adj[]	=	$row_stoadj[id]."^".$row_stoadj[stock_adjustment_reason];
	}
}

$stockadj_val	=	implode("&",$stock_adj);
//pre($stock_adj);
//exit;

if($_GET['id']!=''){
if($_POST['submit']=='Save'){
$sel="select * from product where Product_code ='$Product_code'";
$sel_query=mysql_query($sel);
if(mysql_num_rows($sel_query)=='0') {
$Effective_from=date("Y-m-d",strtotime($Effective_from));
$UOM1='Pieces';	
$UOM2='Pieces';
$Uom_conversion=1;
$sql=("UPDATE product SET 
          Product_code= '$Product_code', 
          Product_description1='$Product_description1', 
          Product_description2='$Product_description2',
		  UOM1='$UOM1',
		  UOM2='$UOM2',
		  Uom_conversion='$Uom_conversion',
		  product_type='$product_type',
		  Focus='$Focus',
		  Effective_date='$Effective_date'
		  WHERE id = $id");
mysql_query( $sql);
header("location:productMaster.php?no=2");
}
else{
header("location:productMaster.php?no=18");
}
}
}
elseif($_POST['submit']=='Save'){
if($Product_code=='' || $Product_description1==''  || $Product_description2=='' || $product_type=='' || $Focus =='')
{
header("location:productMaster.php?no=9");exit;
}
else{
$sel="select * from product where Product_code ='$Product_code'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$Effective_from=date("Y-m-d",strtotime($Effective_from));	
		$active='active';
		$UOM1='Pieces';
		$UOM2='Pieces';
		$Uom_conversion=1;
		$sql="INSERT IGNORE INTO `product`(`Product_code`,`Product_description1`,`Product_description2`,`UOM1`,
`UOM2`,`Uom_conversion`,`product_type`,`Focus`,`Effective_date`)
values('$Product_code','$Product_description1','$Product_description2','$UOM1','$UOM2','$Uom_conversion','$product_type','$Focus','$Effective_date')";
mysql_query($sql);
        header("location:productMaster.php?no=1");
		}
		else {
		header("location:productMaster.php?no=18");
		}
}
}
$id=$_GET['id'];
$list=mysql_query("select * from product where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$Product_code = $row['Product_code'];
	$Product_description1 = $row['Product_description1'];
	$Product_description2 = $row['Product_description2'];
	$UOM1 = $row['UOM1'];
	$UOM2 = $row['UOM2'];
	$Uom_conversion = $row['Uom_conversion'];
	$product_type = $row['product_type'];
	$Focus = $row['Focus'];
	$Effective_date = $row['Effective_date'];
}
?>
<link type="text/css" rel="stylesheet" href="../css/popup.css" />
<style type="text/css">
#errorcyclestoadj {
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

.mycyclestoadj {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}


#closebutton{
	min-height:100%;
}

#ajaxloadid {
	top:300px;
	left:600px;
	position:fixed;
	display:none;
}

.condaily_prod{
	width:100%;
	text-align:left;
	height:200px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
}
.condaily_prod th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}

.condaily_prod td {
	padding:2px 5px 0 5px;
	background:#fff; !important;
	border-collapse:collapse;
	padding-left:10px;
	color:#000; 
	font-size:14px;
}

.condaily_prod {
	background: #c1c1c1;
}
.stockAdjustPopup {
	top:150px;
	left:170px;
	width:75%;
	height:350px;
	background:#EEEEEE;
	position:fixed;
	margin:0 auto;
	display:none;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:2px 2px 2px 2px;
	color:#fff;
}

.printcheck{
	color:#000;
	/* background: #c1c1c1; */
	padding-left:180px;
	white-space:nowrap;
}

.printcyclePopup {
	top:250px;
	left:370px;
	width:45%;
	height:85px;
	background:#EEEEEE;
	position:fixed;
	margin:0 auto;
	display:none;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:10px;
	color:#fff;
}

.commonPopupInner {
	color:#000;
	/* background: #c1c1c1; */
	padding-left:180px;
	white-space:nowrap;
}

.commonPopupClass {
	top:250px;
	left:420px;
	width:35%;
	height:85px;
	background:#EEEEEE;
	position:fixed;
	margin:0 auto;
	display:none;
	border-bottom:2px solid #A09E9E;
	z-index:3;
	border-radius:10px;
	color:#fff;
}

.myaligntable4{
	margin-left:auto;
	margin-right:auto;
}
#mytablecycle {
	background:#fff;
	width:90%;
	margin-left:auto;
	margin-right:auto;
	height:250px;
}
#mainareacycle{
	width:100%;
	height:580px;
	background:#ebebeb;
}
.condaily{
width:100%;
text-align:left;
height:200px;
border-collapse:collapse;
background:#a09e9e;
margin-left:auto;
margin-right:auto;
border-radius:10px;
overflow:scroll;
overflow-x:hidden;
}

</style>
<div id="mainareacycle">
<form action="" method="post" id="validationcycle">
<div class="mcf"></div>
<div align="center" class="headingcycle">Cycle End Reconciliation</div>

<div id="mytablecycle" align="center">
<fieldset class="alignment">
  <legend><strong>Cycle End Reconciliation</strong></legend>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr height="28px">
    <td class="align">SR Name*</td>
    <td><select name="DSR_Code" id="DSR_Code" onChange="loadDSRCycle(this.value);" autocomplete="off">
	<option value="">--- Select ---</option>
	<?php $sel_dsr		=	"SELECT id,DSRName,DSR_Code from dsr GROUP BY DSRName";
	$res_dsr			=	mysql_query($sel_dsr) or die(mysql_error());	
	while($row_dsr		= mysql_fetch_array($res_dsr)){ ?>
	<option value="<?php echo $row_dsr[DSR_Code]; ?>" <?php if($dsr_id == $row_dsr[DSR_Code]) { echo "selected"; } ?> ><?php echo ucwords(strtolower($row_dsr[DSRName])); ?></option>
	<?php } ?>
	</select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class="align">SR Code</td>
	<td>&nbsp;</td>
    <td><input type="text" name="DSR_Val" id="DSR_Val" readonly size="10" value="<?php echo $DSR_Code;  ?>" maxlength="10"  autocomplete='off'/></td>
  </tr>
    
  <tr height="28px">  
    <td class="align" >Cycle Start Date</td>
    <td><input type="text" name="cycleStartDate" id="cycleStartDate" size="20" value="<?php echo $cycleStartDate; ?>" readonly maxlength="10"  autocomplete='off'/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class="align">Cycle End Date*</td>
	<td>&nbsp;</td>
    <td><input type="text" name="cycleEndDate" id="cycleEndDate" size="20" onChange="loadDSRCycleDate(this.value);" value="<?php if(isset($cycleEndDate) && $cycleEndDate != '') { echo $cycleEndDate; } else { echo date('Y-m-d H:i:s'); } ?>" readonly maxlength="10" autocomplete='off'/></td>
  </tr>

  <tr height="28px">
    <td class="align">Device Name</td>
    <td><input type="text" name="deviceName" id="deviceName" readonly size="10" value="<?php echo $deviceName;  ?>" maxlength="10"  autocomplete='off'/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class="align">Device Code</td>
	<td>&nbsp;</td>
    <td><input type="text" name="deviceCode" id="deviceCode" readonly size="10" value="<?php echo $deviceCode; ?>" maxlength="10" autocomplete='off'/></td>
  </tr>

  <tr height="28px">
    <td class="align">Vehicle Name</td>
    <td><input type="text" name="vehicleName" id="vehicleName" readonly size="10" value="<?php echo $vehicleName;  ?>" maxlength="10"  autocomplete='off'/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class="align">Vehicle Code</td>
	<td>&nbsp;</td>
    <td><input type="text" name="vehicleCode" id="vehicleCode" readonly size="10" value="<?php echo $vehicleCode; ?>" maxlength="10" autocomplete='off'/></td>
  </tr>
 
<tr height="28px">
    <td class="align">UOM</td>
    <td><input type="text" name="UOM" id="UOM" readonly size="10" value="<?php echo $UOM;  ?>" maxlength="10"  autocomplete='off'/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class="align">Currency </td>
	<td><img src='../images/currency.gif' width="15px" height="15px"/></td>
    <td><input type="text" name="currency" id="currency" readonly size="10" value="<?php echo $currency; ?>" maxlength="10" autocomplete='off'/></td>
</tr>
</table>
</fieldset>

<fieldset class="alignment">
  <legend><strong>Sale Value Reconciliation</strong></legend>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr height="28px">
    <td nowrap="nowrap">Last Week Shortage Value&nbsp;</td>
    <td nowrap="nowrap"><input type="text" name="lastweekshort" id="lastweekshort" readonly size="10" value="" maxlength="10"  autocomplete='off' style="text-align:right;" /></td>
    <td class="align" nowrap="nowrap">Total Net Sale Value&nbsp;</td>
    <td nowrap="nowrap"><input type="text" name="netSaleValue" id="netSaleValue" readonly size="10" value="<?php echo $netSaleValue;  ?>" maxlength="10"  autocomplete='off'/ style="text-align:right;" ></td>
  </tr>
  <tr height="28px">
    <!-- <td nowrap="nowrap">Total Deposited Value&nbsp;</td> -->
	<td nowrap="nowrap">KD Amount Received&nbsp;</td>
    <td nowrap="nowrap"><input type="text" name="depositValue" id="depositValue" readonly size="10" value="<?php echo $depositValue;  ?>" maxlength="10"  autocomplete='off' style="text-align:right;"/></td>
	<td class="align" nowrap="nowrap" >Shortage&nbsp;</td>
    <td nowrap="nowrap"><input type="text" name="shortageVal" id="shortageVal" readonly size="10" value="<?php echo $shortageVal; ?>" maxlength="10" autocomplete='off'/ style="text-align:right;"></td>
  </tr>
</table>
</fieldset>
</div>
<?php include("../include/error.php");?>
<div class="mcf"></div>    
<div id="containercycle">
        <?php
         //Check whether product is assigned to any masters
		if($_GET['delID']!=''){
	    $id = $_GET['delID'];
		//Check product is Assigned to kd product
		$p_sql="select a.*,b.* from product as a,kd_product as b where a.Product_code ='$Product_code' AND b.Product_code ='$id'";
		$resp=mysql_query($p_sql);
		$cnt=mysql_num_rows($resp);
		if($cnt=='1'){
        header("location:productMaster.php?no=43"); 
		}
		elseif($_GET['delID']!=''){
		//Check product is Assigned to product scheme master
	$ps_sql="select c.*,d.* from product as c,product_scheme_master as d where c.Product_code='$Product_code' AND d.Product_code='$id'";
		$resps=mysql_query($ps_sql);
		$pnt=mysql_num_rows($resps);
		if($pnt=='1'){
        header("location:productMaster.php?no=44"); 
		  }
		else{
	   //Check product is Assigned to price master
 $pm_sql="select e.*,f.* from product as e, price_master as f where e.Product_code='$Product_code' AND f.Product_code='$id'";
		$respm=mysql_query($pm_sql);
		$snt=mysql_num_rows($respm);
		if($snt=='1'){
        header("location:productMaster.php?no=45"); 
		  }
		   }
		 }
		}
		if($_GET['id']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['id'];
		$query = "DELETE FROM `product` WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:productMaster.php?no=3");
		}
		 }
		?> 
		<?php
		if($_GET['submit']!='')
		{
			$qry="SELECT * FROM `cycle_flag` WHERE id = ''";
		}
		else
		{ 
			$qry="SELECT * FROM `cycle_flag` WHERE id = ''";
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,4,4);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="condaily">
        <table width="100%">
        <thead>
		<tr>
		<th class="rounded">S NO</th>
		<th>Product Name</th>
		<th>Cycle Start Loaded Quantity</th>
		<th>Daily Total Loaded Quantity</th>
		<th>Total Sold Quantity</th>
		<th>Total Cancelled Quantity</th>
		<!-- <th>Total Sales Returned Quantity</th> -->
		<th>Market Return Quantity</th>
		<th>Product Sales Value</th>
		<th>SR To Return Quantity (DSR)</th>
		<th>KD Received Quantity</th>
		<!-- <th>SR Returned Quantity (KD)</th> -->
		<th>Shortage</th>
		</tr>
        </thead>
        <tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php $Product_code = $fetch['Product_code'];
		$sel_pname		=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
		$res_pname		=	mysql_query($sel_pname) or die(mysql_error());	
		$row_pname		=	mysql_fetch_array($res_pname);
		echo $row_pname['Product_description1']; ?></td>
		<td><?php echo $fetch['Product_description1']."<br>".$fetch['Product_description2'].".";?></td>
		<td><?php echo $fetch['product_type'];?></td>
       	<td><?php echo $fetch['Focus'];?></td>        
        </tr>
		<?php $c++; $cc++; }
		} else { ?>
		<tr>
			<td align='center' colspan='13'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >LGA</td>
			<td style="display:none;" >City</td>
			<td style="display:none;" >Contact Person</td>
			<td style="display:none;" >Contact Number</td>
			<td style="display:none;" >City</td>
			
		</tr>						
		<?php } ?>
		</tbody>
		</table>
        </div>
	 </div>
	 <div class="mcf"></div>
		 <table style="margin-left:auto;margin-right:auto">
		<tr>
			<td colspan="8" nowrap="nowrap" align="center">
			<input type="hidden" name="allproddetails" id="allproddetails" value="" />
			<input type="hidden" name="alladjreason" id="alladjreason" value="<?php echo $stockadj_val; ?>" />
				
				<span id="printoption" style="display:none;"><!-- <input type="submit" name="print" id="print" class="buttons" value="Print" />&nbsp;&nbsp;&nbsp;&nbsp; -->
				<input type="button" name="confirm" id="confirm"  class="buttons" value="Confirm" onclick="return confirmcycle()";/>&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<!-- <input type="reset" name="reset" id="Clear"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp; -->
				<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
			</td>
		</tr>
	</table>	
	   </form>
		<form id="printpage" method="post" action="printcycleend.php" >
			<input type="hidden" name="printval" id="printval" value="" />
		</form>

		<div style="padding-bottom:4px;"></div>
	   	<div id="errormsgcycle" ><h3 align="center" class="myaligncycle"></h3><button id="closebutton_blue">Close</button></div>
		<div class="mcf"></div>
		<div id="errormsgcycleajx" ><h3 align="center" class="myaligncycleajx"></h3><button id="closebutton">Close</button></div>
	 </div>
</div>
<div id="ajaxloadid"><img src="../images/loading.gif" /></div>
<div id="backgroundChatPopup"></div>
<?php include('../include/footer.php');?>