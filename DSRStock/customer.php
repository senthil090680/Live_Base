<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}

EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
$Effective_date=date("Y-m-d",strtotime($Effective_date));	
$sql=("UPDATE kd SET 
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
		  KD_Category='$KD_Category'
		  WHERE id = $id");
mysql_query( $sql);
header("location:kd.php?no=2");
}
}
elseif($_POST['submit']=='Save'){
$sel="select * from kd where KD_Name ='$KD_Name'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$Effective_date=date("Y-m-d",strtotime($Effective_date));		
		$active='active';
		$sql="INSERT INTO `kd`(`KD_Code`,`KD_Name`, `Address_Line_1`,`Address_Line_2`,`Address_Line_3`,`City`,`Pin`,`Contact_Person`,`Contact_Number`,`Email_ID`,`KD_Category`,`Status`)
values('$KD_Code','$KD_Name','$Address_Line_1','$Address_Line_2','$Address_Line_3','$City','$Pin','$Contact_Person','$Contact_Number','$Email_ID','$KD_Category','$active')";
mysql_query( $sql);
        header("location:kd.php?no=1");
		}
		else {
		header("location:kd.php?no=18");
		}
}

$id=$_GET['id'];
$list=mysql_query("select * from kd where id= '$id'"); 
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
	$KD_Category = $row['KD_Category'];
	}

?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Customer</div>
<div id="mytableformgr2" align="center">
<form action="" method="post" id="validation">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Customer Data</strong></legend>
  <table>
  <tr height="20">
    <td width="120" class="pclr">KD Name*</td>
    <td><input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" class="required" maxlength="20"/></td>
    </tr>
    <tr  height="20">
    <td  width="120">Code</td>
    <td><input type="text" name="Address_Line_1" size="10" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="10"/></td>
    </tr>
    <tr  height="20">
     <td width="120">Customer Name</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
   </table>
 </fieldset>
   </td>
 </tr>
</table>
 <!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="right">
 <tr>
  <td>
   <fieldset class="alignment2">
  <legend><strong>Address</strong></legend>
  <table>
  <tr height="20">
     <td>Line1</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr>
     <tr height="20">
     <td>Line2</td>
      <td><input type="text" name="Address_Line_2" size="30" value="<?php echo $Address_Line_2; ?>" class="required" maxlength="20"/></td>
      </tr>
      <tr height="20">
      <td>Line3</td>
     <td><input type="text" name="Address_Line_3" size="30" value="<?php echo $Address_Line_3; ?>" class="required" maxlength="20"/></td>
    </tr>
    
      <tr height="20">
      <td>Location</td>
     <td>
        <select name="location">
        <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from location"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['location']; ?>" <? if($row_list['location']==$City){ echo "selected"; } ?>><? echo $row_list['location']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
    
      <tr height="20">
      <td>LGA</td>
     <td>
        <select name="Lga">
        <option value="">--- Select ---</option>
        <?php 
        include('config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from lga"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['lga']; ?>" <? if($row_list['lga']==$lga){ echo "selected"; } ?>><? echo $row_list['lga']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
     <tr height="20">
      <td>City</td>
     <td>
        <select name="City">
        <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from city"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['city']; ?>" <? if($row_list['city']==$City){ echo "selected"; } ?>><? echo $row_list['city']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
      <tr height="20">
      <td>State</td>
     <td>
      <select name="State">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from state"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['state']; ?>" <? if($row_list['state']==$State){ echo "selected"; } ?> ><? echo $row_list['state']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     </td>
     </tr>
     <tr height="20">
      <td>Province</td>
     <td>
      <select name="province">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from province"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['province']; ?>" <? if($row_list['province']==$province){ echo "selected"; } ?> ><? echo $row_list['province']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     </td>
     </tr>
      <tr height="26">
      <td>Post Code</td>
     <td><input type="text" name="Address_Line_3" size="30" value="<?php echo $Address_Line_3; ?>" class="required" maxlength="20"/></td>
     </table>
 </fieldset>
<!----------------------------------------------------------- Category --------------------------------------------->
  
  <fieldset class="alignment">
  <legend><strong>Category</strong></legend>
  <table>
    <tr height="20">
     <td>Category1</td>
     <td>
     <select name="category1">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category1"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['category1']; ?>" <? if($row_list['category1']==$category1){ echo "selected"; } ?> ><? echo $row_list['category1']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
        </td>
     </tr>
      <tr height="20">
     <td>Category2</td>
     <td>
         <select name="category2">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category2"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['category2']; ?>" <? if($row_list['category2']==$category1){ echo "selected"; } ?> ><? echo $row_list['category2']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
      </td>
     </tr> 
  <tr height="20">
     <td>Category2</td>
     <td>
              <select name="category2">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category2"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['category2']; ?>" <? if($row_list['category2']==$category1){ echo "selected"; } ?> ><? echo $row_list['category2']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
   </td>
     </tr>
   </table>
 </fieldset>
</td>
</tr>
</table>
 <!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" align="right">
 <tr>
  <td>
   <fieldset class="alignment">
  <legend><strong>Contact</strong></legend>
  <table>
    <tr height="20">
     <td>GPS</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr>
      <tr height="20">
     <td>Contact Person</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr> 
  <tr height="20">
     <td>Contact Number</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr>
        <tr height="20">
     <td>Alt Cont Person</td>
      <td><input type="text" name="Address_Line_2" size="30" value="<?php echo $Address_Line_2; ?>" class="required" maxlength="20"/></td>
      </tr>
     <tr height="20">
     <td>Alt Cont Number</td>
      <td><input type="text" name="Address_Line_2" size="30" value="<?php echo $Address_Line_2; ?>" class="required" maxlength="20"/></td>
      </tr>
     </table>
 </fieldset>
  <fieldset class="alignment">
  <legend><strong>Route Data</strong></legend>
  <table>
    <tr height="20">
     <td>Route</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr>
      <tr height="20">
     <td>Alternate Route</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr> 
  <tr height="20">
     <td>DSR Code</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr>
   </table>
 </fieldset>
</td>
</tr>
</table> 
 <!----------------------------------------------- last Table End -------------------------------------->
  <fieldset class="alignment">
  <legend><strong>Customer Param</strong></legend>
  <table>
    <tr height="20">
     <td>Customer Type</td>
     <td>
         <select name="customer_type">
         <option value="">--- Select ---</option>
        <?php 
        include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from customer_type");        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['customer_type']; ?>" <? if($row_list['customer_type']==$category1){ echo "selected"; } ?> ><? echo $row_list['customer_type']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
    </td>
    <td>&nbsp;&nbsp;Discount</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
      <td>&nbsp;&nbsp;Max Discount</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>" class="required" maxlength="20"/></td>
     </tr> 
   </table>
 </fieldset> 
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" id="reset"  class="buttons" value="Clear" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="view" id="view"  class="buttons" value="View" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='kd.php'"/></td>
      </tr>
 </table>     
</form>
</div>
<!---- Form End ----->
</div>
<?php include('../include/footer.php'); ?>