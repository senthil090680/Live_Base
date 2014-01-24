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
<div >
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($num_rows == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily" <?php } ?> >
   <table id="sort" class="tablesorter" width="100%">
  <thead>
	<tr>
		<!--<th align='center'>Currency</th>-->
		<th class='rounded' align='center'>Customer</th>
		<th class='rounded' align='center'>DSR Name</th>
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
			$Customer_Name					=	upperstate(getdbval($row_salcolval['customerCode'],'Customer_Name','customer_code','customer'));
			$cycleOpenBalDue				=	$row_salcolval['cycleOpenBalDue'];
			$dayOpenBalDue					=	$row_salcolval['dayOpenBalDue'];
			$daySaleValue					=	$row_salcolval['daySaleValue'];
			$dayCollValue					=	$row_salcolval['dayCollValue'];
			$dayBalDue						=	$row_salcolval['dayBalDue'];
	?> 
		<tr>
			<td align='center'><?php echo $Customer_Name; ?></td>
			<td align='center'><?php echo $DSRName; ?></td>
			<td align='center'><?php echo $cycleOpenBalDue; ?></td>
			<td align='center'><?php echo $dayOpenBalDue; ?></td>
			<td align='center'><?php echo $daySaleValue; ?></td>
			<td align='center'><?php echo $dayCollValue; ?></td>
			<td align='center'><?php echo $dayBalDue; ?></td>
		</tr>
	<?php } // WHILE LOOP
	} // IF LOOP
	else { ?>
		<tr>
			<td align='center' colspan="6">No Records Found.</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Cust Name</td>
			<td style="display:none;" >Add Line1</td>	
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
<span style="display:inline;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>">
<span id="printopen" style="padding-left:380px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printcusbaldueajax');"></span>
<!-- <span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span> -->
</span>
<form id="printcusbaldueajax" target="_blank" action="printcusbaldueajax.php" method="post">
	<input type="hidden" name="cusCode" id="cusCode" value="<?php echo $cusCode; ?>" />
	<input type="hidden" name="TransNo" id="TransNo" value="<?php echo $TransNo; ?>" />
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
</form>
<?php exit(0);?>