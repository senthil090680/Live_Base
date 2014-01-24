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
$query_RSM 							=	"select id,DSRName,DSR_Code from rsm_sp";
$res_RSM 							=	mysql_query($query_RSM) or die(mysql_error());

$query_ASM 							=	"select id,DSRName,DSR_Code from asm_sp";
$res_ASM 							=	mysql_query($query_ASM) or die(mysql_error());

$query_SR 							=	"select id,DSRName,DSR_Code from dsr";
$res_SR 							=	mysql_query($query_SR) or die(mysql_error());

$query_branch 						=	"select id,branch from branch";
$res_branch 						=	mysql_query($query_branch) or die(mysql_error());

$id									=	isset($_REQUEST['id']);
?>
<!------------------------------- Form -------------------------------------------------->

<style type="text/css">
.heading_report {
	background:#a09e9e;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
	clear:both;
}
#mytableform_report{
	background:#fff;
	width:99%;
	margin-left:auto;
	margin-right:auto;
	height:480px;
	overflow:scroll;
	overflow:auto;
}
.alignment_report{
	width:96%;
	padding-left:20px;
	margin-left:10px;
	font-size:16px;
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
	height:350px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
}
#errormsgmetrep {
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
.myalignmetrep {
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

.align2 {
	padding-left:10px;
}

#span1 {
	width: 30px; 
	float:left;
  }
#span2 { 
    width: 30px; 
	float:right;
	}
	
#colors{
	background-color:#CCC;
}
</style>

