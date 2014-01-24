<?php
include("../include/config.php");
include "../include/ps_pagination.php";

$process = $_POST['process'];
$route = $_GET['route'];

if ($process == $_POST['process']) {
 // DB Connection
$query = "select * from customer where route ='$process' order by sequence_number asc";
     $id=$row['id'];
	 $cus=$row['Customer_Name'];
	 $cuscount=count($cus);
}
		$result = mysql_query( $query);
		$num_rows= mysql_num_rows($result);			
		$pager = new PS_Pagination($bd, $qry,8,8);
		$results = $pager->paginate();
		?>

<style>
td {
	padding: 3px;
}
.editbox
{
display:none
}
.editbox
{
font-size:14px;
width:270px;
background-color:#ffffcc;
border:solid 1px #000;
padding:4px;
}

.conscroll{
	width:80%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	height:300px;
    overflow:scroll;
	overflow-x:hidden;
}

.conscroll th{
	width:22%;
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.conscroll td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.conscroll tbody tr:hover td{
	background: #c1c1c1;
}

</style>
<script type="text/javascript">
$(document).ready(function()
{
	
$(".edit_tr").click(function()
{

var ID=$(this).attr('id');
//alert(ID);	
$("#first_"+ID).hide();
$("#first_input_"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
//alert(ID);Customer_Name
var first= $("#first_input_"+ID).val();
var sec= $("#sec_input_"+ID).val();
//alert(first);Customer_Name

var dataString = 'id='+ ID +'&sequence_number='+first +'&Customer_Name='+sec;
//alert(dataString);
$("#first_"+ID);


if(first.length >0 & sec.length >0)
{
$.ajax({
type: "POST",
url: "table_edit_ajax.php",
data: dataString,
cache: false,
success: function(html)
{
 $.ajax ({  
      type: 'POST',
      url: "table_edit_ajaxcount.php",
      data: dataString,
      success: function(html){
    	
$("#first_"+ID).html(first);
}
});
 
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



 <div class="conscroll">
        <table id="sort" class="tablesorter" align="center" width="100%" border="1">
		<thead>
        <tr>
        <th>Sequence Number</th>
        <th>Customer Name</th>
        </tr>
		</thead>
		<tbody>
		<?php
		if(!empty($num_rows)){
		$i=1;
		$c=0;$cc=1;
		while($fetch = mysql_fetch_array($result)) {
		if($c % 2 == 0){ $cls =""; } else{ $cls ="class='odd'"; }
		$id=$fetch['id'];
		?>		
		<tr id="<?php echo $id; ?>" class="edit_tr">
	    <td width="50%" class="edit_td">
        <span id="first_<?php echo $id; ?>" class="text"><?php echo $fetch['sequence_number'];?></span>
        <input type="text" name="sequence_number[]" value="<?php echo $fetch['sequence_number'];?>" class="editbox" id="first_input_<?php echo $id; ?>" />
        </td>
        <td>
		<?php echo $fetch['Customer_Name'];?>
        <input type="hidden" name="Customer_Name[]" value="<?php echo $fetch['Customer_Name'];?>" id="sec_input_<?php echo $id;?>" >
        </td>
		</tr>
		<?php $i++; $c++; $cc++; }		 
		}else{  echo "<tr><td align='center' colspan='13'><b>No records found</b></td></tr>";}  ?>
		</tbody>
		</table>
        </div>
