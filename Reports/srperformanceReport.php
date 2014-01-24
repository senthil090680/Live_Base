<?php
ob_start();
include('../include/header.php');
include "../include/ps_pagination.php";
require_once "../include/ajax_pagination.php";
//ini_set("display_errors",false);
//echo ini_get("display_errors");
if(isset($_GET['logout'])){
	session_destroy();
	header("Location:../index.php");
}
extract($_REQUEST);
$msg								=	'';

$query_custype 						=	"select id,customer_type from customer_type";
$res_custype 						=	mysql_query($query_custype) or die(mysql_error());

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

$id									=	isset($_REQUEST['id']);
?>
<!------------------------------- Form -------------------------------------------------->



<!--  DROPBOX LIST JS AND CSS STARTS HERE -->

<link rel="stylesheet" type="text/css" href="../css/droplist/jquery-ui-1.8.13.custom.css">
<link rel="stylesheet" type="text/css" href="../css/droplist/ui.dropdownchecklist.themeroller.css">

<script type="text/javascript" src="../js/droplist/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/droplist/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="../js/droplist/ui.dropdownchecklist.js"></script>

<script type="text/javascript">
$(document).ready(function() {

	//$( "#datepicker" ).datepicker();

	$( ".datepicker" ).datepicker();

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
					//alert('2332');
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


	/* FOR CLOSE BUTTON FOR THE ERROR MESSAGES STARTS HERE */

	/*$('#closebutton_cus').click(function(event) {
		$('#errormsgsrinc').hide();
		return false;
	});*/
	$('#closebutton').button({
		icons: {
			primary : "../images/close_pop.png",
		},
		text:false
	});

	$('#closebutton_cus').button({
		icons: {
			primary : "../images/close_pop.png",
		},
		text:false
	});

	$('#closebutton_blue').button({
		icons : {
			primary : "..images/close_pop.png"
		},
		text:false

	});

	$('#closebutton_blue').click(function(event) {
		$('#errormsg').hide();
		$('#errorbigmsg').hide();
		$('#errormsgmetrics').hide();
		$('#errormsgcustra').hide();
		$('#errormsgveh').hide();
		$('#errormsgcycle').hide();
		$('#errormsgcuscon').hide();
		$('#errormsgsalcol').hide();
		return false;
	});

	$('#closebutton').click(function(event) {
		//alert('232');
		$('#errormsg').hide();
		$('#errorbigmsg').hide();
		$('#errormsgcus').hide();
		$('#errormsgdev').hide();
		$('#errormsgopen').hide();
		$('#errormsgstock').hide();
		$('#errormsgcol').hide();
		$('#errormsgcycleajx').hide();
		$('#errormsgcystart').hide();
		$('#errormsgindopen').hide();
		$('#errormsgdevreg').hide();
		$('#errormsgpopupprod').hide();
		$("#errormsgrouteplan").hide();
		$("#errormsgmonplan").hide();
		$("#errormsgtgt").hide();
		$('#errormsgcredit').hide();
		$('#errormsgcovtgt').hide();
		$('#errormsgsrinc').hide();
		$('#errormsgsrpercent').hide();
		$('#errormsgposmtgt').hide();
		$('#errormsgposmcov').hide();
		$('#errormsgmetrep').hide();
		$('#errormsgsrperf').hide();
		return false;
	});
	/*$('#closebutton_cus').click(function(event) {
		//alert('232');
		$('#errormsgcus').hide();
		$('#errormsgsrinc').hide();
		$('#errormsgsrpercent').hide();
		return false;
	});
	$('#closebutton_cre').click(function(event) {
		//alert('232');
		$('#errormsgcredit').hide();
		return false;
	});*/
	/* FOR CLOSE BUTTON FOR THE ERROR MESSAGES ENDS HERE */
	
	$("tbody").scroll(function(){
		//$("span").text(x+=1);
  });
});
</script>

<!--  DROPBOX LIST JS AND CSS ENDS HERE -->