<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="heading_report">SR METRICS</div>
<div id="mytableform_report" align="center">
<div class="mcf"></div>
<!-- <form method="post" action="" id="routemasterplan"> -->
<div style="background-color:#CCC">
<table width="100%">
 <tr>
       <td align="left" style="width:4%" class="align2">
		<span id="srspan">
			<select class="dsrname" name="srcode" id="srcode" onChange="getasmrsmvalueswithbranch(this.value);">
				<option value="">---SR---</option>
				<?php while($info_sr = mysql_fetch_assoc($res_SR)) { ?>
				<option value="<?php echo  $info_sr['DSR_Code']; ?>" <?php if($srcode == $info_sr['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_sr['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		</td>
    
		<td align="right" style="width:4%"><strong>ASM</strong>&nbsp;:&nbsp; </td>
        <td align="left" nowrap="nowrap" style="width:4%" class="align2">
        <span id="asmid"></span>
        </td> 
       
	   <td align="right" style="width:4%"><strong>RSM</strong>&nbsp;:&nbsp; </td>
       <td align="left" style="width:4%" nowrap="nowrap" class="align2">
		<span id="rsmid"></span>
		</td>
        
	 <td align="right" style="width:4%"><strong>Branch</strong>&nbsp;:&nbsp;</td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2">
	  <span id="branchid"></span>
	  </td>

        <td style="width:4%" class="align2" nowrap="nowrap">
        <strong>Month :</strong> <select name="propmonth" id="propmonth">
		<?php $curmonthval	=	date('m'); ?>
			<option value="">--Select--</option>
			<option value="01" <?php if($curmonthval == 01) { echo "selected"; } ?> >January</option>
			<option value="02" <?php if($curmonthval == 02) { echo "selected"; } ?> >February</option>
			<option value="03" <?php if($curmonthval == 03) { echo "selected"; } ?> >March</option>
			<option value="04" <?php if($curmonthval == 04) { echo "selected"; } ?> >April</option>
			<option value="05" <?php if($curmonthval == 05) { echo "selected"; } ?> >May</option>
			<option value="06" <?php if($curmonthval == 06) { echo "selected"; } ?> >June</option>
			<option value="07" <?php if($curmonthval == 07) { echo "selected"; } ?> >July</option>
			<option value="08" <?php if($curmonthval == 08) { echo "selected"; } ?> >August</option>
			<option value="09" <?php if($curmonthval == 09) { echo "selected"; } ?> >September</option>
			<option value="10" <?php if($curmonthval == 10) { echo "selected"; } ?> >October</option>
			<option value="11" <?php if($curmonthval == 11) { echo "selected"; } ?> >November</option>
			<option value="12" <?php if($curmonthval == 12) { echo "selected"; } ?> >December</option>
		</select> &nbsp;&nbsp; 
		<strong>Year :</strong><select name="propyear" id="propyear" >
			<option value="">--Select--</option>
			<?php $curyear = date("Y");
			for($i=2010; $i<=$curyear;$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($curyear == $i) {  echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select> &nbsp; &nbsp;&nbsp;
       <input type="button" class="buttons" onClick="srmetricsreport();" value="GO" />
        </td>
      </tr>
</table>
</div>     
<div class="mcf">
	<div class="condaily_routeplan">
		<span id="ajaxresultpage">

	<table border="1" width="100%" bgcolor="#CCCCCC">
	  <tr>
	    <td>
	
		  <table border="1" width="100%" bgcolor="#CCCCCC"> <!--First Table-->
			<thead>
			  <tr>
				<th align="center" style="width:50%">Sales(Naira)</th>
				<th align="center" style="width:50%">Customer Coverage</th>
		  </tr>
		  </thead>
	     <tbody>
         <tr> <!--First TR Starts-->
		 <td style="background-color:#999"> <!-- Sale Starts-->
         <table width="100%" bgcolor="#999999" height="80px">
         <tr><td width="50%">Target</td><td align="center">-</td></tr>
         <tr><td width="50%">Actual</td><td align="center">-</td></tr>
         <tr><td width="50%">% Achievement</td><td align="center">-</td></tr>
         </table>
         </td> <!-- Sale Ends-->
        
         <td> <!--customer coverage start-->
         <table width="100%" bgcolor="#999999">
         <tr>
         <td align="center">Total Customers</td>
         <td align="center">Customer Visits</td>
         <td align="center">Sale Visit</td>
         <td align="center">Productivity</td>
         <td align="center">Effectivity</td>
         </tr>
         <tr>
         <td height="40px" colspan="5" align="center"><strong>NO RECORDS FOUND</strong></td>
         </tr>
         </table>
         </td>  <!--customer coverage start-->      
        </tr> <!--First TR End-->
        </tbody>
		</table>
	  </td>
	</tr>

  <tr>
  <td>
  <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Second Table-->
 		<thead>
			  <tr>
				<th align="center" style="width:44%">ITEM</th>
				<th align="center" style="width:6%">VALUE</th>
                <th align="center" style="width:50%">METRIC</th>
		  </tr>
		  </thead>

		<tbody>
         <table width="50%" bgcolor="#999999" align="left">
         <tr>
			 <td>Total Sale</td>
			 <td align="center"><strong>-</strong></td>
         </tr>
         <tr>
			 <td>Total Lines</td>
			 <td align="center">-</td>
         </tr>


         <tr>
			 <td>Total Focus Products</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Focus Lines</td>
			 <td align="center">-</td>
         </tr>
         <tr>
			 <td>Total Checked In Hours</td>
			 <td align="center">-</td>
         </tr>
      </table>
      
        <table width="50%" bgcolor="#999999" align="right"><!-- Metric Start Table-->
         <tr>
			 <td align="center"><strong>Drop Size</strong></td>
			 <td align="center"><strong>Basket Size</strong></td>
			 <td align="center"><strong>Focus Coverage</strong></td>
			 <td align="center"><strong>Efficiency</strong></td>
         </tr>
         
         <tr>
			 <td height="75px" align="center" colspan="4"><strong>NO RECORDS FOUND</strong></td>
         </tr>
         </table>
		 </tbody>
          <!-- Metric Ends-->
        </table>
	</td>
</tr>


       
	<tr>
	  <td>
	   <table width="100%" bgcolor="#CCCCCC" border="1"> <!--Third Table-->
 		<thead>
		  <tr>
			<th align="center" style="width:25%">POSM COVERAGE</th>
			<th align="center" style="width:25%">INCENTIVES(NAIRA)</th>
		  </tr>
		</thead>

		<tbody>

		<tr>
		  <td valign="top">

		<div style="overflow:auto; height:50px;">
         <table width="100%" bgcolor="#999999" align="left">
         <tr>
			 <td width="10%">Customer Type</td>
			 <td width="20%" align="center">Customer Coverage
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Actual</b></td>
					</tr>
				 </table>
			 </td>
			 <td width="20%" align="center">Items
				<table width="100%">
					<tr>
						<td align="center" style="background-color:#999"><b>Planned</b></td>
						<td align="center" style="background-color:#999"><b>Given</b></td>
					</tr>
				</table>
			 </td>
         </tr>
         <tr>
			 <td colspan="3" align="center" height="50px"><strong>NO RECORDS FOUND</strong></td>
         </tr>
       </table>
	   </div>

	  </td>

		<td>
        <table width="100%" bgcolor="#999999" align="right"><!-- Incentive Start Table-->
         <tr>
			 <td align="center" width="15%"><strong>ECO INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
			 <td align="center" width="15%"><strong>QUANTITY INCENTIVE</strong></td>
			 <td align="center" width="10%">-</td>
         </tr>
         </table>
	   </td>
	   </tr>

		 </tbody>
          <!-- Incentive Ends-->
        </table><!--Third Table End-->
        </td>
		</tr>
	 </table>
    </span>
   <!--</table> Main Table End-->
		</div>
 </div>
<div class="mcf"></div>
	 <table width="50%" style="clear:both">
		 <tr align="center" >
			 <td>
			 </td>
		 </tr>
	 </table>
<!-- </form> -->
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
<div id="errormsgmetrep" style="display:none;"><h3 align="center" class="myalignmetrep"></h3><button id="closebutton">Close</button></div>    
 </div>
  </div>
</div>
<?php include('../include/footer.php');?>