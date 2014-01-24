<?php
session_start();
ob_start();
include('../include/header.php');
if(isset($_GET['logout'])) {
	session_destroy();
	header("Location:../index.php");
}
require_once "../include/ajax_pagination.php";
extract($_REQUEST);

//error_reporting(E_ALL);
//ini_set("display_errors",true);
//pre($_REQUEST);
//exit;
$query_RSM 							=	"select id,DSRName,DSR_Code from rsm_sp";
$res_RSM 							=	mysql_query($query_RSM) or die(mysql_error());

$query_ASM 							=	"select id,DSRName,DSR_Code from asm_sp";
$res_ASM 							=	mysql_query($query_ASM) or die(mysql_error());

$query_SR 							=	"select id,DSRName,DSR_Code from dsr";
$res_SR 							=	mysql_query($query_SR) or die(mysql_error());

$query_branch 						=	"select id,branch from branch";
$res_branch 						=	mysql_query($query_branch) or die(mysql_error());

$query_brand 						=	"select id,brand from brand";
$res_brand 							=	mysql_query($query_brand) or die(mysql_error());
$rowcnt_brand 						=	mysql_num_rows($res_brand);

$srstr								=	getdbstr('DSR_Code','dsr');
$rsmstr								=	getdbstr('id','rsm_sp');
$asmstr								=	getdbstr('DSR_Code','asm_Sp');
$branchstr							=	getdbstr('id','branch');
$brandstr							=	getdbstr('id','brand');

//pre($_POST);
//exit;
$KD_Code=getKDCode();
if(isset($_POST[submit]) && $_POST[submit] == 'Save') {
	$k						=	0;
	$monthval				=	trim(date('m'),0);
	$yearval				=	date('Y');
	
	pre($_POST);
	//exit;
	if($fromyear != $toyear) {
		for($k=$frommonth; $k<=12; $k++) {
			$fromcal[$k.$fromyear]	=	$k."~".$fromyear;
		}
		for($k=1; $k<=$tomonth; $k++) {
			$fromcal[$k.$toyear]	=	$k."~".$toyear;
		}
	} else {
		for($k=$frommonth; $k<=$tomonth; $k++) {
			$fromcal[$k]	=	$k."~".$toyear;
		}
	}

	//pre($fromcal);
	//exit;
	if(!isset($tgt_edit) && $tgt_edit != 'edit') {
		$post_dsrcodearr				=	$_POST["srcode"];

		if($post_dsrcodearr[1] == '') {
			if($post_dsrcodearr[0] != '') {

				if(strstr($post_dsrcodearr[0],',')) {
					$post_dsrcodearr[0]		=	str_replace("'",'',$post_dsrcodearr[0]);
					$dsrcodearrval			=	explode(',',$post_dsrcodearr[0]);
				} else {
					$dsrcodearrval		=	$post_dsrcodearr;
				}
			}
		} else {
			$dsrcodearrval		=	$post_dsrcodearr;
		}

		foreach($dsrcodearrval AS $post_dsrcode) {
			for($i = 0; $i < $overall_rowcnt; $i++) {

				$post_prodcode		=	$_POST["productname_".$i];	
				$post_units			=	remcom($_POST["tgt_units_".$i]);
				$post_naira			=	remcom(remdot($_POST["tgt_naira_".$i]));
				//exit;
				$post_targetflag	=	$_POST["targetflag"];
				$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

				if($post_prodcode == '' && $post_dsrcode == '') {
					$post_prodcode		=	'';	
					$post_units			=	'';
					$post_naira			=	'';
					$post_targetflag	=	'';
					$post_tgt_id		=	'';
					continue;
				}

				foreach($fromcal AS $monthyear) {
					
					$monthyeararr	=	explode("~",$monthyear);

					$monthval		=	$monthyeararr[0];
					$yearval		=	$monthyeararr[1];

					$sel_tgtcheck				=	"SELECT * from sr_incentive WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
					//exit;
					$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
					$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
					if($rowcnt_tgtcheck	> 0) {
						$update_tgt		=	"UPDATE sr_incentive SET KD_Code= '$KD_Code',DSR_Code = '$post_dsrcode', target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), Product_id = '$post_prodcode', targetFlag = '$post_targetflag' WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
						$res_tgt		=	mysql_query($update_tgt);
					} else {
						echo $insert_tgt		=	"INSERT INTO sr_incentive (KD_Code,DSR_Code,monthval,yearval,target_units,target_naira,Product_id,insertdatetime,targetFlag) VALUES ('$KD_Code','$post_dsrcode','$monthval','$yearval','$post_units','$post_naira','$post_prodcode',NOW(),'$post_targetflag')";						
						mysql_query($insert_tgt) or die(mysql_error());
						//exit;
					}
				}
			}
		}
		header("location:srincentiveview.php?no=1");

	} else {
		$post_dsrcodearr				=	$_POST["srcode"];

		if($post_dsrcodearr[1] == '') {
			if($post_dsrcodearr[0] != '') {

				if(strstr($post_dsrcodearr[0],',')) {
					$post_dsrcodearr[0]		=	str_replace("'",'',$post_dsrcodearr[0]);
					$dsrcodearrval			=	explode(',',$post_dsrcodearr[0]);
				} else {
					$dsrcodearrval		=	$post_dsrcodearr;
				}
			}
		} else {
			$dsrcodearrval		=	$post_dsrcodearr;
		}

		foreach($post_dsrcodearr AS $post_dsrcode) {
			for($i = 0; $i < $overall_rowcnt; $i++) {

				$post_prodcode		=	$_POST["productname_".$i];	
				$post_units			=	remcom($_POST["tgt_units_".$i]);
				$post_naira			=	remcom(remdot($_POST["tgt_naira_".$i]));
				//exit;
				$post_targetflag	=	$_POST["targetflag"];
				$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

				if($post_prodcode == '' && $post_dsrcode == '') {
					$post_prodcode		=	'';	
					$post_units			=	'';
					$post_naira			=	'';
					$post_targetflag	=	'';
					$post_tgt_id		=	'';
					continue;
				}
				
				foreach($fromcal AS $monthyear) {
					
					$monthyeararr	=	explode("~",$monthyear);

					$monthval		=	$monthyeararr[0];
					$yearval		=	$monthyeararr[1];

					echo $sel_tgtcheck				=	"SELECT * from sr_incentive WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
					$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
					$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
					if($rowcnt_tgtcheck	> 0) {
						$update_tgt		=	"UPDATE sr_incentive SET KD_Code= '$KD_Code',DSR_Code = '$post_dsrcode', target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), Product_id = '$post_prodcode',targetFlag='$post_targetflag' WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
						$res_tgt		=	mysql_query($update_tgt);
					} else {
						$insert_tgt		=	"INSERT INTO sr_incentive (KD_Code,DSR_Code,monthval,yearval,target_units,target_naira,Product_id,insertdatetime,targetFlag) VALUES ('$KD_Code','$post_dsrcode','$monthval','$yearval','$post_units','$post_naira','$post_prodcode',NOW(),'$post_targetflag')";
						
						mysql_query($insert_tgt) or die(mysql_error());
					}
				}
			}
		}
		header("location:srincentiveview.php?no=2");
	}
}

