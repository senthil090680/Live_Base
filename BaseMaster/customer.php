<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
if(isset($_GET['logout'])){
session_destroy();
header("location:../index.php");
}

EXTRACT($_POST);
//
//                   $seqid       =	"SELECT max(sequence_number) FROM customer where route = '$route' GROUP by route";
//		           $resseq		=	mysql_query($seqid);
//				   while($row = mysql_fetch_array($resseq)){
//				   $seq = $row['max(sequence_number)'];
//				   }      
//					
//					if($seq == '00'){
//						     $sequence_number       = 01;
//						     $sequence_number++;
//					}
//					elseif($seq == 99){
//						$sequence_number        =  01;
//						$sequence_number++;
//						
//					}
//					else{
//						$sequence_number = ($seq)+1;
//					}

 $q = mysql_query("SELECT MAX(sequence_number) as sequence_number from `customer` where route = '$route' GROUP by route");
				$row = mysql_fetch_assoc($q);
				$sequence_number = $row['sequence_number'] + 1;


$page=intval($_REQUEST['page']);
$id=$_REQUEST['id'];
if($_GET['id']!=''){
if($_POST['submit']=='Save'){
if($customer_code=='' || $Customer_Name==''  || $City=='' || $State=='' || $lga=='' || $location=='')
{
header("location:customer.php?no=9&id=$id&$page=$page");exit;
}
else{
$sql=("UPDATE customer SET
		KD_Code= '$KD_Code',
		KD_Name='$KD_Name',
		customer_code='$customer_code',
		Customer_Name='$Customer_Name',
		AddressLine1='$AddressLine1',
		AddressLine2='$AddressLine2',
		AddressLine3='$AddressLine3',
		City='$City',
		State='$State',
		province='$province',
		location='$location',
		lga='$lga',
		PostCode='$PostCode',
		GPS='$GPS',
		contactperson='$contactperson',
		contactnumber='$contactnumber',
		Alternatecontactperson='$Alternatecontactperson',
		Alternatecontactnumber='$Alternatecontactnumber',
		route='$route',
		Alternateroute='$Alternateroute',
		DSR_Code='$DSR_Code',
		DSRName='$DSRName',
		category1='$category1',
		category2='$category2',
		category3='$category3',
		miscellaneous_caption='$miscellaneous_caption',
		miscellaneous_data='$miscellaneous_data',
		customer_type='$customer_type',
		DiscountEligibility='$DiscountEligibility',
		Max_Discount='$Max_Discount',
		sequence_number='$sequence_number'
		WHERE id = '$id'");
mysql_query( $sql);
header("location:customerview.php?no=2&$page=$page");
}
}
}
elseif($_POST['submit']=='Save'){?>
<form action="" method="post" id="resubmitform">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="customer_code" value="<?php echo $customer_code; ?>" />
<input type="hidden" name="Customer_Name" value="<?php echo $Customer_Name; ?>" />
<input type="hidden" name="AddressLine1" value="<?php echo $AddressLine1; ?>" />
<input type="hidden" name="AddressLine2" value="<?php echo $AddressLine2; ?>" />
<input type="hidden" name="AddressLine3" value="<?php echo $AddressLine3; ?>" />
<input type="hidden" name="City" value="<?php echo $City; ?>" />
<input type="hidden" name="State" value="<?php echo $State; ?>" />
<input type="hidden" name="province" value="<?php echo $province; ?>" />
<input type="hidden" name="location" value="<?php echo $location; ?>" />
<input type="hidden" name="lga" value="<?php echo $lga; ?>" />
<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>" />
<input type="hidden" name="GPS" value="<?php echo $GPS; ?>" />
<input type="hidden" name="contactperson" value="<?php echo $contactperson; ?>" />
<input type="hidden" name="contactnumber" value="<?php echo $contactnumber; ?>" />
<input type="hidden" name="Alternatecontactperson" value="<?php echo $Alternatecontactperson; ?>" />
<input type="hidden" name="Alternatecontactnumber" value="<?php echo $Alternatecontactnumber; ?>" />
<input type="hidden" name="route" value="<?php echo $route; ?>" />
<input type="hidden" name="Alternateroute" value="<?php echo $Alternateroute; ?>" />
<input type="hidden" name="DSR_Code" value="<?php echo $DSR_Code; ?>" />
<input type="hidden" name="DSRName" value="<?php echo $DSRName; ?>" />
<input type="hidden" name="category1" value="<?php echo $category1; ?>" />
<input type="hidden" name="category2" value="<?php echo $category2; ?>" />
<input type="hidden" name="category3" value="<?php echo $category3; ?>" />
<input type="hidden" name="miscellaneous_caption" value="<?php echo $miscellaneous_caption; ?>" />
<input type="hidden" name="miscellaneous_data" value="<?php echo $miscellaneous_data; ?>" />
<input type="hidden" name="customer_type" value="<?php echo $customer_type; ?>" />
<input type="hidden" name="DiscountEligibility" value="<?php echo $DiscountEligibility; ?>" />
<input type="hidden" name="Max_Discount" value="<?php echo $Max_Discount; ?>" />
<input type="hidden" name="sequence_number" value="<?php echo $sequence_number; ?>" />
<input type="hidden" name="no" value="9" />
 
</form>
<form action="" method="post" id="dataexists">
<input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
<input type="hidden" name="KD_Name" value="<?php echo $KD_Name; ?>" />
<input type="hidden" name="customer_code" value="<?php echo $customer_code; ?>" />
<input type="hidden" name="Customer_Name" value="<?php echo $Customer_Name; ?>" />
<input type="hidden" name="AddressLine1" value="<?php echo $AddressLine1; ?>" />
<input type="hidden" name="AddressLine2" value="<?php echo $AddressLine2; ?>" />
<input type="hidden" name="AddressLine3" value="<?php echo $AddressLine3; ?>" />
<input type="hidden" name="City" value="<?php echo $City; ?>" />
<input type="hidden" name="State" value="<?php echo $State; ?>" />
<input type="hidden" name="province" value="<?php echo $province; ?>" />
<input type="hidden" name="location" value="<?php echo $location; ?>" />
<input type="hidden" name="lga" value="<?php echo $lga; ?>" />
<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>" />
<input type="hidden" name="GPS" value="<?php echo $GPS; ?>" />
<input type="hidden" name="contactperson" value="<?php echo $contactperson; ?>" />
<input type="hidden" name="contactnumber" value="<?php echo $contactnumber; ?>" />
<input type="hidden" name="Alternatecontactperson" value="<?php echo $Alternatecontactperson; ?>" />
<input type="hidden" name="Alternatecontactnumber" value="<?php echo $Alternatecontactnumber; ?>" />
<input type="hidden" name="route" value="<?php echo $route; ?>" />
<input type="hidden" name="Alternateroute" value="<?php echo $Alternateroute; ?>" />
<input type="hidden" name="DSR_Code" value="<?php echo $DSR_Code; ?>" />
<input type="hidden" name="DSRName" value="<?php echo $DSRName; ?>" />
<input type="hidden" name="category1" value="<?php echo $category1; ?>" />
<input type="hidden" name="category2" value="<?php echo $category2; ?>" />
<input type="hidden" name="category3" value="<?php echo $category3; ?>" />
<input type="hidden" name="miscellaneous_caption" value="<?php echo $miscellaneous_caption; ?>" />
<input type="hidden" name="miscellaneous_data" value="<?php echo $miscellaneous_data; ?>" />
<input type="hidden" name="customer_type" value="<?php echo $customer_type; ?>" />
<input type="hidden" name="DiscountEligibility" value="<?php echo $DiscountEligibility; ?>" />
<input type="hidden" name="Max_Discount" value="<?php echo $Max_Discount; ?>" />
<input type="hidden" name="sequence_number" value="<?php echo $sequence_number; ?>" />
<input type="hidden" name="no" value="18" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>

<?php
if($customer_code=='' || $Customer_Name==''  || $City=='' || $State=='' || $lga=='' || $location=='' || $route=='' || $DSRName=='')
{ ?>
<script type="text/javascript">
document.forms['resubmitform'].submit();
</script>

<?php //header("location:customer.php?no=9");exit;
}
else{
$sel="select * from customer where customer_code ='$customer_code' AND Customer_Name= 'Customer_Name'";
$sel_query=mysql_query($sel);
if(mysql_num_rows($sel_query)=='0') {
$sql="INSERT INTO `customer`(`KD_Code`,`KD_Name`, `customer_code`,`Customer_Name`,`AddressLine1`,`AddressLine2`,`AddressLine3`,`City`,`State`,`province`,`location`,`lga`,`PostCode`,`GPS`,`contactperson`,
`contactnumber`,`Alternatecontactperson`,`Alternatecontactnumber`,`route`,`Alternateroute`,`DSR_Code`,`DSRName`,`category1`,`category2`,`category3`,`miscellaneous_caption`,`miscellaneous_data`,`customer_type`,`DiscountEligibility`,`Max_Discount`,`sequence_number`)

values('$KD_Code','$KD_Name','$customer_code','$Customer_Name','$AddressLine1','$AddressLine2','$AddressLine3','$City','$State','$province','$location','$lga','$PostCode','$GPS','$contactperson','$contactnumber','$Alternatecontactperson','$Alternatecontactnumber','$route','$Alternateroute','$DSR_Code','$DSRName','$category1','$category2','$category3','$miscellaneous_caption','$miscellaneous_data','$customer_type','$DiscountEligibility','$Max_Discount','$sequence_number')";
mysql_query( $sql);

    header("location:customerview.php?no=1&$page=$page");
		}
		else {?>
         <script type="text/javascript">
		document.forms['dataexists'].submit();
		</script> 
		<?php //header("location:customer.php?no=18");
		}
    }
}

