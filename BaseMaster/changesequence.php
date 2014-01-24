<?php
session_start();
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
?>
<!------------------------------- Form -------------------------------------------------->
<script type="text/javascript">
$(document).ready(function()
{
$(".editmybox").click(function()
{
var ID=$(this).attr('id');
$("#seq_"+ID).hide();
$("#seq_input_"+ID).show();
}).change(function()

{
var ID=$(this).attr('id');
var seq=$("#seq_input_"+ID).val();
var dataString = 'id='+ ID +'&sequence_number='+seq;


if(seq.length && last.length >0)
{
$.ajax({
type: "POST",
url: "table_edit_ajax.php",
data: dataString,
cache: false,
success: function(html)
{
$("#seq_"+ID).html(first);
}
});
}
else
{
alert('Enter something.');
}

});

$(".editbox").mouseup(function() 
{
return false
});

$(document).mouseup(function()
{
$(".editbox").hide();
$(".text").show();
});

});
</script>


<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingsgr">Change Sequence Number</div>
<div id="mytableformdsr" align="center">
<!--  <div id="search">
        <form action="" method="get">
        <input type="text" name="DSRName" value="<?php $_GET['DSRName']; ?>" autocomplete='off' placeholder='Search By DSR Name'/>
        <input type="submit" name="submit" class="buttonsg" value="GO"/>
        </form>       
        </div>-->
<div class="mcf"></div>        
<div id="containerpr">
 	    <?php
	    $qry= "select * from customer order by sequence_number asc"; 
		$results=mysql_query($qry);
		$pager = new PS_Pagination($bd, $qry,5,5);
		$results = $pager->paginate();
		$num_rows= mysql_num_rows($results);			
		?>
        <div class="con">
        <table width="100%">
		<thead>
		<tr>
		<th class="rounded">Sequence Number<img src="../images/sort.png" width="13" height="13" /></th>
		<th>Customer Name</th>
       	</tr>
		</thead>
        
		<tbody>
		<?php
		if(!empty($num_rows)){
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($results)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls =" class='odd'"; }
		$id= $fetch['id'];
		$sequence_number = $fetch['sequence_number'];
		$route=$fetch['route'];
		$Customer_Name=$fetch['Customer_Name'];
		?>
		<tr id="<?php echo $id; ?>" class="editmybox">
		<td>
        <span id="seq_<?php echo $id; ?>"><?php echo $sequence_number; ?></span>
        <input type="text" value="<?php echo $sequence_number; ?>" class="editbox" id="seq_input_<?php echo $id; ?>" />
        </td>
        <td><?php echo $Customer_Name; ?></td>
        </tr>
		<?php $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
         </div>   
         

      </div> 
   </div>
</div>
<?php include('../include/footer.php'); ?>