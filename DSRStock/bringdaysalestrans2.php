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
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$nextrecval		=	"WHERE Date LIKE '$DateVal%'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	$qry="SELECT Transaction_Number,Customer_code,Net_Sale_value,Collection_Value,Balance_Due_Value,DSR_Code FROM `transaction_hdr` $where";
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
		<th class='rounded' align='center'>Invoice No</th>
		<th align='center'>Customer</th>
		<th align='center'>DSR Name</th>
		<th align='center'>Invoice Value</th>
		<th align='center'>Receipt Value</th>
		<th align='center'>Balance Due</th>
	</tr>
  </thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	//if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] != '') { 

	if($num_rows >0) {
		while($row_salcolval				=	mysql_fetch_array($results_dsr)) {
			$Transaction_Number				=	$row_salcolval['Transaction_Number'];
			$Customer_code					=	$row_salcolval['Customer_code'];
			$Customer_Name					=	upperstate(getdbval($row_salcolval['Customer_code'],'Customer_Name','customer_code','customer'));
			$DSR_Name						=	getdbval($row_salcolval['DSR_Code'],'DSRName','DSR_Code','dsr');
			$Net_Sale_value					=	$row_salcolval['Net_Sale_value'];
			$Collection_Value				=	$row_salcolval['Collection_Value'];
			$Balance_Due_Value				=	$row_salcolval['Balance_Due_Value'];
	?> 
		<tr>
			<td align='center'><?php echo $Transaction_Number; ?></td>
			<td align='center'>
				<span style="cursor:pointer;cursor:hand;color:#4285F4;" onClick="bringCusBalDue('<?php echo $Customer_code; ?>','<?php echo $Transaction_Number; ?>','<?php echo $DSR_Name; ?>','<?php echo $DateVal; ?>')" >
				<?php echo $Customer_Name; ?></span></td>
			<td align='center'><?php echo $DSR_Name; ?></td>
			<td align='center'><?php echo $Net_Sale_value; ?></td>
			<td align='center'><?php echo $Collection_Value; ?></td>
			<td align='center'><?php echo $Balance_Due_Value; ?></td>
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
			rend_salcolajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params); //pagination comes here
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>

<span style="display:inline;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>">
<span id="printopen" style="padding-left:380px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printsalestranscolajax');"></span>
<!-- <span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span> -->
</span>
<form id="printsalestranscolajax" target="_blank" action="printsalestranscolajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
</form>
<?php exit(0);?>