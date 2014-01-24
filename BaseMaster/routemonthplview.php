<?php
session_start();
ob_start();
require_once('../include/header.php');
require_once "../include/ps_pagination.php";
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

if($_REQUEST['DSR_name']!='')
{
	$var = @$_REQUEST['DSR_name'] ;
	$trimmed = trim($var);	
	$qry="SELECT *,rpl.id AS RPLID FROM `routemonthplan` rpl LEFT JOIN dsr ON rpl.DSR_Code = dsr.DSR_Code where DSRName like '%".$trimmed."%'";
}
else
{ 
	$qry="SELECT *,rpl.id AS RPLID FROM `routemonthplan` rpl LEFT JOIN dsr ON rpl.DSR_Code = dsr.DSR_Code";
}
$results	=	mysql_query($qry) or die(mysql_error());
$num_rows	=	mysql_num_rows($results);	

$params			=	$DSR_name."&".$sortorder."&".$ordercol;

$curmonthnum		=	ltrim(date('m'),0);
$curyear			=	date('Y');
$list=array();
	$num_of_days = date('t');
	for($d=1; $d<=$num_of_days; $d++)
	{
		$time=mktime(12, 0, 0, date('m'), $d, date('Y'));
		if (date('m', $time)==date('m'))
			$daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
	}

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
	$orderby	=	"ORDER BY insertdatetime DESC";
} else {
	$orderby	=	"ORDER BY $ordercol $sortorder";
}
$qry			.=	" $orderby LIMIT $Page_Start , $Per_Page";  //need to uncomment
//echo $qry;
//exit;
$results_dsr	=	mysql_query($qry) or die(mysql_error());
/********************************pagination***********************************/

?>

<div id="mainareadaily">
<div class="mcf"></div>
<div><h2 align="center">ROUTE MONTH PLAN</h2></div> 
<div id="containerdaily">
<span style="float:left;"><input type="button" name="kdproduct" value="Add Month Route Plan" class="buttonsbig" onclick="window.location='routemonthlyplan.php'"></span><span style="float:right;"><input type="button" name="kdproduct" value="Close" class="buttons" onclick="window.location='../include/empty.php'"></span>

