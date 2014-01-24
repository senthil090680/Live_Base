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
	$var = @$_REQUEST['DSR_name'] ;
	$trimmed = trim($var);	
	$qry="SELECT *,srinc.id AS SRINCID, srinc.DSR_Code AS DSR_CodeVal FROM `sr_incentive` AS srinc LEFT JOIN dsr ON srinc.DSR_Code = dsr.DSR_Code WHERE dsr.DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,srinc.id AS SRINCID, srinc.DSR_Code AS DSR_CodeVal FROM `sr_incentive` AS srinc LEFT JOIN dsr ON srinc.DSR_Code = dsr.DSR_Code";
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);

$params			=	$DSR_name."&".$sortorder."&".$ordercol;

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
	$orderby	=	"ORDER BY srinc.id DESC";
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
<div><h2 align="center">SR INCENTIVE TARGET VIEW</h2></div> 
<div id="containerdaily">

<span style="float:left;"><input type="button" name="kdproduct" value="Add SR Incentive Target" class="buttonsbig" onclick="window.location='srincentive.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

<div class="clearfix"></div>
 <div id="search">
	<input type="text" name="DSR_name" id="DSR_name" value="<?php echo $_REQUEST['DSR_name']; ?>" autocomplete='off' placeholder='Search By DSR Name'/>
	<input type="button" class="buttonsg" onclick="searchsrincviewajax('<?php echo $Page; ?>');" value="GO"/>
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
        <div id="srincid">
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
			$paramsval	=	$DSR_name."&".$sortorderby."&dsr.DSRName"; ?>
			<th class="rounded" onClick="srincviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');" >DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
			<th align="center">Month & Year</th>
			<th align="center" nowrap="nowrap">Product Name</th>
			<th align="center" nowrap="nowrap">Target Units</th>
			<th align="center" nowrap="nowrap">Target Naira</th>
			<th align="center" nowrap="nowrap">Date</th>
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
		$id= $fetch['SRINCID'];
		$DSRName					=	getdbval($fetch[DSR_CodeVal],'DSRName','DSR_Code','dsr');
		$ProductName				=	getdbval($fetch[Product_id],'Product_description1','id','product');
		?>
		<tr>
			<td align="center"><?php echo $DSRName; ?></td>
			<td align="center"><?php echo date('F', mktime(0, 0, 0, $fetch[monthval])). " & " .$fetch[yearval]; ?></td>
			<td align="center"><?php echo $ProductName; ?></td>
			<td align="right"><?php echo number_format($fetch[target_units]); ?></td>
			<td align="right"><?php echo number_format($fetch[target_naira],2); ?></td>
			<td align="center"><?php echo trim($fetch[insertdatetime],' '); ?></td>
			<td align="right">
			<?php $monthval				=	trim(date('m'),0);
				  //$monthval				=	9;
				  $yearval				=	date('Y');
				if($fetch[monthval] >= $monthval && $fetch[yearval] >= $yearval) {
			?>
			<a href="srincentive.php?id=<?php echo $fetch['SRINCID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
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
			rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'srincviewajax');   //need to uncomment
		} else { 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>
	  <span id="printopen" style="padding-left:380px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printsrincajax');"></span>
		<form id="printsrincajax" target="_blank" action="printsrincajax.php" method="post">
			<input type="hidden" name="DSR_name" id="DSR_name" value="<?php echo $DSR_name; ?>" />
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