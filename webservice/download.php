<?php
include("../include/config.php");
$query = "select CURDATE()";
$result = mysql_query($query);
$mydevicecode = $_POST['Devicecode'];

while($data = mysql_fetch_array($result))
{
   $date = $data['CURDATE()'];
}

$query = "select * from device_registration";
$result = mysql_query( $query);
while($row=mysql_fetch_array($result)){
$device_code = $row['device_code'];
}

$query = "select * from kd";
$result = mysql_query( $query);
while($row=mysql_fetch_array($result)){
$KD_Code = $row['KD_Code'];
}


$uploaddir = "../../../Upload/";

$time = time();

$previousDate = date("d-m-y", mktime(0,0,0,date("n", $time),date("j",$time)- 1 ,date("Y", $time)));

$curdate = date('d-m-y');

$Uploadfileprev = $KD_Code."_".$mydevicecode."_"."up"."_".$previousDate .".txt";

$Uploadfilecur = $KD_Code."_".$mydevicecode."_"."up"."_".$curdate  .".txt";


 if(file_exists($uploaddir.$Uploadfileprev)) {?>
      <script type="text/javascript">
        alert("There is a Upload File name <?php echo $Uploadfileprev; ?>  Please Run Upload Device Data First.");
		window.location='script.php'
        </script> 
        <?php 
                //$strOutput.= $Uploadfilename . "exists"."Update Upload Data";
           }
            elseif(file_exists($uploaddir.$Uploadfilecur)) {?>
            
				<script type="text/javascript">
				alert("There is a Upload File name <?php echo $Uploadfilecur; ?> Please Run  Upload Device Data First.");
				window.location='script.php'
				</script>
		<?php		
               
                //$strOutput.= "No file Found" . $uploaddir . $Uploadfilename;
            }
		else{	

$query = "select * from cycle_assignment where Date LIKE '$date%' AND ((flag_status= '1' and end_flag_status='0') or (flag_status = '0' and end_flag_status='0')) AND device_code = '".$mydevicecode."'";
$result = mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$dsr_code    = $row['dsr_code'];
	$vehicle_id	 = $row['vehicle_id'];
	$device_id	 = $row['device_id'];
	$device_code = $row['device_code'];
	$KD_Code	 = $row['KD_Code'];
	$route_id    = $row['route_id'];
	$location    = $row['location_id'];
    $flagStatus = $row['flag_status'];
	
	

//1 system parameters
$system_parameters = "";
$query = "select currency,displaydateformat,Tran_copies,Trans_Reprint,Transfer_Frequency,CONCAT( MID( Start_time, 1, 2 ),MID( Start_time, 4, 2 )) as Start_time,CONCAT( MID( End_time, 1, 2 ),MID( End_time, 4, 2 )) as End_time,Focus_item_stock,Permit_Return,Customer_Sign,Data_sync_freq from parameters";
$resultsys = mysql_query($query);
while ($datasys = mysql_fetch_array($resultsys)){
	$system_parameters .= "1^" .$KD_Code . "^" . $dsr_code . "^". $device_code . "^";
    $system_parameters .=  $datasys['currency'] . "^";
    $system_parameters .=  $datasys['displaydateformat'] . "^";
    $system_parameters .=  $datasys['Tran_copies'] . "^";
    $system_parameters .=  $datasys['Trans_Reprint'] . "^";
    $system_parameters .=  $datasys['Transfer_Frequency'] . "^";
    $system_parameters .=  $datasys['Start_time'] . "^";
    $system_parameters .=  $datasys['End_time'] . "^";
    $system_parameters .=  $datasys['Focus_item_stock'] . "^";
    $system_parameters .=  $datasys['Permit_Return'] . "^";
    $system_parameters .=  $datasys['Customer_Sign']. "^";
    $system_parameters .=  $datasys['Data_sync_freq']. "^";
	$system_parameters .= PHP_EOL;
    

}

//end of system Parameters

//2 Device ASSIGNMENT		
$device_assignment ="";
			
			$query = "select id,KD_Name from device_master where device_code = '" . $device_code . "'";
			$resultdev = mysql_query($query);
			while ($data = mysql_fetch_array($resultdev)) {
			$deviceid = $data['id']; //device id should be correct device id
			$kdName= $data['KD_Name'];
			}
			
			$query = "select DSRName from dsr where DSR_Code	='" .$dsr_code ."'";
			$resultdsr = mysql_query($query);
			while($datatemp = mysql_fetch_array($resultdsr))
			{
			$dsrName = $datatemp['DSRName'] ;
			
			}
			
		    $query = "select vehicle_code,vehicle_reg_no from vehicle_master where id='" . $vehicle_id ."'";
			$resultveh = mysql_query($query);
			while($datatemp = mysql_fetch_array($resultveh))
			{
			$vehicle = $datatemp['vehicle_code'] ;
			$vehicleRegNo = $datatemp['vehicle_reg_no'] ;
			}
			
			$query = "select route_code,route_desc from route_master where id='" . $route_id ."'";
			$resultrou = mysql_query($query);
			while($datatemp = mysql_fetch_array($resultrou))
			{
			$route = $datatemp['route_code'] ;
			$routeName = $datatemp['route_desc'] ;
			}
			
			$query ="select province,State,City,lga from customer where DSR_Code='" . $dsr_code ."'";
			$resultcus = mysql_query($query);
			while($data = mysql_fetch_array($resultcus))
			{
			$province = $data['province'];
			$state = $data['State'];
			$city = $data['City'];
			$lga = $data['lga'];
			}

$device_assignment .= "2^" ;
$device_assignment .= $date . "^";
$device_assignment .= $device_code . "^";
$device_assignment .= $dsr_code . "^";
$device_assignment .= $dsrName . "^";
$device_assignment .= $KD_Code . "^";
$device_assignment .= $kdName . "^";
$device_assignment .= $vehicle . "^";
$device_assignment .= $vehicleRegNo . "^";
$device_assignment .= $route . "^";
$device_assignment .= $routeName . "^";
$device_assignment .= $province . "^";
$device_assignment .= $state . "^";
$device_assignment .= $city . "^";
$device_assignment .= $lga . "^";
$device_assignment .= $location . "^";
$device_assignment .= $flagStatus . "^";
$device_assignment .= PHP_EOL;

//end of device assignment




//3 Customer Type Master 
$customer_type = "";
$query = "select id,customer_type from customer_type";
$resultcusty = mysql_query($query);
while ($data = mysql_fetch_array($resultcusty)) {
    $customer_type .= "3^" . $data['id'] . "^" . $data['customer_type']. "^";
    $customer_type .= PHP_EOL;
}

//end of customertype master





//4 POSM Product Mapping  Details	
$posm_master = "";
$query = "select id,customer_type from customer_type";
$resultposm = mysql_query($query);
while($data = mysql_fetch_array($resultposm)){ 

$customerid = $data['id'];
$customertype = $data['customer_type'];
  
$query = "select id,Product_code from customertype_product where customer_type ='" . $customertype ."'";
$resultcustype = mysql_query($query);
while ($data = mysql_fetch_array($resultcustype)) {
          $productid = $data['id'];
   }
 }

$posm_master .= "4^" ;
$posm_master .= $customerid . "^";
$posm_master .= $productid  . "^";
$posm_master .= PHP_EOL;
//end POSM product mapping




//5  Feedback Category
$feedback_catergory = "";
$query = "select id,feedback_type from feedback_type";
$resultfeed = mysql_query($query);
while ($data = mysql_fetch_array($resultfeed)) {
    $feedback_catergory .= "5^" . $data['id'] . "^" . $data['feedback_type']. "^";
    $feedback_catergory .= PHP_EOL;
}

//End Feedback Category



// 6 Customer Master
$customerMaster = "";
$query = "select * from customer where DSR_Code='" .$dsr_code."'";
$resultcusmas = mysql_query($query);
while ($data = mysql_fetch_array($resultcusmas)) {
	
	$query = "select * from customerbaldownload where customerCode='" .$data['customer_code']."'";
	$resultcusbal = mysql_query($query);
	while ($databal = mysql_fetch_array($resultcusbal)){
		
		$cusopenbal     =    $databal['cycleOpenBalDue'];
		$dayOpenBalDue  =    $databal['dayOpenBalDue'];
	
	}
    $customerMaster .= "6^" . $date . "^";
    $customerMaster .=  $data['route'] . "^" ;
    $customerMaster .=  $data['sequence_number']."^" ;
    $customerMaster .=  $data['customer_code']."^" ;
    $customerMaster .=  $data['Customer_Name']."^" ;
    $customerMaster .=  $data['AddressLine1']."^" ;
    $customerMaster .=  $data['AddressLine2']."^" ;
    $customerMaster .=  $data['AddressLine3']."^" ;
    $customerMaster .=  $data['Max_Discount']."^" ;
	$customerMaster .=  $data['customer_type']."^" ;
    $customerMaster .=  $data['contactperson'] . "^" ;
    $customerMaster .=  $data['contactnumber'] . "^" ;
    $customerMaster .=  $data['miscellaneous_caption'] . "^" ;
	$customerMaster .=  $data['miscellaneous_data'] . "^" ;
	$customerMaster .=  $cusopenbal . "^" ;
	$customerMaster .=  $dayOpenBalDue  . "^" ;
    $customerMaster .= PHP_EOL;
}

//End of customer Master


// 7 Brand Master	

$brandMaster = "";
$query = "select id,brand from brand";
$resultbran = mysql_query($query);
while ($data = mysql_fetch_array($resultbran)) {
    $brandMaster .= "7^" . $data['id'] . "^" . $data['brand']. "^";
    $brandMaster .= PHP_EOL;
}

//end of Brand Master



// 8 Product Master & Scheme PRODUCT & Scheme DETAIL	
	

$productMaster ="";
$productScheme="";
$SchemeDetail="";

   $query ="select id,Product_code,Product_description1,Product_description_length,Product_display_description,UOM1,Focus,product_type,batch_ctrl,brand from product";
   $resultProductmas = mysql_query($query);
    while($dataProductmas = mysql_fetch_array($resultProductmas))
    {
		
		$curdate = date('Y-m-d');
		$id                                    =  $dataProductmas['id'];
		$productCode                           =  $dataProductmas['Product_code'];
		$ProductDescription                    =  $dataProductmas['Product_description1'];
		$ProductDescriptionLength              =  $dataProductmas['Product_description_length'];
		$ProductDisplayDescription             =  $dataProductmas['Product_display_description'];
		$UOM1                                  =  $dataProductmas['UOM1'];
		$Focus                                 =  $dataProductmas['Focus'];  
       
	   
	     
	$queryprice = "select Price from price_master where Product_code= '" . $dataProductmas['Product_code']. "'";
			$resultprice = mysql_query($queryprice);
			while($dataprice = mysql_fetch_array($resultprice))
			{
			$Price = $dataprice['Price'] ;
			}
        
    
  $query = "select Scheme_code from  product_scheme_master where Header_Product_code = '" . $dataProductmas['Product_code']. "'";
  $resultSchemeflag= mysql_query($query);
  $count = mysql_num_rows($resultSchemeflag);
  
       
		  
			$productMaster .=  "8^";
			$productMaster .= $id  . "^";
			$productMaster .= $productCode . "^";
			$productMaster .= $ProductDescription   . "^";
			$productMaster .= $ProductDescriptionLength  . "^";
			$productMaster .= $UOM1  . "^";
	        $productMaster .= $Price . "^";
			$productMaster .= $Focus  . "^";
			
			
			if($count > 0 )
			$productMaster .= "1^";
			else
			$productMaster .= "0^";
			
			if($dataProductmas['product_type'] =="POSM")
			$productMaster .= "1^";
			else
			$productMaster .= "0^";
			
			if($dataProductmas['batch_ctrl']=="OFF")
			$productMaster .= "0^";
			else
			$productMaster .= "1^";
			
			$productMaster .=  $dataProductmas['brand'] . "^" ;
			$productMaster .=  $ProductDisplayDescription  . "^";
			$productMaster .= PHP_EOL;
  			
}


//	     // 9 Scheme PRODUCT
//
//         $query ="select GROUP_CONCAT(DISTINCT Scheme_code) AS Scheme_code,
//                  GROUP_CONCAT(DISTINCT Effective_from) AS Effective_from,
//                  GROUP_CONCAT(DISTINCT Effective_to) AS Effective_to,
//                  GROUP_CONCAT(DISTINCT SchemeType) AS SchemeType,
//                  GROUP_CONCAT(DISTINCT rebate) AS rebate,
//                  GROUP_CONCAT(DISTINCT rebateunits) AS rebateunits,
//                  GROUP_CONCAT(DISTINCT rebatevalue) AS rebatevalue,1 as Header_Product_code,Header_Quantity,2 as line_Product_Code,line_Product_quantity from product_scheme_master  GROUP by Scheme_code";
//        $resultScheme = mysql_query($query);
//        while($dataScheme = mysql_fetch_array($resultScheme))
//        {
//   
//
//            $productScheme .= "9^";
//            $productScheme .= $dataScheme['Scheme_code']  ."^";
//            $productScheme .= $dataScheme['Effective_from']."^";
//            $productScheme .= $dataScheme['Effective_to']."^";
//           			
//			if($dataScheme['SchemeType'] =="Individual")
//			$productScheme .= "I^";
//			elseif($dataScheme['SchemeType'] =="Combined")
//			$productScheme .= "C^";
//			else
//			$productScheme .= "T^";
//
//			if($dataScheme['rebateunits'] =="Naira")
//			$productScheme .= "2^";
//			elseif($dataScheme['rebateunits'] =="%")
//			$productScheme .= "1^";
//			else
//			$productScheme .= "0^";
//			
//			if($dataScheme['rebate'] =="1")
//			$productScheme .= $dataScheme['rebatevalue']."^";
//			else
//			$productScheme .= "0^";
//            
//            $productScheme .= PHP_EOL;
//
//
//
//}

//
//  // 10 Scheme Detail
//            $schemeDetail .= "10^";
//			$schemeDetail .= $dataScheme['Scheme_code']."^";
//			$schemeDetail .= $dataScheme['Header_Product_code'] ."^";
//			$schemeDetail .= $id ."^";
//			$schemeDetail .= $dataScheme['Header_Quantity']."^";
//			$schemeDetail .= PHP_EOL;
//			
//			$schemeDetail .= "10^";
//			$schemeDetail .= $dataScheme['Scheme_code']."^";
//			$schemeDetail .= $dataScheme['line_Product_Code'] ."^";
//			$schemeDetail .= $id."^";
//			$schemeDetail .= $dataScheme['line_Product_quantity'] ."^";
//			$schemeDetail .= PHP_EOL;
 



// 11 feed back 
$feedback ="";
$query ="select Distinct customer_code from customer where DSR_Code ='" . $dsr_code ."'";
$resultcustom = mysql_query($query);
while($data = mysql_fetch_array($resultcustom))
{

    $query ="select Distinct Feedback_type,Feedback_Serial,Feedback from feedback where DSR_Code='" . $dsr_code ."'";
    $resultFeedback=mysql_query($query);
    while($dataFeedback = mysql_fetch_array($resultFeedback))
    {
        $feedback .= "11^";
		$feedback .= $data['customer_code']."^";
        $feedback .=  $dataFeedback['Feedback_type'] ."^";
        $feedback .=  $date ."^";
        $feedback .=  $dataFeedback['Feedback_Serial']."^";
        $feedback .=  $dataFeedback['Feedback']."^";
        $feedback .= PHP_EOL;
    }
    
}
//end of feedback



//12 Contact Details

$contactDetails = "";
$query = "select KD_Name,Contact_Number,Email_ID from kd_information";
$resultkdin = mysql_query($query);
while ($data = mysql_fetch_array($resultkdin)) {
    $contactDetails .= "12^" . $data['KD_Name'] . "^" . $data['Contact_Number']."^" .  $data['Email_ID']. "^";
    $contactDetails .= PHP_EOL;
}

//end of Contact Details




//13 new customer

$newcustomer="";
$query = "select * from new_customer_details";
$resultcusdet = mysql_query($query);
while($data = mysql_fetch_array($resultcusdet))
{
    
    $newcustomer .= "13^";
    $newcustomer .= $data['device_customer_code'] . "^";
    $newcustomer .= $data['Date'] . "^";
    $newcustomer .= $data['new_customer_code'] ."^";
    $newcustomer .= PHP_EOL;
    
}

//End new customer




//14 Check Out


$checkOut = "";
$query = "select id,reason from check_out_reason";
$resultcheck = mysql_query($query);
while ($data = mysql_fetch_array($resultcheck)) {
    $checkOut .= "14^" . $data['id'] . "^" . $data['reason'].  "^";
    $checkOut .= PHP_EOL;
}

//end of Check Out



//15 Return Reasons
$salereturn = "";
$query = "select id,salereturn from salereturn";
$resultsale = mysql_query($query);
while ($data = mysql_fetch_array($resultsale)) {
    $salereturn .= "15^" . $data['id'] . "^" . $data['salereturn'].  "^";
    $salereturn .= PHP_EOL;
}

//Return Reasons

//16 target Details
$Target ="";
$query ="select SUM(target_units) as valueSum from sr_incentive where KD_Code ='". $KD_Code."'";
$resultinc = mysql_query($query);
while($data = mysql_fetch_array($resultinc))
{
		
    $query ="select GROUP_CONCAT(DISTINCT eff_visit) as eff_visit,GROUP_CONCT(DISTINCT tgtTypeEff) as tgtTypeEff from coverage_target_setting where KD_Code ='" .$KD_Code."' GROUP by eff_visit";
    $resultvisit=mysql_query($query);
    while($datavisit = mysql_fetch_array($resultvisit))
    {
	
	$query ="select customer_count from customer_count where KD_Code ='" .$KD_Code."'";
	$results=mysql_query($query);
	while($visit = mysql_fetch_array($results))
	    {
		$Targetdetails .= "16^";
		$Targetdetails .=  $data['valueSum'] ."^";
		$Targetdetails .=  $datavisit['eff_visit'] ."^";
	    $Targetdetails .=  $visit['customer_count'] ."^";
		if($datavisit['tgtTypeEff'] =="0")
		$Targetdetails .= "T^";
		else
		$Targetdetails .= "C^";  	
    	$Targetdetails .= PHP_EOL;
        }
   }
}
//17 Product Target

//$ProductTargetDetails = "";
//$query = "select GROUP_CONCAT(DISTINCT Product_id) as Product_id,GROUP_CONCAT(DISTINCT target_units) as target_units,GROUP_CONCAT(DISTINCT target_naira) as target_naira from sr_incentive GROUP by Product_id";
//$resultsrinc = mysql_query($query);
//while ($data = mysql_fetch_array($resultsrinc)) {
//    $ProductTargetDetails .= "17^" . $data['Product_id'] . "^" . $data['target_units'].  "^". $data['target_naira'].  "^";
//    $ProductTargetDetails .= PHP_EOL;
//}

//End Product Target


//17 Brand Target
$BrandTargetDetails = "";
$querybr = "select GROUP_CONCAT(DISTINCT Brand_id) as Brand_id,GROUP_CONCAT(DISTINCT target_units) as target_units,GROUP_CONCAT(DISTINCT target_naira) as target_naira from srbrand_incentive GROUP by Brand_id";
$resultsrbrand = mysql_query($querybr);
while ($databr = mysql_fetch_array($resultsrbrand)) {
    $BrandTargetDetails .= "17^" . $databr['Brand_id'] . "^" . $databr['target_units'].  "^". $databr['target_naira'].  "^";
    $BrandTargetDetails .= PHP_EOL;
}

//End Brand Target


$curdate = date('d-m-y');
$directory = "../../../Download/"; 
$downloadirectory = "../../../Downloaded/";


$filename = $KD_Code."_".$device_code."_"."dl"."_".$curdate .".txt";

if(!is_dir($downloadirectory)){
  $mode = 0777;
  if(mkdir($downloadirectory,$mode,true)) {
 }
}

copy($directory.$filename, $downloadirectory.$filename);

$fp = fopen($directory.$filename, 'w+');

$write = fputs($fp, $system_parameters);
$write = fputs($fp, $device_assignment);
$write = fputs($fp, $customer_type);
$write = fputs($fp, $posm_master);
$write = fputs($fp, $feedback_catergory);
$write = fputs($fp, $customerMaster);
$write = fputs($fp, $brandMaster);
$write = fputs($fp ,$productMaster);
$write = fputs($fp ,$productBatch);
$write = fputs($fp ,$productScheme);
$write = fputs($fp ,$schemeDetail);
$write = fputs($fp ,$feedback);
$write = fputs($fp, $contactDetails);
$write = fputs($fp ,$newcustomer);
$write = fputs($fp, $checkOut);
$write = fputs($fp, $salereturn);
$write = fputs($fp, $Targetdetails);
/*$write = fputs($fp, $ProductTargetDetails);*/
$write = fputs($fp, $BrandTargetDetails);
fclose($fp);
} ?>
<script type="text/javascript">
alert("Download File <?php echo $filename ; ?> Success.");
window.location.href = "Downloadfile.php";
</script> 
<?php
	}

/*$query = "select * from device_registration where device_code = '".$mydevicecode."'";
$result = mysql_query( $query);*/
?>
