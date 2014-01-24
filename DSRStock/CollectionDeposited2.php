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
		if($Date=='' || $Transaction_number==''  || $DSR_Code	=='' || $bankcnt =='' || $Total_Amount =='')
		{
			header("location:CollectionDeposited.php?no=9&id=$id");exit;
		}
		else{
			$KD_Code=getKDCode();
			/*echo "<pre>";
			print_r($_REQUEST);
			echo "</pre>";

			exit;*/

			for($k=1; $k <= $bankcnt; $k++) { 

				$sno				=	$_POST["sno_".$k];
				$Bank_Name			=	$_POST["Bank_Name_".$k];
				$Challan_Number		=	$_POST["Challan_Number_".$k];
				$Challan_Date		=	$_POST["Challan_Date_".$k];
				$Currency			=	$_POST["Currency_".$k];
				$Amount_Deposited	=	$_POST["Amount_Deposited_".$k];
				
				if($_POST["Bank_Name_".$k]	== 'Cash') {
					$Challan_Number		=	'';
					$Challan_Date		=	'';
				}

				/*if($Challan_Number=='')
				{
					header("location:CollectionDeposited.php?no=9&id=$id");exit;
				}*/
				$sql	=	"UPDATE dsr_collection SET Date=NOW(),KD_Code= '$KD_Code', Transaction_number='$Transaction_number',DSR_Code='$DSR_Code',Total_Amount='$Total_Amount',Serial_Number='$sno',Bank_Name='$Bank_Name',Challan_Number='$Challan_Number',Challan_Date='$Challan_Date',Currency='$Currency',Amount_Deposited='$Amount_Deposited' WHERE id = '$id'";
			}
			
			mysql_query( $sql) or die(mysql_error());

			$sql_total	=	"UPDATE dsr_collection SET Total_Amount='$Total_Amount' WHERE Transaction_number='$Transaction_number'";

			mysql_query($sql_total) or die(mysql_error());

			header("location:CollectionDepositedview.php?no=2");
		}
	}
}
elseif($_POST['submit']=='Save'){

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";

	exit;*/
	if($Date=='' || $Transaction_number==''  || $DSR_Code	=='' || $bankcnt =='' || $Total_Amount =='')
	{
		//exit;
		header("location:CollectionDeposited.php?no=9&id=$id");exit;
	}
	else{
		$sel="select * from dsr_collection where Date LIKE '$Date%' AND Transaction_number ='$Transaction_number'";
		$sel_query=mysql_query($sel) or die(mysql_error());
		if(mysql_num_rows($sel_query)=='0') {
			
			$KD_Code=getKDCode();
			$ins_val	=	'';
			for($k=1; $k <= $bankcnt; $k++) { 

				$sno				=	$_POST["sno_".$k];
				$Bank_Name			=	$_POST["Bank_Name_".$k];
				$Challan_Number		=	$_POST["Challan_Number_".$k];
				$Challan_Date		=	$_POST["Challan_Date_".$k];
				$Currency			=	$_POST["Currency_".$k];
				$Amount_Deposited	=	$_POST["Amount_Deposited_".$k];
				
				if($_POST["Bank_Name_".$k]	== 'Cash') {
					$Challan_Number		=	'';
					$Challan_Date		=	'';
				}
				/*if($Challan_Number=='')
				{
					header("location:CollectionDeposited.php?no=9&id=$id");exit;
				}*/

				if($k == $bankcnt) {
					$ins_val	.=	"(NOW(),'$KD_Code','$Transaction_number','$DSR_Code','$Total_Amount','$sno','$Bank_Name','$Challan_Number','$Challan_Date','$Currency','$Amount_Deposited')";
				} else {
					$ins_val	.=	"(NOW(),'$KD_Code','$Transaction_number','$DSR_Code','$Total_Amount','$sno','$Bank_Name','$Challan_Number','$Challan_Date','$Currency','$Amount_Deposited'),";
				}
			}
			//echo $ins_val;
			//exit;

			echo $sql="INSERT INTO `dsr_collection`(`Date`,`KD_Code`,`Transaction_number`,`DSR_Code`,`Total_Amount`,`Serial_Number`,`Bank_Name`,`Challan_Number`,`Challan_Date`,`Currency`,`Amount_Deposited`) values $ins_val";
			mysql_query($sql) or die(mysql_error());
			header("location:CollectionDepositedview.php?no=1");
		}
		else {
			header("location:CollectionDeposited.php?no=18");
		}
	}
}