$id=$_GET['id'];
$list=mysql_query("select * from customer where id= '$id'"); 
while($row = mysql_fetch_array($list)){ 
	$KD_Code = $row['KD_Code'];
	$KD_Name = $row['KD_Name'];
	$customer_code = $row['customer_code'];
	$Customer_Name = $row['Customer_Name'];
	$AddressLine1 = $row['AddressLine1'];
	$AddressLine2 = $row['AddressLine2'];
	$AddressLine3 = $row['AddressLine3'];
	$City = $row['City'];
	$State = $row['State'];
	$province = $row['province'];
	$location = $row['location'];
	$lga = $row['lga'];
	$PostCode = $row['PostCode'];
	$GPS = $row['GPS'];
	$contactperson = $row['contactperson'];
	$contactnumber = $row['contactnumber'];
	$Alternatecontactperson = $row['Alternatecontactperson'];
	$Alternatecontactnumber = $row['Alternatecontactnumber'];
	$route = $row['route'];
	$Alternateroute = $row['Alternateroute'];
	$DSR_Code= $row['DSR_Code'];
	$DSRName = $row['DSRName'];
	$category1 = $row['category1'];
	$category2 = $row['category2'];
	$category3 = $row['category3'];
	$miscellaneous_caption = $row['miscellaneous_caption'];
	$miscellaneous_data = $row['miscellaneous_data'];
	$customer_type = $row['customer_type'];
	$DiscountEligibility = $row['DiscountEligibility'];
	$Max_Discount = $row['Max_Discount'];
	$sequence_number = $row['sequence_number'];
	
	}
