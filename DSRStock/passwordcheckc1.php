<?php
//Connect to database from here
include "../include/config.php";
ob_Start();
EXTRACT($_POST);
$id=$_GET['id'];
$loginpass = "Editpassword";
$password=$_POST['password'];
if($_POST['password']!=""){
		if($password==$loginpass)
		{
		 header("location:CollectionDeposited.php?id=$id");
		}
		else
		{ 
		header("location:passwordcheckc1.php");?>
        <script type="text/javascript">
		alert('Please Enter Correct Password');
		</script>
        
		 
	<?php }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HOST SYSTEM</title>
<style>
body{
background:#000000;
}
.editform{
background:#FFFFFF;
margin-top:10%;
padding-left:10px;
padding-top:50px;
width:300px;
height:80px;
margin-left:auto;
margin-right:auto;
border-radius:10px;
}

</style>
</head>
<body>
<h2 align="center" style="color:#FFF">Enter Password To Edit</h2>
<div class="editform">
       <form action="" method="post" autocomplete="off">
        <input type="hidden" name="popid" value="<?php echo $_GET['id']; ?> "/>
        <input type="text" name="password" value=""/>
        <input type="submit" name="submit"  value="Go" class="buttons"/>
        <input type="button" name="Cancel"  value="Cancel" class="buttons" onclick="window.location='CollectionDepositedview.php'"/>
       </form>       
</div> 
<?php if($_GET['msg']=='2'){ 
echo "Please Enter Password";
}
?>
                 
</body>
</html>