$id=$_REQUEST['id'];
$list=mysql_query("select * from dsr_collection where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$Date = $row['Date'];
	$Transaction_number = $row['Transaction_number'];
	$DSR_Code = $row['DSR_Code'];
	$KD_Code = $row['KD_Code'];
	$Bank_Name = $row['Bank_Name'];
	$Challan_Number = $row['Challan_Number'];
	$Total_Amount = $row['Total_Amount'];
	$Challan_Date = $row['Challan_Date'];
	$Currency = $row['Currency'];
	$Amount_Deposited = $row['Amount_Deposited'];
}
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainareastock">
<div class="mcf"></div>
<div align="center" class="headingsgr">COLLECTION DEPOSIT</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidationcoll" onSubmit="return checkCollection();">
 <fieldset class="alignment">
  <legend><strong>COLLECTION DEPOSIT</strong></legend>
<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="30">
    <td width="120">Date*</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
	<!-- <input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/> -->
	<input type="text" name="Date" id="Date" size="15" value="<?php if(isset($Date) && $Date != '') { echo $Date; } else { echo date('Y-m-d'); } ?>" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
    
	<tr height="40">
     <td width="120">SR Name*</td>
	 <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td><select name="DSRName" id="DSRName" onChange="loadDSRCol(this.value);">
	  <option value="" >--Select--</option>
		<?php $sel_supp		=	"SELECT DSRName,DSR_Code from dsr GROUP BY DSRName";
		$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
		while($row_supp	= mysql_fetch_array($res_supp)){ ?>
		<option value="<?php echo $row_supp[DSR_Code]; ?>" <?php if($DSR_Code == $row_supp[DSR_Code]) { echo "selected"; } ?> ><?php echo $row_supp[DSRName]; ?></option>
		<?php } ?>
		</select>&nbsp;
	  </td>
	</tr>

   </table>
   </td>
 </tr>
</table>

<!----------------------------------------------- Left Table End -------------------------------------->

<table width="50%" align="left">
 <tr>
  <td>
  <table>

	<tr height="32">
	<?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$query_oldtranum					=	"SELECT Transaction_number FROM dsr_collection ORDER BY id DESC";			
			$res_oldtranum						=	mysql_query($query_oldtranum) or die(mysql_error());
			$rowcnt_oldtranum					=	mysql_num_rows($res_oldtranum);
			//$rowcnt_oldtranum					=	0; // comment if live
			if($rowcnt_oldtranum > 0) {
				$row_oldtranum					=	mysql_fetch_array($res_oldtranum);
				$Old_Transaction_number				=	$row_oldtranum['Transaction_number'];

				$gettxnno						=	abs(str_replace("COL",'',strstr($Old_Transaction_number,"COL")));
				$gettxnno++;
				if($gettxnno < 10) {
					$createdcode	=	"00".$gettxnno;
				} else if($gettxnno < 100) {
					$createdcode	=	"0".$gettxnno;
				} else {
					$createdcode	=	$gettxnno;
				}

				$Transaction_number				=	getKDCode()."COL".$createdcode;
			} else {
				$Transaction_number				=	getKDCode()."COL001";
			}
		}
	?>
     <td width="120" nowrap="nowrap">Transaction Number*</td>
     <td><input type="text" name="Transaction_number" id="Transaction_number" size="30" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "readonly"; } else { echo ""; } ?> value="<?php echo $Transaction_number; ?>" readonly maxlength="20" autocomplete='off'/></td>
	</tr>
     
	<tr height="40">
		<td width="120">SR Code</td>
		<td><input type="text" name="DSR_Code" readonly size="30" value="<?php echo $DSR_Code; ?>" maxlength="20" autocomplete='off'/>&nbsp;
		</td>
    </tr>
       </table>
       </td>
     </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->

<table width="50%" align="left">
 <tr>
  <td>
  <table>
    <tr height="10">
    <td width="120">Bank Name*</td>
	 <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="120"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="bank_names" id="bank_names">
	<option value="" >--Select Bank--</option>
	<?php $sel_supp		=	"SELECT kd_banks,id from kd_banks";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[kd_banks]; ?>" <?php if($Bank_Name == $row_supp[kd_banks]) { echo "selected"; } ?> ><?php echo $row_supp[kd_banks]; ?></option>
	<?php } ?>
	</select></td>
    <td>
	<?php $sel_cur		=	"SELECT currency,id,symbol from currency";
	$res_cur			=	mysql_query($sel_cur) or die(mysql_error());	
	$row_cur			=	mysql_fetch_array($res_cur); ?>
	<input type="button" value="Add" class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addbank('<?php echo $row_cur[currency]; ?>');" /></td>
    </tr>
	<tr height="9">
		<td><span id="showerr" style="display:none;color:#FF0000;">Choose Bank</span><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="bankcnt" id="bankcnt" /></td>
	</tr>
   </table>
   </td>
 </tr>
</table>

<table width="50%" align="right">
 <tr>
  <td>
  <table>
    <tr height="20">
    <td width="120">Total Amount</td>
	<td><img src='../images/currency.gif' width="15px" height="15px"/></td>
    <td align="right" ><input name="Total_Amount" id="Total_Amount" value='<?php echo $Total_Amount; ?>' readonly style="background-color:#e7e7e7;text-align: right;"/>&nbsp;
	</td>
    </tr>
       </table>
       </td>
     </tr> 
