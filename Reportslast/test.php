<?php
include ("../include/ajax_pagination.php");
$val = Array(Array("destination" => "Sydney","airlines" => "airline_2","one_way_fare" => 150,"return_fare" => 300),Array("destination" => "Sydney","airlines" => "airline_3","one_way_fare" => 180,"return_fare" => 350),Array("destination" => "Sydney","airlines" => "airline_1","one_way_fare" => 100,"return_fare" => 380),Array("destination" => "Sydney","airlines" => "airline_2","one_way_fare" => 100,"return_fare" => 380),Array("destination" => "Sydney","airlines" => "airline_1","one_way_fare" => 120));


pre($val);

$de	=	array_multi_sort($val, "airlines","one_way_fare", $order=SORT_ASC);

function array_multi_sort($val, $on1,$on2, $order=SORT_ASC) {
	foreach($val as $key=>$value){
		$one_way_fares[$key] = $value[$on2];
		$return_fares[$key] = $value[$on1];
	}
	array_multisort($return_fares,$order,$one_way_fares,$order,$val);
	return $val;
}

pre($de);
?>