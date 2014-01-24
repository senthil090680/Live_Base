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
$id=$_REQUEST['id'];
//echo $id;
//exit;
if($_REQUEST['Challan_Number']!='')
{
	$var = @$_REQUEST['Challan_Number'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `dsr_collection` where Challan_Number like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *  FROM `dsr_collection`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);

$params			=	$Challan_Number."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 12;   // Records Per Page

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
	$orderby	=	"ORDER BY id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
//$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
$qry.=" $orderby ";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<title>COLLECTION DEPOSITED</title>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<link type="text/css" href="../css/edit.css" rel="stylesheet" />


<table width="100%" border="1" style="border-collapse:collapse">
<thead>
<tr>
	<th nowrap="nowrap">SL No</th>
	<?php //echo $sortorderby;
	if($sortorder == 'ASC') {
		$sortorderby = 'DESC';
	} elseif($sortorder == 'DESC') {
		$sortorderby = 'ASC';
	} else {
		$sortorderby = 'DESC';
	}
	$paramsval	=	$Challan_Number."&".$sortorderby."&Bank_Name"; ?>
	<th nowrap="nowrap" class="rounded" onClick="colviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Bank Name<img src="../images/sort.png" width="13" height="13" /></th>
	<th nowrap="nowrap">DSR Name</th>
    <th nowrap="nowrap">Transaction Number</th>
	<th nowrap="nowrap">Challan Number</th>
	<th nowrap="nowrap">Challan Date</th>
	<th nowrap="nowrap">Currency</th>
	<th nowrap="nowrap">Amount Deposited</th>
	<th nowrap="nowrap">Total Amount</th>
</tr>
</tr>
</thead>
<tbody>
<?php
if(!empty($num_rows)){
	$slno	=	($Page-1)*$Per_Page + 1;
$c=0;$cc=1;
while($fetch = mysql_fetch_array($results_dsr)) {
if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
$id= $fetch['id'];
?>
<tr>
	<td><?php echo $slno; ?></td>
	<td><?php echo $fetch['Bank_Name'];?></td>
    <td><?php echo getdbval($fetch['DSR_Code'],'DSRName','DSR_Code','dsr');?></td>
	<td><?php echo $fetch['Transaction_number'];?></td>
	<td><?php echo $fetch['Challan_Number'];?></td>
	<td><?php echo $fetch['Challan_Date'];?></td>
	<td><?php echo $fetch['Currency'];?></td>
	<td align="right"><?php echo number_format($fetch['Amount_Deposited'],2);?></td>
	<td align="right"><?php echo number_format($fetch['Total_Amount'],2);?></td>	
</tr>
<?php $c++; $cc++; $slno++; }		 
}else{  ?>
	<tr>
		<td align='center' colspan='8'><b>No records found</b></td>
	</tr>
<?php } ?>
</tbody>
</table>
 </div>   
 <div class="paginationfile" align="center">
 <table>
 <tr>
 <th class="pagination" scope="col">          
<?php 
if(!empty($num_rows)){
	
} else { 
	echo "&nbsp;"; 
} ?>      
</th>
</tr>
</table>
<span id="printopen"<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php exit(0); ?>