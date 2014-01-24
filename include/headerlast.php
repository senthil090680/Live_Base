<?php
session_start();
include "../include/config.php";
$list=mysql_query("select * from kd_information"); 
$fetch=mysql_fetch_array($list);
if($_REQUEST['no']!=''){
$error_sql="select * from  error_message where id=".$_REQUEST['no'];
$error_exec=mysql_query($error_sql);
$error_fetch=mysql_fetch_array($error_exec);
}
//echo $error_fetch[1].'<br>'.$error_fetch[2].'<br>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Base System</title>
<link rel="stylesheet" href="../css/style.css" type="text/css" />
<link rel="stylesheet" href="../css/menu.css" type="text/css" />
<link rel="stylesheet" href="../css/facebox.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/editbox.css" type="text/css" />
<script type="text/javascript" src="../js/jquery1.js"></script>
<script type="text/javascript" src="../js/jquery2.js"></script>
<script type="text/javascript" src="../js/facebox.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
<!--<script type="text/javascript" src="../js/sorterjs/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="../js/sorterjs/jquery.tablesorter.pager.js"></script> -->
<script type="text/javascript" src="../js/jconfirmaction.jquery.js"></script>

	<script type="text/javascript" src="../lib/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="../lib/jquery.mousewheel-3.0.4.pack.js"></script>
	
	<script type="text/javascript" src="../lib/jquery.fancybox-1.3.4.js"></script>
	<link rel="stylesheet" type="text/css" href="../source/jquery.fancybox-1.3.4.css" media="screen" />
	
	<link rel="stylesheet" type="text/css" href="../source/style.css" media="screen" />
	<script type="text/javascript" src="../lib/web.js?m=20100203"></script>
<!--[if IE 6]>
	<script src="/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
		DD_belatedPNG.fix('.png_bg');
	</script>
<![endif]-->
	<script src="/js/cufon-yui.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		Cufon.replace('h1');
	</script>
<!-- FANCY BOX -->

</head>


<body>
<div id="wrapper">
 <!------------------------------- Header Start ---------------------------------------->
 <div id="header">
    <div id="logo">
      <div class="left"><img src="../kdlogo/<?php echo $fetch['kdlogo'];?>" width="60" height="70" /></div>
      <div class="left">
      <h2 align="center">Base System</h2>
      <h2 align="center"><?php echo $fetch['KD_Name'];?></h2></div>
      <div class="left"><img src="../images/logo_tts.png" width="60" height="72" style="padding-left:295px;"/></div> 
      </div>
      <div id="menuleft">
<li><a href="#">Master Data</a>
       <ul>
           <li><a href="#">Host Value Sets </a>
          <ul>
            <li><a href="../valueSets/branch.php">Branch</a></li> 
            <li><a href="../valueSets/province.php">Zone</a></li>
            <li><a href="../valueSets/state.php">State</a></li>
            <li><a href="../valueSets/city.php">City</a></li>
            <li><a href="../valueSets/lga.php">LGA</a></li>
            <li><a href="../valueSets/location.php">Location</a></li>
              <li><a href="#">Category Values</a>  
            <ul> 
            <li><a href="../valueSets/customerCategory1.php">Customer Category1</a></li>
            <li><a href="../valueSets/customerCategory2.php">Customer Category2</a></li>
            <li><a href="../valueSets/customerCategory3.php">Customer Category3</a></li>
            </ul>
            </li>
            <li><a href="../valueSets/feedbackType.php">Feedback Type</a></li>
            <li><a href="../valueSets/productType.php">Product Type</a></li>
            <li><a href="../valueSets/currency.php">Currency</a></li>
            <li><a href="../valueSets/CustomerType.php">Customer Type</a></li>
            <li><a href="../valueSets/principal.php">Principal</a></li>
            <li><a href="../valueSets/brand.php">Brand</a></li>
           <li><a href="../valueSets/productcategory.php">Product category</a></li>
          </ul>
        </li>
        <li><a href="#">Host Master Data </a>
         <ul>
	    <li><a href="../masterData/uomMaster.php" title="">UOM1</a></li>
        <li><a href="../masterData/uom2.php" title="">UOM2</a></li>
        <li><a href="../masterData/uomConversion.php" title="">UOM Conversion</a></li>
        <li><a href="../masterData/productMaster.php" title="">Product</a></li>
        <li><a href="../masterData/price.php" title="">Price</a></li>    
        <li><a href="../masterData/kd.php" title="">KD</a></li>
        <li><a href="../masterData/rsm.php" title="">RSM</a></li>
        <li><a href="../masterData/asm.php" title="">ASM</a></li>
        <li><a href="../masterData/sr.php" title="">SR</a></li>
        <li><a href="../masterData/dsr.php" title="">DSR</a></li> 
        <li><a href="../masterData/scheme.php" title="">Scheme</a></li>
        <li><a href="../masterData/productscheme.php" title="">Product Scheme</a></li>
        <li><a href="../masterData/Customertypeproduct.php" title="">CustomerType POSM</a></li>
		</ul>
</li>
<li><a href="#">Base Value Set</a>
        <ul>
	    <li><a href="../BaseValuesets/SupplierCategory.php" title="">Stock Inward</a></li>
        <li><a href="../BaseValuesets/StockAdjustmentReason.php" title="">Stock Adjustment Reason</a></li>
        <li><a href="../BaseValuesets/KdBanks.php" title="">KD Banks</a></li>
        <li><a href="../BaseValuesets/checkoutReason.php" title="">Checkout Reasons</a></li>
        <li><a href="../BaseValuesets/Salereturn.php" title="">Sale Return</a></li>
        </ul>
