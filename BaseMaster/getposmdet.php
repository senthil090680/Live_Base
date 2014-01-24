<?php
session_start();
ob_start();
require_once "../include/config.php";
require_once "../include/ajax_pagination.php";
//require_once "../include/ps_pagination.php";
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_GET);
if(isset($_GET[prodid]) && $_GET[prodid] !='') {
	$nextrecval		=	"WHERE (Product_id = '$prodid')";	
} else {
	$nextrecval		=	"";
}
$where		=	"$nextrecval";

if(isset($_GET) && $_GET !='')
{
	//$qry="SELECT * FROM `product` $where";
	$qry="SELECT * FROM `customertype_product` $where";
}
else
{ 
	echo "Invalid Query";
	exit;
}
$res_Brand							=	mysql_query($qry);
$num_Brand							=	mysql_num_rows($res_Brand);

if($num_Brand > 0 ) {
	$row_Brand						=	mysql_fetch_array($res_Brand);
	$Brand							=	$row_Brand[brand];
	$Principal						=	$row_Brand[principal];
}

$brandName							=	upperstate(getdbval($Brand,'brand','id','brand'));
$princName							=	upperstate(getdbval($Principal,'principal','id','principal'));
$prodcode							=	upperstate(getdbval($prodid,'Product_code','Product_id','customertype_product'));
$customer_type						=	upperstate(finddbval("('".$prodcode."')",'customer_type','Product_code','customertype_product'));
$customer_typeval					=	str_replace("'","",$customer_type);
$customer_typeval					=	str_replace(",","+",$customer_typeval);
$customer_typearr					=	explode(',',str_replace("'","",$customer_type));
//pre($customer_typearr);
$val					=	0;
$customer_typenameval	=	'';
$num_cuscnt				=	0;
foreach($customer_typearr AS $CUSTYPEVAL) {
	$qry_cuscnt							=	"SELECT id FROM `customer` WHERE customer_type = '$CUSTYPEVAL'";
	$res_cuscnt							=	mysql_query($qry_cuscnt);
	$num_cuscntval						+=	mysql_num_rows($res_cuscnt);

	if($val == 0) {
		//echo "232<br>";
		$customer_typenameval			=	upperstate(getdbval($CUSTYPEVAL,'customer_type','id','customer_type'));
		$val++;
	} else {
		//echo "456<br>";
		$customer_typename				=	upperstate(getdbval($CUSTYPEVAL,'customer_type','id','customer_type'));
		$customer_typenameval		.=	", ".$customer_typename;
	}
	$customer_typename					=	'';
	$num_cuscnt							=	0;
}

//echo $customer_typenameval;
//exit;

echo $Brand."~".$brandName."~".$Principal."~".$princName."~".$customer_typeval."~".$customer_typenameval."~".$num_cuscntval;
exit(0);?>