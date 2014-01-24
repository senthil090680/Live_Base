<script type="text/javascript">
alert("Test");
</script>

<?php
include "../include/config.php";
$mydeviceCode = $_POST['Devicecode'];

$query = "select * from device_registration where device_code = '".$mydeviceCode."'";
$result = mysql_query( $query);
?>
<style>
td {
	padding: 3px;
}
</style>
<div class="conscroll">
<table width="100%">
  <thead>
    <tr>
      <th>Device Code</th>
      <th>KD Name</th>
      <th>KD Code</th>
      <th>DSR Code</th>
      <th>Status</th>
    </tr>
  </thead>
  <?php
        while($data = mysql_fetch_array($result)) {
			$codedevice = $data['device_code'];
			$kdname     = $data['KD_Name'];
			$kdcode     = $data['KD_Code'];
			$dsrcode    = $data['dsr_code'];
			$status     = "Download Success";
			
            echo "<tr>";
            echo "<td>" . $codedevice . "</td> <td>" . $kdname . "</td> <td>" . $kdcode . "</td> <td>" . $dsrcode . "</td> <td>" . $status . "</td>";
            echo "</tr>";
        }
    ?>
</table>
</div>
