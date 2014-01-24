<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

EXTRACT($_POST);
$id=$_REQUEST['id'];
if($_REQUEST['id']!=''){
	if($_POST['submit']=='Save'){
		if($Date=='')
		{
			header("location:openingstockins.php?no=9&id=$id");exit;
		}
		else{
			$KD_Code=getKDCode();

			for($k=1; $k <= $prodcnt; $k++) { 

				$sno	=	$_POST["sno_".$k];
				$pcode	=	$_POST["pcode_".$k];
				$pname	=	$_POST["pname_".$k];
				$uom	=	$_POST["uom_".$k];
				$qty	=	$_POST["qty_".$k];

				if($qty=='')
				{
					header("location:openingstockedit.php?no=9&id=$id");exit;
				}
				
				$sql	=	"UPDATE opening_stock_update SET Date='$Date',KD_Code= '$KD_Code', Product_code='$pcode',UOM1='$uom',quantity='$qty' WHERE id = '$id'";
			}				
			mysql_query( $sql) or die(mysql_error());
			header("location:openingstockview.php?no=2");
		}
	}
}
elseif($_POST['submit']=='Save'){

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";

	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";

	exit;*/

	if($Date=='' || $_FILES[excelfile][name]=='')
	{
		//exit;
		header("location:openingstockins.php?no=9&id=$id");exit;
	}
	else {			
		$KD_Code=getKDCode();
		$ins_val	=	'';

		$file=$_FILES['excelfile']['tmp_name'];

		$filename=$_FILES['excelfile']['name'];

		$fname=explode(".",$filename);
		$ext=$fname[1];
		
		$w	=	0;
		if($ext=="csv") {
			$handle=fopen($file,"r");
			while(($fileop=fgetcsv($handle,10000,",")) !== FALSE)
			{	
				if($w == 0){
					$w++;
					continue;
				}
				$KD_Code		=	$fileop[0];
				$Product_code	=	$fileop[1];
				$UOM			=	$fileop[2];
				$quantity		=	$fileop[3];

				$ins_val	=	"('$Date','$KD_Code','$Product_code','$UOM','$quantity')";
				echo $sql="INSERT INTO `opening_stock_update`(`Date`,`KD_Code`,`Product_code`,`UOM1`,`quantity`) values $ins_val";
				mysql_query($sql) or die(mysql_error());

				$KD_Code		=	'';
				$Product_code	=	'';
				$UOM			=	'';
				$quantity		=	'';
			}
		}
		header("location:openingstockview.php?no=1");
	}
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from opening_stock_update where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$Transaction_number = $row['Transaction_number'];
	$Date = $row['Date'];
	$DSR_Code = $row['DSR_Code'];
	$Product_code = $row['Product_code'];
	$sel_pname		=	"SELECT Product_description1 from product WHERE Product_code = '$Product_code'";
	$res_pname			=	mysql_query($sel_pname) or die(mysql_error());	
	$row_pname	= mysql_fetch_array($res_pname);

	$Product_name = $row_pname['Product_description1'];
	$reason = $row['reason'];
	$quantity = $row['quantity'];
	$UOM = $row['UOM1'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareastockstatic">
<div class="mcf"></div>
<div align="center" class="headingsgr">OPENING STOCK UPDATE</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="dailystockvalidation" enctype="multipart/form-data" onSubmit="return checkOpening();">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Date</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120">Date*</td>
    <td><input type="text" name="Date" size="15" value="<?php echo $Date; ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>   
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } ?> >
 <tr>
  <td>
  <div class="con">
  <table>
  <thead><tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Product Code</th><th align='center'>Product Name</th><th align='center'>UOM</th><th align='center'>Quantity</th></tr></tr></thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $id; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='pcode_<?php echo $t; ?>' /><?php echo $Product_code; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='pname_<?php echo $t; ?>' /><?php echo $Product_name; ?></td><td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='uom_<?php echo $t; ?>' /><?php echo $UOM; ?></td><td align='center'><input type='text' value='<?php echo $quantity; ?>' name='qty_<?php echo $t; ?>' id='qty_<?php echo $t; ?>' /></td></tr>
	<?php } ?>
  </tbody>
   </table>
   </div>
   </td>
 </tr>
</table>


<table width="50%" style="clear:both" >
      <tr align="center" height="50px;">
      <td <?php if($_GET['del'] != 'del'){ ?>style="display:block;"<?php }else{?>style="display:none;"<?php }?> ><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="View" value="View" class="buttons" onclick="window.location='openingstockview.php'"/>
	 </td>
      </tr>
 </table>
</form>
</div>

<div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
 <form action="openingstockview.php" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='openingstockview.php'"/>
 </form>
</div> 



<!---- Form End ----->

<?php include("../include/error.php");?>
<div class="mcf"></div>        
<div id="errormsgopen" style="display:none;"><h3 align="center" class="myalignopen"></h3><button id="closebutton">Close</button></div>
   </div>
</div>
<?php include('../include/footer.php');?>