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

    $content_lines =explode("\n", $content);
	

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
            $queryData .= trim($contentData);
            $queryData .= trim("','");
        }
		
		 if ($kdSpecific == "Y") {
 		      if($tableName[0] == "kd")
           {
              
             $query = "select * from kd where  KD_Code = '" . $Kdcode  ."'";
             $resultProduct =  mysql_query($query);
             
             $value= mysql_num_rows($resultProduct);
              
             if ($value ==0 )
               {    
           $query = "insert into kd values('". trim($contentDatas[0]) ."','". trim($contentDatas[1]) ."','" . trim($contentDatas[2]) . "','" . trim($contentDatas[3]) . "','" . trim($contentDatas[5]) . "','". trim($contentDatas[6]) ."','". trim($contentDatas[7]) ."','". trim($contentDatas[8]) ."','". trim($contentDatas[9]) ."','". trim($contentDatas[10]) ."','". trim($contentDatas[11]) ."','". trim($contentDatas[12]) ."','". trim($contentDatas[13]) ."','". trim($contentDatas[14]) ."','". trim($contentDatas[15]) ."','". trim($contentDatas[16]) ."')";
              
                $return=   mysql_query($query);
                         if($return == false)
                 {
                     echo "Error";
                     exit();
                 }
               }
               else {
                   $query = "update kd set 
				   id = '" . trim($contentDatas[0]). "',
				   KD_Code ='" .trim($contentDatas[1]). "',
				   KD_Name ='" .trim($contentDatas[2]). "',
				   Address_Line_1 ='" .trim($contentDatas[3]). "',
				   Address_Line_2 ='" .trim($contentDatas[4]). "'
				   Address_Line_3 ='" .trim($contentDatas[5]). "',
				   City ='" .trim($contentDatas[6]). "',
				   Pin ='" .trim($contentDatas[7]). "',
				   Contact_Person ='" .trim($contentDatas[8]). "',
				   Contact_Number ='" .trim($contentDatas[9]). "',
				   Email_ID ='" .trim($contentDatas[10]). "',
				   kd_category ='" .trim($contentDatas[11]). "',
				   kd_analysis ='" .trim($contentDatas[12]). "',
				   miscellaneous_caption ='" .trim($contentDatas[13]). "',
				   miscellaneous_data ='" .trim($contentDatas[14]). "',
				   AUDIT_DATE_TIME ='" .trim($contentDatas[15]). "'
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
               
            $query = "INSERT INTO " . $tableName[0] . " values (".$queryData."')";
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
                    $query = "Insert into " . $tableName[0] . " values(".$queryData."')";
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
                    $query = "Insert into " . $tableName[0] . " values (".$queryData."')";
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