</table>


</fieldset>

<!----------------------------------------------- last Table End -------------------------------------->


<table width="100%" align="left" id="banksadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } if(isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } ?>>
 <tr>
  <td>
  <div class="con condaily">
  <table>
  <thead><tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Bank Name</th><th align='center'>Teller Number</th><th align='center'>Teller Date</th><th align='center'>Currency</th><th align='center'>Amount Deposited</th></tr></tr></thead>  
  <tbody id="banksadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' id='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Bank_Name; ?>' name='Bank_Name_<?php echo $t; ?>' id='Bank_Name_<?php echo $t; ?>' /><?php echo $Bank_Name; ?></td>
		 
		<td align='center'><input type='<?php if($Bank_Name == 'Cash') { echo "hidden"; } else { echo "text"; } ?>' value='<?php echo $Challan_Number; ?>' name='Challan_Number_<?php echo $t; ?>' id='Challan_Number_<?php echo $t; ?>' autocomplete='off' /></td>
		
		<td align='center'><input type='<?php if($Bank_Name == 'Cash') { echo "hidden"; } else { echo "text"; } ?>' readonly class='datepicker' value='<?php echo $Challan_Date; ?>' name='Challan_Date_<?php echo $t; ?>' id='Challan_Date_<?php echo $t; ?>' autocomplete='off' />
		
		</td><td align='center'><input type='hidden' value='<?php echo $Currency; ?>' name='Currency_<?php echo $t; ?>' /><?php echo $Currency; ?></td><td align='right'><input type='text' onBlur='addeditamt(this.value);currencyformat(this.value,"<?php echo $t; ?>")' value='<?php echo $Amount_Deposited; ?>' name='Amount_Deposited_<?php echo $t; ?>' id='Amount_Deposited_<?php echo $t; ?>' style="text-align:right;" autocomplete='off' /><input type='hidden' value='<?php $balAmt	= $Total_Amount - $Amount_Deposited; echo $balAmt; ?>' name='balAmount' id='balAmount' /></td></tr>
	<?php } else { for($t=1;$t<=100;$t++) { ?>
		
		<tr id="bankid_<?php echo $t; ?>" style="display:none;"><td align='center'>		
		<input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Bank_Name; ?>' name='Bank_Name_<?php echo $t; ?>' id='Bank_Name_<?php echo $t; ?>' /><span id='Bank_Name_Show_<?php echo $t; ?>' ><?php echo $Bank_Name; ?></span></td><td align='center'><input type='text' value='<?php echo $Challan_Number; ?>' name='Challan_Number_<?php echo $t; ?>' id='Challan_Number_<?php echo $t; ?>' autocomplete='off' /></td><td align='center'><input type='text' readonly class='datepicker' value='<?php echo $Challan_Date; ?>' name='Challan_Date_<?php echo $t; ?>' id='Challan_Date_<?php echo $t; ?>' autocomplete='off' /></td><td align='center'><input type='hidden' value='<?php echo $row_cur[currency]; ?>' name='Currency_<?php echo $t; ?>' /><?php echo $row_cur[currency]; ?></td><td align='right'><input type='text' onBlur='addamount(this.value);currencyformat(this.value,"<?php echo $t; ?>");' style="text-align:right;" value='0' name='Amount_Deposited_<?php echo $t; ?>' id='Amount_Deposited_<?php echo $t; ?>' autocomplete='off' /><input type='hidden' value='<?php $balAmt	= $Total_Amount - $Amount_Deposited; echo $balAmt; ?>' name='balAmount' id='balAmount' /></td></tr>
	<?php } }?>
  </tbody>
   </table>
   </div>
   </td>
 </tr>
</table>
<?php if($_GET['del'] != 'del'){ ?>
<table width="50%" style="clear:both">
  <tr align="center" height="50px;">
	<td><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
		 <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
		 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
		 <input type="button" name="View" value="View" class="buttons" onclick="window.location='CollectionDepositedview.php'"/>
	</td>
  </tr>
</table>     
 <?php } ?>
 </form>

<div class="msg" align="center" <?php if($_GET['id']!='' && $_GET['del'] =='del'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
 <form action="CollectionDepositedview.php" method="post">
	 <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="hidden" name="id" id="id" class="buttonsdel" value="<?php echo $_GET[id]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='CollectionDepositedview.php'"/>
 </form>
</div>

</div>

<!---- Form End ----->

<?php include("../include/error.php"); ?>
<div class="mcf"></div>        
	 <div id="errormsgcol" style="display:none;"><h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>
	</div>
</div>
<?php include('../include/footer.php');?>