$query_prod 						=	"select id,Product_code,Product_description1,brand from product WHERE product_type != 'POSM'";
$res_prod 							=	mysql_query($query_prod) or die(mysql_error());
$rowcnt_prod 						=	mysql_num_rows($res_prod);
while($row_prod 					=	mysql_fetch_array($res_prod)) {
	$product_codearr[]				=	$row_prod[Product_code];
}

$sel_tgtsr							=	"SELECT * from sr_incentive WHERE id = '$id'";
$res_tgtsr							=	mysql_query($sel_tgtsr) or die(mysql_error());
$rowcnt_tgtsr						=	mysql_num_rows($res_tgtsr);
$rowcnt_tgtsel						=	0;	
if($rowcnt_tgtsr	> 0) {
	$row_tgtsr						=	mysql_fetch_array($res_tgtsr);
	$tgtsrmonth						=	$row_tgtsr[monthval];
	$tgtsryear						=	$row_tgtsr[yearval];
	$tgtsrDSR_Code					=	$row_tgtsr[DSR_Code];
	$tgtsrcustomer_count			=	$row_tgtsr[customer_count];
	$tgtsrcustomer_naira			=	$row_tgtsr[customer_naira];
	$targetFlag						=	$row_tgtsr[targetFlag];
	
	$sel_tgtsel						=	"SELECT * from sr_incentive WHERE monthval = '$tgtsrmonth' AND yearval = '$tgtsryear' AND DSR_Code = '$tgtsrDSR_Code'";
	$res_tgtsel						=	mysql_query($sel_tgtsel) or die(mysql_error());
	$rowcnt_tgtsel					=	mysql_num_rows($res_tgtsel);
	if($rowcnt_tgtsel	> 0) {
		while($row_tgtsel			=	mysql_fetch_array($res_tgtsel)) {
			$tgtsel[]				=	$row_tgtsel;
		}
	}
}
//pre($tgtsel);
?>


<!--  DROPBOX LIST JS AND CSS STARTS HERE -->

<?php //if($rowcnt_tgtsel == 0) { ?>
<link rel="stylesheet" type="text/css" href="../css/droplist/jquery-ui-1.8.13.custom.css">
<link rel="stylesheet" type="text/css" href="../css/droplist/ui.dropdownchecklist.themeroller.css">

<script type="text/javascript" src="../js/droplist/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/droplist/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="../js/droplist/ui.dropdownchecklist.js"></script>



