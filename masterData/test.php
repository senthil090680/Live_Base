<?php ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
EXTRACT($_POST);	
if($submit=='Save')
{
			if($Kd_Category=='')
			{
			echo "Enter Mandatory Fields";
			//		header("location:test.php");exit;
			}
else
{
				if($_GET['Product_code']==''){
				$Effective_date=date("Y-m-d");	
				$cnt=count($_POST['Product_code']);
				for($j=0;$j<$cnt;$j++){
				$Product_code=$_POST['Product_code'][$j];
				//echo("Element". $j. "contains". $_POST['Product_code'][$j]."<br/>"); 
				mysql_query("INSERT INTO kd_product(KD_Code,Kd_Category,UOM1,Product_code,Effective_date,Status_kd_product)VALUES('','$Kd_Category','$UOM1','$Product_code','$Effective_date','Checked')");
				mysql_query("UPDATE product SET 
					  Status='Checked'
					  WHERE Product_code = '$Product_code'"); 
				}
				//echo "Added Successfully";
				header("location:test.php?no=1");exit;
				}
}
}
			// exit;
			if($_GET['Product_code']!=''){
			$kdpdt1="SELECT * FROM kd_product where Product_code = '".$_GET['Product_code']."'";
			$kdpdt1_result=mysql_query($kdpdt1);
			$kdpdt1_row=mysql_fetch_array($kdpdt1_result);
			//print_r($kdpdt1_row);
			$cnt=count($_POST['Product_code']);
			if($submit=='Save')
			{
			echo $myArray = explode('|',$_POST['update_date']);
			for($i=0;$i<count($myArray);$i++){
			//echo("Element". $i. "contains". $myArray[$i]."<br/>");
			$Product_code=$_POST['Product_code'][$i];
			$upd_kdpdt_id=$_POST['kdpdt_id'][$i];
			$updateDate=$myArray[$i];
			echo "UPDATE kd_product SET 
					  KD_Code='', 
					  Kd_Category='$Kd_Category',Effective_date='$updateDate'
					  WHERE Product_code = '".$_GET['Product_code']."'";
			
			
			}
			//echo "Updated Successfully";
			//header("location:test.php?no=2");
			}
			}
			


 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>KD Product</title>
<SCRIPT language="javascript">
$(document).ready(function() {

$(function(){
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
	// alert("selectall");

          $('.Product_code').attr('checked', this.checked);
    });
 	$(".Product_code").click(function(){

		if($(".Product_code").length == $(".Product_code:checked").length) {
			$("#selectall").attr("checked", "checked");
		} else {
			$("#selectall").removeAttr("checked");
		}

	});

});
//KD PRODUCT						   
$(".Effective_date_update_submit").click(function(){
alert("kl");
		var txtData = [];
		$(".Effective_date_update").each(function() {
		txtData.push($(this).val());
		});
		$("#hdnData").val(txtData.join("|"));
        e.preventDefault();
});

$('a#editenable').click(function() {
var click_id=$(this).prev().attr('value');
//$(".Effective_date_update").each(function() {
var hid_id = '<?php echo $_GET['hid_id']; ?>';
//alert("P");
alert("CLICK"+click_id);
//alert("HID"+hid_id);
if(click_id==hid_id)
{
alert("HI");
$(this).removeAttr('disabled');
return false;
}
//});
});
});
</SCRIPT>
</head>

<body>
  <form method="post" action="" name="register">

<table width="100%"  border="1">
  <tr>
    <td>KD Category<input type="hidden" name="update_date" value="" autocomplete="off" id="hdnData" readonly> </td>
    <td><select name="Kd_Category" id="user"  autocomplete="off" style="width:100%;" value="">
			<option value="">--- Select ---</option>
			<?php 
			$list=mysql_query("select * from  kd_category"); 
			while($row=mysql_fetch_assoc($list)){
			?>
			<option value='<?php echo $row['kd_category']; ?>'<?php if($row['kd_category']==$kdpdt1_row['Kd_Category']){ echo 'selected' ; }?>
			><?php echo $row['kd_category']; ?></option>
			<?php 
			// End while loop. 
			} 
			?>
			</select></td>
    <td>Effective Date</td>
    <td><input type="text" name="Effective_date" value="<?php echo date("Y-m-d");?>" class="datepickerkdpdt" autocomplete="off" size="20" maxlength="20" disabled></td>
  </tr>
