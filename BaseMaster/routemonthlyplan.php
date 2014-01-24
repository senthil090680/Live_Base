<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";

//ini_set("display_errors",false);
//echo ini_get("display_errors");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
$msg								=	'';
$query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
$query 								=	mysql_query($query_DSR) or die(mysql_error());
$sql_device   						=	"select id,device_description,device_code from device_master";
$sql   								=	mysql_query($sql_device) or die(mysql_error());
$route_sql							=	"select id,location,route_desc,route_code from route_master";
$route								=	mysql_query($route_sql) or die(mysql_error());
$vehicle_sql						=	"select id,vehicle_desc,vehicle_code,vehicle_reg_no from vehicle_master";
$vehicle							=	mysql_query($vehicle_sql) or die(mysql_error());
$location_sql						=	"select id,location from location";
$location_res						=	mysql_query($location_sql) or die(mysql_error());
$id									=	isset($_REQUEST['id']);

if(isset($_GET['idvalnum']) && $_GET['idvalnum'] !='') {
	$routemonth	=	ltrim(date('m'),0);
	$routeyear	=	date('Y');
	$where							=	"WHERE id = '$idvalnum'";
	$qry_showplan					=	"SELECT * FROM `routemonthplan` $where";
	$res_showplan					=	mysql_query($qry_showplan) or die(mysql_error());
	$rowcnt_showplan				=	mysql_num_rows($res_showplan);
	if($rowcnt_showplan > 0) {
		$row_showplan				=	mysql_fetch_array($res_showplan);
		$dsrcode				=	$row_showplan[DSR_Code];
	}
} 

