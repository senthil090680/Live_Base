<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}

//print_r($_REQUEST);
EXTRACT($_POST);
$page=intval($_GET['page']);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
if($DSR_Code=='' || $DSRName=='' || $city==''||  $state ==''|| $Contact_Number ==''|| $Salesperson_id =='')
{
header("location:dsr.php?no=9&page=$page");exit;
}
else{
$sql=("UPDATE dsr SET 
          KD_Code= '$KD_Code', 
          KD_Name='$KD_Name',
		  DSR_Code='$DSR_Code', 
	      DSRName='$DSRName', 
          Address_Line_1='$Address_Line_1',
		  Address_Line_2='$Address_Line_2',
		  Address_Line_3='$Address_Line_3',
		  city='$city',
		  state='$state',
		  PostCode='$PostCode',
		  Contact_Number='$Contact_Number',
		  Alternate_cont_num='$Alternate_cont_num',
		  Salesperson_id='$Salesperson_id'
		  WHERE id = '$id'");
		  mysql_query( $sql);
header("location:dsr.php?no=2&page=$page");
}
}
}
elseif($_POST['submit']=='Save'){ ?>
<form action="" method="post" id="resubmitform">
<input type="hidden" name="DSR_Code" value="<?php echo $DSR_Code; ?>" />
<input type="hidden" name="DSRName" value="<?php echo $DSRName; ?>" />
<input type="hidden" name="Address_Line_1" value="<?php echo $Address_Line_1; ?>" />
<input type="hidden" name="Address_Line_2" value="<?php echo $Address_Line_2; ?>" />
<input type="hidden" name="Address_Line_3" value="<?php echo $Address_Line_3; ?>" />
<input type="hidden" name="city" value="<?php echo $city; ?>" />
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>" />
<input type="hidden" name="Contact_Number" value="<?php echo $Contact_Number; ?>" />
<input type="hidden" name="Alternate_cont_num" value="<?php echo $Alternate_cont_num; ?>" />
<input type="hidden" name="Salesperson_id" value="<?php echo $Salesperson_id; ?>" />
<input type="hidden" name="no" value="9" />
</form>

<form action="" method="post" id="dataexists">
<input type="hidden" name="DSR_Code" value="<?php echo $DSR_Code; ?>" />
<input type="hidden" name="DSRName" value="<?php echo $DSRName; ?>" />
<input type="hidden" name="Address_Line_1" value="<?php echo $Address_Line_1; ?>" />
<input type="hidden" name="Address_Line_2" value="<?php echo $Address_Line_2; ?>" />
<input type="hidden" name="Address_Line_3" value="<?php echo $Address_Line_3; ?>" />
<input type="hidden" name="city" value="<?php echo $city; ?>" />
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>" />
<input type="hidden" name="Contact_Number" value="<?php echo $Contact_Number; ?>" />
<input type="hidden" name="Alternate_cont_num" value="<?php echo $Alternate_cont_num; ?>" />
<input type="hidden" name="Salesperson_id" value="<?php echo $Salesperson_id; ?>" />
<input type="hidden" name="no" value="18" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>
<?php

if($DSR_Code=='' || $DSRName=='' || $city==''||  $state ==''|| $Contact_Number ==''|| $Salesperson_id =='')
{ ?>
<script type="text/javascript">
document.forms['resubmitform'].submit();
</script>
<?php }
else{ 
$sel="select * from dsr where DSR_Code='$DSR_Code'";
$sel_query=mysql_query($sel);
		if(mysql_num_rows($sel_query)=='0') {
		$KD_Code="KD001";
		$sql="INSERT INTO `dsr`(`KD_Code`,`KD_Name`,`DSR_Code`,`DSRName`, `Address_Line_1`,`Address_Line_2`,`Address_Line_3`,`city`,`state`,`PostCode`,`Contact_Number`,`Alternate_cont_num`,`Salesperson_id`)
values('$KD_Code','$KD_Name','$DSR_Code','$DSRName','$Address_Line_1','$Address_Line_2','$Address_Line_3','$city','$state','$PostCode','$Contact_Number','$Alternate_cont_num','$Salesperson_id')";
mysql_query( $sql);
		header("location:dsr.php?no=1&page=$page"); }
		else { ?>
        <script type="text/javascript">
		document.forms['dataexists'].submit();
		</script>
		<?php //header("location:dsr.php?no=18&page=$page");
		}
}
}
$id=$_GET['id'];
$list=mysql_query("select * from dsr where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KD_Name = $row['KD_Name'];
	$DSR_Code= $row['DSR_Code'];
	$DSRName = $row['DSRName'];
	$Address_Line_1 = $row['Address_Line_1'];
	$Address_Line_2 = $row['Address_Line_2'];
	$Address_Line_3 = $row['Address_Line_3'];
	$city = $row['city'];
	$state = $row['state'];
	$PostCode = $row['PostCode'];
	$Contact_Number = $row['Contact_Number'];
	$Alternate_cont_num = $row['Alternate_cont_num'];
	$Salesperson_id = $row['Salesperson_id'];
	}
	
	
$kdi=mysql_query("select * from kd_information");	
while($row = mysql_fetch_array($kdi)){ 
$KD_Code=$row['KD_Code'];
$KD_Name=$row['KD_Name'];
}		

