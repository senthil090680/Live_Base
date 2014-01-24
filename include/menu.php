<?php
ob_start();
include('header.php');
session_start();
?>
<!------------------------------- Form -------------------------------------------------->
<div id="mainarea">
<div  style="padding-top:20%;" class="mydiv">
<h3 align="center" class="sucmsg">
Welcome <?php echo $_SESSION['username'];?>
</h3>
</div>
</div>
<?php include('footer.php');?>