?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">
#mytableformgr{
	background:#fff;
	width:95%;
	margin-left:auto;
	margin-right:auto;
	height:480px;
}
.condaily_routeplan th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}
.condaily_routeplan td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}
.condaily_routeplan tbody tr:hover td {
	background: #c1c1c1;
}
.condaily_routeplan{
	width:99%;
	text-align:left;
	height:280px;
	border-collapse:collapse;
	background:#a09e9e;
	/*margin-left:auto;*/
	margin-right:auto;
	border-radius:10px;
	overflow:auto;
}
.alignment {
	font-size:16px;
	margin-left:10px;
	/*padding-left:20px;*/
	width:95%
}
#errormsgmonplan {
	display:none;
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
.myalignmonplan {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

.buttons_new{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#31859C;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:160px;
	height:15px;
}
.buttons_gray {
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#A09E9E;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:240px;
	height:15px;
}

.alignsize {
	font-size:16px;
}

.pad5 { 
	padding-bottom:7px;
}

.textalg {
	text-align:left;
}

input[type="radio"] {
  margin-top: -1px;
  vertical-align: middle;
}
</style>
<body <?php if($dsrcode != '') { ?> onload="getDSRRoutes('<?php echo $dsrcode; ?>')" <?php } ?> >
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">JOURNEY PLAN</div>
<div id="mytableformgr" align="center">
<form method="post" action="" id="routemasterplan">
<table width="100%" >
<tr>
<td>

  <table width="100%" >
 <tr>
	<td colspan="7">
	<fieldset class="alignment">
  <legend><strong>Journey Plan</strong></legend>
	  <table>
	    <tr>
		<td align="left" class="alignsize" nowrap="nowrap">DSR Name*</td>
		<td align="left" nowrap="nowrap" class="align2 alignsize" >
		<!-- <select class="dsrname" name="dsrname" id="dsrname" onChange="getAllRoutes(this.value);"> -->
		<select class="dsrname" style="width:250px" name="dsrname" id="dsrname" onChange="getDSRRoutes(this.value);">
		  <option value="">---Select---</option>
		<?php while($info = mysql_fetch_assoc($query)){?>
		<option value="<?php echo  $info['DSR_Code']; ?>" <?php if($dsrcode == $info['DSR_Code']) { echo "selected"; } ?> > <?php echo  $info['DSRName'] ?></option>
		<?php }?> 
		</select>
		</td>
		
		<td align="left" nowrap="nowrap" class="align alignsize" style="padding-left:40px;">Month & Year  &nbsp;:&nbsp;&nbsp;&nbsp;
		
		<!-- </td>
		<td align="left" nowrap="nowrap" style="width:2%" class="alignsize"> -->

		<?php
		$list=array();
		$num_of_days = date('t');
		for($d=1; $d<=$num_of_days; $d++)
		{
			$time=mktime(12, 0, 0, date('m'), $d, date('Y'));
			if (date('m', $time)==date('m'))
				$daysval[ltrim(date('d', $time),0)]		=	date('D', $time);
		}

		//pre($daysval);
		$curdate			=	ltrim(date('d'),0);
		$curmonth			=	date('F');
		$curyear			=	date('Y');
		$curmonthnum		=	date('m');
		$firmonth			=	date('F',strtotime("-1 months"));
		$firmonthnum		=	ltrim(date('m',strtotime("-1 months")),0);
		$secmonth			=	date('F',strtotime("-2 months"));
		$secmonthnum		=	ltrim(date('m',strtotime("-2 months")),0);
		$thimonth			=	date('F',strtotime("-3 months"));
		$thimonthnum		=	ltrim(date('m',strtotime("-3 months")),0);
		echo $curmonth." & ".$curyear;
		?></td>
		</tr>
		
		<tr>
			<td class="pad5"></td>
		</tr>
		<tr>
			<td align="left" class="alignsize"><span id="copyfromspan" nowrap="nowrap">Copy From </span></td>
			<td align="left" nowrap="nowrap" class="align2 alignsize"><span id="copyfromselectspan">
				<select class="dsrname" style="width:250px" name="monthplan" id="monthplan" onChange="getOldOrMasterRoutes(this.value);">
				  <option value="">---Select---</option>
				<option value="1" > Master Plan</option>
				<option value="<?php echo $firmonthnum; ?>" align="center"> <?php echo $firmonth; ?></option>
				<option value="<?php echo $secmonthnum; ?>" align="center"> <?php echo $secmonth; ?></option>
				<option value="<?php echo $thimonthnum; ?>" align="center"> <?php echo $thimonth; ?></option>
				</select> &nbsp; <input type="button" onClick="copyfromold()" value="Copy" class="buttons">
				</span>
			</td>

		<?php //$curmonth		=	date('F',strtotime("-1 months")); ?>
		<td align="left" class="align alignsize" style="padding-left:40px;" nowrap="nowrap" >
			<div id="tobecopied" > <input type="checkbox" onClick="includeweekend();" name="includesatsun" id="includesatsun" value="includeweekends" />	&nbsp;Include Saturdays </div>
			<div id="alreadycopied" > <input type="checkbox" onClick="repeatrouteweek();" name="repeatroute" id="repeatroute" value="repeatrouteval" /> &nbsp;Allow Route Repetition in a Week </div>	
		</td>
	  </tr>

	  <tr>
			<td class="pad5"></td>
		</tr>
	 </table>
	 </fieldset>
	 </td>
  </tr>
  


  <tr>
	<td colspan="7">
	<div class="condaily_routeplan">
	  <table border="1">
  <tr>
    <th align="center" style="width:15%" height="28">Route</th>
    <td align="center" style="width:15%">
    <span id="monrouteselect">
		<select class="location" name="route_mon" id="route_mon" style="width:100px" onChange="bringcust(this.value,'mon','route_mon');">
			<option value="">---Select---</option>
		</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="tuerouteselect">
    <select class="location" name="route_tue" id="route_tue" style="width:100px" onChange="bringcust(this.value,'tue','route_tue');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="wedrouteselect">
    <select class="location" name="route_wed" id="route_wed" style="width:100px" onChange="bringcust(this.value,'wed','route_wed');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="thurouteselect">
    <select class="location" name="route_thu" id="route_thu" style="width:100px" onChange="bringcust(this.value,'thu','route_thu');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="frirouteselect">
    <select class="location" name="route_fri" id="route_fri" style="width:100px" onChange="bringcust(this.value,'fri','route_fri');">
		<option value="">---Select---</option>		
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="satrouteselect">
    <select class="location" name="route_sat" id="route_sat" style="width:100px" disabled onChange="bringcust(this.value,'sat','route_sat');">		
		<option value="">---Select---</option>
	</select>
	</span>
    </td>
  </tr>

  <tr>
    <th align="center" style="width:15%" height="28">Dates</th>
    <td align="center" style="width:15%" nowrap="nowrap">
    <span id="monroutedates">
		<?php $i		=	0;
		//debugerr($daysval);
		foreach($daysval AS $daykey=>$daysvalue) {
			$daysvalue			=	strtolower($daysvalue);
			if($daykey >= $curdate) {
				if($daysvalue == 'mon') {
					if($i	==	0) {
						echo $daykey;
						$i++;
					} else {
						echo " , ". $daykey;
					}
				}
			}
		} ?>	
	</span>
    </td>

	<td align="center" style="width:15%" nowrap="nowrap">
	<span id="tueroutedates">
		<?php //debugerr($daysval);
		$j		=	0;
		foreach($daysval as $daykey=>$daysvalue) {
			//echo $j;
			$daysvalue			=	strtolower($daysvalue); 
			if($daykey >= $curdate) {
				if($daysvalue == 'tue') {
					//echo $daysvalue;
					//echo $j;
					if($j	==	0) {
						echo $daykey;
						$j++;
					} else {
						echo " , ". $daykey;
					}
				}
			}
		} ?>
	</span>
    </td>

	<td align="center" style="width:15%" nowrap="nowrap">
	<span id="wedroutedates">
		<?php $k		=	0;
		foreach($daysval as $daykey=>$daysvalue) {
			$daysvalue			=	strtolower($daysvalue); 
			if($daykey >= $curdate) {
				if($daysvalue == 'wed') {
					if($k	==	0) {
						echo $daykey;
						$k++;
					} else {
						echo " , ". $daykey;
					}
				}		
			}
		} ?>
	</span>
    </td>

	<td align="center" style="width:15%" nowrap="nowrap">
	<span id="thuroutedates">
		<?php $l		=	0; //its l
		//pre($daysval);
		foreach($daysval as $daykey=>$daysvalue) {
			$daysvalue			=	strtolower($daysvalue);
			//echo "<br>". $daykey."<br>";
			//echo "<br>". $curdate."<br>";
			if($daykey >= $curdate) {
				if($daysvalue == 'thu') {
					if($l	==	0) {
						echo $daykey;
						$l++;
					} else {
						echo " , ". $daykey;
					}
				}
			}
		} ?>
	</span>
    </td>

	<td align="center" style="width:15%" nowrap="nowrap">
	<span id="friroutedates">
		<?php $m		=	0;
		foreach($daysval as $daykey=>$daysvalue) {
			$daysvalue			=	strtolower($daysvalue);
			if($daykey >= $curdate) {
				if($daysvalue == 'fri') {
					if($m	==	0) {
						echo $daykey;
						$m++;
					} else {
						echo " , ". $daykey;
					}
				}
			}
		} ?>
	</span>
    </td>

	<td align="center" style="width:15%" nowrap="nowrap">
	<span id="satroutedates">
		<?php $n		=	0;
		foreach($daysval as $daykey=>$daysvalue) {
			$daysvalue			=	strtolower($daysvalue);
			if($daykey >= $curdate) {
				if($daysvalue == 'sat') {
					if($n	==	0) {
						echo $daykey;
						$n++;
					} else {
						echo " , ". $daykey;
					}
				}
			}
		} ?>
	</span>
    </td>
  </tr>
	
  <tr>
    <th align="center" style="width:15%" height="28">Seq. No\Day</th>
    <th align="center" style="width:15%">Monday</th>

	<th align="center" style="width:15%">Tuesday</th>

	<th align="center" style="width:15%">Wednesday</th>

	<th align="center" style="width:15%">Thursday</th>

	<th align="center" style="width:15%">Friday</th>

	<th align="center" style="width:15%">Saturday</th>
  </tr>

<?php for($k=1; $k<26; $k++) { ?>
  <tr>
    <td align="center" style="width:15%" height="28"><?php echo $k; ?></td>
    <td align="center" style="width:15%"><span id="mon_<?php echo $k; ?>"> </span></td>
	<td align="center" style="width:15%"><span id="tue_<?php echo $k; ?>"> </span></td>
	<td align="center" style="width:15%"><span id="wed_<?php echo $k; ?>"> </span></td>
	<td align="center" style="width:15%"><span id="thu_<?php echo $k; ?>"> </span></td>
	<td align="center" style="width:15%"><span id="fri_<?php echo $k; ?>"> </span></td>
	<td align="center" style="width:15%"><span id="sat_<?php echo $k; ?>"> </span></td>
  </tr>
<?php } ?>


  </table>
  </div>
 </td>
</tr>

</table>
</td>
</tr> 
</table>
	 <table width="50%" style="clear:both">
		 <tr align="center" height="10px;">
			 <td ><input type="button" name="submit" id="submit" class="buttons" value="Save" onClick="return routemonthpl();"/>&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="button" name="View" value="View" class="buttons" onclick="window.location='routemonthplview.php'"/></td>
			 </td>
		 </tr>
	 </table>
</form>
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
	  <div id="errormsgmonplan" style="display:none;"><h3 align="center" class="myalignmonplan"></h3><button id="closebutton">Close</button></div>
    
     </div>
  </div>
</div>
<?php include('../include/footer.php');?>