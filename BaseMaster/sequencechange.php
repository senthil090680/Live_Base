<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
EXTRACT($_POST);	
//Insert Query
$page=intval($_GET['page']);
$id=$_REQUEST['id'];
EXTRACT($_POST);
$sequence_number = $_POST['sequence_number'];
$route_code = $_POST['route_code'];
$page=intval($_GET['page']);

if($_POST['Update']){
$query =mysql_query("select * from customer_count");
$res=mysql_fetch_array($query);
$route=$res['route'];
$ids=$res['id'];
if($sequence_number=='')
{
header("location:sequencechange.php?no=9");
}
else{
for($i=0;$i<sizeof($sequence_number);$i++){	
echo $sql=("UPDATE customer_count SET
		route= '$route',
		DSRName='$DSRName',
		customer_count='$customer_count',
		sequence_number='$sequence_number[$i]',
		Customer_Name='$Customer_Name[$i]'
		WHERE sequence_number='$sequence_number[$i]'");
mysql_query( $sql);
header("location:sequencechange.php?no=2&$page=$page");
}
}
}
if($_POST['submit']=='Save')
{
			if($sequence_number=='')
			{
			header("location:sequencechange.php?no=9");
			}
			else
			{	
			   
			    $sel1="select * from  customer_count where route ='$route'"; 
				$sel_query1=mysql_query($sel1);
				$row=mysql_num_rows($sel_query1);
			  	for($i=0;$i<sizeof($sequence_number);$i++){
				if($row === 0) {	
				echo $query=mysql_query("INSERT INTO customer_count(KD_Code,route,DSRName,customer_count, sequence_number, Customer_Name)
				VALUES('".$KD_Code."','".$route."','".$DSRName."','".$customer_count."', 
				'".$sequence_number[$i]."','".$Customer_Name[$i]."')");
				header("location:sequencechange.php?no=1&page=$page");
			      }
				 else
				  { 
				 header("location:sequencechange.php?no=18&page=$page");exit;
                 }
		    }
      }
}


$id=$_GET['id'];
$list=mysql_query("select * from price_master where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
    $kd_category = $row['kd_category']; 
	$kdprice = $row['kdprice']; 
	$Product_code = $row['Product_code'];
	$Product_description1 = $row['Product_description1'];
	$UOM1 = $row['UOM1'];
	$Price = $row['Price'];
	$Effective_date = $row['Effective_date'];
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
				logProgress();
				function logProgress()
						  	
				{
					var process = $("#route").val();
					var posting = $.post("log.php", {process: process});

					posting.done(function(data) {
					   $("#log").html(data);
					});
					
									
			var val=$('#route option:selected').text();
	        $.ajax({
            url: 'get_cuscount.php?val=' + val,
            success: function(data) {
				//alert(data);
				var value=$.trim(data);//To Remove White Space in string
				var value1=data.substring(0,value.length-1);//To return part of the string
				var list= value1.split("|"); 
				for (var i=0; i<list.length; i++) {
					var arr_i= list[i].split("^");
					//alert(arr_i[6]);
					$("#routename").val(arr_i[0]);
					$("#dsr").val(arr_i[1]);
					$("#cuscount").val(arr_i[2]);
			}

			}
        });
				}



//Validate of Non Empty Fields
function validateform() {
	//alert(232);
	var KdCategory					=	$('#kd_category').val();
	var kdprice			            =	$('#kdprice').val();
	var EffectiveDate				=	$('#Effective_date').val();
	var price						=	array($('#price')).val();
	if(KdCategory == ''){
		$('.myaligncol').html('ERR : Select KD Category');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(kdprice == ''){
		$('.myaligncol').html('ERR : Select KD price');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(EffectiveDate == ''){
		$('.myaligncol').html('ERR : Select Effective Date');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	if(price == '') {
	   $('.myaligncol').html('ERR : Enter Price');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
			$('#errormsgcol').hide();
		},5000);
		return false;
	}
	$('#errormsgcol').css('display','none');
	//return false;
}

</script>
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingskdp">Sequence Change</div>
<div class="mytable3">
<div class="mcf"></div>
<form method="post" action="">        
<table width="70%" align="center" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td width="100">Route*<td>
    <td>
     <input type="hidden" name="KD_Code" id="KD_Code" value="<?php echo $KD_Code ?>" />
     <select name="route" class="route" id="route"  autocomplete="off"  value="" onchange="logProgress()"; > 
			<option value="">--- Select ---</option>
			<?php 
			$list=mysql_query("select * from  route_master"); 
			while($row=mysql_fetch_assoc($list)){
			$cus=$row['Customer_Name'];
		     $cuscount=count($cus);
			?>
		<option value='<?php echo $row['route_code']; ?>'<?php if($row['route_code']==$route){ echo 'selected' ; }?>><?php echo $row['route_code']; ?></option>
		<?php }?>
		</select>
    </td>
     <td>Route Name</td>
    <td width=100><input type="text" name="routename" id="routename" size="10" value=""  autocomplete="off" /></td>
    
    <td>DSR</td>
    <td width=100><input type="text" name="DSRName" id="dsr" size="10" value=""  autocomplete="off" /></td>
     <td>Customer Count</td>
    <td width=100><input type="text" name="customer_count" id="cuscount" size="10" value=""  autocomplete="off" /></td>
  </tr>
</table>
<div class="mcf"></div>
  <div id="log"> </div>
  
 <table width="100%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" name="Update" id="Update" class="buttons" value="Update" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      </tr>
 </table>
 <?php include("../include/error.php");?>
</form>   
</div>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>
</div>   
</div>
<?php include('../include/footer.php'); ?>