<div class="clearfix"></div>
 <div id="search">
	<input type="text" name="DSR_name" id="DSR_name" value="<?php echo $_REQUEST['DSR_name']; ?>" autocomplete='off' placeholder='Search By DSR Name'/>
	<input type="button" class="buttonsg" onclick="searchroutemonthviewajax('<?php echo $Page; ?>');" value="GO"/>
 </div>
 <div class="clearfix"></div>
		<div id="routemonthplid">
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
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
			<th nowrap="nowrap" onClick="routemonthviewajax('<?php echo $Page; ?>','<?php echo $paramsval; ?>');">DSR Name <img src="../images/sort.png" width="13" height="13" /></th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
			<th nowrap="nowrap">Month</th>
			<th nowrap="nowrap">Year</th>
			<th>Edit</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
			$c=0;$cc=1;
			while($fetch_rpl = mysql_fetch_array($results_dsr)) {
				$array_value[]		=	$fetch_rpl;
			}
			foreach($array_value	AS $array_fetch) {
				//pre($array_fetch);
				if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
				
				//echo $array_fetch[RPLID]."<br>";
				$monthvaluecal				=	$array_fetch['routemonth'];
				$yearvaluecal				=	$array_fetch['routeyear'];
				$DSRName					=	$array_fetch['DSRName'];

				//pre($array_fetch);
				//echo $monthvaluecal."<br>";
				//echo $yearvaluecal."<br>";
				//echo $array_fetch[RPLID]."<br>";
				
				//echo $curmonthnum."<br>";
				//echo $curyear."<br>";				
				

				$query_routemon				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_mon]'";			
				$res_routemon				=	mysql_query($query_routemon) or die(mysql_error());
				$row_routemon				=	mysql_fetch_array($res_routemon);
				$route_mon					=	$row_routemon['route_desc'];

				//echo $array_fetch[route_mon]."<br>";
				//echo $array_fetch[RPLID]."<br>";

				$query_routetue				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_tue]'";			
				$res_routetue				=	mysql_query($query_routetue) or die(mysql_error());
				$row_routetue				=	mysql_fetch_array($res_routetue);
				$route_tue					=	$row_routetue['route_desc'];

				//echo $array_fetch[RPLID]."<br>";

				$query_routewed				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_wed]'";			
				$res_routewed				=	mysql_query($query_routewed) or die(mysql_error());
				$row_routewed				=	mysql_fetch_array($res_routewed);
				$route_wed					=	$row_routewed['route_desc'];

				//echo $array_fetch[RPLID]."<br>";

				$query_routethu				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_thu]'";			
				$res_routethu				=	mysql_query($query_routethu) or die(mysql_error());
				$row_routethu				=	mysql_fetch_array($res_routethu);
				$route_thu					=	$row_routethu['route_desc'];

				//echo $array_fetch[RPLID]."<br>";

				$query_routefri				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_fri]'";			
				$res_routefri				=	mysql_query($query_routefri) or die(mysql_error());
				$row_routefri				=	mysql_fetch_array($res_routefri);
				$route_fri					=	$row_routefri['route_desc'];

				//echo $array_fetch[RPLID]."<br>";

				$query_routesat				=	"SELECT route_desc FROM route_master WHERE route_code = '$array_fetch[route_sat]'";			
				$res_routesat				=	mysql_query($query_routesat) or die(mysql_error());
				$row_routesat				=	mysql_fetch_array($res_routesat);
				$route_sat					=	$row_routesat['route_desc'];
				
				//echo $array_fetch[RPLID]."<br>";

				//echo $monthvaluecal."<br>"; echo $array_fetch[RPLID]."<br>"; 
				//echo $array_fetch[RPLID]."<br>";
				?>
				<tr>				
				<td nowrap="nowrap"><?php echo $DSRName; ?></td>
				<?php //pre($array_fetch); //echo $array_fetch[RPLID]."<br>"; ?>
				<td nowrap="nowrap"><?php //pre($array_fetch); 
				echo $route_mon;	?></td>
				<?php //echo $array_fetch[RPLID]."<br>"; ?>
				<td nowrap="nowrap"><?php
				//echo $array_fetch[RPLID]."<br>"; 
				echo $route_tue;	?></td>
				<td nowrap="nowrap"><?php echo $route_wed;	?></td>
				<td nowrap="nowrap"><?php echo $route_thu;	?></td>
				<td nowrap="nowrap"><?php echo $route_fri;	?></td>
				<td nowrap="nowrap"><?php echo $route_sat;	?></td>
				<td nowrap="nowrap"><?php //echo $monthvaluecal."<br>";
				//echo date('F',mktime(0,0,0,$array_fetch['routemonth'],10))."<br>"; 
				echo date('F',mktime(0,0,0,$monthvaluecal,10));?></td>
				<td nowrap="nowrap"><?php echo $yearvaluecal;?></td>
				<td align="right" nowrap="nowrap">				
				<?php if($curmonthnum == $monthvaluecal && $curyear == $yearvaluecal) { ?>
					<a href="../BaseMaster/routemonthlyplan.php?idvalnum=<?php echo $array_fetch['RPLID'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php 
				} else { 
					echo "No Edit"; 
				} ?>
				</td>
				</tr>
				<?php $c++; $cc++; 
				$monthvaluecal				=	'';
				$yearvaluecal				=	'';
				$route_mon					=	'';
				$route_tue					=	'';
				$route_wed					=	'';
				$route_thu					=	'';
				$route_fri					=	'';
				$route_sat					=	'';
			}
		} else { ?>
			<tr>
				<td align='center' colspan='13'><b>No records found</b></td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line2</td>
				<td style="display:none;" >Cust Name</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
				<td style="display:none;" >Add Line1</td>
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
			rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'routemonthviewajax');   //need to uncomment
		} else	{ 
			echo "&nbsp;"; 
		} ?>      
		</th>
		</tr>
        </table>
      </div>
	</div>    
     </div>  
   </div>
 <?php require_once('../include/error.php'); ?>
</div>
<?php require_once('../include/footer.php');?>