?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">DSR</div>
<div id="mytableformdsr" align="center">
<form action="" method="post" id="validation">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>DSR Data</strong></legend>
  <table>
  <tr height="20">
    <td width="120" class="pclr">KD Name*</td>
    <input type="hidden" name="KD_Code" size="30" value="<?php echo $KD_Code; ?>" maxlength="20"/>  
    <td><input type="text" name="KD_Name" size="30" value="<?php echo $KD_Name; ?>" maxlength="20"/></td>
    </tr>
    <tr  height="20">
    <td  width="120">DSR Code*</td>
           <?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$dsrid					=	"SELECT DSR_Code FROM  dsr ORDER BY id DESC";			
			$dsrold					=	mysql_query($dsrid) or die(mysql_error());
			$dsrcnt					=	mysql_num_rows($dsrold);
			//$dsrcnt					=	0; // comment if live
			if($dsrcnt > 0) {
				$row_dsr					  =	 mysql_fetch_array($dsrold);
				$dsrnumber	  =	$row_dsr['DSR_Code'];

				$getdsr						=	abs(str_replace("DSR",'',strstr($dsrnumber,"DSR")));
				$getdsr++;
				if($getdsr < 10) {
					$createdcode	=	"00".$getdsr;
				} else if($getdsr < 100) {
					$createdcode	=	"0".$getdsr;
				} else {
					$createdcode	=	$getdsr;
				}

				$DSR_Code				=	"DSR".$createdcode;
			} else {
				$DSR_Code				=	"DSR001";
			}
		}
	?>
    <td><input type="text" name="DSR_Code" size="10" value="<?php echo $DSR_Code; ?>" maxlength="10" autocomplete='off'/></td>
    </tr>
    <tr  height="20">
     <td width="120">DSR Name*</td>
     <td><input type="text" name="DSRName" size="30" value="<?php echo $DSRName; ?>" maxlength="20" autocomplete='off'/></td>
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
  <legend><strong>Address</strong></legend>
  <table>
  <tr height="26">
     <td>Line1</td>
     <td><input type="text" name="Address_Line_1" size="30" value="<?php echo $Address_Line_1; ?>"  maxlength="50" autocomplete='off'/></td>
     </tr>
     <tr height="26">
     <td>Line2</td>
      <td><input type="text" name="Address_Line_2" size="30" value="<?php echo $Address_Line_2; ?>" maxlength="50" autocomplete='off'/></td>
      </tr>
      <tr height="26">
      <td>Line3</td>
     <td><input type="text" name="Address_Line_3" size="30" value="<?php echo $Address_Line_3; ?>"  maxlength="50" autocomplete='off'/></td>
     </tr>
      <tr height="26">
      <td>City*</td>
     <td><select name="city">
        <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from city"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['city']; ?>" <? if($row_list['city']==$city){ echo "selected"; } ?>><? echo $row_list['city']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select></td>
     </tr>
           <tr height="26">
      <td>State*</td>
     <td>
      <select name="state">
         <option value="">--- Select ---</option>
        <?php 
      //  include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from state"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['state']; ?>" <? if($row_list['state']==$state){ echo "selected"; } ?> ><? echo $row_list['state']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     
     
     </td>
     </tr>
           <tr height="26">
      <td>Post Code</td>
     <td><input type="text" name="PostCode" size="30" value="<?php echo $PostCode; ?>" maxlength="20" autocomplete='off'/></td>
 
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
     <td>Contact Number*</td>
     <td><input type="text" name="Contact_Number" size="30" value="<?php echo $Contact_Number; ?>"  maxlength="20" autocomplete='off'/></td>
     </tr>
     <tr height="20">
     <td>Alt Cont Number</td>
      <td><input type="text" name="Alternate_cont_num" size="30" value="<?php echo $Alternate_cont_num; ?>"  maxlength="20" autocomplete='off'/></td>
      </tr>
      <tr height="20">
      <td>SalesRep Code*</td>
     <td>
      <select name="Salesperson_id">
        <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from sales_representative"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['Salesperson_id']; ?>" <? if($row_list['Salesperson_id']==$Salesperson_id){ echo "selected"; } ?>><? echo $row_list['Salesperson_id']; ?></option>
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
 <!----------------------------------------------- last Table End -------------------------------------->
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
     <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" onclick="return dsrclear();"/>&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
      </tr>
 </table>     
</form>
</div>

<!---- Form End ----->



<?php include("../include/error.php");?>
  <div id="search">
        <form action="" method="get">
        <input type="text" name="DSRName" value="<?php $_GET['DSRName']; ?>" autocomplete='off' placeholder='Search By DSR Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
        <?php
		if($_GET['delId']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['delId'];
		$query = "DELETE FROM `dsr` WHERE id = $id";
        //Run the query
        $result = mysql_query($query) or die(mysql_error());
        header("location:dsr.php?no=3");
		}
		 }
		?> 
	    <?php
		if($_GET['submit']!='')
		{
		$var = @$_GET['DSRName'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `dsr` where DSRName like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `dsr` order by id asc"; 
		}
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table id="sort" class="tablesorter" width="100%">
		<thead>
		<tr>
		<th>DSR Code</th>
		<th class="rounded">DSR Name<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Address</th>
        <th>Contact Number</th>
        <th align="right">Edit/Del</th>
		</tr>
		</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		?>
		<tr>
		<td><?php echo $fetch['DSR_Code'];?></td>
	    <td><?php echo $fetch['DSRName'];?></td>
        <td><?php echo $fetch['Address_Line_1'];?></td>
        <td><?php echo $fetch['Contact_Number'];?></td>
       	<td align="right">
        <a href="dsr.php?id=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="dsr.php?id=<?php echo $fetch['id'];?>&delId=<?php echo $fetch['id'];?>&page=<?php echo intval($_GET['page']);?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
		</th>
		</tr>
        </table>
      </div> 
     <div class="msg" align="center" <?php if($_GET['delId']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='dsr.php'"/>
      </form>
     </div>  
   </div>
</div>
<?php include('../include/footer.php'); ?>