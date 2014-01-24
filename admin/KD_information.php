<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}


$img1 =   $_FILES["kdlogo"]["name"];
@move_uploaded_file($_FILES["kdlogo"]['tmp_name'],"../kdlogo/".$img1);
$filename = $_FILES["kdlogo"]["name"];  

EXTRACT($_POST);
if(isset($_POST['submit'])=='Save'){
$sql=("UPDATE kd_information SET 
          KD_Code= '$KD_Code', 
          KD_Name='$KD_Name', 
          Address_Line_1='$Address_Line_1',
		  Address_Line_2='$Address_Line_2',
		  Address_Line_3='$Address_Line_3',
		  City='$City',
		  Pin='$Pin',
		  Contact_Person='$Contact_Person',
		  Contact_Number='$Contact_Number',
		  Email_ID='$Email_ID',
		  kd_category='$kd_category',
		  kdlogo='$filename'
		  WHERE id = 1");
mysql_query( $sql);

header("location:KD_information.php?no=2");

}


//Query to select data
$id=$_GET['id'];
$list=mysql_query("select * from  kd_information where id = 1"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KD_Name = $row['KD_Name'];
	$Address_Line_1 = $row['Address_Line_1'];
	$Address_Line_2 = $row['Address_Line_2'];
	$Address_Line_3 = $row['Address_Line_3'];
	$City = $row['City'];
	$Pin = $row['Pin'];
	$Contact_Person = $row['Contact_Person'];
	$Contact_Number = $row['Contact_Number'];
	$Email_ID = $row['Email_ID'];
	$kd_category = $row['kd_category'];
	$kdlogo = $row['kdlogo'];
	
	}
	

?>
<!------------------------------- Form -------------------------------------------------->
<script type="text/javascript">
function kdinform()
{
	var val=$('.KD_Name option:selected').text();
	 $.ajax({
            url: 'get_kdcode.php?val=' + val,
            success: function(data) {
				//alert(data);
				var value=$.trim(data);//To Remove White Space in string
				var value1=data.substring(0,value.length-1);//To return part of the string
				var list= value1.split("|"); 
				for (var i=0; i<list.length; i++) {
					var arr_i= list[i].split("^");
					$(".KD_Name").val(arr_i[0]);
					$(".KD_Code").val(arr_i[1]);
					$(".Address_Line_1").val(arr_i[2]);
					$(".Address_Line_2").val(arr_i[3]);
					$(".Address_Line_3").val(arr_i[4]);
					$(".City").val(arr_i[5]);
					$(".Pin").val(arr_i[6]);
					$(".Contact_Person").val(arr_i[7]);
					$(".Contact_Number").val(arr_i[8]);
					$(".Email_ID").val(arr_i[9]);
					$(".kd_category").val(arr_i[10]);
				}

			}
        });

}
</script>
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Key Distributor Information</div>
<div id="mytableformgrkd" align="center">
<form action="" method="POST" enctype="multipart/form-data">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>KD</strong></legend>
  <table>
  <tr height="25">
    <td width="110" class="pclr">KD Name*</td>
    <td>
      <?php 
        //include('../include/config.php');
	    $list=mysql_query("select * from kd"); 
        // Show records by while loop. 
	    // End while loop. 
        ?>
        <select name="KD_Name" class="KD_Name"  autocomplete="off" onChange="return kdinform();" >
        <option value="">--- Select ---</option>
		<?php 		
		while($row_list=mysql_fetch_assoc($list)){ 
		$id=$row_list['KD_Name'];
		?>
        <option value="<?php echo $row_list['KD_Name']; ?>" <? if($row_list['KD_Name']==$KD_Name){ echo "selected"; } ?>><? echo $row_list['KD_Name']; ?></option>
        <?php  } ?>
        </select>    
    
     </td>
    </tr>
    <tr  height="25">
    <td  width="110">KD Code</td>
    <td><input type="text" name="KD_Code"  class="KD_Code" size="10" value="<?php echo $KD_Code; ?>" autocomplete='off' maxlength="10"/></td>
    </tr>
   </table>
 </fieldset>
 <fieldset class="alignment">
 
 <legend><strong>Contact</strong></legend>
  <table>
  <tr  height="30">
    <td width="110">Contact Person</td>
    <td><input type="text" name="Contact_Person" class="Contact_Person" size="30" value="<?php echo $Contact_Person; ?>" autocomplete='off' maxlength="20"/></td>
    </tr>
    <tr  height="30">
    <td  width="110">Contact Number</td>
    <td><input type="text" name="Contact_Number" class="Contact_Number" size="30" value="<?php echo $Contact_Number; ?>" autocomplete='off' maxlength="10"/></td>
    </tr>
    <tr  height="30">
    <td  width="110">Email ID</td>
    <td><input type="text" name="Email_ID" class="Email_ID" size="30" value="<?php echo $Email_ID; ?>" autocomplete='off' maxlength="20"/></td>
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

<table width="50%" align="right">
 <tr>
  <td>
 
   <fieldset class="alignment">
  <legend><strong>Address</strong></legend>
  <table>
  <tr height="25">
     <td width="110">Line1</td>
     <td><input type="text" name="Address_Line_1"  class="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
     <tr height="25">
     <td>Line2</td>
      <td><input type="text" name="Address_Line_2"  class="Address_Line_2" size="30" value="<?php echo $Address_Line_2; ?>" autocomplete='off' maxlength="20"/></td>
      </tr>
      <tr height="25">
      <td>Line3</td>
     <td><input type="text" name="Address_Line_3"  class="Address_Line_3" size="30" value="<?php echo $Address_Line_3; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
     <tr  height="25">
     <td>City</td>
     <td><input type="text" name="City"  class="City" size="30" value="<?php 
	$city=mysql_query("select * from city where id= '$City'"); 
	$row=mysql_fetch_array($city);
	$cityname=$row['city'];
	if($City = $cityname){echo $cityname;}?>" autocomplete='off' maxlength="20"/></td>
     <td width="100">PostCode</td>
    <td><input type="text" name="Pin" class="Pin" size="10" value="<?php echo $Pin; ?>" autocomplete='off' maxlength="10"/></td>
  </tr>
   </table>
 </fieldset>
 <fieldset class="alignment">
<legend><strong>Parameter</strong></legend>
  <table>
  <tr height="30">
     <td  width="80" class="align">KD Catgory</td>
     <td><input type="text" name="kd_category" class="kd_category" size="10" value="<?php 
	$kdcs=mysql_query("select * from kd_category where id= '$kd_category'"); 
	$row=mysql_fetch_array($kdcs);
	$kdc=$row['kd_category'];
	if($kd_category = $kdc){echo $kdc;}?>" autocomplete='off' maxlength="10"/></td>
    </tr>
    <tr>
     <td  width="40" class="align">Logo</td>
     <td>
     <input type="File" name="kdlogo" class="kdlogo"  value="<?php echo $kdlogo; ?>" autocomplete='off' maxlength="10"/>
     <?php echo $kdlogo; ?>
     </td>
     </tr>
    
    </table>
 </fieldset>
 
</td>
</tr>
</table>
<table width="50%" style="clear:both">
      <tr align="center" height="70px;">
      <td><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" id="Clear"  class="buttons" value="Clear" onclick="return kdclr()";/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/></td>
      </tr>
 </table>     
</form>
</div>
<?php include("../include/error.php");?>
</div>


<?php include('../include/footer.php'); ?>