<?php
//echo "Hi"; exit;
set_time_limit(0);
error_reporting(0);
include "../include/config.php";
$remIp = $_GET['remip'];   // Specifies Remote Host Ip.
$table = $_GET['table'];  //Specifies Table Name 
$path = $_GET['path'];        // Specifies Remote path.
$type = $_GET['type'];
$tableName = explode("@", $table);
$kdSpecific = $_GET['kdSpecific'];
$Kdcode = $_GET['Kdcode'];
$remIp = str_replace("_", "/", $remIp);
//echo "<br>";
//echo $tableName;

//$path = str_replace("_", "/", $path);

if ($fp = fopen("http://" . $remIp .  $path . "/" . $table . ".csv", 'r')) {
    $content = '';
    // keep reading until there's nothing left 
    while ($line = fread($fp, 10000)) {
        $content .= $line;
    }
    //$filePath = "Table";
	
	
	$curdate =  date("Y-m-d");
	$directory = "..//..//d2r//functions//Table//$curdate//";
	
    if (!is_dir($directory)) {
		
	   $mode = 0777;	
       mkdir($directory,$mode,true);
    }
	
    writeCsv($directory.$table . ".csv", $content);

    $content_lines = explode("\n", $content);
	

    if ($type == "full") {
        $query = "truncate table " . $tableName[0];
        mysql_query($query);
    }

    foreach ($content_lines as $line) {
         //echo $line;
       //echo "<br>";
        $flag = false;
        $firstRecord = "";
        $contentDatas = explode(",", $line);
        $queryData = "'";
 
        foreach ($contentDatas as $contentData) {
            if ($flag == false) {
                $firstRecord = $contentData;
                $flag = true;
            }
            $queryData .= $contentData;
            $queryData .= "','";
        }
       
	   
	     if ($kdSpecific == "Y") {
 		 if($tableName[0] == "kd")
           {
              
             $query = "select * from kd where  KD_Code = '" . $Kdcode  ."'";
             $resultProduct =  mysql_query($query);
             
             $value= mysql_num_rows($resultProduct);
              
             if ($value ==0 )
               {    
                   $query = "insert into kd values('". $contentDatas[0] ."','". $contentDatas[1] ."','" . $contentDatas[2] . "','" . $contentDatas[3] . "','" . $contentDatas[5] . "','". $contentDatas[6] ."','". $contentDatas[7] ."','". $contentDatas[8] ."','". $contentDatas[9] ."','". $contentDatas[10] ."','". $contentDatas[11] ."','". $contentDatas[12] ."','". $contentDatas[13] ."','". $contentDatas[14] ."','". $contentDatas[15] ."','". $contentDatas[16] ."')";
              
                $return=   mysql_query($query);
                         if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
               }
               else {
                   $query = "update kd set 
				   id = '" . $contentDatas[0]. "',
				   KD_Code ='" .$contentDatas[1]. "',
				   KD_Name ='" .$contentDatas[2]. "',
				   Address_Line_1 ='" .$contentDatas[3]. "',
				   Address_Line_2 ='" .$contentDatas[4]. "'
				   Address_Line_3 ='" .$contentDatas[5]. "',
				   City ='" .$contentDatas[6]. "',
				   Pin ='" .$contentDatas[7]. "',
				   Contact_Person ='" .$contentDatas[8]. "',
				   Contact_Number ='" .$contentDatas[9]. "',
				   Email_ID ='" .$contentDatas[10]. "',
				   kd_category ='" .$contentDatas[11]. "',
				   kd_analysis ='" .$contentDatas[12]. "',
				   miscellaneous_caption ='" .$contentDatas[13]. "',
				   miscellaneous_data ='" .$contentDatas[14]. "',
				   AUDIT_DATE_TIME ='" .$contentDatas[15]. "'
			       where KD_Code='" .$Kdcode. "'";
                   //echo $query;
                 $return=  mysql_query($query);
                 if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
                    }
           }
		}		
   
        if ($firstRecord) {
	        if ($type == "full") {
                //echo " " . $tableName[0] . " ";
                $queryData = substr($queryData, 0, -3);
               
            $query = "INSERT INTO " . $tableName[0] . " values (" . $queryData . "')";
          // echo $query . "a";
             $return= mysql_query($query) or die(mysql_error());
                   if($return == false)
                 {
                     echo "Error";
                    
                     exit();
                 }
            }

            if ($type == "slu") {
                
                $query = "select * from " . $tableName[0] . " where id='" . $firstRecord . "'";
                $result = mysql_query($query);
                //echo $query;
                if (mysql_num_rows($result) == 0) {
                    $queryData = substr($queryData, 0, -3);
                    $query = "Insert into " . $tableName[0] . " values(" . $queryData . "')";
                 $return =  mysql_query($query);
                       if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
                } else {
                    $query = "delete from " . $tableName[0] . " where id ='" . $firstRecord . "'";
                    mysql_query($query);
                    $queryData = substr($queryData, 0, -3);
                    $query = "Insert into " . $tableName[0] . " values(" . $queryData . "')";
                 $return= mysql_query($query);
                       if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
                }
            }
			
			
		   
			
			
           if($tableName[0] == "product")
           {
              
               $query = "select * from opening_stock_update where  Product_code = '" . $contentDatas[2] ."'";
             $resultProduct =  mysql_query($query);
             
             $value= mysql_num_rows($resultProduct);
              
             if ($value ==0 )
               {    
                   $query = "insert into opening_stock_update values('','','" . $contentDatas[2] . "','" . $contentDatas[3] . "','" . $contentDatas[5] . "','','','','','','','','','')";
              
                $return=   mysql_query($query);
                         if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
               }
               else {
                    $query = "update opening_stock_update set Product_description = '" . $contentDatas[3]. "',UOM1 ='" .$contentDatas[5]. "' where Product_code='" .$contentDatas[2] . "'";
                   //echo $query;
                 $return=  mysql_query($query);
                 if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
                    }
           }
        }
    }
    echo "Completed";
}

function writeCsv($fname, $content) {

    $fp = fopen($fname, 'w+');
    $write = fputs($fp, $content);
    fclose($fp);
}

?>