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

//echo "http://".$_SERVER[HTTP_HOST].$_SERVER[PHP_SELF];

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

$srstr								=	getdbstr('DSR_Code','dsr');
$rsmstr								=	getdbstr('id','rsm_sp');
$asmstr								=	getdbstr('DSR_Code','asm_Sp');
$branchstr							=	getdbstr('id','branch');

//pre($_POST);
//exit;
$KD_Code=getKDCode();
if(isset($_POST[submit]) && $_POST[submit] == 'Save') {
	$k						=	0;
	$monthval				=	trim(date('m'),0);
	$yearval				=	date('Y');
	
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
	//pre($_REQUEST);
	if(!isset($tgt_edit) && $tgt_edit != 'edit') {
		
		/*if(strstr($_POST["dsrselecttext"],'ALL')) {
			$srcodestr						=	implode("",$_POST["srcode"]);
			$post_dsrcodearr				=	explode(",",$srcodestr);
		} else {
			$post_dsrcodearr				=	$_POST["srcode"];
		}*/
 
		//foreach($post_dsrcodearr AS $post_dsrcode) {
			for($i = 0; $i < $overall_rowcnt; $i++) {

				$post_dsrcode		=	$_POST["srname_".$i];
				$post_covper		=	$_POST["cov_per_".$i];	
				$post_prodper		=	$_POST["prod_per_".$i];
				$post_effper		=	$_POST["eff_per_".$i];
				$post_covvisit		=	remcom(remdot($_POST["cov_visit_".$i]));	
				$post_prodvisit		=	remcom(remdot($_POST["prod_visit_".$i]));
				$post_effvisit		=	remcom(remdot($_POST["eff_visit_".$i]));				
				$post_visitcov		=	$_POST["visitcov"];
				$post_visitprod		=	$_POST["visitprod"];
				$post_visiteff		=	$_POST["visiteff"];
				$tgtTypeCov			=	$_POST["tgtTypeCov"];
				$tgtTypeProd		=	$_POST["tgtTypeProd"];
				$tgtTypeEff			=	$_POST["tgtTypeEff"];
				$post_targetflag	=	$_POST["targetflag"];
				$post_tgt_id		=	$_POST["tgt_qry_id_".$i];	

				if($post_dsrcode == '') {
					$post_dsrcode		=	'';
					$post_covper		=	'';	
					$post_prodper		=	'';
					$post_effper		=	'';
					$post_covvisit		=	'';	
					$post_prodvisit		=	'';
					$post_effvisit		=	'';				
					$post_visitcov		=	'';
					$post_visitprod		=	'';
					$post_visiteff		=	'';
					$post_targetflag	=	'';
					$post_tgt_id		=	'';
					$tgtTypeCov			=	'';
					$tgtTypeProd		=	'';
					$tgtTypeEff			=	'';
					continue;
				}

				foreach($fromcal AS $monthyear) {
					
					$monthyeararr	=	explode("~",$monthyear);

					$monthval		=	$monthyeararr[0];
					$yearval		=	$monthyeararr[1];

					$sel_tgtcheck				=	"SELECT * from coverage_target_setting WHERE SR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval'";
					$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
					$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
					if($rowcnt_tgtcheck	> 0) {
						echo $update_tgt		=	"UPDATE coverage_target_setting SET KD_Code= '$KD_Code',SR_Code = '$post_dsrcode', coverage_percent = '$post_covper', productive_percent = '$post_prodper', effective_percent = '$post_effper', cov_visit = '$post_covvisit', prod_visit = '$post_prodvisit', eff_visit = '$post_effvisit', cov_status = '$post_visitcov', prod_status = '$post_visitprod', eff_status = '$post_visiteff',  updatedatetime = NOW(),tgtTypeCov='$tgtTypeCov',tgtTypeProd='$tgtTypeProd',tgtTypeEff='$tgtTypeEff' WHERE SR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval'";
						$res_tgt		=	mysql_query($update_tgt);
					} else {
						echo $insert_tgt		=	"INSERT INTO coverage_target_setting (KD_Code,SR_Code,monthval,yearval,coverage_percent,productive_percent,effective_percent,cov_visit,prod_visit,eff_visit,cov_status,prod_status,eff_status,insertdatetime,tgtTypeCov,tgtTypeProd,tgtTypeEff) VALUES ('$KD_Code','$post_dsrcode','$monthval','$yearval','$post_covper','$post_prodper','$post_effper','$post_covvisit','$post_prodvisit','$post_effvisit','$post_visitcov','$post_visitprod','$post_visiteff',NOW(),'$tgtTypeCov','$tgtTypeProd','$tgtTypeEff')";						
						mysql_query($insert_tgt) or die(mysql_error());
					}
				}
			}
		//}
		//exit;
		header("location:covtargetentryview.php?no=1");

	} else {
		$post_dsrcodearr				=	$_POST["srcode"];
		foreach($post_dsrcodearr AS $post_dsrcode) {
			for($i = 0; $i < $overall_rowcnt; $i++) {

				$post_prodcode		=	$_POST["productname_".$i];	
				$post_units			=	$_POST["tgt_units_".$i];
				$post_naira			=	$_POST["tgt_naira_".$i];
				$post_tgt_id		=	$_POST["tgt_qry_id_".$i];

				if($post_prodcode == '' && $post_dsrcode == '') {
					$post_prodcode		=	'';	
					$post_units			=	'';
					$post_naira			=	'';
					$post_tgt_id		=	'';
					continue;
				}
				
				foreach($fromcal AS $monthyear) {
					
					$monthyeararr	=	explode("~",$monthyear);

					$monthval		=	$monthyeararr[0];
					$yearval		=	$monthyeararr[1];

					$sel_tgtcheck				=	"SELECT * from sr_incentive WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
					$res_tgtcheck				=	mysql_query($sel_tgtcheck) or die(mysql_error());
					$rowcnt_tgtcheck			=	mysql_num_rows($res_tgtcheck);
					if($rowcnt_tgtcheck	> 0) {
						$update_tgt		=	"UPDATE sr_incentive SET KD_Code= '$KD_Code',DSR_Code = '$post_dsrcode', target_units = '$post_units', target_naira = '$post_naira',updatedatetime = NOW(), Product_id = '$post_prodcode' WHERE DSR_Code = '$post_dsrcode' AND monthval = '$monthval' AND yearval = '$yearval' AND Product_id = '$post_prodcode'";
						$res_tgt		=	mysql_query($update_tgt);
					} else {
						$insert_tgt		=	"INSERT INTO sr_incentive (KD_Code,DSR_Code,monthval,yearval,target_units,target_naira,Product_id,insertdatetime) VALUES ('$KD_Code','$post_dsrcode','$monthval','$yearval','$post_units','$post_naira','$post_prodcode',NOW())";
						
						mysql_query($insert_tgt) or die(mysql_error());
					}
				}
			}
		}
		header("location:covtargetentryview.php?no=2");
	}
}

