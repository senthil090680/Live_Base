<?php
session_start();
ob_start();
include('../include/header.php');
include("../include/ajax_pagination.php");
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
if($_REQUEST['device_description']!='')
{
	$var = @$_REQUEST['device_description'] ;
	$trimmed = trim($var);	
	$qry="SELECT * FROM `route_master` where route_desc like '%".$trimmed."%' order by route_desc asc";
}
else
{ 
	$qry="SELECT * FROM `route_master`"; 
}
$results=mysql_query($qry);
$num_rows= mysql_num_rows($results);			

$params			=	$device_description."&".$sortorder."&".$ordercol;

/********************************pagination start***********************************/
$strPage = $_REQUEST[page];
//$params = $_REQUEST[params];

//if($_REQUEST[mode]=="Listing"){
//$Num_Rows = mysql_num_rows ($res_search);

########### pagins

$Per_Page =5;   // Records Per Page

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




EXTRACT($_POST);
$id=$_REQUEST['id'];
$page=intval($_REQUEST['page']);
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
$sql=("UPDATE route_master SET 
          KD_Code= '$KD_Code', 
          KD_Name='$KD_Name', 
          route_code='$route_code',
		  route_desc='$route_desc',
		  location='$location',
		  route_distance='$route_distance'
		  WHERE id = $id");
mysql_query( $sql);
header("location:route.php?no=2&page=$page");
}
}
elseif($_POST['submit']=='Save'){ ?>
<form action="" method="post" id="resubmitform">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="route_code" value="<?php echo $route_code; ?>" />
<input type="hidden" name="route_desc" value="<?php echo $route_desc; ?>" />
<input type="hidden" name="location" value="<?php echo $location; ?>" />
<input type="hidden" name="route_distance" value="<?php echo $route_distance; ?>" />
<input type="hidden" name="no" value="9" />
 
</form>
<form action="" method="post" id="dataexists">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="route_code" value="<?php echo $route_code; ?>" />
<input type="hidden" name="route_desc" value="<?php echo $route_desc; ?>" />
<input type="hidden" name="location" value="<?php echo $location; ?>" />
<input type="hidden" name="route_distance" value="<?php echo $route_distance; ?>" />
<input type="hidden" name="no" value="18" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>
<?php
if($route_code=='' || $route_desc==''  || $location=='')
{?>
<script type="text/javascript">
document.forms['resubmitform'].submit();
</script>
<?php
}
else{
$sel="select * from route_master where route_code ='$route_code'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$sql="INSERT INTO `route_master`(`KD_Code`,`KD_Name`,`route_code`,`route_desc`,`location`,`route_distance`)
                            values('$KD_Code','$KD_Name','$route_code','$route_desc','$location','$route_distance')";
        mysql_query( $sql);
        header("location:route.php?no=1&page=$page");
		}
		else { ?>
         <script type="text/javascript">
		document.forms['dataexists'].submit();
		</script>
        <?php 
		//header("location:route.php?no=18");
		}
}
}
$id=$_GET['id'];
$list=mysql_query("select * from route_master where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KD_Name = $row['KD_Name'];
	$route_code = $row['route_code'];
	$route_desc = $row['route_desc'];
	$location = $row['location'];
	$route_distance = $row['route_distance'];
	}

$kdi=mysql_query("select * from kd_information");	
while($row = mysql_fetch_array($kdi)){ 
$KD_Code=$row['KD_Code'];
$KD_Name=$row['KD_Name'];
}	

?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">
#errormsgcol {
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

.myaligncol {
	 clear:both;
	padding-top:10px;
	margin:0 auto;
	color:#FF0000;
}

#closebutton {
  position:relative;
  top:-35px;
  right:-190px;
  border:none;
  background:url(../images/close_pop.png) no-repeat;
  color:transparent;
  }
  
</style>


