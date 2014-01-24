<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
$params=$DateVal;
if(isset($_REQUEST[TransNo]) && $_REQUEST[TransNo] !='') {
	$nextrecval		=	"WHERE customerCode LIKE '$cusCode%' AND Transaction_Number = '$TransNo'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT customerCode,cycleStart,Transaction_Number,cycleOpenBalDue,dayOpenBalDue,daySaleValue,dayCollValue,dayBalDue FROM `customerbaldownload` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$num_rows= mysql_num_rows($results_dsr);

/********************************pagination start***********************************/

/*

$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page =5;   // Records Per Page

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
$qry.="  LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());

*/

/********************************pagination***********************************/
?>   

<title>SALES & COLLECTIONS WITH CUSTOMERS</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>
<link type="text/css" rel="stylesheet" href="../css/popup.css" />

<style type="text/css">
.SalesTransPop {
	margin:0 auto;
	display:none;
	background:#A09E9E;
	color:#fff;
	width:800px;
	height:230px;
	position:fixed;
	left:250px;
	top:250px;
	border:1px solid #EEEEEE;
	z-index:2;
	border-radius:5px 5px 5px 5px;
}

.condaily {
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.condaily th {
	width:22%;
	/*padding:2px 5px 0 5px;*/
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.condaily td {
	/*padding:2px 5px 0 5px;*/
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.condaily tbody tr:hover td {
	background: #c1c1c1;
}
</style>

<div >
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily" <?php } ?> >
   <table id="sort" class="tablesorter" width="100%">
  <thead>
  <tr>
		<th align="left" colspan="15">
			<div >SALES & COLLECTIONS WITH CUSTOMERS  &nbsp;&nbsp;&nbsp;&nbsp; DATE : <?php echo $DateVal; ?> &nbsp;&nbsp;&nbsp;&nbsp; TRANSACTION NO : <?php echo $TransNo; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CUSTOMER NAME : <?php echo getdbval($cusCode,'Customer_Name','customer_code','customer'); ?> </div>
		</th>
	</tr>
	<tr>
		<!--<th align='center'>Currency</th>-->
		<th class='rounded' align='center'>Customer</th>
		<th align='center'>Cycle Open Bal Due</th>
		<th align='center'>Day Open Bal Due</th>
		<th align='center'>Day Sale Value</th>
		<th align='center'>Day Collection Value</th>
		<th align='center'>Day Balance Due</th>
	</tr>
  </thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	//if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] != '') { 

	if($num_rows >0) {
		while($row_salcolval				=	mysql_fetch_array($results_dsr)) {
			$Transaction_Number				=	$row_salcolval['Transaction_Number'];
			$Customer_code					=	$row_salcolval['customerCode'];
			$Customer_Name					=	getdbval($row_salcolval['customerCode'],'Customer_Name','customer_code','customer');
			$cycleOpenBalDue				=	$row_salcolval['cycleOpenBalDue'];
			$dayOpenBalDue					=	$row_salcolval['dayOpenBalDue'];
			$daySaleValue					=	$row_salcolval['daySaleValue'];
			$dayCollValue					=	$row_salcolval['dayCollValue'];
			$dayBalDue						=	$row_salcolval['dayBalDue'];
	?> 
		<tr>
			<td align='center'><?php echo $Customer_Name; ?></td>
			<td align='right'><?php echo number_format($cycleOpenBalDue,2); ?></td>
			<td align='right'><?php echo number_format($dayOpenBalDue,2); ?></td>
			<td align='right'><?php echo number_format($daySaleValue,2); ?></td>
			<td align='right'><?php echo number_format($dayCollValue,2); ?></td>
			<td align='right'><?php echo number_format($dayBalDue,2); ?></td>
		</tr>
	<?php } // WHILE LOOP
	} // IF LOOP
	else { ?>
		<tr>
			<td align='center' colspan="3">No Records Found.</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>		
		</tr>
		<?php } ?>
  </tbody>
   </table>
     </div>
   </td>
 </tr>
</table>
</div>
<div class="paginationfile" align="center">
         <table>
         <tr>
		 <th class="pagination" scope="col">          
		<?php 
		if(!empty($num_rows)){
			//rend_salcolajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params); //pagination comes here
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>
<span id="printopen" style="padding-left:580px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0);?>