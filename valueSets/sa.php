    //Check city is Assigned to Customer
		if($_GET['delID']!=''){
	    $id = $_GET['delID'];
		$city_sql="select a.*,b.* from customer as a,city as b where a.city='$city' AND b.city='$id'";
		$rescity=mysql_query($city_sql);
		$cnt=mysql_num_rows($rescity);
		if($cnt=='1'){
        header("location:city.php?no=26"); 
		  }
		else{
		//Check city is Assigned to DSR
		$citydsr="select a.*,b.* from dsr as a,city as b where a.city='$city' AND b.city='$id'";
		$dsr=mysql_query($citydsr);
		$cnt=mysql_num_rows($dsr);
		if($cnt=='1'){
        header("location:city.php?no=27"); 
		  }
		 }
		else{
		//Check city is Assigned to DSR
		$citykd="select a.*,b.* from kd as a,city as b where a.city='$city' AND b.city='$id'";
		$kd=mysql_query($citykd);
		$cnt=mysql_num_rows($kd);
		if($cnt=='1'){
        header("location:city.php?no=28"); 
		  }
		 }  
		}