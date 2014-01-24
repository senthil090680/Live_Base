<?php
$timestamp= time();
$img1 = $timestamp."_".$_FILES["kdlogo"]["name"];
@move_uploaded_file($_FILES["kdlogo"]['tmp_name'],"../kdlogo/".$img1);

EXTRACT($_POST);
$id=$_REQUEST['id'];
if(isset($_POST['submit'])=='Save'){
echo $sql=("UPDATE kd_information SET 
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
		  Kd_category='$Kd_category'
		  WHERE id = 1");
mysql_query( $sql);

echo "Insert Successfully";
//header("location:KD_information.php?no=2");

}


?>