<style type="text/css">
.heading_report{
	background:#a09e9e;
	width:100%;
	margin-left:auto;
	margin-right:auto;
	height:25px;
	padding-top:5px;
	border-radius:6px;
	font-weight:bold;
	font-size:14px;
	clear:both;
}
#mytableform_report{
	background:#fff;
	width:99%;
	margin-left:auto;
	margin-right:auto;
	height:450px;
}
.alignment_report {
	width:96%;
	padding-left:20px;
	margin-left:10px;
	font-size:16px;
}

/* .condaily_routeplan th {
	padding:2px 5px 0 5px;
	font-weight:bold;
	font-size:14px;
	color:#000;
}
.condaily_routeplan td {
	padding:2px 5px 0 5px;
	background:#fff;
	border-collapse:collapse;
	padding-left:10px;
	color:#000;
	font-size:14px;
}
 */
 
.condaily_routeplan table{
	border-collapse:collapse;
}
 

.condaily_routeplan td {
	background:#fff;
}

.condaily_routeplan tbody tr:hover td {
	background: #c1c1c1;
}
.condaily_routeplan {
	/* width:100%; */
	text-align:left;
	height:350px;
	border-collapse:collapse;
	background:#a09e9e;
	margin-left:auto;
	margin-right:auto;
	border-radius:10px;
	overflow-y:hidden;
	overflow-x:scroll;
}

.tablecls{
    width: 127%;
    background-color: #aaa;
  }

 .tbodycls{
    background-color: #CCCCCC;
    display: block;
    overflow-x: hidden;
    overflow-y: scroll;
    width: 160%;
	height: 240px;    
  }

  .theadcls {
	display: block;
	height: 50px;
	width: 160%;
   }
  .tbodycls td {
	width: 120px;
    height: 30px;
    background-color: #eee;
  }
  
  .theadcls th{
    width: 120px;
    height: 50px;
    background-color: #a09e9e;
	color:#000;
  }
  
  .tablefixed {
	table-layout:fixed;
  }
  .tablefixed td{
	width:20px;
  }
  .methead{
	width:180px;
  }
  .metbody{
	width:160px;
  }
 

#errormsgsrperf {
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
.myalignsrperf {
	padding-top:8px;
	margin:0 auto;
	color:#FF0000;
}

.buttons_new{
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#31859C;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:160px;
	height:15px;
}
.buttons_gray {
	-webkit-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	-moz-box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	box-shadow:rgba(0,0,0,0.2) 0 1px 0 0;
	border-bottom-color:#333;
	border:1px solid #686868;
	background-color:#A09E9E;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	color:#000;
	font-family:Calibri;
	font-size:12px;
	padding:3px;
	cursor:pointer;
	width:240px;
	height:15px;
}

.align2 {
	padding-left:10px;
}

#span1 {
	width: 30px; 
	float:left;
  }
#span2 { 
    width: 30px; 
	float:right;
	}
	