</li>

<li><a href="#">Base Master Data </a>
         <ul>
        <li><a href="../BaseMaster/route.php" title="">Route</a></li>
        <li><a href="../BaseMaster/customer.php" title="">Customer</a></li> 
        <li><a href="" title="">Journey Plan</a>
         <ul>
        <li><a href="../BaseMaster/routeplan.php" title="">Master Journey Plan</a></li>
		<li><a href="../BaseMaster/routemonthlyplan.php" title="">Journey Plan</a></li>
        <li><a href="../BaseMaster/sequencechange.php" title="">SequenceChange</a></li>
        </ul>
         </li> 
        <li><a href="#" title="">SR Incentive & Target Setting</a>
        <ul>
	   	<li><a href="../BaseMaster/srincentive.php" title="">Product Target</a></li>
		<li><a href="../BaseMaster/covtargetentry.php" title="">Effective Coverage Target</a></li></li></ul>
		<li><a href="../BaseMaster/posmtarget.php" title="">POSM Target Entry</a></li>
        <li><a href="../BaseMaster/devicemaster.php" title="">Device</a></li>
        <li><a href="../BaseMaster/vehicle.php" title="">Vehicle</a></li>
       </ul>
</li>
    </ul>
 </li>
   <li><a href="#">KD Stock Management</a>
     <ul>           <li><a href="../StockManagement/openingstockins.php" title="" class="sub1">Opening Stock Update</a></li>
					<li><a href="#" title="" class="sub1">Transactions</a>
                      <ul>
                           <li><a href="../StockManagement/StockReceipts.php">Stock Receipts</a></li>
                             <li><a href="../StockManagement/StockIssues.php">Stock Issues</a></li>
                               <li><a href="../StockManagement/StockAdjustment.php">Stock Adjustment</a></li>
                                 <li><a href="../StockManagement/StockStatus.php">Stock Status</a></li>
                                  </ul>
                                </li>
                 		</ul>
  
  </li>
  <li><a href="#">DSR Stock & Sale </a>
    <ul>
					<li><a href="../DSRStock/cycle_assignment.php" title="">Daily Assignment</a></li>
					<li><a href="../DSRStock/customer_confirmation.php" title="">Daily Customer Confirm</a></li>
					<li><a href="../DSRStock/DailyStockLoading.php" title="">Daily Stock Loading</a></li>
                    <li><a href="../DSRStock/deviceTransactions.php" title="">Device Transactions </a></li>
					<li><a href="../DSRStock/creditnote.php" title="">Credit Note</a></li>
                    <li><a href="../DSRStock/DSRVehicleStock.php" title="">Vehicle Stock</a></li>
                    <li><a href="../DSRStock/sales_collection.php" title="">Sales & Collections</a></li>
                    <li><a href="../DSRStock/CollectionDeposited.php" title="">Collection Deposit</a></li>
                    <li><a href="../DSRStock/customer_tracking.php" title="">Customer Visit Tracking</a></li>
                    <li><a href="../DSRStock/cycleendreconciliation.php" title="">Cycle End Reconciliation</a></li>
					</ul>
  
  </li>
                 
		      <li><a href="#">Reports</a>
					<ul>
                 <li><a href="../Reports/srperformanceReport.php">SR Performance Report</a></li>
                 <li><a href="../Reports/posmcoverage.php">POSM Coverage</a></li>
				 <li><a href="../Reports/srmetrics.php">SR Metrics</a></li>
		
                    <li><a href="../Reports/srincentivestatus.php">SR Incentive Status </a></li>
                     <!--<li><a href="../Reports/srincentive.php">Daily Assignment Report</a></li>-->
					</ul>			  			
			  </li>
             <li style="float:right;padding-right:80px;"><a href="../index.php?logout=logout"><strong><?php echo $_SESSION['username'];?></strong></a>
             <ul>        
             <li style="float:right;"><a href="../index.php?logout=logout">Logout</a></li>  
             </ul>
                    <?php 		
					$UN=strtolower($_SESSION['username']); 
					if($UN=='admin'){ ?>
					<li><a href="#">Admin</a>
					<ul>
                    <li><a href="">Administration</a>
                    <ul>
                    <li><a href="../login/register.php">User Registration</a></li>
					<li><a href="../admin/userchangePassword.php">User Administration</a></li>
                    </ul>
                    </li> 
					<li style="float:right"><a href="#">System Administration</a>
					<ul>
					<li><a href="../admin/setupParam.php">Setup Parameters</a></li>
                    <li><a href="../admin/KD_information.php">KD Information</a></li>
                    <li><a href="../admin/deviceReg.php">Device Registration</a></li>
					<li><a href="../admin/devicestatus.php">Device Status</a></li>
                 	<li><a href="../webservice/process.php">Download/ Upload Status</a></li>
                    <li><a href="../webservice/Downloadfile.php">Master Download</a></li>
                    <li><a href="../webservice/script.php">Run Script</a></li>
					</ul>
					</li>
					</ul>
					</li>
					<?php }else{?>
					<li style="float:right"><a href="../admin/changePassword.php">Change Password</a> </li>
					<?php }?> 
					</ul>
</div>