$kdi=mysql_query("select * from kd_information");	
while($row = mysql_fetch_array($kdi)){ 
$KD_Code=$row['KD_Code'];
$KD_Name=$row['KD_Name'];
}	

?>
<!------------------------------- Form -------------------------------------------------->
<style type="text/css">
#errormsgcol {
	display:none;
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}

.myaligncol {
	 clear:both;
	padding-top:10px;
	margin:0 auto;
	color:#FF0000;
}

#closebutton {
  position:relative;
  top:-35px;
  right:-190px;
  border:none;
  background:url(../images/close_pop.png) no-repeat;
  color:transparent;
  }
  
</style>

<script type="text/javascript">
$(function(){
  // hide by default
  $('#heading').css('display', 'none');
  $('#Max_Discount').css('display', 'none');

  $('#DiscountEligibility').change(function(){
   if ($(this).val() === 'select') {
	 $('#heading').css('display', 'none');  
     $('#Max_Discount').css('display', 'none');
   }										
   if ($(this).val() === 'Yes') {
	 $('#heading').css('display', 'block');  
     $('#Max_Discount').css('display', 'block');
   }
   if($(this).val() === 'No') {
	  $('#heading').css('display', 'none');  
     $('#Max_Discount').css('display', 'none');
   }
 });
});	