#colors{
	background-color:#CCC;
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
<body <?php if($dsrcode != '') { ?> onLoad="getDSRRoutes('<?php echo $dsrcode; ?>')" <?php } ?> >
<div id="mainarea">
<div class="mcf"></div>
<div align="center" class="heading_report">SR PERFORMANCE REPORT</div>
<div id="mytableform_report" align="center">
<div class="mcf"></div>
<!-- <form method="post" action="" id="routemasterplan"> -->
<div style="background-color:#CCC">
<table width="100%">
 <tr>
 <td align="left" style="width:9%;" class="align alignsize" >SR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	<td align="left" style="width:10%" class="align2 alignsize">
	<span id="srspan">
		<select class="dsrname s9" style="width:40%" name="srcode[]" id="srcode" multiple onChange="getsrspecific(this.value);">
			<option value="">---SR---</option>
			<option value="<?php 				
			echo $srstr; ?>"> ALL</option>
			<?php while($info_sr = mysql_fetch_assoc($res_SR)) { ?>
			<option value="<?php echo  $info_sr['DSR_Code']; ?>" <?php if($srcode == $info_sr['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_sr['DSRName']); ?></option>
			<?php } ?> 
		</select>
	</span>
	</td>
	
	<td align="left" style="width:9%" class="align alignsize">ASM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>	
	<td align="left" nowrap="nowrap" style="width:20%" class="alignsize">
	<span id="asmspan">
		<select class="dsrname s9" name="asmcode[]" id="asmcode" multiple onChange="getasmspecificwithsr(this.value);">
			<option value="">---ASM---</option>
			<option value="<?php 				
			echo $asmstr; ?>"> ALL</option>
			<?php while($info_asm = mysql_fetch_assoc($res_ASM)) { ?>
			<option value="<?php echo  $info_asm['DSR_Code']; ?>" <?php if($asmcode == $info_asm['DSR_Code']) { echo "selected"; } ?> > <?php echo  upperstate($info_asm['DSRName']); ?></option>
			<?php } ?> 
		</select>
	</span>
	</td> 
</tr>
<tr>
	<td class="pad5"></td>
</tr>
<tr>
	
	 <td align="left" style="width:9%;" class="align alignsize">RSM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	<td align="left" style="width:4%" nowrap="nowrap" class="align2 alignsize">
	<span id="rsmspan">
		<select class="dsrname s9" name="rsmcode[]" id="rsmcode" multiple onChange="getrsmspecificwithsr(this.value);">
			<option value="">---RSM---</option>
			<option value="<?php 				
			echo $rsmstr; ?>"> ALL</option>
			<?php while($info_rsm = mysql_fetch_assoc($res_RSM)) { ?>
			<option value="<?php echo  $info_rsm['id']; ?>" <?php if($rsmcode == $info_rsm['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_rsm['DSRName']); ?></option>
			<?php } ?> 
		</select>
	</span>
	</td>

<td align="left" style="width:9%;" class="align alignsize">BRANCH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
  <td align="left" nowrap="nowrap" style="width:2%" class="alignsize">
	<span id="branchspan">
		<select class="dsrname s9" name="branchcode[]" id="branchcode" multiple onChange="getbranchspecificwithsr(this.value);">
			<option value="">---BRANCH---</option>
			<option value="<?php 				
			echo $branchstr; ?>"> ALL</option>
			<?php while($info_branch = mysql_fetch_assoc($res_branch)) { ?>
			<option value="<?php echo  $info_branch['id']; ?>" <?php if($branchcode == $info_branch['id']) { echo "selected"; } ?> > <?php echo  upperstate($info_branch['branch']); ?></option>
			<?php } ?> 
		</select>
	</span>
	 <!-- &nbsp; ASM : &nbsp; <span id="asmid">&nbsp;</span> -->
	</td>
</tr>
<tr>
	<td class="pad5"></td>
</tr>

        <!-- <td align="left" style="width:4%" class="align2" nowrap="nowrap">
        		<span id="custypespan">
        			<select class="dsrname" name="custype" id="custype" multiple>
        			<option value="">---Customer Type---</option>
        			<?php while($info = mysql_fetch_assoc($res_custype)){?>
        			<option value="<?php echo  $info['id']; ?>" <?php if($custype == $info['id']) { echo "selected"; } ?> > <?php echo  $info['customer_type'] ?></option>
        			<?php }?> 
        			</select>
        		</span>
        		</td> -->
<tr>
<td align="left" style="width:9%;" class="align alignsize" nowrap="nowrap">REPORT BY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
<td style="width:2%" class="align2 alignsize" nowrap="nowrap">
 <select name="reportby" id="reportby">
	<option value="">---Select---</option>
	<option value="DateVal" > Date</option>
	<option value="DSR_Name" > SR</option>
</select>&nbsp; &nbsp;
</td>
	
<td class="align alignsize" style="width:9%;" nowrap="nowrap">
	FROM DATE &nbsp;<input type="text" size="9" readonly class="datepicker" name="fromdates" id="fromdates" onChange="changeDateFormat(this.value,'fromdates')" value="<?php echo date('Y-m-d'); ?>" />	&nbsp;&nbsp;
</td>
<td class="alignsize" nowrap="nowrap">
	TO DATE&nbsp; <input type="text" size="9" readonly class="datepicker" name="todates" id="todates" onChange="changeDateFormat(this.value,'todates')" value="<?php echo date('Y-m-d'); ?>" />  &nbsp; &nbsp;
   <input type="button" class="buttons" onClick="srperformreport();" value="GO" />
</td>
	<!-- <td colspan="2">&nbsp;</td> -->
 </tr>
</table>
</div>     
<div class="mcf">
	<div class="condaily_routeplan">
		 <span id="ajaxresultpage">
		  <div id="header_1">
		  <table width="100%" border="1">
		  <tr>
			<th id="head1"><strong>SR Name</strong></th>
			<th id="head2"><strong>Date</strong></th>
			<th id="head3"><strong>First Check In Time</strong></th>
			<th id="head4"><strong>Last Check Out Time</strong></th>
			<th id="head5"><strong>TotalHours</strong></th>
			<th id="head6"><strong>Total Customers Planned</strong></th>
			<th id="head7"><strong>Total Customer Visited</strong></th>
			<th id="head8"><strong>TotalCustomers Not Covered</strong></th>
			<th id="head9"><strong>Total Sale Visits</strong></th>
			<th id="head10"><strong>Coverage %</strong>
			 <table width="100%" >
                 <tr>
                 <td><strong>Target</strong></td>
                 <td> <strong>Actual</strong></td>
                 </tr>
              </table>
			</th>
            
			<th id="head10"><strong>Effectivity %</strong>
		    <table width="100%" >
            <tr>
            <td><strong>Target</strong></td>
            <td> <strong>Actual</strong></td>
            </tr>
            </table>
			</th>
            
			<th id="head11"><strong>Total Sales (Naira)</strong></th>
			<th id="head12"><strong>Total Invoices</strong></th>
			<th id="head13"><strong>Total Line Items</strong></th>
            
			<th id="head14"><strong>Focus Items</strong>
			<table width="100%">
            <tr>
            <td><strong>In Plan</strong></td>
            <td><strong>Sold</strong></td>
            </tr>
            </table>
			</th>
            
			<th id="head15"><strong>Metrics</strong>
			<table width="100%">
            <tr>
            <td><strong>Drop Size</strong></td>
            <td><strong>Basket Size</strong></td>
            <td><strong>Focus Coverage</strong></td>
            </tr>
            </table>
			</th>
            
			</tr>
		  </table>
		  </div>
		 <!-- <div style="clear:both;"></div> -->
		 <div id="tbody">
		 <table border="1" width="100%">
		 <tr>
			<td colspan="16" align="center" style="background:#fff;">
				<strong>NO RECORDS FOUND</strong>
			</td>
		 </tr>
	       <!-- <tr>
			 <td>&nbsp;</td>
			 <td>&nbsp;</td>	
			 <td>&nbsp;</td>
			 <td>&nbsp;</td>	
			 <td>&nbsp;</td>
			 <td>&nbsp;</td>	
			 <td>&nbsp;</td>	
			 <td>&nbsp;</td>	
			 <td>&nbsp;</td>
         </tr> -->
		</table>
		</div>
	   </span>
	 </div>      
</div>
<div class="mcf"></div>
	 <table width="50%" style="clear:both">
		 <tr align="center">
		 </tr>
	 </table>
<!-- </form> -->
<?php include("../include/error.php"); ?>
<div class="mcf"></div> 
	  <div id="errormsgsrperf" style="display:none;"><h3 align="center" class="myalignsrperf"></h3><button id="closebutton">Close</button></div>
    
     </div>
  </div>
</div>
<?php include('../include/footer.php');?>