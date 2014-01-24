<?php
session_start();
ob_start();
require_once('../include/config.php');
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_REQUEST);

//brand, transaction_hdr, transaction_line, product, srbrand_incentive

$monthval				=	ltrim(date('m',strtotime($propmonths)),0);

$query_transhdr													=   "SELECT id,Transaction_Number,Date,Time,transaction_Reference_Number FROM transaction_hdr WHERE Date LIKE '$propyears-$monthval-%' AND DSR_Code = '$DSR_Code'";
//echo $query_transhdr;
//exit;
$res_transhdr													=   mysql_query($query_transhdr);
$transno_transhdr												=	array();
while($row_transhdr												=   mysql_fetch_assoc($res_transhdr)) {		
	$Transaction_Number											=	$row_transhdr[Transaction_Number];
	$query_returnline											=   "SELECT id,Transaction_Number FROM transaction_return_line WHERE Transaction_Number = '$Transaction_Number'";
	$res_returnline												=   mysql_query($query_returnline);
	$rowcnt_returnline											=   mysql_num_rows($res_returnline);

	if($rowcnt_returnline == 0) {
		$Transaction_Number_sales							=   $row_transhdr[Transaction_Number];
		if($row_transhdr[transaction_Reference_Number] !='' && $row_transhdr[transaction_Reference_Number] != '0') {
			$transaction_Reference_Number_cancel[]			=   $row_transhdr[transaction_Reference_Number];
			$transno_cancel_number[]						=   $row_transhdr[Transaction_Number];
		}
		$transhdr_result[]									=   $row_transhdr;
		$transhdrInfo[$row_transhdr[Transaction_Number]]	=   $row_transhdr;
		$transno_transhdr[]									=   $row_transhdr[Transaction_Number];
	}
}
 
//pre($transno_transhdr);	
//pre($transaction_Reference_Number_cancel);
//pre($transno_cancel_number);

foreach($transaction_Reference_Number_cancel AS $REFVALE){
	//echo $REFVALE		=	trim($REFVALE);
	//pre($transno_transhdr);
	//echo $arraysearchval		=	array_search($REFVALE,$transno_transhdr);
	//echo $REFVALE."++".pre($transno_transhdr)."<br>";
	if(array_search($REFVALE,$transno_transhdr) !== false) {
		//echo $REFVAL;
		$arraysearchval		=	array_search($REFVALE,$transno_transhdr);
		//echo $arraysearchval;
		unset($transno_transhdr[$arraysearchval]);
	} else {
		//echo $arraysearchval		=	array_search($REFVAL,$transno_transhdr);
		//echo "notin";
	}
}

//pre($transno_transhdr);
//exit;
//pre($transno_cancel_number);
foreach($transno_cancel_number AS $REFVALUE){
	if(array_search($REFVALUE,$transno_transhdr) !== false) {
		$arraysearchval		=	array_search($REFVALUE,$transno_transhdr);
		unset($transno_transhdr[$arraysearchval]);
	}
}

//pre($transno_transhdr);

//exit;
$transno_transhdr		=	array_unique($transno_transhdr);
$transno_Total			=	implode("','",$transno_transhdr);

$qry_brand="SELECT *, SUM(Sold_quantity) AS SOLQTY, br.brand AS BrandName, br.id AS BrandId, SUM(Price) FROM `transaction_line` AS tl LEFT JOIN product pd ON tl.Product_code = pd.Product_code LEFT JOIN brand br on pd.brand = br.id WHERE tl.Transaction_Number IN ('".$transno_Total."') AND tl.DSR_Code = '$DSR_Code' AND tl.Product_code != '' GROUP BY br.Brand";

//AND CASE WHEN sbi.monthval IS NOT NULL THEN sbi.monthval = '$monthval' ELSE TRUE END AND CASE WHEN sbi.DSR_Code IS NOT NULL THEN sbi.DSR_Code = '$DSR_Code' ELSE TRUE END AND CASE WHEN sbi.yearval IS NOT NULL THEN sbi.yearval = '$propyears' ELSE TRUE END 

//echo $qry_brand;
//exit;

$results_brand		=	mysql_query($qry_brand);
$num_rows			=	mysql_num_rows($results_brand);

$params				=	$DSR_Code."&".$propyears."&".$propmonths."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 10;  // Records Per Page

