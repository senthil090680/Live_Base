<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
$params=$DateVal."&".$DSR_Code;
if(isset($_GET[DateVal]) && $_GET[DateVal] !='') {
	$nextrecval		=	"WHERE Date LIKE '$DateVal%' AND DSR_Code = '$DSR_Code'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	$qry="SELECT * FROM `sale_and_collection` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$num_rows= mysql_num_rows($results_dsr);

/********************************pagination start***********************************/
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
/********************************pagination***********************************/

$findvehdevqry					=	$qry;
$results_findvehdev				=	mysql_query($findvehdevqry);
$rowcnt_findvehdev				=	mysql_num_rows($results_findvehdev);
if($rowcnt_findvehdev > 0){
	$row_findvehdev				=	mysql_fetch_array($results_findvehdev);
	$vehicle_codeval			=	$row_findvehdev['Vehicle_Code'];
	$Device_Codeval				=	$row_findvehdev['device_code'];
	$vehicle_name				=	getvehicleval($vehicle_codeval,'vehicle_desc','vehicle_code');
}
echo $vehicle_name."~".$Device_Codeval."~";
?>       
<div >
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($nor_dsr == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily" <?php } ?> >
   <table id="sort" class="tablesorter" width="100%">
  <thead>
	<tr>
		<!--<th align='center'>Currency</th>-->
		<th class='rounded' align='center'>Total Sales Value</th>
		<th align='center'>Total Collection Value</th>
		<th align='center'>Balance Due</th>
	</tr>
  </thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	//if(isset($_GET[DateVal]) && $_GET[DateVal] != '') { 

	if($num_rows >0) {
		while($row_salcolval				=	mysql_fetch_array($results_dsr)) {
			$currency						=	$row_salcolval['currency'];
			$total_sale_value				=	$row_salcolval['total_sale_value'];
			$total_collection_value			=	$row_salcolval['total_collection_value'];
			$device_code					=	$row_salcolval['device_code'];
			$Vehicle_Code					=	$row_salcolval['Vehicle_Code'];
	?> 
		<tr>
			<!-- <td align='center'><?php echo $currency; ?></td> -->
			<?php $BalDue	=	($total_sale_value) - ($total_collection_value); ?>
			<td align='center'>
				<span style="cursor:pointer;cursor:hand;color:#4285F4;" onClick="bringDaySalesTrans('<?php echo $DateVal; ?>')" >
				<?php echo $total_sale_value; ?></span></td>
			<td align='center'><?php echo $total_collection_value; ?></td>
			<td align='center'><?php echo $BalDue; ?></td>
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
<span id="printopen" style="padding-left:0px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printsalescolajax');"></span>
<!-- <span style="padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span> -->
</span>
<form id="printsalescolajax" target="_blank" action="printsalescolajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
	<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>