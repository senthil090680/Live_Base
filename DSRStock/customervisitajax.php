<?php
include "../include/config.php";
include "../include/ajax_pagination.php";
error_reporting(E_ALL & ~(E_NOTICE) & ~(E_WARNING)); 
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
EXTRACT($_REQUEST);
if($k == '' || !isset($k)) {
	$k = 1;
}
$params=$DateVal."&".$DSR_Code."&".$k."&".$sortorder."&".$ordercol;
if(isset($DateVal) && isset($DSR_Code)) {
	 $where					=	"WHERE CVT.DSR_Code='$DSR_Code' AND Date LIKE '%$DateVal%'";
}
?>
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div class="condailytrack">
  <table>
<?php $query_track		=	"SELECT CVT.id AS CVTID, CVT.DSR_Code AS CVTDSR, Date , CUS.Customer_Name, CVT.Sequence_Number, CVT.Customer_Code, Check_In_Time, Checkin_GPS, Check_Out_Time, Checkout_GPS, check_out_id  FROM customer_visit_tracking AS CVT LEFT JOIN customer AS CUS ON CVT.Customer_Code = CUS.customer_code $where";
	$res_track			=	mysql_query($query_track)or die(mysql_error());
	$num_rows			=	mysql_num_rows($res_track);

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 8;   // Records Per Page

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
	$orderby	=	"ORDER BY CVT.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$query_track.=" $orderby LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($query_track) or die(mysql_error());
/********************************pagination***********************************/

	?>
  <thead>
	<tr>
		<th align='center'>Track Number</th>
		<th class='rounded' align='center'>Sequence Number</th>
		<th align='center'>Customer Code</th>
		<?php //echo $sortorderby;
		if($sortorder == 'ASC') {
			$sortorderby = 'DESC';
		} elseif($sortorder == 'DESC') {
			$sortorderby = 'ASC';
		} else {
			$sortorderby = 'DESC';
		}
		$paramsval	=	$DateVal."&".$DSR_Code."&".$k."&".$sortorderby."&Customer_Name"; ?>
		<th align='center' onClick="pag_cusvisitajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');"><span style="cursor:hand;cursor:pointer;"> Customer Name <img src="../images/sort.png" width="13" height="13" /></span></th>
		<th align='center'>Check In Time</th>
		<th align='center'>Check In GPS</th>
		<th align='center'>Check Out Time</th>
		<th align='center'>Check Out GPS</th>
		<th align='center'>Check Out Reason</th>
	</tr>
</thead>  
  <tbody id="productsadded">
   <?php if($num_rows > 0) {
   $slno	=	($Page-1)*$Per_Page + 1;
   while($row_track	=	mysql_fetch_array($results_dsr)) { ?>		
		<tr>
			<td align='center'><?php echo $slno; ?></td>
			<td align='center'><?php echo $row_track[Sequence_Number]; ?></td>
			<td align='center'><?php echo $row_track[Customer_Code]; 
				/*$query_CustomerName			=	"SELECT Customer_Name FROM customer WHERE customer_code = '$row_track[Customer_Code]'";			
				$res_CustomerName			=	mysql_query($query_CustomerName) or die(mysql_error());
				$row_CustomerName			=	mysql_fetch_array($res_CustomerName);
				$CustomerName				=	$row_CustomerName['Customer_Name'];*/
			
			?></td>
			<td align='center'><?php //echo $CustomerName; 
			echo ucwords(strtolower($row_track[Customer_Name]));
			?></td>
			<td align='center'><?php echo $row_track[Check_In_Time]; ?></td>
			<td align='center'><?php echo $row_track[Checkin_GPS]; ?></td>
			<td align='center'><?php echo $row_track[Check_Out_Time]; ?> </td>
			<td align='center'><?php echo $row_track[Checkout_GPS]; ?></td>
			<td align='center'><?php 
				$query_reason				=	"SELECT reason FROM check_out_reason WHERE id = '$row_track[check_out_id]'";			
				$res_reason					=	mysql_query($query_reason) or die(mysql_error());
				$row_reason					=	mysql_fetch_array($res_reason);
				$reason						=	$row_reason['reason'];		
				echo $reason; ?>
			</td>
		</tr>
	<?php 
		$k++; $slno++; }  // WHILE LOPP
		} // IF LOOP
		else { ?>
				<tr>
					<td colspan="9" align='center'>No Records Found</td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
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

<div class="paginationfile" align="center">
         <table>
         <tr>
		 <th class="pagination" scope="col">          
		<?php 
		if(!empty($num_rows)){
			rend_cusvisitajaxpagination($Num_Pages,$Page,$Prev_Page,$Next_Page,$params); //pagination comes here
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>
<table width="50%" style="clear:both">
	<tr align="center" height="20px;">
		<td>&nbsp;&nbsp;&nbsp;</td>
	</tr>
 </table>
 <span id="printopen" style="padding-left:0px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printcustrackviewajax');"></span>
<form id="printcustrackviewajax" target="_blank" action="printcustrackviewajax.php" method="post">
	<input type="hidden" name="DateVal" id="DateVal" value="<?php echo $DateVal; ?>" />
	<input type="hidden" name="DSR_Code" id="DSR_Code" value="<?php echo $DSR_Code; ?>" />
	<input type="hidden" name="k" id="k" value="<?php echo $k; ?>" />
	<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
	<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
</form>
<?php exit(0);?>