$query_srfind 						=	"select id,DSR_Code,DSRName from dsr";
$res_srfind 						=	mysql_query($query_srfind) or die(mysql_error());
$rowcnt_srfind 						=	mysql_num_rows($res_srfind);
while($row_srfind 					=	mysql_fetch_array($res_srfind)) {
	$sr_codearr[]					=	$row_srfind[DSR_Code];
}

$sel_tgtsr							=	"SELECT * from coverage_target_setting WHERE id = '$id'";
$res_tgtsr							=	mysql_query($sel_tgtsr) or die(mysql_error());
$rowcnt_tgtsr						=	mysql_num_rows($res_tgtsr);
$rowcnt_tgtsel						=	0;	
if($rowcnt_tgtsr	> 0) {
	$row_tgtsr						=	mysql_fetch_array($res_tgtsr);
	$tgtsrmonth						=	$row_tgtsr[monthval];
	$tgtsryear						=	$row_tgtsr[yearval];
	$tgtsrDSR_Code					=	$row_tgtsr[SR_Code];
	$coverage_percent				=	$row_tgtsr[coverage_percent];
	$productive_percent				=	$row_tgtsr[productive_percent];
	$effective_percent				=	$row_tgtsr[effective_percent];
	$cov_visit						=	$row_tgtsr[cov_visit];
	$prod_visit						=	$row_tgtsr[prod_visit];
	$eff_visit						=	$row_tgtsr[eff_visit];
	$cov_status						=	$row_tgtsr[cov_status];
	$prod_status					=	$row_tgtsr[prod_status];
	$eff_status						=	$row_tgtsr[eff_status];
	//$targetFlag					=	$row_tgtsr[targetFlag];
	$tgtTypeCov						=	$row_tgtsr[tgtTypeCov];
	$tgtTypeProd					=	$row_tgtsr[tgtTypeProd];
	$tgtTypeEff						=	$row_tgtsr[tgtTypeEff];

	$sel_tgtsel						=	"SELECT * from coverage_target_setting WHERE monthval = '$tgtsrmonth' AND yearval = '$tgtsryear' AND SR_Code = '$tgtsrDSR_Code'";
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


<?php //if($rowcnt_tgtsel == 0) { ?>

<link rel="stylesheet" type="text/css" href="../css/droplist/jquery-ui-1.8.13.custom.css">
<link rel="stylesheet" type="text/css" href="../css/droplist/ui.dropdownchecklist.themeroller.css">

<script type="text/javascript" src="../js/droplist/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/droplist/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="../js/droplist/ui.dropdownchecklist.js"></script>

<?php // } ?>

<!--  DROPBOX LIST JS AND CSS STARTS HERE -->

<script type="text/javascript">
$(document).ready(function() {
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
	 },width:170  });

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
				//alert(srlength);
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
		$('#errormsgsrpercent').hide();
		return false;
	});
});
</script>