<script type="text/javascript">
$(document).ready(function() {

	//$("#srcode").dropdownchecklist( width: 250 } ); 

	 $("#srcode").dropdownchecklist( { textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
				var listCnt		=	options.size() - 1;
                switch(countOfSelected) {
                    case 0: { 
						return "---SR---";
					}
                    case 1: return selectedOptions.text();
                    case listCnt: return "ALL";
                    default: {
						var srcodes		=	$("#srcode").val();
						if($.isArray(srcodes)) {
							var myArray = $("#srcode option:selected").map(function() {
								 return $(this).text();
							  }).get();
							 
							//alert(myArray);
							if(myArray.indexOf(" ALL") != -1) {			
								return " ALL";
							} else {
								if(myArray.indexOf("---SR---") != -1) {			
									if(countOfSelected == 2) {
										var textval			=	selectedOptions.text();
										textval = textval.replace("---SR---", "");
										//alert(textval);
										return textval;
									} else {
										//alert(countOfSelected);
										//alert(listCnt);
										return " Multiple";
									}
								} else {
									//alert('23');
									//alert(countOfSelected);
									//alert(listCnt);
									var listCntVal		=	listCnt - 1;
									if(countOfSelected == listCntVal) {
										return " ALL";
									} else {
										return " Multiple";
									}
								}
							}
						}											
					}
                }
	 },width:170 });

	 $("#asmcode").dropdownchecklist( { textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
				var listCnt		=	options.size() - 1;
                switch(countOfSelected) {
                    case 0: return "---ASM---";
                    case 1: return selectedOptions.text();
                    case listCnt: return "ALL";
                    default: {
						var asmcodes		=	$("#asmcode").val();
						if($.isArray(asmcodes)) {
							var myArray = $("#asmcode option:selected").map(function() {
								 return $(this).text();
							  }).get();
							 
							//alert(myArray);
							if(myArray.indexOf(" ALL") != -1) {			
								return " ALL";
							} else {
								if(myArray.indexOf("---ASM---") != -1) {			
									if(countOfSelected == 2) {
										var textval			=	selectedOptions.text();
										textval = textval.replace("---ASM---", "");
										//alert(textval);
										return textval;
									} else {
										return " Multiple";
									}
								} else {
									//alert(countOfSelected);
									//alert(listCnt);
									var listCntVal		=	listCnt - 1;
									if(countOfSelected == listCntVal) {
										return " ALL";
									} else {
										return " Multiple";
									}
								}
							}
						}											
					}
                }
	 },width:170   });


	 $("#rsmcode").dropdownchecklist( { textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
				var listCnt		=	options.size() - 1;
                switch(countOfSelected) {
                    case 0: return "---RSM---";
                    case 1: return selectedOptions.text();
                    case listCnt: return "ALL";
                    default: {
						var rsmcodes		=	$("#rsmcode").val();
						if($.isArray(rsmcodes)) {
							var myArray = $("#rsmcode option:selected").map(function() {
								 return $(this).text();
							  }).get();
							 
							//alert(myArray);
							if(myArray.indexOf(" ALL") != -1) {			
								return " ALL";
							} else {
								if(myArray.indexOf("---RSM---") != -1) {			
									if(countOfSelected == 2) {
										var textval			=	selectedOptions.text();
										textval = textval.replace("---RSM---", "");
										//alert(textval);
										return textval;
									} else {
										return " Multiple";
									}
								} else {
									var listCntVal		=	listCnt - 1;
									if(countOfSelected == listCntVal) {
										return " ALL";
									} else {
										return " Multiple";
									}
								}
							}
						}											
					}
                }
	 },width:170   });


	 $("#branchcode").dropdownchecklist( { textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
				var listCnt		=	options.size() - 1;
                switch(countOfSelected) {
                    case 0: return "---BRANCH---";
                    case 1: return selectedOptions.text();
                    case listCnt: return "ALL";
                    default: {
						var branchcodes		=	$("#branchcode").val();
						if($.isArray(branchcodes)) {
							var myArray = $("#branchcode option:selected").map(function() {
								 return $(this).text();
							  }).get();
							 
							//alert(myArray);
							if(myArray.indexOf(" ALL") != -1) {			
								return " ALL";
							} else {
								if(myArray.indexOf("---BRANCH---") != -1) {			
									if(countOfSelected == 2) {
										var textval			=	selectedOptions.text();
										textval = textval.replace("---BRANCH---", "");
										//alert(textval);
										return textval;
									} else {
										return " Multiple";
									}
								} else {
									var listCntVal		=	listCnt - 1;
									if(countOfSelected == listCntVal) {
										return " ALL";
									} else {
										return " Multiple";
									}
								}
							}
						}											
					}
                }
	 },width:170   });


	 $("#brandcode").dropdownchecklist( { textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
				var listCnt		=	options.size() - 1;
                switch(countOfSelected) {
                    case 0: { 
						return "---BRAND---";
					}
                    case 1: return selectedOptions.text();
                    case listCnt: return "ALL";
                    default: {
						var brandcodes		=	$("#brandcode").val();
						if($.isArray(brandcodes)) {
							var myArray = $("#brandcode option:selected").map(function() {
								 return $(this).text();
							  }).get();
							 
							//alert(myArray);
							if(myArray.indexOf(" ALL") != -1) {			
								return " ALL";
							} else {
								if(myArray.indexOf("---BRAND---") != -1) {			
									if(countOfSelected == 2) {
										var textval			=	selectedOptions.text();
										textval = textval.replace("---BRAND---", "");
										//alert(textval);
										return textval;
									} else {
										//alert(countOfSelected);
										//alert(listCnt);
										return " Multiple";
									}
								} else {
									//alert('23');
									//alert(countOfSelected);
									//alert(listCnt);
									var listCntVal		=	listCnt - 1;
									if(countOfSelected == listCntVal) {
										return " ALL";
									} else {
										return " Multiple";
									}
								}
							}
						}											
					}
                }
	 },width:170 });

	$("#brandcode").live("change", function() {
		
		$("#ddcl-brandcode-i0").attr("checked",false);

		//alert('1232');
		var brandcodes		=	$("#brandcode").val();

		if($.isArray(brandcodes)) {

			var myArray = $("#brandcode option:selected").map(function() {
				 return $(this).text();
			  }).get();
			 
			//alert(myArray);
			if(myArray.indexOf(" ALL") != -1) {			
				//alert('2323');				
				var srlength	=	$('#brandcode option').length;
				$("#brandcode").get(0).selectedIndex = 1;

				for(var c = 0; c <= srlength; c++) {
					if(c != 1) {
						$("#ddcl-brandcode-i"+c).attr("checked",false);
					}
				}
			}
		}
	});
	
	$("#srcode").live("change", function() {
		
		$("#ddcl-srcode-i0").attr("checked",false);

		//alert('1232');
		var srcodes		=	$("#srcode").val();

		if($.isArray(srcodes)) {

			var myArray = $("#srcode option:selected").map(function() {
				 return $(this).text();
			  }).get();
			 
			//alert(myArray);
			if(myArray.indexOf(" ALL") != -1) {			
				//alert('2323');				
				var srlength	=	$('#srcode option').length;
				$("#srcode").get(0).selectedIndex = 1;

				for(var c = 0; c <= srlength; c++) {
					if(c != 1) {
						$("#ddcl-srcode-i"+c).attr("checked",false);
					}
				}
				//srcodes			=	$("#srcode").val();
				//alert(srcodes);
			}
		}

		 $("#asmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					//alert(options.size());
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {						
						case 0: return "---ASM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var asmcodes		=	$("#asmcode").val();
							if($.isArray(asmcodes)) {
								var myArray = $("#asmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---ASM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---ASM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#rsmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---RSM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var rsmcodes		=	$("#rsmcode").val();
							if($.isArray(rsmcodes)) {
								var myArray = $("#rsmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---RSM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---RSM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170 });


		 $("#branchcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---BRANCH---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var branchcodes		=	$("#branchcode").val();
							if($.isArray(branchcodes)) {
								var myArray = $("#branchcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---BRANCH---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---BRANCH---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });
	});

	$("#asmcode").live("change", function() {

		$("#ddcl-asmcode-i0").attr("checked",false);

		//alert('1232');
		var asmcodes		=	$("#asmcode").val();

		if($.isArray(asmcodes)) {

			var myArray = $("#asmcode option:selected").map(function() {
				 return $(this).text();
			  }).get();
			 
			//alert(myArray);
			if(myArray.indexOf(" ALL") != -1) {			
				//alert('2323');				
				var asmlength	=	$('#asmcode option').length;
				$("#asmcode").get(0).selectedIndex = 1;

				for(var c = 0; c <= asmlength; c++) {
					if(c != 1) {
						$("#ddcl-asmcode-i"+c).attr("checked",false);
					}
				}
				//asmcodes			=	$("#asmcode").val();
				//alert(asmcodes);
			}
		}

		 $("#srcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---SR---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var srcodes		=	$("#srcode").val();
							if($.isArray(srcodes)) {
								var myArray = $("#srcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---SR---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---SR---", "");
											//alert(textval);
											return textval;
										} else {
											//alert(countOfSelected);
											//alert(listCnt);
											return " Multiple";
										}
									} else {
										//alert('23');
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#rsmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---RSM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var rsmcodes		=	$("#rsmcode").val();
							if($.isArray(rsmcodes)) {
								var myArray = $("#rsmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---RSM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---RSM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#branchcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---BRANCH---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var branchcodes		=	$("#branchcode").val();
							if($.isArray(branchcodes)) {
								var myArray = $("#branchcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---BRANCH---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---BRANCH---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });
	});

	$("#rsmcode").live("change", function() {

		$("#ddcl-rsmcode-i0").attr("checked",false);

		//alert('1232');
		var rsmcodes		=	$("#rsmcode").val();

		if($.isArray(rsmcodes)) {

			var myArray = $("#rsmcode option:selected").map(function() {
				 return $(this).text();
			  }).get();
			 
			//alert(myArray);
			if(myArray.indexOf(" ALL") != -1) {			
				//alert('2323');				
				var rsmlength	=	$('#rsmcode option').length;
				$("#rsmcode").get(0).selectedIndex = 1;

				for(var c = 0; c <= rsmlength; c++) {
					if(c != 1) {
						$("#ddcl-rsmcode-i"+c).attr("checked",false);
					}
				}
				//rsmcodes			=	$("#rsmcode").val();
				//alert(rsmcodes);
			}
		}

		 $("#asmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---ASM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var asmcodes		=	$("#asmcode").val();
							if($.isArray(asmcodes)) {
								var myArray = $("#asmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---ASM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---ASM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170  });


		 $("#srcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---SR---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var srcodes		=	$("#srcode").val();
							if($.isArray(srcodes)) {
								var myArray = $("#srcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---SR---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---SR---", "");
											//alert(textval);
											return textval;
										} else {
											//alert(countOfSelected);
											//alert(listCnt);
											return " Multiple";
										}
									} else {
										//alert('23');
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#branchcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---BRANCH---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var branchcodes		=	$("#branchcode").val();
							if($.isArray(branchcodes)) {
								var myArray = $("#branchcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---BRANCH---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---BRANCH---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });
	});

	$("#branchcode").live("change", function() {
		 $("#ddcl-branchcode-i0").attr("checked",false);

		//alert('1232');
		var branchcodes		=	$("#branchcode").val();

		if($.isArray(branchcodes)) {

			var myArray = $("#branchcode option:selected").map(function() {
				 return $(this).text();
			  }).get();
			 
			//alert(myArray);
			if(myArray.indexOf(" ALL") != -1) {			
				//alert('2323');				
				var branchlength	=	$('#branchcode option').length;
				$("#branchcode").get(0).selectedIndex = 1;

				for(var c = 0; c <= branchlength; c++) {
					if(c != 1) {
						$("#ddcl-branchcode-i"+c).attr("checked",false);
					}
				}
				//srcodes			=	$("#srcode").val();
				//alert(srcodes);
			}
		}
		 				 
		 $("#asmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---ASM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var asmcodes		=	$("#asmcode").val();
							if($.isArray(asmcodes)) {
								var myArray = $("#asmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---ASM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---ASM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#rsmcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---RSM---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var rsmcodes		=	$("#rsmcode").val();
							if($.isArray(rsmcodes)) {
								var myArray = $("#rsmcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---RSM---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---RSM---", "");
											//alert(textval);
											return textval;
										} else {
											return " Multiple";
										}
									} else {
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });


		 $("#srcode").dropdownchecklist( { textFormatFunction: function(options) {
					var selectedOptions = options.filter(":selected");
					var countOfSelected = selectedOptions.size();
					var listCnt		=	options.size() - 1;
					switch(countOfSelected) {
						case 0: return "---SR---";
						case 1: return selectedOptions.text();
						case listCnt: return "ALL";
						default: {
							var srcodes		=	$("#srcode").val();
							if($.isArray(srcodes)) {
								var myArray = $("#srcode option:selected").map(function() {
									 return $(this).text();
								  }).get();
								 
								//alert(myArray);
								if(myArray.indexOf(" ALL") != -1) {			
									return " ALL";
								} else {
									if(myArray.indexOf("---SR---") != -1) {			
										if(countOfSelected == 2) {
											var textval			=	selectedOptions.text();
											textval = textval.replace("---SR---", "");
											//alert(textval);
											return textval;
										} else {
											//alert(countOfSelected);
											//alert(listCnt);
											return " Multiple";
										}
									} else {
										//alert('23');
										//alert(countOfSelected);
										//alert(listCnt);
										var listCntVal		=	listCnt - 1;
										if(countOfSelected == listCntVal) {
											return " ALL";
										} else {
											return " Multiple";
										}
									}
								}
							}											
						}
					}
		 },width:170   });
	});

	$('#closebutton_cus').click(function(event) {
		$('#errormsgsrinc').hide();
		return false;
	});
});
</script>

<!--  DROPBOX LIST JS AND CSS ENDS HERE -->

<?php //} ?>

<style type="text/css">

.headingscredit{
	background:#a09e9e;
	width:95%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
}
#top{
background:#fff;
width:85%;
margin-left:auto;
margin-right:auto;
height:160px;
overflow-x:auto;
overflow-y:hidden;	
}
#mytableformcredit{
background:#fff;
width:95%;
margin-left:auto;
margin-right:auto;
height:480px;
}

#closebutton_cus {
  position:relative;
  top:-35px;
  color:transparent;
  right:-190px;
  border:none;
  clear:both;
  height:100%;
  min-height:100%;
  background:url(../images/close_pop.png) no-repeat;
 }

.concredit {
	width:60%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
}

.conscrollc {
	width:70%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
	height:220px;
}

.concredit th,.conscrollc th{
	width:22%;
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:13px;
	color:#000;
}
.concredit td,.conscrollc td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	color: #000;
	font-size:13px;
}
.concredit tbody tr:hover td,.conscrollc tbody tr:hover td{
	background: #c1c1c1;
}

#errormsgsrinc {
	display:none;
	width:40%;
	height:30px;
	background:#c1c1c1;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	padding-top:0px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	-ms-border-radius:10px;
	-o-border-radius:10px;
	text-align:center;
}
.myalignsrinc {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

.alignsize {
	font-size:16px;
}

.pad5 { 
	padding-bottom:7px;
}

.textalg {
	text-align:left;
}

input[type="radio"] {
  margin-top: -1px;
  vertical-align: middle;
}

</style>
<!-- <body onLoad="getasmrsmvalueswithbranch('<?php echo $tgtsrDSR_Code; ?>');"> -->
<body >
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingscredit">PRODUCT TARGET</div>
<div id="mytableformcredit" align="center">
<?php $curmonthval	=	ltrim(date('m'),0);
	$curyearval	=	date('Y');
?>
<form id="srincentry" method="post" action="" onSubmit="return checksrincentry('<?php echo $rowcnt_prod; ?>','<?php echo $curmonthval; ?>','<?php echo $curyearval; ?>');">
<div class="mcf"></div>
<div id="top">
<div style="float:center">

<fieldset class="alignment textalg">
<legend><strong>Product Target</strong></legend>

<table align="center" width="100%">
<tr height="20">
      <td align="left" class="alignsize" >SR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
     <td align="left" style="width:40%" nowrap="nowrap" class="align2 alignsize">
		<?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="srspan">
			<select class="dsrname" style="width:300px" class="s9" name="srcode[]" id="srcode" multiple onChange="getasmrsmsrinc(this.value);">
				<option value="">---SR---</option>
				<option value="<?php 				
				echo $srstr; ?>"> ALL</option>
				<?php while($info_sr = mysql_fetch_assoc($res_SR)) { ?>
				<option value="<?php echo  $info_sr['DSR_Code']; ?>" <?php if($tgtsrDSR_Code == $info_sr['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_sr['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		
		<?php /*} else {  
				?>
			 <span id="srid"><input type="hidden" autocomplete="off" name="srcode[]" id="srcode" value="<?php echo $tgtsrDSR_Code; ?>" /><?php echo getdbval($tgtsrDSR_Code,'DSRName','DSR_Code','dsr'); ?></span>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				
		<?php } */ ?>
       </td>
    
		<td align="left" class="align alignsize">ASM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
		<td align="left" style="width:40%" nowrap="nowrap" class="align2 alignsize">
		<?php //if($rowcnt_tgtsel == 0) { ?>
		  <span id="asmspan">
			<select class="dsrname" class="s9" name="asmcode[]" id="asmcode" multiple onChange="getasmspecificsrinc(this.value);">
				<option value="">---ASM---</option>
				<option value="<?php 				
				echo $asmstr; ?>"> ALL</option>
				<?php while($info_asm = mysql_fetch_assoc($res_ASM)) { 

				$query_ASMRSM 							=	"select ASM,RSM from dsr WHERE DSR_Code = '$tgtsrDSR_Code'";
				$res_ASMRSM 							=	mysql_query($query_ASMRSM) or die(mysql_error());
				$row_ASMRSM 							=	mysql_fetch_array($res_ASMRSM);
				
				$asmid									=	$row_ASMRSM[ASM];
				$rsmid									=	$row_ASMRSM[RSM];
				$asmcode								=	getdbval($asmid,"DSR_Code","id","asm_sp");
				$branchcode								=	getdbval($rsmid,"branch_id","id","rsm_sp");
				
				?>
				<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else { 
				?>
			<span id="asmid"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
			<?php } */ ?>
		</td>
		
		</tr>
		
		<tr>
			<td class="pad5"></td>
		</tr>
		<tr>
       <!-- <td>ASM : </td>
       <td><span id="asmid">&nbsp;</span></td> -->

	 <td align="left" class="alignsize">RSM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:40%" class="align2 alignsize">
	  <?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="rsmspan">
			<select class="dsrname" class="s9" name="rsmcode[]" id="rsmcode" multiple onChange="getrsmspecificsrinc(this.value);">
				<option value="">---RSM---</option>
				<option value="<?php 				
				echo $rsmstr; ?>"> ALL</option>
				<?php while($info_rsm = mysql_fetch_assoc($res_RSM)) { ?>
				<option value="<?php echo  $info_rsm['id']; ?>" <?php if($rsmid == $info_rsm['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsm['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else { 
				?>
			<span id="rsmid"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php }  */ ?>
        </td> 

	<td align="left" class="align alignsize">Branch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">
	   <?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="branchspan">
			<select class="dsrname" class="s9" name="branchcode[]" id="branchcode" multiple onChange="getbranchspecificsrinc(this.value);">
				<option value="">---BRANCH---</option>
				<option value="<?php 				
				echo $branchstr; ?>"> ALL</option>
				<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
				<option value="<?php echo  $info_branch['id']; ?>" <?php if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else { 
		          ?>
			<span id="branchid"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php } */ ?>
        
		
		</td> 		 
		
	  </tr>	
	
	<tr>
		<td class="pad5"></td>
	</tr>

	<tr>
	<td align="left" style="width:9%;" class="alignsize">BRAND&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	<td align="left" style="width:4%" nowrap="nowrap" class="align2 alignsize">
			<span id="brandspan">
				<select class="dsrname" name="brandcode[]" id="brandcode" multiple onChange="getprodforbrand(this.value);">
					<option value="">---Brand---</option>
					<option value="<?php 				
					echo $brandstr; ?>"
					
					<?php $w	=	0;
					for($k=0; $k<$rowcnt_prod; $k++) {
						//echo $tgtsel[$k]['Product_id'];
						$brandid[]	=	getdbval($tgtsel[$k]['Product_id'],'brand','id','product');						
					}	$brandiduni	=	array_unique($brandid);
						$brandidstr	=	implode("','",$brandiduni);
						if(strstr($brandidstr,$brandstr)) { 
							echo "selected";
							$w++;
						}
					 ?>
					
					> ALL</option>
					<?php while($info = mysql_fetch_assoc($res_brand)) { 								
					?>
					<option value="<?php echo $info['id']; ?>" <?php 
					if($w	== 0) { 
					$j	=	0;
					//pre($tgtsel);
					for($k=0; $k<$rowcnt_prod; $k++) { 
						//echo $tgtsel[$k]['Product_id'];
						$brandcode	=	getdbval($tgtsel[$k]['Product_id'],'brand','id','product');
						//echo $j;
						if($j == 0) {
							if($brandcode == $info['id']) { echo "selected"; $j++; }							
						}
					} } ?> > <?php echo upperstate($info['brand']); ?></option>
					<?php } ?> 
				</select>
			</span>
		</td>
	
		<td align="left" style="width:9%" class="align alignsize">Target Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>	
	<td align="left" nowrap="nowrap" style="width:20%" class="alignsize">
			<?php if($rowcnt_tgtsel == 0) { ?>
			<span id="targetflagspan">
			    <input type="radio" name="targetflag" id="targetflag" value="0" />&nbsp;T <sup>+</sup>&nbsp;&nbsp;
			    <input type="radio" name="targetflag" id="targetflag" value="1" />&nbsp;T <sup>C</sup>

				<!-- <input type="radio" name="targetflag" id="targetflag" value="0" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','noton')" />&nbsp;T <sup>+</sup>&nbsp;&nbsp;
			    <input type="radio" name="targetflag" id="targetflag" value="1" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','notoff')" />&nbsp;T <sup>C</sup>  -->

			</span>
			<?php } else { ?>
				<span id="flagid">
				
				<input type="radio" name="targetflag" id="targetflag" <?php if($targetFlag == "0") echo "checked"; ?> value="0" />&nbsp;T <sup>+</sup> 
				<input type="radio" name="targetflag" id="targetflag" <?php if($targetFlag == "1") echo "checked"; ?> value="1" />&nbsp;T <sup>C</sup> 

				<!-- <input type="radio" name="targetflag" id="targetflag" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','noton')" <?php if($targetFlag == "0") echo "checked"; ?> value="0" />&nbsp;T <sup>+</sup> 
				<input type="radio" name="targetflag" id="targetflag" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','notoff')" <?php if($targetFlag == "1") echo "checked"; ?> value="1" />&nbsp;T <sup>C</sup> --> 
							
				</span>
			<?php } ?>	
		</td>
	
	</tr>

	<tr>
		<td class="pad5"></td>
	</tr>

	  <tr>	  	  
	   <?php if($rowcnt_tgtsel == 0) { ?>
	   <td align="left" nowrap="nowrap" class="alignsize">From Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">

			<select name="frommonth" id="frommonth">
			<option value="">--Month--</option>
			<option value="1" <?php if($curmonthval == 1) { echo "selected"; } ?> >January</option>
			<option value="2" <?php if($curmonthval == 2) { echo "selected"; } ?>>February</option>
			<option value="3" <?php if($curmonthval == 3) { echo "selected"; } ?>>March</option>
			<option value="4" <?php if($curmonthval == 4) { echo "selected"; } ?>>April</option>
			<option value="5" <?php if($curmonthval == 5) { echo "selected"; } ?>>May</option>
			<option value="6" <?php if($curmonthval == 6) { echo "selected"; } ?>>June</option>
			<option value="7" <?php if($curmonthval == 7) { echo "selected"; } ?>>July</option>
			<option value="8" <?php if($curmonthval == 8) { echo "selected"; } ?>>August</option>
			<option value="9" <?php if($curmonthval == 9) { echo "selected"; } ?>>September</option>
			<option value="10" <?php if($curmonthval == 10) { echo "selected"; } ?>>October</option>
			<option value="11" <?php if($curmonthval == 11) { echo "selected"; } ?>>November</option>
			<option value="12" <?php if($curmonthval == 12) { echo "selected"; } ?>>December</option>
		</select> 
		<!-- &nbsp;&nbsp;  -->

	<!-- <td align="left" nowrap="nowrap" class="align alignsize">Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td> 

	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">  -->
		<select name="fromyear" id="fromyear" >
			<option value="">--Year--</option>
			<?php $curyear = date("Y");
			for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($curyear == $i) {  echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>            
		</td>
		<!-- &nbsp;&nbsp;  -->

	<td align="left" nowrap="nowrap" class="align alignsize">To Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">

		<select name="tomonth" id="tomonth">
			<option value="">--Month--</option>
			<option value="1" <?php if($curmonthval == 1) { echo "selected"; } ?> >January</option>
			<option value="2" <?php if($curmonthval == 2) { echo "selected"; } ?>>February</option>
			<option value="3" <?php if($curmonthval == 3) { echo "selected"; } ?>>March</option>
			<option value="4" <?php if($curmonthval == 4) { echo "selected"; } ?>>April</option>
			<option value="5" <?php if($curmonthval == 5) { echo "selected"; } ?>>May</option>
			<option value="6" <?php if($curmonthval == 6) { echo "selected"; } ?>>June</option>
			<option value="7" <?php if($curmonthval == 7) { echo "selected"; } ?>>July</option>
			<option value="8" <?php if($curmonthval == 8) { echo "selected"; } ?>>August</option>
			<option value="9" <?php if($curmonthval == 9) { echo "selected"; } ?>>September</option>
			<option value="10" <?php if($curmonthval == 10) { echo "selected"; } ?>>October</option>
			<option value="11" <?php if($curmonthval == 11) { echo "selected"; } ?>>November</option>
			<option value="12" <?php if($curmonthval == 12) { echo "selected"; } ?>>December</option>
		</select> 

		<!-- &nbsp;&nbsp;  -->
		
<!-- 	<td align="left" nowrap="nowrap" class="align alignsize">Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">
	 -->
		<select name="toyear" id="toyear" >
			<option value="">--Year--</option>
			<?php for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($curyear == $i) {  echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select> 
		</td>
		
		<td colspan="2">&nbsp;</td>

		<!-- &nbsp; &nbsp; <input type="button" class="buttons" onClick="srtargetentrycheck('<?php echo $curmonthval; ?>','<?php echo $curyearval; ?>');" value="GO" /> -->
		<?php } else { ?>
	<td align="left" nowrap="nowrap" class="alignsize">From Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">

		<select name="frommonth" id="frommonth">
			<option value="">--Month--</option>
			<option value="1" <?php if($tgtsel[0][monthval] == 1) { echo "selected"; } ?> >January</option>
			<option value="2" <?php if($tgtsel[0][monthval] == 2) { echo "selected"; } ?>>February</option>
			<option value="3" <?php if($tgtsel[0][monthval] == 3) { echo "selected"; } ?>>March</option>
			<option value="4" <?php if($tgtsel[0][monthval] == 4) { echo "selected"; } ?>>April</option>
			<option value="5" <?php if($tgtsel[0][monthval] == 5) { echo "selected"; } ?>>May</option>
			<option value="6" <?php if($tgtsel[0][monthval] == 6) { echo "selected"; } ?>>June</option>
			<option value="7" <?php if($tgtsel[0][monthval] == 7) { echo "selected"; } ?>>July</option>
			<option value="8" <?php if($tgtsel[0][monthval] == 8) { echo "selected"; } ?>>August</option>
			<option value="9" <?php if($tgtsel[0][monthval] == 9) { echo "selected"; } ?>>September</option>
			<option value="10" <?php if($tgtsel[0][monthval] == 10) { echo "selected"; } ?>>October</option>
			<option value="11" <?php if($tgtsel[0][monthval] == 11) { echo "selected"; } ?>>November</option>
			<option value="12" <?php if($tgtsel[0][monthval] == 12) { echo "selected"; } ?>>December</option>
		</select> 

		<!-- </td>
		 &nbsp;&nbsp;  -->
		
	<!-- <td align="left" nowrap="nowrap" class="align alignsize">Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize"> -->

		<select name="fromyear" id="fromyear" >
			<option value="">--Year--</option>
			<?php $curyear = date("Y");
			for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($tgtsel[0][yearval] == $i) { echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>            
		</td>
		<!-- &nbsp;&nbsp;  -->

	<td align="left" nowrap="nowrap" class="align alignsize">To Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">

		<select name="tomonth" id="tomonth">
			<option value="">--Month--</option>
			<option value="1" <?php if($tgtsel[0][monthval] == 1) { echo "selected"; } ?> >January</option>
			<option value="2" <?php if($tgtsel[0][monthval] == 2) { echo "selected"; } ?>>February</option>
			<option value="3" <?php if($tgtsel[0][monthval] == 3) { echo "selected"; } ?>>March</option>
			<option value="4" <?php if($tgtsel[0][monthval] == 4) { echo "selected"; } ?>>April</option>
			<option value="5" <?php if($tgtsel[0][monthval] == 5) { echo "selected"; } ?>>May</option>
			<option value="6" <?php if($tgtsel[0][monthval] == 6) { echo "selected"; } ?>>June</option>
			<option value="7" <?php if($tgtsel[0][monthval] == 7) { echo "selected"; } ?>>July</option>
			<option value="8" <?php if($tgtsel[0][monthval] == 8) { echo "selected"; } ?>>August</option>
			<option value="9" <?php if($tgtsel[0][monthval] == 9) { echo "selected"; } ?>>September</option>
			<option value="10" <?php if($tgtsel[0][monthval] == 10) { echo "selected"; } ?>>October</option>
			<option value="11" <?php if($tgtsel[0][monthval] == 11) { echo "selected"; } ?>>November</option>
			<option value="12" <?php if($tgtsel[0][monthval] == 12) { echo "selected"; } ?>>December</option>
		</select>
		
		<!-- </td> &nbsp;&nbsp;  -->
		
    <!-- <td align="left" nowrap="nowrap" class="align alignsize">Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
    	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize"> -->

		<select name="toyear" id="toyear" >
			<option value="">--Year--</option>
			<?php for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($tgtsel[0][yearval] == $i) { echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select> 
		</td>
	
		<td colspan="2">&nbsp;</td>

		<!-- &nbsp; &nbsp; -->
		<?php } ?>
     </tr>

	
	<!--<tr>
		<td class="pad5"></td>
	</tr>
		
	<tr>
		
		<td align="left" nowrap="nowrap" class="alignsize">Target Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">
		   <?php if($rowcnt_tgtsel == 0) { ?>
			<span id="targetflagspan">
			    <input type="radio" name="targetflag" id="targetflag" value="0" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','noton')" />&nbsp;T <sup>+</sup>&nbsp;&nbsp;
				<input type="radio" name="targetflag" id="targetflag" value="1" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','notoff')" />&nbsp;T <sup>C</sup> 

			</span>
			<?php } else { ?>
				<span id="flagid">
				
				<input type="radio" name="targetflag" id="targetflag" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','noton')" <?php if($targetFlag == "0") echo "checked"; ?> value="0" />&nbsp;T <sup>+</sup> 
				<input type="radio" name="targetflag" id="targetflag" onClick="checkSRTgtUnits('<?php echo $rowcnt_prod; ?>','tgt_units_','notoff')" <?php if($targetFlag == "1") echo "checked"; ?> value="1" />&nbsp;T <sup>C</sup> 
					
				</span>
			<?php } ?>		
		</td>
		<td colspan="8"></td>
	</tr>-->

	<tr>
		<td class="pad5"></td>
	</tr>
	
</table>
</fieldset>

</div>

<!-- <div class="concredit" style="float:right">
Top Customer Name & Address
<table align="center" width="100%">
	<thead>
	<tr>
	<th align="center" colspan="10" style="background-color:#FFF">Effective Coverage/Month of <?php echo date('F'). " & ".date('Y'); ?> </th>
	</tr>
	<tr>
	<th align="center">Customer Count</th>
	<th align="center">Naira</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="customer_count" id="customer_count" value="<?php echo $tgtsrcustomer_count; ?>" /></td>
	<td align="center"><input type="text" size="5" maxlength="5" autocomplete="off" name="customer_naira" id="customer_naira" value="<?php echo $tgtsrcustomer_naira; ?>" /></td>	
	</td>
	</tr>
	</tbody>       
</table>
</div> --> <!--con ending-->
</div> <!--top ending-->

<div class="conscrollc">

<span id="brandbasedprodspan">

<table align="center" width="100%">
        <thead>
        <tr>
        <th align="center" style="width:70%">Product</th>
        <th align="center">Target<br/>Units/Month</th>
        <th  align="center">Naira/Unit</th>
        </tr>
        </thead>
        <tbody>
        <?php
		//pre($product_codearr);
		//echo $rowcnt_prod;

		if(!isset($_REQUEST[id]) && $_REQUEST[id] == '') {
			//echo "werwerwe";
			$rowcnt_prod		=	0;
		}

		if($rowcnt_tgtsel > 0) {
			for($k=0; $k<$rowcnt_tgtsel; $k++) {			
			?>
			<tr>
			<td width="731" style="width:70%"><input type="hidden" autocomplete="off" name="productname_<?php echo $k; ?>" id="productname_<?php echo $k; ?>" value="<?php echo getdbval($product_codearr[$k],'id','Product_code','product'); ?>" /><span><?php echo getdbval($product_codearr[$k],'Product_description1','Product_code','product'); //PARAMETERS ARE TABLE QUERY COLUMN VALUE, TABLE RESULT COLUMN, TABLE QUERY COLUMN NAME, TABLE NAME ?></span></td>

			<td align="center"><input type="text" size="5" maxlength="10" <?php //if($rowcnt_tgtsel > 0) { if($targetFlag == "1") { echo "disabled"; }  } ?> autocomplete="off" name="tgt_units_<?php echo $k; ?>" id="tgt_units_<?php echo $k; ?>" value="<?php /* if($rowcnt_tgtsel > 0) { if($targetFlag == "0") { */ echo number_format($tgtsel[$k]["target_units"]); //}  } 
			?>" /></td>	
			<td align="center"><input type="text" size="5" maxlength="10" autocomplete="off" name="tgt_naira_<?php echo $k; ?>" id="tgt_naira_<?php echo $k; ?>" value="<?php echo number_format($tgtsel[$k]["target_naira"],2); ?>" /></td>
			</tr>
			<?php 
			} 	//for loop ?>
			<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_tgtsel; ?>" />
			<?php if($rowcnt_tgtsel > 0) { ?>
			<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
			if($rowcnt_tgtsel > 0) {
				echo "edit";
			} ?>" />
		<?php  }  else { ?>
				<input type="hidden" name="tgt_edit" id="tgt_edit" value="0" /> 		
			<?php } //edit if loop
		
			} // if loop 
			else { ?>
				<tr>
					<td width="731" align="center" colspan="3">NO RECORDS FOUND
						<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_prod; ?>" />
							<?php if($rowcnt_tgtsel > 0) { ?>
							<input type="hidden" name="tgt_edit" id="tgt_edit" value="<?php 
							if($rowcnt_tgtsel > 0) {
								echo "edit";
							} ?>" />
							<?php  }  else { ?>
							 <input type="hidden" name="tgt_edit" id="tgt_edit" value="0" /> 		
								<?php } //edit if loop
						?>
					</td>
				</tr>
			 <?php } ?>
        </tbody>
       
</table>

</span> 

</div> <!--con ending-->
<?php require_once "../include/error.php"; ?>
<div style="clear:both"></div>
<div id="errormsgsrinc" style="display:none;clear:both;"><h3 align="center" class="myalignsrinc"></h3><button id="closebutton_cus">Close</button></div>
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" class="buttons" value="Clear" id="clear"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="button" name="View" value="View" class="buttons" onclick="window.location='srincentiveview.php'"/>
	  </td>
      </tr>
 </table>       
 </form>
  </div> <!--mytableform ending-->
</div><!-- mainarea ending-->
<?php include('../include/footer.php'); ?>