<script type="text/javascript">
function validateroute() {
	var Description				=	$('#route_desc').val();
	var Location         		=	$('#location').val();
	//var Distance        		=	$('#distance').val();
	

	if(Description == ''){
		$('.myaligncol').html('ERR : Enter Description');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(Location == ''){
		$('.myaligncol').html('ERR : Enter Location');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

/*  	if(Distance == ''){
		$('.myaligncol').html('ERR : Enter Distance');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
*/
	$('#errormsgcol').css('display','none');
	//return false;
}



function rousearch(page){  // For pagination and sorting of the SR search in view page
	var route_desc	=$("input[name='route_desc']").val();
	$.ajax({
		url : "rouajax.php",
		type: "get",
		dataType: "text",
		data : { "route_desc" : route_desc, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#rouid").html(trimval);
		}
	});
}

function rouviewajax(page,params){   // For pagination and sorting of the SR view page
	var splitparam	=	params.split("&");
	var route_desc   	    =	splitparam[0];
	var sortorder	        =	splitparam[1];
	var ordercol	        =	splitparam[2];
	$.ajax({
		url : "rouajax.php",
		type: "get",
		dataType: "text",
		data : {"route_desc" : route_desc, "sortorder" : sortorder, "ordercol" : ordercol, "page" : page },
		success : function(dataval) {
			var trimval		=	$.trim(dataval);
			//alert(trimval);
			$("#rouid").html(trimval);
		}
	});
}

</script>


<div id="mainarea">
<div class="mcf"></div>
 <div style="padding-left:20px"><a href="viewcustomer.php?id=<?php echo $fetch['id'];?>" class="link" rel="facebox"><input type="button" name="view" id="view"  class="buttonsdel" value="View Customer" <?php if($_GET['id']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>/></a></div>
<div class="clearfix"></div>
<div align="center" class="headingsgr">Route</div>

<div id="mytableformgr" align="center">
<form  method="post" onsubmit="return validateroute();">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Route Data</strong></legend>
  <table>
  <tr height="30">
    <td width="120" class="pclr">KD Name*</td>
    <td>
    <input type="hidden" name="KD_Code" size="30" value="<?php echo $KD_Code; ?>" maxlength="20"/>  
    <input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" autocomplete='off' maxlength="20"/></td>
    </tr>
    <tr  height="30">

     <?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$rouid					=	"SELECT route_code FROM route_master ORDER BY id DESC";
			$rouold					=	mysql_query($rouid) or die(mysql_error());
			$roucnt					=	mysql_num_rows($rouold);
			//$roucnt					=	0; // comment if live
			if($roucnt > 0) {
				$row_rou					  =	 mysql_fetch_array($rouold);
				$devnumber	  =	$row_rou['route_code'];

				$getrouno						=	abs(str_replace("ROU",'',strstr($devnumber,"ROU")));
				$getrouno++;
				if($getrouno < 10) {
					$createdcode	=	"00".$getrouno;
				} else if($getrouno < 100) {
					$createdcode	=	"0".$getrouno;
				} else {
					$createdcode	=	$getrouno;
				}

				$route_code 				=	"ROU".$createdcode;
			} else {
				$route_code 				=	"ROU001";
			}
		}
	?>

    <td  width="120">Route 	Code</td>
    <td><input type="text" name="route_code" size="10" value="<?php echo $route_code; ?>"  readonly="readonly" autocomplete='off' maxlength="10"/></td>
    </tr>
    <tr  height="30">
     <td width="120">Description*</td>
     <td><input type="text" name="route_desc" size="30"  id="route_desc"  value="<?php echo $route_desc; ?>" autocomplete='off' maxlength="20"/></td>
   </tr>
     <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     </tr> 
   </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="right">
 <tr>
  <td>
   <fieldset class="alignment">
  <legend><strong>Route parameters</strong></legend>
  <table>
  <tr height="30">
     <td>Location*</td>
     <td>
      <select name="location" id="location">
        <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from location"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['location']; ?>" <?php if($row_list['location']==$location){ echo "selected"; } ?>><?php echo $row_list['location']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
      
      </td>
     </tr>
      <tr height="40">
      <td>Distance</td>
     <td><input type="text" name="route_distance" size="12" id="distance"  value="<?php echo $route_distance; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
     
     <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     </tr> 
     
     <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     </tr> 
    
     </table>
 </fieldset>
</td>
</tr>
</table>
 <!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" class="buttons" value="Clear" id="clear" onclick="window.location='route.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      </tr>
 </table>     
</form>
</div>

 <div id="search">
        <input type="text" name="route_desc" value="<?php echo $_REQUEST['route_desc']; ?>" autocomplete='off' placeholder='Search By Route Desc'/>
        <input type="button" class="buttonsg" onclick="devsearch('<?php echo $Page; ?>');" value="GO"/>
 </div>
        

<!---- Form End ----->
<?php include("../include/error.php");?>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>



<div class="clearfix"></div>
    

		<?php
		if($_GET['id']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['id'];
		$query = "DELETE FROM `route_master` WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:route.php?no=3");
		}
		 }
		?>
       <div id="rouid"> 
		<div class="con" width="100%">
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
			  $paramsval	=	$route_desc."&".$sortorderby."&route_desc"; ?>
        <th>KD Name</th>
        <th nowrap="nowrap" class="rounded" onClick="rouviewajax('<?php echo $Page;?>','<?php echo $paramsval;?>');">Route Description<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Route Code</th> 
        <th>Location</th>
        <th align="right">Mod/Del</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php
        if(!empty($num_rows)){
        $c=0;$cc=1;
        while($fetch = mysql_fetch_array($results_dsr)) {
        if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
        $id= $fetch['id'];
        $city= $fetch['City'];
        $lga= $fetch['lga'];
        ?>
		<tr>
		<td><?php echo $fetch['KD_Name'];?></td>
        <td><?php echo $fetch['route_desc'];?></td>
	    <td><?php echo $fetch['route_code'];?></td>
       <td><?php echo $fetch['location'];?></td>
       	<td align="right">
        <a href="route.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="route.php?id=<?php echo $fetch['id'];?>&delId=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>
        </tr>
		<?php $c++; $cc++; }		 
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
				
				rendering_pagination_common($Num_Pages,$Page,$Prev_Page,$Next_Page,$params,'rouviewajax');   //need to uncomment
				
			} else { 
				echo "&nbsp;"; 
			} ?>      
			</th>
			</tr>
			</table>
		  </div>
     <div class="msg" align="center" <?php if($_GET['delId']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='route.php'"/>
      </form>
     </div>  
   </div>
</div>
<?php include('../include/footer.php'); ?>