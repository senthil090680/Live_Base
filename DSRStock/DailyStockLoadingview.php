<?php
session_start();
ob_start();
require_once('../include/header.php');
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
if($_REQUEST['Product_name']!='')
{
	$var		=	@$_REQUEST['Product_name'] ;
	$trimmed	=	trim($var);	
	$qry="SELECT *,DSL.id AS DSLID, DSL.Date AS DSLDATE, DSL.Product_code AS DSLPC FROM `dailystockloading` AS DSL LEFT JOIN product AS prod ON DSL.Product_code = prod.Product_code WHERE Product_description1 LIKE '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,DSL.id AS DSLID, DSL.Date AS DSLDATE, DSL.Product_code AS DSLPC FROM `dailystockloading` AS DSL LEFT JOIN product AS prod ON DSL.Product_code = prod.Product_code";
}
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);

$params			=	$Product_name."&".$sortorder."&".$ordercol;

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
	$orderby	=	"ORDER BY DSL.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<style type="text/css">
#containerdailysto {
	padding:0px;
	width:100%;
	margin-left:auto;
	margin-right:auto;
}
.conitems {
	width:100%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}
.conitems th {
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.conitems td {
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.conitems tbody tr:hover td {
	background: #c1c1c1;
}
</style>

<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">DAILY STOCK LOADING</h2></div> 
<div id="containerdailysto">

<span style="float:left;"><input type="button" name="kdproduct" value="Add Daily Stock Loading" class="buttonsbig" onclick="window.location='DailyStockLoading.php'"></span><span style="float:left;padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

 <div id="search">
	<input type="text" name="Product_name" id="Product_name" value="<?php echo $_REQUEST['Product_name']; ?>" autocomplete='off' placeholder='Search By Product Name'/>
	<input type="button" onclick="searchdailyviewajax('<?php echo $Page; ?>');" class="buttonsg" value="GO"/>
 </div>

        <div class="clearfix"></div>
        <?php
		if($_REQUEST['id']!=''){
			if($_REQUEST['submit']=='ConfirmDelete'){
				$query = "DELETE FROM `dailystockloading` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:DailyStockLoadingview.php?no=3");
			}
		 }		
		?>
        <div id="dailyviewajaxid">
			<div class="conitems">
			<table width="100%">
			<thead>
			<tr>
				<?php //echo $sortorderby;
				if($sortorder == 'ASC') {
					$sortorderby = 'DESC';
				} elseif($sortorder == 'DESC') {
					$sortorderby = 'ASC';
				} else {
					$sortorderby = 'DESC';
				}
				$paramsval	=	$Product_name."&".$sortorderby."&Product_description1"; ?>

				<th nowrap="nowrap" class="rounded" width="20%" onClick="dailyviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">Product Name<img src="../images/sort.png" width="13" height="13" /></th>
				<th width="5%">Product Code</th>
				<!--<th width="1%" align="center">UOM</th>-->
				<th width="5%" align="center">SR</th>
				<th width="5%" align="center">Veh. Reg. No.</th>
				<th width="4%" align="center">Load Seq. No.</th>
				<th width="4%" align="center">Loaded Qty</th>
				<th width="2%" align="center">Confirmed Qty</th>
				<!-- <th width="4%" align="center">Focus Flag</th>
				<th width="4%" align="center">Scheme Flag</th> -->
				<th width="4%" align="center" nowrap="nowrap">Date</th>
			<!--	<th width="4%" align="center">Product Type</th>-->
				<th width="4%" align="center">Edit/Del</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['DSLID'];
			//echo $fetch['id'];
			?>
			<tr>
			<td ><?php 
			$prod_name	=	$fetch['Product_description1'];
			if($prod_name == '') {
				$prod_name	=	getdbval($fetch['DSLPC'],'Product_description1','Product_code','customertype_product');
			}
			echo ucwords(strtolower($prod_name)); ?></td>
			<td align="center"><?php echo $fetch['DSLPC'];?></td>
			<!--<td align="center"><?php echo $fetch['UOM']; ?></td>-->
			<td align="center"><?php echo getdbval($fetch['DSR_Code'],'DSRName','DSR_Code','dsr');?></td>
			<td align="center"><?php echo getdbval($fetch['vehicle_code'],'vehicle_reg_no','vehicle_code','vehicle_master');?></td>
			<td align="center"><?php echo $fetch['Load_Sequence_No']; ?></th>
			<td align="right"><?php echo number_format($fetch['Loaded_Qty']);?></td>
			<td align="center"><?php echo number_format($fetch['Confirmed_Qty']);?></td>
			<!-- <td align="center"><?php echo $fetch['focus_Flag'];?></td>
			<td align="center"><?php echo $fetch['scheme_Flag'];?></td> -->
			<td align="center" nowrap="nowrap"><?php echo $fetch['DSLDATE'];?></td>
		<!--	<td align="center"><?php echo $fetch['ProductType'];?></td>-->
			<td align="right" align="center" nowrap="nowrap">

			<?php $curdate		=	date('Y-m-d');
				//$curdate		=	"2013-08-22";
				$datearr		=	explode(' ',$fetch[DSLDATE]);
			
				if($datearr[0] == $curdate) { 
			?>
			<a href="../DSRStock/DailyStockLoading.php?id=<?php echo $fetch['DSLID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="../DSRStock/DailyStockLoading.php?id=<?php echo $fetch['DSLID'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>

			<?php } else {  echo "No Edit/Del"; } ?>

			</td>
			</tr>
			<?php $c++; $cc++; }		 
			}else{  ?>
				<tr>
					<td align='center' colspan='13'><b>No records found</b></td>
					<td style="display:none;" >Cust Name</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Add Line1</td>
					<td style="display:none;" >Add Line2</td>
					<td style="display:none;" >LGA</td>
					<td style="display:none;" >LGA</td>
					<td style="display:none;" >City</th>
					<td style="display:none;" >Contact Person</td>
					<td style="display:none;" >Contact Number</td>
					<td style="display:none;" >Contact Number</td>
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
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'dailyviewajax');   //need to uncomment
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
		<span id="printopen" style="padding-left:470px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printdailyajax');"></span>
		<form id="printdailyajax" target="_blank" action="printdailyajax.php" method="post">
			<input type="hidden" name="Product_name" id="Product_name" value="<?php echo $Product_name; ?>" />
			<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
			<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
			<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
		</form>
	  </div>
     <div class="msg" align="center" <?php if($_REQUEST['id']!='' && $_REQUEST['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='DailyStockLoadingview.php'"/>
      </form>
     </div>	 
   </div>
 <?php require_once('../include/error.php'); ?>
</div>
<?php require_once('../include/footer.php');?>