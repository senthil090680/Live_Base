<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}

extract($_POST);
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Stock Receipts</div>
<div id="mytableformreceipts" align="center">
<form action="" method="post" id="validation">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Stock Receipts</strong></legend>
  <table>
  <!--<tr height="20">
    <td width="120" class="pclr">KD Code*</td>
    <td><input type="text" name="KDName" size="30" value="Bolarwina" maxlength="20"/></td>
    </tr>-->
    <tr  height="20">
    <td  width="120">Date*</td>
    <td><input type="text" name="Date" size="10" value="" class="datepicker" readonly autocomplete='off'/></td>
    </tr>
    <tr  height="20">
     <td width="120" nowrap="nowrap">Transaction Number*</td>
     <td id="TransDetails"><input type="text" name="TxnNum" size="10" value="" autocomplete='off'/>
	 
	 
	 <!--<select name="TxnNum" id="TxnNum">
	 <option value="">--Select--</option>
	 <?php $qry="SELECT *  FROM `stock_receipts` GROUP BY Transaction_number order by id asc";
		$results=mysql_query($qry);
		$num_rows= mysql_num_rows($results);			
		while($fetch = mysql_fetch_array($results)) { ?>
	 <option value="<?php echo $fetch[Transaction_number]; ?>"><?php echo $fetch[Transaction_number]; ?></option>
	 <?php } ?>
	 </select>	 -->
	 </td>
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
  <legend><strong>Supplier</strong></legend>
  <table>
  <!--<tr height="20">
    <td width="120" class="pclr">KD Code*</td>
    <td><input type="text" name="KDName" size="30" value="Bolarwina" maxlength="20"/></td>
    </tr>-->
    <tr  height="20">
    <td  width="120">Category*</td>
    <td><input type="text" name="SupCat" size="10" value="" autocomplete='off'/></td>
    </tr>
    <tr  height="20">
     <td width="120" nowrap="nowrap">Name*</td>
     <td><input type="text" name="SupNam" size="10" value="" autocomplete='off'/>
	 </td>
   </table>
 </fieldset>
</td>
</tr>
</table>
 <!----------------------------------------------- Right Table End -------------------------------------->

<!--<table width="50%" align="right">
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
        include('../include/config.php');
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
</table> -->
 <!----------------------------------------------- last Table End --------------------------------------><table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
	  <!--<input type="submit" name="submit" id="submit" class="buttons" value="Show" />-->
	  <button class="buttons" onClick="return getAjaxReceipts();">Show</button>
	  
	  &nbsp;&nbsp;&nbsp;&nbsp;
     <input type="reset" name="reset" class="buttons" value="Clear" id="clear" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
      </tr>
 </table>     
</form>
</div>

<!---- Form End ----->



<?php include("../include/error.php");?>
  <div id="search">
        <form action="" method="get">
        <input type="text" name="DSRName" value="<?php $_GET['DSRName']; ?>" autocomplete='off' placeholder='Search By Transaction Number'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
        <?php
		if($_GET['id']!=''){
		if($_POST['submit']=='ConfirmDelete'){
		$id = $_GET['id'];
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
		$var = @$_GET['Transaction_Number'] ;
        $trimmed = trim($var);	
	    $qry="SELECT * FROM `stock_receipts` where Transaction_Number like '%".$trimmed."%' order by id asc";
		}
		else
		{ 
		$qry="SELECT *  FROM `stock_receipts` order by id asc"; 
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
		<th>KD Code</th>
		<th class="rounded">Transaction Number</th>
        <th>Line Number</th>
        <th>Product Code</th>
		<th>UOM</th>
		<th>Quantity</th>
        <!--<th align="right">Mod/Del</th>-->
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
		<td><?php echo $fetch['KD_Code'];?></td>
	    <td><?php echo $fetch['Transaction_number'];?></td>
        <td><?php echo $fetch['line_number'];?></td>
        <td><?php echo $fetch['Product_code'];?></td>
		<td><?php echo $fetch['UOM'];?></td>
		<td><?php echo $fetch['quantity'];?></td>
       	<!--<td align="right">
        <a href="../BaseMaster/dsr.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="../BaseMaster/dsr.php?id=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
        </td>-->
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
     <div class="msg" align="center" <?php if($_GET['id']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='dsr.php'"/>
      </form>
     </div>  
   </div>
</div>
<?php include('../include/footer.php'); ?>