<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
//echo isset($_REQUEST['id']);
//ini_set("display_errors",false);
//echo ini_get("display_errors");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
//debugerr($_REQUEST);
extract($_REQUEST);
$msg								=	'';
$query_DSR 							=	"select id,DSRName,DSR_Code from dsr";
$query 								=	mysql_query($query_DSR) or die(mysql_error());
$route_sql							=	"select id,location,route_desc,route_code from route_master";
$route								=	mysql_query($route_sql) or die(mysql_error());
$id							=	$_REQUEST['id'];
//debugerr($_REQUEST);
$query_routemas						=	"select * from routemasterplan where id = '$id'";
$list								=	mysql_query($query_routemas) or die(mysql_error()); 
$row								=	mysql_fetch_array($list); 
$dsrcode							=	$row['DSR_Code'];
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
	width:100%;
	text-align:left;
	height:310px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
}
#errormsgrouteplan {
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
.myalignrouteplan {
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
</style>
<body onload="getAllRoutes('<?php echo $dsrcode; ?>')">
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Master Route Plan</div>
<div id="mytableformgr" align="center">
<!--<div></div>-->

<form method="post" action="" id="routemasterplan">
<table width="100%" >
<tr>
<td>
<fieldset class="alignment">
  <legend><strong>Master Route Plan</strong></legend>
  <table width="100%" >
 <tr>
	<td colspan="7">	 	  
	  <table>
	    <tr>
		<td align="left" style="width:25%" height="28" colspan="2">DSR Name*</td>
		<td align="left" style="width:25%" colspan="3">
		<select class="dsrname" name="dsrname" id="dsrname" onChange="getAllRoutes(this.value);">
		  <option value="">---Select---</option>
		<?php while($info = mysql_fetch_assoc($query)){?>
		<option value="<?php echo  $info['DSR_Code']; ?>" <?php if($dsrcode == $info['DSR_Code']) { echo "selected"; } ?> > <?php echo  $info['DSRName'] ?></option>
		<?php }?> 
		</select>
		</td>
		<?php $curmonth		=	date('F',strtotime("-1 months")); ?>
		<td height="28" align="left" style="width:25%" nowrap="nowrap" colspan="2">
			<div id="tobecopied" > <input type="checkbox" onClick="includeweekend();" name="includesatsun" id="includesatsun" value="includeweekends" />	&nbsp;Include Saturdays </div>
			<div id="alreadycopied" > <input type="checkbox" onClick="repeatrouteweek();" name="repeatroute" id="repeatroute" value="repeatrouteval" /> &nbsp;Allow Route Repetition in a Week </div>	
		</td>
	  </tr>
	 </table>
	 </td>
  </tr>
  
  <tr>
	<td colspan="7">
	<div class="condaily_routeplan">
	  <table border="1">
	    <tr>
    <td align="center" style="width:15%" height="28">Route</td>
    <td align="center" style="width:15%">
    <span id="monrouteselect">
		<select class="location" name="route_mon" id="route_mon" onChange="bringcustomers(this.value,'mon','route_mon');">
			<option value="">---Select---</option>
		</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="tuerouteselect">
    <select class="location" name="route_tue" id="route_tue" onChange="bringcustomers(this.value,'tue','route_tue');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="wedrouteselect">
    <select class="location" name="route_wed" id="route_wed" onChange="bringcustomers(this.value,'wed','route_wed');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="thurouteselect">
    <select class="location" name="route_thu" id="route_thu" onChange="bringcustomers(this.value,'thu','route_thu');">
		<option value="">---Select---</option>
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="frirouteselect">
    <select class="location" name="route_fri" id="route_fri" onChange="bringcustomers(this.value,'fri','route_fri');">
		<option value="">---Select---</option>		
	</select>
	</span>
    </td>

	<td align="center" style="width:15%">
	<span id="satrouteselect">
    <select class="location" name="route_sat" id="route_sat" disabled onChange="bringcustomers(this.value,'sat','route_sat');">		
		<option value="">---Select---</option>
	</select>
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
</fieldset>
</td>
</tr> 
</table>
	 <table width="50%" style="clear:both">
		 <tr align="center" height="10px;">
			 <td ><input type="button" name="submit" id="submit" class="buttons" value="Save" onClick="return routemasterpl();"/>&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
				 <input type="button" name="View" value="View" class="buttons" onclick="window.location='routemasterplview.php'"/></td>
			 </td>
		 </tr>
	 </table>
</form>
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
	  <div id="errormsgrouteplan" style="display:none;"><h3 align="center" class="myalignrouteplan"></h3><button id="closebutton">Close</button></div>
    
     </div>
  </div>
</div>
<?php include('../include/footer.php');?>