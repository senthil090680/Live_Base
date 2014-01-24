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
$qry.=" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//exit;
$results_dsr = mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>
<style type="text/css">
#containerprforcd {
	padding:0px;
	width:100%;
	margin-left:auto;
	margin-right:auto;
}
</style>
<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">COLLETION DEPOSIT</h2></div> 

<div id="containerprforcd">

<span style="float:left;"><input type="button" name="kdproduct" value="Add Collection Deposit" class="buttonsbig" onclick="window.location='CollectionDeposited.php'"></span><span style="float:left;padding-left:20px;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>
 <div id="search">
        <input type="text" name="Challan_Number" value="<?php echo $_REQUEST['Challan_Number']; ?>" autocomplete='off' placeholder='Search By Challan Number'/>
        <input type="button" class="buttonsg" onclick="searchcolviewajax('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
        <?php
		if($_POST['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$query = "DELETE FROM `dsr_collection` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:CollectionDepositedview.php?no=3");
			}
		 }		
		?>
		<div id="colviewajaxid">
			<div class="con">
			<table width="100%">
			<thead>
			<tr>
				<th nowrap="nowrap">Serial Number</th>
				<?php //echo $sortorderby;
				if($sortorder == 'ASC') {
					$sortorderby = 'DESC';
				} elseif($sortorder == 'DESC') {
					$sortorderby = 'ASC';
				} else {
					$sortorderby = 'DESC';
				}
				$paramsval	=	$Challan_Number."&".$sortorderby."&DSR_Code"; ?>
				<th>Bank Name</th>
				<th nowrap="nowrap" class="rounded" onClick="colviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">DSR Name<img src="../images/sort.png" width="13" height="13" /></th>
				<th nowrap="nowrap">Transaction Number</th>
				<th nowrap="nowrap">Challan Number</th>
				<th nowrap="nowrap">Challan Date</th>
				<th nowrap="nowrap">Currency</th>
				<th nowrap="nowrap">Amount Deposited</th>
				<th nowrap="nowrap">Total Amount</th>
				<!--<th align="right">Edit/Del</th>-->
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
				<td align="right"><?php echo number_format(trim($fetch['Amount_Deposited']),2);?></td>
				<td align="right"><?php echo number_format(trim($fetch['Total_Amount']),2);?></td>
				<!--<td align="right" nowrap="nowrap">

				<?php $curdate		=	date('Y-m-d');
					//$curdate		=	"2013-08-22";
					$datearr		=	explode(' ',$fetch['Date']);
					//echo $datearr[0];
					if($datearr[0] == $curdate) { 
				?>
				<a href="../DSRStock/CollectionDeposited.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="../DSRStock/CollectionDeposited.php?id=<?php echo $fetch['id'];?>&del=del"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>

				<?php } else {  echo "No Edit/Del"; } ?>

				</td>-->
			</tr>
			<?php $c++; $cc++; $slno++; }		 
			}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
			</tbody>
			</table>
			 </div>   
			 <div class="paginationfile" align="center">
			 <table>
			 <tr>
			 <th class="pagination" scope="col">          
			<?php 
			if(!empty($num_rows)){
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'colviewajax');   //need to uncomment
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
		<span id="printopen" style="padding-left:500px;padding-top:10px;<?php if($num_rows > 0 ) { echo "display:block;"; } else { echo "display:none;"; } ?>" ><input type="button" name="kdproduct" value="Print" class="buttons" onclick="print_pages('printcolviewajax');"></span>
			<form id="printcolviewajax" target="_blank" action="printcolviewajax.php" method="post">
				<input type="hidden" name="Challan_Number" id="Challan_Number" value="<?php echo $Challan_Number; ?>" />
				<input type="hidden" name="sortorder" id="sortorder" value="<?php echo $sortorder; ?>" />
				<input type="hidden" name="ordercol" id="ordercol" value="<?php echo $ordercol; ?>" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST[page]; ?>" />
			</form>
	 </div>
     <div class="msg" align="center" <?php if($_REQUEST['id']!='' && $_REQUEST['del']=='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='CollectionDeposited.php'"/>
      </form>
     </div>  
   </div>
   <div class="mcf"></div>
    <?php include("../include/error.php"); ?>
</div>
<?php require_once('../include/footer.php');?>