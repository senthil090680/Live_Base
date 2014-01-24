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
$params=$return_id."&".$sortorder."&".$ordercol;;
if(isset($return_id) && isset($return_id)) {
	 $where					=	"WHERE return_transno ='$return_id'";
}
?>
<?php
$query_track		=	"SELECT * FROM credit_note $where";
$res_track			=	mysql_query($query_track)or die(mysql_error());
$num_rows			=	mysql_num_rows($res_track);

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page = 7;   // Records Per Page

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
	$orderby	=	"ORDER BY id ASC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
echo $query_track.=" $orderby LIMIT $Page_Start , $Per_Page";
//exit;
$results_dsr = mysql_query($query_track) or die(mysql_error());
/********************************pagination***********************************/


$query_salesdate			=	"SELECT * FROM transaction_hdr WHERE Transaction_Number ='$return_id'";
$res_salesdate				=	mysql_query($query_salesdate)or die(mysql_error());
$rowcnt_salesdate			=	mysql_num_rows($res_salesdate);

if($rowcnt_salesdate > 0 ) {
	$row_salesdate			=	mysql_fetch_array($res_salesdate);
	$salesdate				=	$row_salesdate['Date'];
	$customer_id			=	$row_salesdate['Customer_code'];

	$query_cusid				=	"SELECT * FROM customer WHERE customer_code = '$customer_id'";
	$res_cusid				=	mysql_query($query_cusid)or die(mysql_error());
	$rowcnt_cusid			=	mysql_num_rows($res_cusid);

	if($rowcnt_cusid > 0 ) {
		$row_cusid				=	mysql_fetch_array($res_cusid);
		$cusname				=	$row_cusid['Customer_Name'];
		$AddressLine1			=	$row_cusid['AddressLine1'];
		$AddressLine2			=	$row_cusid['AddressLine2'];
		$AddressLine3			=	$row_cusid['AddressLine3'];
		$cus_details			=	ucwords(strtolower($cusname. " & ".  $AddressLine1 ." ". $AddressLine2 ." ".  $AddressLine3));
	}
}
if($num_rows > 0) {
   $slno	=	($Page-1)*$Per_Page + 1;
   $r							=	1;
   $totpriceval					=	0;
   while($row_track	=	mysql_fetch_array($results_dsr)) { ?>		
		<tr>
			<td align='center'><?php
			$query_prodname			=	"SELECT Product_code,Product_description1,id FROM product WHERE id = '$row_track[Product_id]'";			
			$res_prodname			=	mysql_query($query_prodname) or die(mysql_error());
			$row_prodname			=	mysql_fetch_array($res_prodname);
			$prodname				=	$row_prodname['Product_description1'];
			$prod_id				=	$row_prodname['id'];
			$prod_code				=	$row_prodname['Product_code'];

   
			echo $prodname; ?>
			<input type="hidden" name="prodcode_<?php echo $r; ?>" id="prodcode_<?php echo $r; ?>" value="<?php echo $prod_id; ?>" />
			</td>
			<td align='center'><?php echo $row_track[quantity]; ?><input type="hidden" name="returnqty_<?php echo $r; ?>" id="returnqty_<?php echo $r; ?>" value="<?php echo $row_track[quantity]; ?>" /></td>
			<?php
			$priceval				=	$row_track['price_val']; ?>
			<td align='center'><span><?php echo $priceval; ?></span><input type="hidden" name="priceval_<?php echo $r; ?>" id="priceval_<?php echo $r; ?>" value="<?php echo $priceval; ?>" /></td>

			<td align='center'><span id="valuevalspan_<?php echo $r; ?>" ><?php //echo $CustomerName; 
			$totpriceval	+=	$priceval * $row_track[quantity];
			echo $valueval	=	$priceval * $row_track[quantity];
			?></span><input type="hidden" name="valueval_<?php echo $r; ?>" id="valueval_<?php echo $r; ?>" value="<?php echo $valueval; ?>" /></td>			
		</tr>
		
		<?php if($r == $num_rows) { ?>
		<tr>
        <td nowrap="nowrap" colspan="3" align="right"><strong style="margin-left:700px">Total Value: <img src='../images/currency.gif' style="vertical-align:bottom;" width="15px" height="15px" /></strong></td>
		
		<td align="center"> <span id="totvalspan"><?php echo $totpriceval; ?></span> <input type="hidden" name="totval" id="totval"  value="<?php echo $totpriceval; ?>" /> 
		<input type="hidden" name="cuscode" id="cuscode" value="<?php echo $customer_id; ?>" />
		<input type="hidden" name="total_rows" id="total_rows" value="<?php echo $num_rows; ?>" />
		</td>
        </tr>
		<?php } ?>
	<?php 
		$k++; $slno++; $r++; }  // WHILE LOPP
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
	<?php } 

echo "~".$salesdate."~".$cus_details."~";
?>
<div class="paginationfile" align="center">
         <table>
         <tr>
		 <th class="pagination" scope="col">          
		<?php 
		if(!empty($num_rows)){
			//rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'creditnoteajax');	//pagination comes here
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div><?php echo "~"; ?>
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