<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[sup_cat]) && $_GET[sup_cat] !='') {
	$sup_cat	=	trim($_GET[sup_cat]);
	$where		=	"WHERE Date= $dateval";
} ?>
	<div class="mcf"></div>
<div align="center" class="headingsgr">STOCK RECEIPTS</div>
<div id="mytableformreceipt" align="center">
<form action="" method="post" id="stockvalidation">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Receipts</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120">Date*</td>
    <td><input type="text" name="Date" size="15" value="<?php echo $DateVal; ?>" class="datepicker" readonly maxlength="10" autocomplete='off'/>
	</td>
    </tr>
    <tr  height="20">
     <td width="120">Receipt Number*</td>
     <td><input type="text" name="Transaction_number" size="30" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "readonly"; } else { echo ""; } ?> value="<?php echo $Transaction_number; ?>" maxlength="20" autocomplete='off'/></td>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- Left Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Supplier</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120">Category*</td>
    <td><select name="supplier_category" >
	<option value="" >--Select--</option>
	<?php $sel_supp		=	"SELECT supplier_category from supplier_category GROUP BY supplier_category";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[supplier_category]; ?>" <?php if($supplier_category == $row_supp[supplier_category]) { echo "selected"; } else if($_GET[sup_cat] == $row_supp[supplier_category]) { echo "selected"; } ?> ><?php if($row_supp[supplier_category] == 'Fareast') { echo "FMCL"; } else echo $row_supp[supplier_category]; ?></option>
	<?php } ?>
	</select>&nbsp;<span id="supcaterr" style="display:none;color:red;">Choose Category</span>
	</td>
    </tr>
    <tr  height="20">
     <td width="120">Name*</td>
     <td><input type="text" name="supplier_name" size="30" <?php if($supplier_category == 'Fareast') { ?> disabled <?php } ?> value="<?php if($supplier_category == 'Fareast') { echo "Fareast"; } ?>" maxlength="20" autocomplete='off'/></td>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- Right Table End -------------------------------------->
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Product</strong></legend>
  <table>
    <tr  height="20">
    <td  width="120"><select <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> name="product_names" id="product_names">
	<option value="" >--Select Product--</option>
	<?php $sel_supp		=	"SELECT product_code,Product_description1 from product";
	$res_supp			=	mysql_query($sel_supp) or die(mysql_error());	
	while($row_supp	= mysql_fetch_array($res_supp)){ ?>
	<option value="<?php echo $row_supp[Product_description1]; ?>" <?php if($Product_code == $row_supp[product_code]) { echo "selected"; } ?> ><?php echo $row_supp[product_code]; ?></option>
	<?php } ?>
	</select></td>
    <td><button class="buttons" <?php if(isset($_GET[id]) && $_GET[id] != '') { echo "disabled"; } ?> onClick="return addproduct();">Add</button></td>
    </tr>
	<tr>
		<td><span id="showerr" style="display:none;color:red;">Choose Product</span><input type="hidden" value="<?php if(isset($_GET[id]) && $_GET[id] != '') { ?> 1 <?php } ?>" name="prodcnt" id="prodcnt" /></td>
	</tr>
   </table>
 </fieldset>
   </td>
 </tr>
</table>

<!----------------------------------------------- last Table End -------------------------------------->

<table width="100%" align="left" id="productsadd" <?php if(!isset($_GET[id]) && $_GET[id] == '') { ?> style="display:none" <?php } ?>>
 <tr>
  <td>
  <div class="con">
  <table>
  <thead><tr><th align='center'>Serial Number</th><th class='rounded' align='center'>Product Code</th><th align='center'>Product Name</th><th align='center'>UOM</th><th align='center'>Quantity</th></tr></tr></thead>  
  <tbody id="productsadded">
	<?php $t = 1; if(isset($_GET[id]) && $_GET[id] != '') { ?> 
		<tr><td align='center'><input type='hidden' value='<?php echo $t; ?>' name='sno_<?php echo $t; ?>' /><?php echo $t; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_code; ?>' name='pcode_<?php echo $t; ?>' /><?php echo $Product_code; ?></td><td align='center'><input type='hidden' value='<?php echo $Product_name; ?>' name='pname_<?php echo $t; ?>' /><?php echo $Product_name; ?></td><td align='center'><input type='hidden' value='<?php echo $UOM; ?>' name='uom_<?php echo $t; ?>' /><?php echo $UOM; ?></td><td align='center'><input type='text' value='<?php echo $quantity; ?>' name='qty_<?php echo $t; ?>' /></td></tr>
	<?php } ?>
  </tbody>
   </table>
   </div>
   </td>
 </tr>
</table>


<table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td><input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
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
        <input type="text" name="Product_name" value="<?php $_GET['Product_name']; ?>" autocomplete='off' placeholder='Search By Product Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>
<div class="mcf"></div>        
<div id="containerpr">
        <?php
		if($_GET['id']!=''){
			if($_POST['submit']=='ConfirmDelete'){
				$id = $_GET['id'];
				$query = "DELETE FROM `stock_receipts` WHERE id = $id";
				//Run the query
				$result = mysql_query($query) or die(mysql_error());
				header("location:StockReceipts.php?no=3");
			}
		 }
		?> 
	    <?php
		if($_GET['submit']!='')
		{
			$var = @$_GET['DSRName'] ;
			$trimmed = trim($var);	
			$qry="SELECT * FROM `stock_receipts` where DSRName like '%".$trimmed."%' order by id asc";
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
		<th>Serial Number</th>
		<th class="rounded">Product Code<img src="../images/sort.png" width="13" height="13" /></th>
        <th>Product Name</th>
        <th>UOM</th>
		<th>Quantity</th>
        <th align="right">Mod/Del</th>
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
		<td><?php echo $fetch['line_number'];?></td>
		<td><?php echo $fetch['Product_code'];?></td>
	    <td><?php echo $fetch['Product_name'];?></td>
        <td><?php echo $fetch['UOM'];?></td>
        <td><?php echo $fetch['quantity'];?></td>
       	<td align="right">
        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/user_edit.png" alt="" title="" width="11" height="11"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="../StockManagement/StockReceipts.php?id=<?php echo $fetch['id'];?>"><img src="../images/trash.png" alt="" title="" width="11" height="11" /></a>
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
     <div class="msg" align="center" <?php if($_GET['id']!=''){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
     <form action="" method="post">
     <input type="submit" name="submit" id="submit" class="buttonsdel" value="ConfirmDelete" />&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='StockReceipts.php'"/>
      </form>
     </div>
 <?php exit(0); ?>