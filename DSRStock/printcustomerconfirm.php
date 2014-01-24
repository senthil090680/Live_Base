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
$params=$DateVal."&".$DSR_Code;
if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] !='') {
	$nextrecval		=	"WHERE Date LIKE '%$DateVal%' AND dsr_id = '$DSR_Code'";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_REQUEST) && $_REQUEST !='')
{
	echo $qry="SELECT * FROM `cycle_assignment` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$results_dsr=mysql_query($qry);
$num_rows= mysql_num_rows($results_dsr);
//exit;

if($num_rows > 0) {
	$row = mysql_fetch_array($results_dsr); 
	$route_id				=	$row['route_id'];
	$location_id				=	$row['location_id'];

	$sel_locname			=	"SELECT location from location WHERE id = '$location_id'";
	$res_locname			=	mysql_query($sel_locname) or die(mysql_error());	
	$row_locname			=	mysql_fetch_array($res_locname);
	$location				=	$row_locname['location'];

	$sel_dsrid				=	"SELECT DSR_Code from dsr WHERE id = '$DSR_Code'";
	$res_dsrid				=	mysql_query($sel_dsrid) or die(mysql_error());	
	$row_dsrid				=	mysql_fetch_array($res_dsrid);
	$DSR_CodeVal			=	$row_dsrid['DSR_Code'];

	$sel_routecode			=	"SELECT route_code,route_desc from route_master WHERE id = '$route_id'";
	$res_routecode			=	mysql_query($sel_routecode) or die(mysql_error());	
	$row_routecode			=	mysql_fetch_array($res_routecode);
	$route_code				=	$row_routecode['route_code'];
	$route_name				=	$row_routecode['route_desc'];
}


//$address	= urlencode($resultArray[0][location_area]);
$address	= $location;
$zoom = 15;
$type = 'ROADMAP';
$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=true");
//echo $json;

$data = json_decode($json,true);

//pre($data);

//echo $data['results'][0]['geometry']['location']['lat']."<br>"; echo $data['results'][0]['geometry']['location']['lng']."<br>";

$map_status = $data['status'];
//exit(0);
//$data = file_get_contents("http://maps.google.com/maps/geo?output=csv&q=".urlencode($address));

if ($map_status == 'OK') {
	$lat = $data['results'][0]['geometry']['location']['lat'];
	$long = $data['results'][0]['geometry']['location']['lng'];
} else {
	//die();
}

//echo $DSR_CodeVal."~".$route_name."~".$location."~".$route_code."~";

$sel_cusdet					=	"SELECT id,Customer_Name,sequence_number,AddressLine1,AddressLine2,lga,City,contactperson,contactnumber from customer WHERE route = '$route_code'";
$res_cusdet					=	mysql_query($sel_cusdet) or die(mysql_error());	
$nor_dsr					=	mysql_num_rows($res_cusdet);
//$pager						=	new PS_Pagination($bd, $sel_cusdet,5,5);
//$results					=	$pager->paginate();


/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
$num_rows = $nor_dsr;

########### pagins

$Per_Page = 5;   // Records Per Page

$Page = $strPage;
if(!$strPage)
{
$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($nor_dsr<=$Per_Page)
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
$sel_cusdet.="  LIMIT $Page_Start , $Per_Page";
$res_cusdet = mysql_query($sel_cusdet) or die(mysql_error());
/********************************pagination***********************************/


?>
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>

<span style="float:left;" id="showviewbutton" ><input type="button" name="kdproduct" value="View Customer Confirmation" class="buttonsbig" onclick="window.location='customer_confirmation.php'"></span>
<div>
<table width="100%" align="left" id="productsadd">
 <tr>
  <td>
  <div <?php if($nor_dsr == 0) { ?> class="condailynorec" <?php } else { ?> class="condaily" <?php } ?> >
   <table id="sort" class="tablesorter" border="1" width="100%">
  <thead>
  <tr>
  <th align="center" colspan="8">Customer Details</th>
  </tr>
  <tr>
	<th align='center'>Sequence Number</th>
	<th class='rounded' align='center'>Customer Name</th>
	<th align='center'>Address Line 1</th>
	<th align='center'>Address Line 2</th>
	<th align='center'>LGA</th>
	<th align='center'>City</th>
	<th align='center'>Contact Person</th>
	<th align='center'>Contact Number</th>
  </tr>
  </thead>  
  <tbody id="productsadded">
	<?php $t = 1; 
	//if(isset($_REQUEST[DateVal]) && $_REQUEST[DateVal] != '') { 

	if($nor_dsr >0) {
		while($row_cusdet			=	mysql_fetch_array($res_cusdet)) {
		$cus_id						=	$row_cusdet['sequence_number'];
		$Customer_Name				=	$row_cusdet['Customer_Name'];
		$AddressLine1				=	$row_cusdet['AddressLine1'];
		$AddressLine2				=	$row_cusdet['AddressLine2'];
		$lga						=	$row_cusdet['lga'];
		$City						=	$row_cusdet['City'];
		$contactperson				=	$row_cusdet['contactperson'];
		$contactnumber				=	$row_cusdet['contactnumber'];
		?> 
			<tr>
				<td align='center' nowrap="nowrap"><?php if($cus_id != '') {
				echo $cus_id;
				} else {
					echo "&nbsp;"; 
				}?></td>
				<td align='center' nowrap="nowrap"><?php if($Customer_Name != '') {
				echo $Customer_Name;
				} else {
					echo "&nbsp;"; 
				}
				?></td>
				<td align='center' nowrap="nowrap"><?php if($AddressLine1 != '') {
				echo $AddressLine1;
				} else {
					echo "&nbsp;"; 
				} ?></td>
				<td align='center' nowrap="nowrap"><?php if($AddressLine2 != '') {
				echo $AddressLine2;
				} else {
					echo "&nbsp;"; 
				} ?></td>
				<td nowrap="nowrap" align='center'><?php if($lga != '') {
				echo $lga;
				} else {
					echo "&nbsp;"; 
				} ?></td>
				<td nowrap="nowrap" align='center'><?php if($City != '') {
				echo $City;
				} else {
					echo "&nbsp;"; 
				} ?></td>
				<td nowrap="nowrap" align='center'><?php if($contactperson != '') {
				echo $contactperson;
				} else {
					echo "&nbsp;"; 
				} ?> </td>
				<td nowrap="nowrap" align='center'><?php if($contactnumber != '') {
				echo $contactnumber;
				} else {
					echo "&nbsp;"; 
				} ?></td>
			</tr>
	<?php } } else { ?>
		<tr><td align='center' colspan="8">No Records Found.</td>
		<td style="display:none;" >Cust Name</td>
        <td style="display:none;" >Add Line1</td>
        <td style="display:none;" >Add Line2</td>
		<td style="display:none;" >LGA</td>
        <td style="display:none;" >City</th>
		<td style="display:none;" >Contact Person</td>
		<td style="display:none;" >Contact Number</td>
		
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
		if(!empty($nor_dsr)){ 
			$printvalue = 1;
		rend_cust_confirm($Num_Pages,$Page,$Prev_Page,$Next_Page,$params);

			/*
			//Display the link to first page: First
			echo $pager->renderFirst()."&nbsp; ";
			//Display the link to previous page: <<
			echo $pager->renderPrev();
			//Display page links: 1 2 3
			echo $pager->renderNav();
			//Display the link to next page: >>
			echo $pager->renderNext()."&nbsp; ";
			//Display the link to last page: Last
			echo $pager->renderLast(); } else{ echo "&nbsp;"; */
				
		} ?>      
		</th>
		</tr>
        </table>
      </div>
	  <span id="printopen" style="padding-left:650px;padding-top:10px;" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="hideprintbutton();"></span>
<?php //echo "~".$lat."~".$long."~".$printvalue;
exit(0);?>