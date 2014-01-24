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
$qry="SELECT * FROM `credit_note` GROUP BY return_transno";
$results		=	mysql_query($qry);
$num_rows		=	mysql_num_rows($results);

$params			=	$Product_name."&".$sortorder."&".$ordercol;

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
<div><h2 align="center">CREDIT NOTE</h2></div> 
<div id="containerdailysto">

<span style="float:left;"><input type="button" name="kdproduct" value="Add Credit Note" class="buttonsbig" onclick="window.location='creditnote.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

<div class="clearfix"></div>

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

				<th width="5%">Transaction Number</th>
				<th width="1%" align="center">Return Date</th>
				<th width="4%" align="center">Total Value</th>
				<th width="4%" align="center">Customer</th>
				<th width="4%" align="center">View</th>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch = mysql_fetch_array($results_dsr)) {
			if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
			$id= $fetch['id'];
			//echo $fetch['id'];
			?>
			<tr>
			<td ><?php echo $fetch['return_transno']; ?></td>
			<td align="center"><?php echo $fetch['return_date'];?></td>
			<td align="center"><?php echo $fetch['total_val']; ?></td>
			<td align="center"><?php
			$query_cusid				=	"SELECT * FROM customer WHERE customer_code = '$fetch[customer_code]'";
			$res_cusid					=	mysql_query($query_cusid)or die(mysql_error());
			$rowcnt_cusid				=	mysql_num_rows($res_cusid);

			if($rowcnt_cusid > 0 ) {
				$row_cusid				=	mysql_fetch_array($res_cusid);
				echo $cusname			=	$row_cusid['Customer_Name'];
			}
			?></td>
			<td align="center">
			<a href="../DSRStock/creditnoteviewlist.php?id=<?php echo $fetch[return_transno]; ?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>			
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