<!--  DROPBOX LIST JS AND CSS ENDS HERE -->

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
width:95%;
margin-left:auto;
margin-right:auto;
height:135px;
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
	width:85%;
	text-align:left;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow:scroll;
	overflow-x:hidden;
	height:260px;
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

#errormsgsrpercent {
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
.myalignsrpercent {
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
<body onLoad="getasmrsmvalueswithbranch('<?php echo $tgtsrDSR_Code; ?>');">
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="headingscredit">EFFECTIVE COVERAGE TARGET</div>
<div id="mytableformcredit" align="center">
<?php $curmonthval	=	ltrim(date('m'),0);
	$curyearval	=	date('Y');
?>
<form id="srincentry" method="post" action="" onSubmit="return checkpercententry('<?php echo $curmonthval; ?>','<?php echo $curyearval; ?>');">
<div class="mcf"></div>
<div id="top">
<div style="float:center">
<fieldset class="alignment textalg">
<legend><strong>Effective Coverage Target</strong></legend>
<table align="center" width="100%">

<tr height="20">
      <td align="left" class="alignsize">SR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
     <td align="left" style="width:40%" nowrap="nowrap" class="align2 alignsize">
		<?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="srspan">
			<select style="width:300px" class="dsrname" class="s9" name="srcode[]" id="srcode" multiple onChange="getasmrsmsrpercent(this.value);">
				<option value="">---SR---</option>
				<!-- <option value="<?php 
				$sr_codestr		=	implode(",",$sr_codearr);				
				echo $sr_codestr; ?>"> ALL</option> -->
				<option value="<?php echo $srstr; ?>"> ALL</option>
				<?php while($info_sr = mysql_fetch_assoc($res_SR)) { ?>
				<option value="<?php echo  $info_sr['DSR_Code']; ?>" <?php if($tgtsrDSR_Code == $info_sr['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_sr['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else {  
			?>
			<span id="srid"><input type="hidden" autocomplete="off" name="srcode[]" id="srcode" value="<?php echo $tgtsrDSR_Code; ?>" /><?php echo getdbval($tgtsrDSR_Code,'DSRName','DSR_Code','dsr'); ?></span>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				
		<?php } */ ?>
       </td>
    
		<td align="left" class="align alignsize">ASM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left" style="width:40%" nowrap="nowrap" class="align2 alignsize">
		<?php //if($rowcnt_tgtsel == 0) { ?>
		  <span id="asmspan">
			<select class="dsrname" class="s9" name="asmcode[]" id="asmcode" multiple onChange="getasmspecificsrpercent(this.value);">
				<option value="">---ASM---</option>
				<option value="<?php echo $asmstr; ?>"> ALL</option>
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
			<span id="asmid"></span>		
			<?php } */ ?>
		</td>

	</tr>
		
		<tr>
			<td class="pad5"></td>
		</tr>
		<tr>	

       <!-- <td>ASM : </td>
       <td><span id="asmid">&nbsp;</span></td> -->

	 <td align="left" class="alignsize">RSM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">
	  <?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="rsmspan">
			<select class="dsrname" class="s9" name="rsmcode[]" id="rsmcode" multiple onChange="getrsmspecificsrpercent(this.value);">
				<option value="">---RSM---</option>
				<option value="<?php echo $rsmstr; ?>"> ALL</option>
				<?php while($info_rsm = mysql_fetch_assoc($res_RSM)) { ?>
				<option value="<?php echo  $info_rsm['id']; ?>" <?php if($rsmid == $info_rsm['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsm['DSRName']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else { 
				?>
			<span id="rsmid"></span>
		<?php } */ ?>
        </td> 

	<td align="left" class="align alignsize">Branch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2 alignsize">
	   <?php //if($rowcnt_tgtsel == 0) { ?>
        <span id="branchspan">
			<select class="dsrname" class="s9" name="branchcode[]" id="branchcode" multiple onChange="getbranchspecificsrpercent(this.value);">
				<option value="">---BRANCH---</option>
				<option value="<?php echo $branchstr; ?>"> ALL</option>
				<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
				<option value="<?php echo  $info_branch['id']; ?>" <?php if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
				<?php } ?> 
			</select>
		</span>
		<?php /* } else { 
				?>
			<span id="branchid"></span>
		<?php } */ ?>
        </td> 

		<!-- <td align="right" class="align"><strong>Target Flag</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		  <td align="left" nowrap="nowrap" style="width:2%" class="align2">
		   <?php if($rowcnt_tgtsel == 0) { ?>
			<span id="targetflagspan">
				<input type="radio" name="targetflag" id="targetflag" value="0" />T <sup>+</sup>&nbsp;&nbsp;
				<input type="radio" name="targetflag" id="targetflag" value="1" />T <sup>C</sup> 
		
				<select class="dsrname" name="targetflag" id="targetflag">
					<option value="">---TARGET FLAG---</option>
					<option value="0" >Target +</option>  	
					<option value="1" >Target Continuous</option>  	
				</select>
			</span>
			<?php } else { ?>
			<span id="flagid">
			
			<input type="radio" name="targetflag" id="targetflag" <?php if($targetFlag == "0") echo "checked"; ?> value="0" />T <sup>+</sup>&nbsp;&nbsp; 
			<input type="radio" name="targetflag" id="targetflag" <?php if($targetFlag == "1") echo "checked"; ?> value="1" />T <sup>C</sup> 
							
				<select class="dsrname" name="targetflag" id="targetflag">
					<option value="">---TARGET FLAG---</option>
					<option value="0" <?php if($targetFlag == "0") echo "selected"; ?> >Target +</option>  	
					<option value="1" <?php if($targetFlag == "1") echo "selected"; ?> >Target Continuous</option>  	
				</select>
			</span>
			<?php } ?>
		</td> -->  
		
	  </tr>	

	  <tr>
		<td class="pad5"></td>
      </tr>

	  <tr>
	  
	  <?php if($rowcnt_tgtsel == 0) { ?>
	  <td align="left" nowrap="nowrap" class="alignsize">From Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
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
<!-- <td align="right" nowrap="nowrap" class="align"><strong>Year</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2">-->

		<select name="fromyear" id="fromyear" >
			<option value="">--Year--</option>
			<?php $curyear = date("Y");
			for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($curyear == $i) {  echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>            
		</td>
		<!-- &nbsp;&nbsp;  -->

<td align="left" nowrap="nowrap" class="align alignsize">To Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
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

<!-- 	<td align="right" nowrap="nowrap" class="align"><strong>Year</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2">
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

	<td align="left" nowrap="nowrap" class="alignsize">From Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
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
	
		<!-- &nbsp;&nbsp;  -->
		
	<!--<td align="right" nowrap="nowrap" class="align"><strong>Year</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2">-->
		<select name="fromyear" id="fromyear" >
			<option value="">--Year--</option>
			<?php $curyear = date("Y");
			for($i=$curyear; $i<=($curyear+1);$i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($tgtsel[0][yearval] == $i) { echo "selected"; } ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>            
		</td>
		<!-- &nbsp;&nbsp;  -->
	<td align="left" nowrap="nowrap" class="align alignsize">To Month & Year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
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
		
		<!--</td> &nbsp;&nbsp;  -->
 <!--	<td align="right" nowrap="nowrap" class="align"><strong>Year</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  <td align="left" nowrap="nowrap" style="width:2%" class="align2"> -->
	
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
 	<tr>
		<td class="pad5"></td>
	</tr>
</table>
</div>
</div> <!--top ending-->

<div class="conscrollc">
<span id="coventryspan">
<table align="center" width="100%">
	<thead>
	<tr>
	<th align="center" style="width:30%">SR</th>
	<th align="left" nowrap="nowrap">Coverage %
	<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; --> 
	
	<!-- <input type="radio" name="tgtTypeCov" id="tgtTypeCov" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','cov_per_','visitcov','noton')" <?php if($tgtTypeCov == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp;
	<input type="radio" name="tgtTypeCov" id="tgtTypeCov" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','cov_per_','visitcov','notoff')"  <?php if($tgtTypeCov == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> --> 

	<input type="radio" name="tgtTypeCov" id="tgtTypeCov" value="0" <?php if($tgtTypeCov == "0") echo "checked"; ?> /> T <sup>+</sup>&nbsp;&nbsp;
	<input type="radio" name="tgtTypeCov" id="tgtTypeCov" value="1" <?php if($tgtTypeCov == "1") echo "checked"; ?> /> T <sup>C</sup>
	
	<br/><br/>
	
	<!-- <input type="radio" value="5" name="visitcov" id="visitcov" <?php if($tgtTypeCov == "1") { echo "disabled"; } else {  if($cov_status == '5') { echo "checked"; }  } ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','cov_per_','noton')"  /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visitcov" id="visitcov" <?php if($tgtTypeCov == "1") { echo "disabled"; } else { if($cov_status == '10') { echo "checked"; }  } ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','cov_per_','notoff')" /> Visits -->

	<input type="radio" value="5" name="visitcov" id="visitcov" <?php if($cov_status == '5') { echo "checked"; } ?> /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visitcov" id="visitcov" <?php if($cov_status == '10') { echo "checked"; } ?> /> Visits
	
	</th>
	<th align="left" nowrap="nowrap">Productivity %
	<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; --> 
	
	<!-- <input type="radio" name="tgtTypeProd" id="tgtTypeProd" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','prod_per_','visitprod','noton')" <?php if($tgtTypeProd == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
	<input type="radio" name="tgtTypeProd" id="tgtTypeProd" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','prod_per_','visitprod','notoff')" <?php if($tgtTypeProd == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> -->

	<input type="radio" name="tgtTypeProd" id="tgtTypeProd" value="0" <?php if($tgtTypeProd == "0") echo "checked"; ?> /> T <sup>+</sup>&nbsp;&nbsp; 
	<input type="radio" name="tgtTypeProd" id="tgtTypeProd" value="1" <?php if($tgtTypeProd == "1") echo "checked"; ?> /> T <sup>C</sup>
	
	<br/><br/>
	
	<!-- <input type="radio" value="5" name="visitprod" id="visitprod" <?php if($tgtTypeProd == "1") { echo "disabled"; } else { if($prod_status == '5') { echo "checked"; } }  ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','prod_per_','noton')" /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visitprod" id="visitprod" <?php if($tgtTypeProd == "1") { echo "disabled"; } else { if($prod_status == '10') { echo "checked"; } } ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','prod_per_','notoff')" /> Visits -->

	<input type="radio" value="5" name="visitprod" id="visitprod"   <?php if($prod_status == '5') { echo "checked"; } ?> /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visitprod" id="visitprod" <?php if($prod_status == '10') { echo "checked"; } ?> /> Visits

	</th>
	<th align="left" nowrap="nowrap">Effective Cov. %
	<br/><br/> <!-- Tgt Type : &nbsp;&nbsp; -->
	
	<!-- <input type="radio" name="tgtTypeEff" id="tgtTypeEff" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','eff_per_','visiteff','noton')" <?php if($tgtTypeEff == "0") echo "checked"; ?> value="0" /> T <sup>+</sup>&nbsp;&nbsp; 
	<input type="radio" name="tgtTypeEff" id="tgtTypeEff" onClick="checkCovTgt('<?php echo $rowcnt_tgtsel; ?>','eff_per_','visiteff','notoff')" <?php if($tgtTypeEff == "1") echo "checked"; ?> value="1" /> T <sup>C</sup> -->

	<input type="radio" name="tgtTypeEff" id="tgtTypeEff" value="0" <?php if($tgtTypeEff == "0") echo "checked"; ?> /> T <sup>+</sup>&nbsp;&nbsp; 
	<input type="radio" name="tgtTypeEff" id="tgtTypeEff" value="1" <?php if($tgtTypeEff == "1") echo "checked"; ?> /> T <sup>C</sup>

	<br/><br/>
	
	<!-- <input type="radio" value="5" name="visiteff" id="visiteff" <?php if($tgtTypeEff == "1") { echo "disabled"; } else { if($eff_status == '5') { echo "checked"; }  } ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','eff_per_','noton')" /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visiteff" id="visiteff" <?php if($tgtTypeEff == "1") { echo "disabled"; } else {  if($eff_status == '10') { echo "checked"; }   } ?> onClick="checkCovVisit('<?php echo $rowcnt_tgtsel; ?>','eff_per_','notoff')" /> Visits -->

	<input type="radio" value="5" name="visiteff" id="visiteff" <?php if($eff_status == '5') { echo "checked"; } ?> /> %&nbsp;&nbsp;&nbsp;
	<input type="radio" value="10" name="visiteff" id="visiteff" <?php if($eff_status == '10') { echo "checked"; } ?> /> Visits

	</th>
	<th align="center" >
		<table border="1">
		<tr>
		  <td align="center" colspan="3">INCENTIVE NAIRA</td>
		</tr>
		<tr>
		  <td align="center" nowrap="nowrap">Coverage Incentive</td>
		  <td align="center" nowrap="nowrap">Productivity Incentive</td>
		  <td align="center" nowrap="nowrap">Effective Cov. Incentive</td>
		</tr>
		</table>
	</th>
	</tr>
	</thead>
	<tbody>
		<?php if($rowcnt_tgtsel == 0) { 
			
		?>
		<tr>
			<td align="center" colspan="7"><strong>NO RECORDS FOUND</strong></td>
		</tr>
		<?php } elseif($rowcnt_tgtsel > 0) { $k	=	0; 			
		?>
		<tr>
			<td width="731" style="width:45%"><input type="hidden" autocomplete="off" name="srname_<?php echo $k; ?>" id="srname_<?php echo $k; ?>" value="<?php echo $tgtsrDSR_Code; ?>" /><span><?php echo getdbval($tgtsrDSR_Code,'DSRName','DSR_Code','dsr'); //PARAMETERS ARE TABLE QUERY COLUMN VALUE, TABLE RESULT COLUMN, TABLE QUERY COLUMN NAME, TABLE NAME ?></span></td>
			<td align="center">
			
			<!-- <input type="text" size="5" maxlength="5" <?php if($tgtTypeCov == "1") { echo "disabled"; } if($cov_status == '10') { echo "disabled"; }  ?> autocomplete="off" name="cov_per_<?php echo $k; ?>" id="cov_per_<?php echo $k; ?>" value="<?php if($tgtTypeCov == "0") {  if($cov_status == '5') { echo $coverage_percent; } } ?>" /> -->
			
			<input type="text" size="5" maxlength="5" autocomplete="off" name="cov_per_<?php echo $k; ?>" id="cov_per_<?php echo $k; ?>" value="<?php echo $coverage_percent; ?>" />

			</td>	
			<td align="center">
			
			<!-- <input type="text" size="5" maxlength="5" <?php if($tgtTypeProd == "1") { echo "disabled"; } if($prod_status == '10') { echo "disabled"; }  ?> autocomplete="off" name="prod_per_<?php echo $k; ?>" id="prod_per_<?php echo $k; ?>" value="<?php if($tgtTypeProd == "0") {  if($prod_status == '5') { echo $productive_percent; } } ?>" /> -->
			
			<input type="text" size="5" maxlength="5" autocomplete="off" name="prod_per_<?php echo $k; ?>" id="prod_per_<?php echo $k; ?>" value="<?php echo $productive_percent; ?>" />

			</td>
			<td align="center">
			
			<!-- <input type="text" size="5" maxlength="5" <?php if($tgtTypeEff == "1") { echo "disabled"; } if($eff_status == '10') { echo "disabled"; }  ?> autocomplete="off" name="eff_per_<?php echo $k; ?>" id="eff_per_<?php echo $k; ?>" value="<?php if($tgtTypeEff == "0") { if($eff_status == '5') { echo $effective_percent; } } ?>" /> -->

			<input type="text" size="5" maxlength="5" autocomplete="off" name="eff_per_<?php echo $k; ?>" id="eff_per_<?php echo $k; ?>" value="<?php echo $effective_percent; ?>" />
			
			</td>
			<td align="center">
			<table>
			  <tr>
				 <td align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="cov_visit_<?php echo $k; ?>" id="cov_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'cov_visit_<?php echo $k; ?>');" value="<?php echo number_format($cov_visit,2); ?>" />						
				</td>	
				<td  align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="prod_visit_<?php echo $k; ?>" id="prod_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'prod_visit_<?php echo $k; ?>');" value="<?php echo number_format($prod_visit,2); ?>" />
				</td>
				<td  align="center" nowrap="nowrap"><input type="text" size="15" maxlength="5" autocomplete="off" name="eff_visit_<?php echo $k; ?>" id="eff_visit_<?php echo $k; ?>" onBlur="curformatreturntoid(this.value,'eff_visit_<?php echo $k; ?>');" value="<?php echo number_format($eff_visit,2); ?>" />
				</td>
			 </tr>
		  </table>
		</tr>
		<input type="hidden" name="overall_rowcnt" id="overall_rowcnt" value="<?php echo $rowcnt_tgtsel; ?>" />
	<?php } ?>
	</tbody>
</table>
</span>       
     </div> <!--con ending-->
<?php require_once "../include/error.php"; ?>
<input type="hidden" name="dsrselecttext" id="dsrselecttext" value="" />
<div id="errormsgsrpercent" style="display:none;"><h3 align="center" class="myalignsrpercent"></h3><button id="closebutton_cus">Close</button></div>
 <table width="50%" style="clear:both">
      <tr align="center" height="50px;">
      <td>
      <input type="submit" name="submit" id="submit" class="buttons" value="Save" />&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" name="reset" class="buttons" value="Clear" id="clear"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="cancel" value="Cancel" class="buttons" onclick="window.location='../include/menu.php'"/>&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="button" name="View" value="View" class="buttons" onclick="window.location='covtargetentryview.php'"/>
	  </td>
      </tr>
 </table>       
 </form>
  </div> <!--mytableform ending-->
</div><!-- mainarea ending-->
<?php include('../include/footer.php'); ?>