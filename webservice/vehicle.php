<?php
include "../include/config.php";
include "../include/ajax_pagination.php";
$query = "select KD_Code from kd_information";
$result = mysql_query($query);
$data = mysql_fetch_array($result);
$kdcode = $data['KD_Code'];

//$deviceCode = "DV001";
$query = "select device_code from device_registration";
$result = mysql_query($query);
$data = mysql_fetch_array($result);
$devicecode = $data['device_code'];

$query = "select CURDATE()";
$result = mysql_query($query);
while($data = mysql_fetch_array($result))
{
    $date = $data['CURDATE( )'];
	$time_now=mktime(date('g')+4,date('i')-30,date('s')); $time = date('Y-m-d h:i:s',$time_now);  
	$datetime = $time;
}


$time = time();		

$curdate = date('Y-m-d');

$mysqltime = date("Y-m-d H:i:s");

$previousDate = date("Y-m-d", mktime(0,0,0,date("n", $time),date("j",$time)- 1 ,date("Y", $time)));

$previousDatefile = date("d-m-y", mktime(0,0,0,date("n", $time),date("j",$time)- 1 ,date("Y", $time)));





$query = "select * from cycle_assignment where Date LIKE '$date%' AND ((flag_status= '1' and end_flag_status='0') or (flag_status = '0' and end_flag_status='0')) ORDER BY dsr_code";
//echo $query;
//exit;
$result = mysql_query($query);
while($data = mysql_fetch_array($result)) {
	$dsr_code = $data['dsr_code'];
	$vehicle_id	 = $data['vehicle_id'];
	$device_id	 = $data['device_id'];
	$device_code = $data['device_code'];
	$KD_Code	 = $data['KD_Code'];
    $route_id    = $data['route_id'];
	//$vehicle_code	=	getdbval($vehicle_id,'vehicle_code','id','vehicle_master');
	//$devicecode		=	getdbval($device_id,'device_code','id','device_master');
	
	
	
    $query = "select vehicle_code,vehicle_reg_no from vehicle_master where id='" . $vehicle_id ."'";
	$resultveh = mysql_query($query);
	while($datatemp = mysql_fetch_array($resultveh))
	{
	$vehicle_code = $datatemp['vehicle_code'];
	$vehicleRegNo = $datatemp['vehicle_reg_no'] ;
	}
	
	


$vehicleStockheader = "";
$query = "select  * from dailystockloading where DSR_Code = '" . $dsr_code. "' AND Vehicle_Code = '$vehicle_code'  AND Date LIKE '$curdate%' order by DSR_Code,Load_Sequence_No asc";

	
	//$u	=	0;
	//$vehicleStockheader	=	'';
	//$vehicleStock		=	'';
	$resultvehicle = mysql_query($query);
    while($dataveh = mysql_fetch_array($resultvehicle))
    {   
		$sequence = $dataveh['Load_Sequence_No'];
		$Productcodecur = $dataveh['Product_code'];
		

          
	        $time = time();
		    $previousDate = date("Y-m-d", mktime(0,0,0,date("n", $time),date("j",$time)- 1 ,date("Y", $time)));	
			$query = ("select * from dailystockloading where Date LIKE '$previousDate%' AND Confirmed_Qty = '' AND DSR_Code = '" . $dsr_code. "' AND updateflag = 0");	
			$res=mysql_query($query);
			while($row=mysql_fetch_array($res)){
			$Product_code = $row['Product_code'];
			$DSR_Code = $row['DSR_Code'];
			$sequencenew = $row['Load_Sequence_No'];
			$newqty = $row['Loaded_Qty'];
			$updateflag = $row['updateflag'];
			$quantity = $dataveh['Loaded_Qty'] + $newqty;
			$curdate = date('Y-m-d');
			
		   	if($Product_code  == $Productcodecur){
			$curdate = date('Y-m-d');
			$sql = "UPDATE dailystockloading SET Loaded_Qty ='$quantity' WHERE Product_code = '$Productcodecur' AND DSR_Code = '$DSR_Code' AND  updateflag = 0 AND  Date LIKE '$curdate%'";
			$res=mysql_query($sql);
			$flagsql = "UPDATE dailystockloading SET updateflag = 1, myflag = 1 WHERE Product_code = '$Productcodecur' AND Date LIKE '$previousDate%' AND DSR_Code = '$DSR_Code'";
			$res=mysql_query($flagsql);
			 }  
	    }
		
 }			
		   $query = ("select * from dailystockloading where Date LIKE '$previousDate%' AND Confirmed_Qty = ''AND DSR_Code = '" . $dsr_code. "' AND myflag = 0");	
$resp=mysql_query($query);
			while($row=mysql_fetch_array($resp)){
			$Product_codes = $row['Product_code'];
			$qty = $row['Loaded_Qty'];
			$KD_Code = $row['KD_Code'];
			$DSR_Code = $row['DSR_Code'];
			$vehicle_code = $row['vehicle_code'];
            $UOM = $row['UOM'];
			$date=$row['Date'];
			$scheme_Flag = $row['scheme_Flag'];
			$focus_Flag = $row['focus_Flag'];
			$ProductType = $row['ProductType'];
			$myflag = 0;
			$download_status = 0;
			
		    if($Product_codes != $Productcodecur){
			//if($x==0){
		    $sql = "INSERT INTO dailystockloading(
  `Kd_Code`,`Date`,`DSR_Code`,`vehicle_code`,`Product_code`,`UOM`,`Load_Sequence_No`,`Loaded_Qty`,`Confirmed_Qty`,`focus_Flag`,`scheme_Flag`,`ProductType`,`download_status`,`myflag`)
  VALUES('$KD_Code','$mysqltime','$DSR_Code','$vehicle_code','$Product_codes','$UOM','$sequencenew','$qty','','$focus_Flag','$scheme_Flag','$ProductType','$download_status','$myflag')";
        $query = mysql_query($sql); 
		$flagup = "UPDATE dailystockloading SET updateflag = 1, myflag = 1 WHERE Product_codes = '$Productcodecur' AND Date LIKE '$previousDate%' AND DSR_Code = '$DSR_Code'";
		$resup =mysql_query($flagup);
			//}
			//$x++;
		  }
		}

	
		
