<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ajax_pagination.php";
if(isset($_REQUEST['logout'])){
	session_destroy();
	header("Location:../index.php");
}

error_reporting(E_ALL && ~ E_NOTICE);
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_REQUEST);
$id=$_REQUEST['id'];
if($_REQUEST['submit']!='')
{
	$var = @$_REQUEST['Product_name'] ;
	$trimmed = trim($var);	
	$qry="SELECT *,posm.id AS POSMID FROM `posmtarget` AS posm LEFT JOIN customertype_product prod ON posm.productId = prod.Product_id WHERE prod.Product_description1 like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,posm.id AS POSMID FROM `posmtarget` AS posm LEFT JOIN customertype_product prod ON posm.productId = prod.Product_id";
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);

$params			=	$Product_name."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

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
	$orderby	=	"ORDER BY posm.id DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//echo $qry;
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/
?>
<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">POSM TARGET VIEW</h2></div> 
<div id="containerdaily">

<span style="float:left;"><input type="button" name="kdproduct" value="Add POSM Target" class="buttonsbig" onclick="window.location='posmtarget.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

<div class="clearfix"></div>
 <div id="search">
	<input type="text" name="Product_name" id="Product_name" value="<?php echo $_REQUEST['Product_name']; ?>" autocomplete='off' placeholder='Search By Product Name'/>
	<input type="button" class="buttonsg" onclick="searchposmtgtviewajax('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
        <?php
		if($_REQUEST['id']!=''){
			if($_REQUEST['submit']=='ConfirmDelete'){
				$Sql						=	"DELETE from sr_incentive WHERE id=$id";
				$delres						=	mysql_query($Sql) or die (mysql_error());
				if($delres) {
				   header("location:srincentive.php?no=3");
			   }
			}
		 }				
		?>
        <div id="posmtgtid">
		<div class="con">
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
			$paramsval	=	$Product_name."&".$sortorderby."&prod.Product_description1"; ?>
			<th class="rounded" onClick="posmtgtviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >Product Name <img src="../images/sort.png" width="13" height="13" /></th>
			<th align="center">Principal</th>
			<th align="center">Brand</th>
			<th align="center">Customer Type</th>
			<th align="center">No. of Cus.</th>
			<th align="center" nowrap="nowrap">Units</th>
			<th align="center">Month & Year</th>
			<th align="right">Mod</th>
			<!-- <th align="right">Mod/Del</th> -->
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results_dsr)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['POSMID'];
		$ProductName					=	$fetch[Product_description1];
		$principalName					=	getdbval($fetch[principalId],'principal','id','principal');
		$brandName						=	getdbval($fetch[brandId],'brand','id','brand');

		$customer_typearr					=	explode('+',$fetch[custypeId]);
		//pre($customer_typearr);
		$val					=	0;
		$customer_typenameval	=	'';
		$num_cuscnt				=	0;
		foreach($customer_typearr AS $CUSTYPEVAL) {
			$qry_cuscnt							=	"SELECT id FROM `customer` WHERE customer_type = '$CUSTYPEVAL'";
			$res_cuscnt							=	mysql_query($qry_cuscnt);
			$num_cuscntval						+=	mysql_num_rows($res_cuscnt);

			if($val == 0) {
				//echo "232<br>";
				$customer_typenameval			=	upperstate(getdbval($CUSTYPEVAL,'customer_type','id','customer_type'));
				$val++;
			} else {
				//echo "456<br>";
				$customer_typename				=	upperstate(getdbval($CUSTYPEVAL,'customer_type','id','customer_type'));
				$customer_typenameval		.=	", ".$customer_typename;
			}
			$customer_typename					=	'';
			$num_cuscnt							=	0;
		}

		//$custypeName					=	getdbval($fetch[custypeId],'customer_type','id','customer_type');
		?>
		<tr>
			<td align="center"><?php echo upperstate($ProductName); ?></td>
			<td align="center"><?php echo upperstate($principalName); ?></td>
			<td align="center"><?php echo upperstate($brandName); ?></td>
			<td align="center"><?php echo upperstate($customer_typenameval); ?></td>
			<td align="center"><?php echo $fetch[noofcus]; ?></td>
			<td align="center"><?php echo $fetch[unitval]; ?></td>
			<td align="center"><?php echo date('F', mktime(0, 0, 0, $fetch[monthval])). " & " .$fetch[yearval]; ?></td>
			<td align="right">
			<?php /*$monthval				=	trim(date('m'),0);
				  //$monthval			=	9;
				  $yearval				=	date('Y');
				  $dateval				=	date('d');
			      //$fetch[fromdate]	=	"2013-08-12";
				  $monthdb				=	trim(substr($fetch[fromdate],5,2),0);
				  $yeardb				=	substr($fetch[fromdate],0,4);
				  $datedb				=	trim(substr($fetch[fromdate],8,2),0);
				if($monthdb >= $monthval && $yeardb >= $yearval && $datedb >= $dateval) {*/

					 $monthval				=	trim(date('m'),0);
				  //$monthval				=	9;
				  $yearval				=	date('Y');
				if($fetch[monthval] >= $monthval && $fetch[yearval] >= $yearval) {
			?>
			<a href="posmtarget.php?id=<?php echo $fetch['POSMID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<!-- <a href="srincentive.php?id=<?php echo $fetch['SRINCID'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a> -->
			<?php } else { echo "No Edit"; } ?>
			</td>
        </tr>
		<?php $c++; $cc++; }
		}else { ?>
			<tr>
				<td align='center' colspan='9'><b>No records found</b></td>
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
			rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'posmtgtviewajax');   //need to uncomment
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>
	  <span id="printopen" style="padding-left:380px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printposmtgtajax');"></span>
		<form id="printposmtgtajax" target="_blank" action="printposmtgtajax.php" method="post">
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