function validatecus() {
	var CustomerName			=	$('#customer_Name').val();
	var Location         		=	$('#location').val();
	var LGA        		        =	$('#lga').val();
	var City        		    =	$('#City').val();
	var State        		    =	$('#State').val();
	var Zone        		    =	$('#province').val();
	var Route        		    =	$('#route').val();
	var Category1        	    =	$('#category1').val();
	var DSR        	            =	$('#DSRName').val();
	var CustomerType       	    =	$('#customer_type').val();
	

	if(CustomerName == ''){
		$('.myaligncol').html('ERR : Enter Customer Name');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(Location == ''){
		$('.myaligncol').html('ERR : Select Location');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

  	if(LGA == ''){
		$('.myaligncol').html('ERR : Select LGA');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	
  	if(City == ''){
		$('.myaligncol').html('ERR : Select City');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	if(State == ''){
		$('.myaligncol').html('ERR : Select State');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	if(Zone == ''){
		$('.myaligncol').html('ERR : Select Zone');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	if(Route == ''){
		$('.myaligncol').html('ERR : Select Route');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}

	if(Category1 == ''){
		$('.myaligncol').html('ERR : Select Category1');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	
   	if(DSR == ''){
		$('.myaligncol').html('ERR : Select DSR');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}
	
	
    if(CustomerType == ''){
		$('.myaligncol').html('ERR : Select Customer Type');
		$('#errormsgcol').css('display','block');
		setTimeout(function() {
		$('#errormsgcol').hide();
		},5000);
		return false;
	}		

	$('#errormsgcol').css('display','none');
	//return false;
}



</script>
<div id="mainareacus">
<div class="mcf"></div>
<div align="center" class="headingsgr">Customer</div>
<div id="mytableformgr2" align="center">
<form action="" method="post"  onsubmit="return validatecus();">
<table width="50%" align="left">
 <tr>
  <td>
 <fieldset class="alignment">
  <legend><strong>Customer Data</strong></legend>
  <table>
  <tr height="20">
    <td width="120" class="pclr">KD Name*</td>
    <td>
    <input type="hidden" name="KD_Code" value="<?php echo $KD_Code; ?>" />
    <input type="text" name="KD_Name" size="30" readonly="readonly" value="<?php echo $KD_Name; ?>" autocomplete='off' maxlength="50"/></td>
    </tr>
    <tr  height="20">
    <td  width="120">Customer Code*</td>
    	<?php
		 if(!isset($_GET[id]) && $_GET[id] == '') {
			$cusid					=	"SELECT customer_code FROM  customer ORDER BY id DESC";			
			$cusold					=	mysql_query($cusid) or die(mysql_error());
			$cuscnt					=	mysql_num_rows($cusold);
			//$cuscnt					=	0; // comment if live
			if($cuscnt > 0) {
				$row_cus					  =	 mysql_fetch_array($cusold);
				$cusnumber	  =	$row_cus['customer_code'];

				$getcusno						=	abs(str_replace("CUS",'',strstr($cusnumber,"CUS")));
				$getcusno++;
				if($getcusno < 10) {
					$createdcode	=	"00".$getcusno;
				} else if($getcusno < 100) {
					$createdcode	=	"0".$getcusno;
				} else {
					$createdcode	=	$getcusno;
				}

				$customer_code				=	"CUS".$createdcode;
			} else {
				$customer_code				=	"CUS001";
			}
		}
	?>
    
    
    
    
    <td><input type="text" name="customer_code" size="10"  readonly="readonly" value="<?php echo $customer_code; ?>"  autocomplete='off' maxlength="10"/></td>
    </tr>
    <tr  height="20">
     <td width="120">Customer Name*</td>
     <td><input type="text" name="Customer_Name" id="customer_Name" size="30" value="<?php echo $Customer_Name; ?>" autocomplete='off' maxlength="50"/></td>
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
     <td><input type="text" name="AddressLine1" size="30" value="<?php echo $AddressLine1; ?>" autocomplete='off' maxlength="100"/></td>
     </tr>
     <tr height="20">
     <td>Line2</td>
      <td><input type="text" name="AddressLine2" size="30" value="<?php echo $AddressLine2; ?>" autocomplete='off' maxlength="100"/></td>
      </tr>
      <tr height="20">
      <td>Line3</td>
     <td><input type="text" name="AddressLine3" size="30" value="<?php echo $AddressLine3; ?>" autocomplete='off' maxlength="100"/></td>
    </tr>
    
      <tr height="20">
      <td>Location*</td>
     <td>
        <select name="location" id="location">
        <option value="">--- Select ---</option>
        <?php 
       // include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from location"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<? echo $row_list['id']; ?>" <?php if($row_list['id']==$location){ echo "selected"; } ?>><?php echo $row_list['location']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
    
      <tr height="20">
      <td>LGA*</td>
     <td>
        <select name="lga" id="lga">
        <option value="">--- Select ---</option>
        <?php 
        //include('config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from lga"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$lga){ echo "selected"; } ?>><?php echo $row_list['lga']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
     <tr height="20">
      <td>City*</td>
     <td>
        <select name="City" id="City">
        <option value="">--- Select ---</option>
        <?php 
       // include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from city"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$City){ echo "selected"; } ?>><?php echo $row_list['city']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
       </td>
     </tr>
      <tr height="20">
      <td>State*</td>
     <td>
      <select name="State" id="State">
         <option value="">--- Select ---</option>
        <?php 
       // include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from state"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$State){ echo "selected"; } ?> ><?php echo $row_list['state']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     </td>
     </tr>
     <tr height="20">
      <td>Zone*</td>
     <td>
      <select name="province" id="province">
         <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from province"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$province){ echo "selected"; } ?> ><?php echo $row_list['province']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     </td>
     </tr>
     
     
      <tr height="26">
      <td>Post Code</td>
     <td><input type="text" name="PostCode" size="30" value="<?php echo $PostCode; ?>" autocomplete='off' maxlength="10"/></td>
 
     </table>
 </fieldset>
<!----------------------------------------------------------- Category --------------------------------------------->
  
  <fieldset class="alignment">
  <legend><strong>Category</strong></legend>
  <table>
    <tr height="20">
     <td>Category1*</td>
     <td>
     <select name="category1" id="category1">
         <option value="">--- Select ---</option>
        <?php 
       // include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category1"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$category1){ echo "selected"; } ?> ><?php echo $row_list['category1']; ?></option>
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
       // include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category2"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$category2){ echo "selected"; } ?> ><?php echo $row_list['category2']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
      </td>
     </tr> 
  <tr height="20">
     <td>Category3</td>
     <td>
              <select name="category3">
         <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from category3"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$category3){ echo "selected"; } ?> ><?php echo $row_list['category3']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
   </td>
     </tr>
     <tr>
     <?php
     $misc = "select * from kd";
	 $res =mysql_query($misc);
	 while($row =mysql_fetch_array($res)){
		$misccap = $row['miscellaneous_caption'];
		$miscdata = $row['miscellaneous_data'];
     }
     ?>  
      <td>Miscellaneous Caption</td>
     <td><input type="text" name="miscellaneous_caption"  id="miscellaneous_caption" size="20" value="<?php echo $misccap; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
      <tr>
      <td>Miscellaneous Data</td>
     <td><input type="text" name="miscellaneous_data" id="miscellaneous_data" size="20" value="<?php echo $miscdata	; ?>" autocomplete='off' maxlength="20"/></td>
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
  <legend><strong>Contact*</strong></legend>
  <table>
    <tr height="20">
     <td>GPS</td>
     <td><input type="text" name="GPS" size="30" value="<?php echo $GPS; ?>" autocomplete='off' maxlength="100"/></td>
     </tr>
      <tr height="20">
     <td>Contact Person</td>
     <td><input type="text" name="contactperson" size="30" value="<?php echo $contactperson; ?>" autocomplete='off' maxlength="100"/></td>
     </tr> 
  <tr height="20">
     <td>Contact Number</td>
     <td><input type="text" name="contactnumber" size="30" value="<?php echo $contactnumber; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
        <tr height="20">
     <td>Alt Cont Person</td>
      <td><input type="text" name="Alternatecontactperson" size="30" value="<?php echo $Alternatecontactperson; ?>" autocomplete='off' maxlength="30"/></td>
      </tr>
     <tr height="20">
     <td>Alt Cont Number</td>
      <td><input type="text" name="Alternatecontactnumber" size="30" value="<?php echo $Alternatecontactnumber; ?>" autocomplete='off' maxlength="20"/></td>
      </tr>
     </table>
 </fieldset>
 
 
 
  <fieldset class="alignment">
  <legend><strong>Route Data</strong></legend>
  <table>
    <tr height="20">
     <td>Route*</td>
     <td>
      <select name="route" id="route">
         <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from  route_master"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['route_code']; ?>" <?php if($row_list['route_code']==$route){ echo "selected"; } ?> ><?php echo $row_list['route_code']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
     
     </td>
     </tr>
       <!--  <tr height="20">
   <td>Sequence No*</td>

    
   <?php
//		 if(!isset($_GET[id]) && $_GET[id] == '') {
//		        echo    $seqid					    =	"SELECT max(sequence_number) FROM customer where route = '".$_POST['route']."' order by route";
//		            $res_oldseq					=	mysql_query($seqid) or die(mysql_error());
//					$rowcnt_oldseq				=	mysql_num_rows($res_oldseq);
//					$rowcnt_oldseq			=	0; // comment if live
//					if($rowcnt_oldseq > 0) {
//						$row_oldseq				=	mysql_fetch_array($res_oldseq);
//						$Old_seqnum				=	$row_oldseq['sequence_number'];
//					} else {
//						$Old_seqnum				=	"00";
//					}
//					
//					$getseqno					=	abs($Old_seqnum);
//					$getseqno++;
//
//					if($getseqno < 10) {
//						$sequence_number			=	"0".$getseqno;
//					} else if($getseqno > 99) {
//						$sequence_number			=	"01";
//					}
//		 }
	?>
    <!-- <td><input type="text" name="sequence_number" size="30" value="<?php echo $sequence_number; ?>"  autocomplete='off' maxlength="20" readonly="readonly"/></td>
     </tr>-->
      <tr height="20">
     <td>Alternate Route</td>
     <td><input type="text" name="Alternateroute" size="30" value="<?php echo $Alternateroute; ?>" autocomplete='off' maxlength="20"/></td>
     </tr> 
  <tr height="20">
     <td>DSR*</td>
     <td> 
       <input type="hidden" name="DSR_Code"  id="DSR_Code"  value="<?php echo $DSR_Code; ?>"  autocomplete='off'/>
       <select name="DSRName"  id="DSRName" onchange="return DSRCODE()">
         <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from dsr"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['DSRName']; ?>" <?php if($row_list['DSRName']==$DSRName){ echo "selected"; } ?> ><?php echo $row_list['DSRName']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select></td>
        
     </tr>
   </table>
 </fieldset>
 

</td>
</tr>
</table> 

<div style="clear:both"></div>
 <!----------------------------------------------- last Table End -------------------------------------->
  <fieldset class="alignment">
  <legend><strong>Customer Parameters</strong></legend>
  <table>
    <tr height="20">
     <td>Customer Type*</td>
     <td>
         <select name="customer_type" id="customer_type">
         <option value="">--- Select ---</option>
        <?php 
        //include('../include/config.php');
        // Get records from database (table "name_list"). 
        $list=mysql_query("select * from customer_type"); 
        
        // Show records by while loop. 
        while($row_list=mysql_fetch_assoc($list)){ 
        ?>
        <option value="<?php echo $row_list['id']; ?>" <?php if($row_list['id']==$customer_type){ echo "selected"; } ?> ><?php echo $row_list['customer_type']; ?></option>
        <?php 
        // End while loop. 
        } 
        ?>
        </select>
    </td>
      <td>&nbsp;&nbsp;Discount</td>
      <td>
     <select name="DiscountEligibility" id="DiscountEligibility">
     <option value="select">--Select--</option>
     <option value="Yes" <?php if($DiscountEligibility=='Yes'){ echo 'selected' ; }?> >Yes</option>
     <option value="No" <?php if($DiscountEligibility=='No'){ echo 'selected' ; }?>>No</option>
     </select>
      </td>
      <td id="heading">&nbsp;&nbsp;Max Discount</td>
     <td><input type="text" name="Max_Discount" size="10"  id="Max_Discount" value="<?php echo $Max_Discount; ?>" autocomplete='off' maxlength="20"/></td>
     </tr>
 
   </table>
 </fieldset> 
<div class="mcf"></div> 
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" class="buttons" value="Clear" id="clear" onclick="window.location='customer.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="view" id="view"  class="buttons" value="View"  onclick="window.location='customerview.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/empty.php'"/></td>
      </tr>
 </table>     
</form>
<div class="mcf"></div>
<?php include("../include/error.php");?>
<div id="errormsgcol" style="display:none;clear:both;">
<h3 align="center" class="myaligncol"></h3><button id="closebutton">Close</button></div>

</div>

<!---- Form End ----->
</div>
<?php include('../include/footer.php'); ?>