$query = "select KD_Code,DSR_Code,Vehicle_Code,Load_Sequence_No,Product_code,Loaded_Qty from dailystockloading where DSR_Code = '" . $dsr_code. "' AND Vehicle_Code = '$vehicle_code' AND  download_status = 0 AND Date LIKE '$curdate%' order by DSR_Code,Load_Sequence_No asc";
	
	$u	=	0;
	$vehicleStockheader	=	'';
	$vehicleStock		=	'';
	$resultvehicle = mysql_query($query);
    while($dataveh = mysql_fetch_array($resultvehicle))
    {   
		$sequence = $dataveh['Load_Sequence_No'];
		$Productcodecur = $dataveh['Product_code'];		
		if($u == 0) { 
			$vehicleStockheader .= "1^";
			$vehicleStockheader .= $device_code. "^";
			$vehicleStockheader .= $dataveh['DSR_Code']. "^";
			$vehicleStockheader .= $dataveh['KD_Code']. "^";
			$vehicleStockheader .= $dataveh['Vehicle_Code']. "^";
			$vehicleStockheader .= $dataveh['Load_Sequence_No'] . "^";
			$vehicleStockheader .= PHP_EOL;
			$sequenceval	=	$dataveh['Load_Sequence_No'] ;
			$update_query	=	"UPDATE dailystockloading SET download_status = 1 WHERE Load_Sequence_No = '$sequenceval' AND DSR_Code = '".$dsr_code."'";
			mysql_query($update_query);	
		}
		
		
		if($sequenceval == $sequence) {
				$vehicleStock .= "2^";
				$product_id	   = getdbval($dataveh[Product_code],'id','Product_code','product');
				$vehicleStock .= $product_id  . "^";
				$vehicleStock .= $dataveh['Loaded_Qty']  . "^";
				$vehicleStock .= PHP_EOL;	
       } 
     $u++;
}

 
	/*Start of Product Batch*/
	$productMaster ="";
	$productBatch ="";

	$query ="select id,Product_code,batch_ctrl from product";
    $resultProduct = mysql_query($query);
    while($dataProduct = mysql_fetch_array($resultProduct))
    {                    
       if($dataProduct['batch_ctrl']=="ON")
        {
            $query = "select Product_code,Batch_Number,Expiry_Date from batch_master where Product_code='" . $dataProduct['Product_code'] ."'";
            $resultBatch = mysql_query($query);
            while($dataBatch = mysql_fetch_array($resultBatch))
            {
                $productBatch .= "3^";
                $productBatch .= $date . "^";
                $productBatch .= $dataBatch['Product_code'] . "^";
                $productBatch .= $dataBatch['Batch_Number'] . "^";
                $productBatch .= $dataBatch['Expiry_Date']  . "^";
                $productBatch .= $data['Quantity'] . "^";
                $productBatch .= PHP_EOL;
            }    
		}
	}
	
	
	
	
	
	/*end of Product Batch*/

	//file writing

	
	//echo $vehicleStockheader."<br>";
	//echo $vehicleStock."<br>";
	//echo $productBatch."<br>";
	
	if($u !=0) {
		
		$time = time();		
		$previousDate = date("d-m-y", mktime(0,0,0,date("n", $time),date("j",$time)- 1 ,date("Y", $time)));
		$curdate = date('d-m-y'); 
		
		$directory = "../../../Download/"; 
		$vehicledirectory = "../../../Vehicleload/";
		
		$filename = $KD_Code."_".$device_code."_"."vh"."_".$curdate .".txt";
		
    	$filenameprev = $KD_Code."_".$device_code."_"."vh"."_".$previousDate .".txt";
		
			
		if(file_exists($directory.$filenameprev)) {
		 unlink($directory.$filenameprev);
		}
		
		if(!is_dir($vehicledirectory)){
			
		  $mode = 0777;	
		  if(mkdir($vehicledirectory,$mode,true)) {
		}
		}
		
		copy($directory.$filename, $vehicledirectory.$filename);

		
		//echo $filename = "vehicle.txt";
		$fp = fopen($directory.$filename, 'w+');

		$write = fputs($fp, $vehicleStockheader);
		$write = fputs($fp, $vehicleStock);
		$write = fputs($fp, $productBatch);
		fclose($fp);
	}
}


?>