</table>
<?php
		$qry="SELECT  * FROM `product` order by id asc";
		//echo $qry="SELECT DISTINCT a.Product_description1,a.Product_description2,a.UOM1,a.*,b.id as kdpdt_id,b.Product_code as kdProduct_code,b.Effective_date as kdEffective_date FROM `product` a join`kd_product` b GROUP BY Product_description1,Product_description2,UOM1 order by id asc";
		// $qry="SELECT a.*,b.id as kdpdt_id,b.Product_code as kdProduct_code,b.Effective_date as kdEffective_date FROM `product` a join`kd_product` b order by id asc";
		$results=mysql_query($qry);
		$num_rows= mysql_num_rows($results);			
		$pager = new PS_Pagination($bd, $qry,10,10);
		$results = $pager->paginate();
		?>
		<table id="rounded-corner" class="tablesorter" align="center">
		<thead>
		<tr>
 		<th scope="col" class="rounded roundimage">Select </th>
  		<th scope="col" class="rounded roundimage">Product<br /><img src="../images/sort.png" width="13" height="13" /></th>
		<th>UOM</th>
   		<th scope="col" class="rounded roundimage">Effective Date</th>
		<th scope="col" class="rounded">Edit</th>
		</tr>
		<tr><td><input type="checkbox" name="selectall" value="" id="selectall" checked></td></tr>
		</thead>
        
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls ="class='odd'"; }
		?>		
		<tr>
		<td>
		<input type="checkbox" name="Product_code[]" value="<?php echo $fetch['Product_code'];?>" class="Product_code" <?php if($_GET['no']=='1'){echo $fetch['Status'];}else{ echo 'Checked';}?>></td>
		<td><?php echo $fetch['Product_description1'].''.$fetch['Product_description2'];?></td>
		<td><input type="hidden" name="UOM1" value="<?php echo $fetch['UOM1']?>" autocomplete="off" size="20" maxlength="20"><?php echo $fetch['UOM1'];?></td>
		<td>
		<input type="text" class="Effective_date_update" disabled>

		</td>
		<td align="right">
						<input type="text" id="hid_id" value="<?php echo $fetch['id']?>">
						<a href="test.php?Product_code=<?php echo $fetch['Product_code'];?>&hid_id=<?php echo $fetch['id']?>" id="editenable">
		<img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a></td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        
<!--Pagination  -->
 
		<?php 
		if($num_rows > 10){?>     
        <div class="paginationfile" align="center">
	    <?php 
		if(!empty($num_rows)){
		//Display the link to first page: First
		echo $pager->renderFirst()."&nbsp; ";
		//Display the link to previous page: <<
		echo $pager->renderPrev();
		//Display page links: 1 2 3
		echo $pager->renderNav();
		//Display the link to next page: >>
		echo $pager->renderNext()."&nbsp; ";
		//Display the link to last page: Last
		echo $pager->renderLast(); } else{ echo "&nbsp;"; } ?>      
		</div>   
		<?php } else{ echo "&nbsp;"; }?>

	</div>   
	<table width="50%" style="clear:both">
<tr align="center" height="50px;">
<td><input type="submit" name="submit" id="submit" class="buttons Effective_date_update_submit" value="Save" /></td>
<td><input type="button" name="clear" value="Clear" id="clear" class="buttons" onclick="return userPwd();" /></td>
<td><a href="../include/menu.php" style="text-decoration:none"><input type="button" name="cancel" id="cancel"  class="buttons" value="Cancel"/></a></td>
</tr>
</table> 
</form>
</body>
</html>