$Page = $strPage;
if(!$strPage)
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($num_rows<=$Per_Page)
{
$Num_Pages =1;
}
else if(($num_rows % $Per_Page)==0)
{
$Num_Pages =($num_rows/$Per_Page) ;
}
else
{
$Num_Pages =($num_rows/$Per_Page)+1;
$Num_Pages = (int)$Num_Pages;
}
if($sortorder == "")
{
	$orderby	=	"ORDER BY br.brand ASC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry_brand		.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//echo $qry_brand;
//exit;
$results_dsr = mysql_query($qry_brand) or die(mysql_error());
/********************************pagination***********************************/
?>
	<div class="con">
	<table width="100%">
	<thead>
	<tr>
		<th align="center" colspan="19">ALL BRANDS TARGET INCENTIVE</th>
	 </tr>
    <tr>
		<th align="left" colspan="19"><?php echo "Month & Year : &nbsp;&nbsp;&nbsp;".$propmonths."&nbsp; & ".$propyears."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;SR : &nbsp;&nbsp;".upperstate(getdbval($DSR_Code,'DSRName','DSR_Code','dsr')); ?></th>		
	</tr>
	<tr>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$DSR_Code."&".$propyears."&".$propmonths."&".$sortorderby."&br.brand"; ?>
		<th align="center" class="rounded" onClick="brandincviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >Brand Name <img src="../images/sort.png" width="13" height="13" /></th>
		<th align="center" style="width:10%">Brand Target
		 <table  width="100%"><tr><td>Units</td><td>Naira</td></tr></table>
		</th>
		<th align="center">Sold Quantity</th>
		<th align="center">Possible Incentive</th>
		<th align="center">Payable Incentive</th>
		<!-- <th align="right">Mod/Del</th> -->
	</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($num_rows)){
	$c=0;$cc=1;
	while($fetch = mysql_fetch_array($results_dsr)) {
	if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
	
	$BrandName					=	$fetch['BrandName'];
	$BrandId					=	$fetch['BrandId'];

	$qry_br_tgt					=	"SELECT * FROM `srbrand_incentive` WHERE DSR_Code = '$DSR_Code' AND monthval = '$monthval' AND yearval = '$propyears' AND Brand_id = '$BrandId' ";
	//echo $qry_br_tgt;
	//exit;
	$res_br_tgt					=	mysql_query($qry_br_tgt);
	$rowcnt_br_tgt				=	mysql_num_rows($res_br_tgt);
	
	$row_br_tgt					=	mysql_fetch_object($res_br_tgt);

	$TgtUnits					=	$row_br_tgt->target_units;
	$TgtNaira					=	$row_br_tgt->target_naira;

	$TgtUnits					=	$row_br_tgt->target_units;
	$TgtNaira					=	$row_br_tgt->target_naira;

	if($TgtUnits == '') {
		$TgtUnits		=	0;		
	}

	if($TgtNaira == '') {
		$TgtNaira		=	0;
	}
	$SolQty						=	$fetch['SOLQTY'];
	$TgtFlg						=	$row_br_tgt->targetFlag;

	$PossInc		=	($SolQty * $TgtNaira);

	if($TgtFlg == 0) {
		if($SolQty >= $TgtUnits) {
			$PayInc			=	($SolQty * $TgtNaira);
		} else {
			$PayInc			=	0;
		}
	}

	if($TgtFlg == 1) {
		$PayInc				=	($SolQty * $TgtNaira);
	}
	
	?>
	<tr>
		<td align="center"><?php echo $BrandName;?></td>
		<th align="center" style="width:10%">
		 <table width="100%" style="table-layout:fixed;">
			<tr>
				<td align="right" style="width:60px;"><?php echo number_format($TgtUnits); ?></td>
				<td align="right" style="width:60px;"><?php echo number_format($TgtNaira,2); ?></td>
			</tr>
		</table>
		</th>
		<td align="right"><?php echo number_format($SolQty); ?></td>
		<td align="right" <?php if($PayInc == 0) { ?> style="color:#FF0000;" <?php } else { ?> style="color:#008000;" <?php } ?> ><?php echo number_format($PossInc,2); ?></td>
		<td align="right"><?php echo number_format($PayInc,2); ?></td>
	</tr>
	<?php $c++; $cc++; }		 
	}else { ?>
		<tr>
			<td align='center' colspan='5'><b>No records found</b></td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
			<td style="display:none;" >Add Line2</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>
		</tr>
	<?php }  ?>
	</tbody>
	</table>
	 </div>   
	 <div class="paginationfile" align="center">
	 <table>
	 <tr>
	 <th class="pagination" scope="col">          
	<?php 
	if(!empty($num_rows)){
		rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'brandincviewajax');   //need to uncomment
	} else { 
		echo "&nbsp;"; 
	} ?>      
	</th>
	</tr>
	</table>
  </div>
  <span id="printopen" style="padding-left:380px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printbrandincajax');"></span>
	<form id="printbrandincajax" target="_blank" action="printbrandincajax.php" method="post">
		<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
		<input type="hidden" name="propmonths" id="propmonths" value="<?php echo $propmonths; ?>" />
		<input type="hidden" name="propyears" id="propyears" value="<?php echo $propyears; ?>" />
		<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
		<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
		<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
	</form>
<?php exit(0); ?>