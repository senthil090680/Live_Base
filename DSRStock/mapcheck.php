<?php
require_once "../include/header.php";
//$address	= urlencode($resultArray[0][location_area]);
$address	= "velachery";
$zoom = 15;
$type = 'ROADMAP';
$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=true");
//echo $json;

$data = json_decode($json,true);

//pre($data);

//echo $data['results'][0]['geometry']['location']['lat']."<br>"; echo $data['results'][0]['geometry']['location']['lng']."<br>";

$map_status = $data['status'];
//exit(0);
//$data = file_get_contents("http://maps.google.com/maps/geo?output=csv&q=".urlencode($address));

if ($map_status == 'OK') {
	$lat = $data['results'][0]['geometry']['location']['lat'];
	$long = $data['results'][0]['geometry']['location']['lng'];
} else {
	echo "<script type='text/javascript'>alert('Google Map Lookup Failed');</script>";
	//die();
}

?>
<script type="text/javascript">
$(document).ready(function() {
	var latlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
	var settings = {
		zoom: 15,
		center: latlng,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl: true,
		navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
		mapTypeId: google.maps.MapTypeId.ROADMAP};
	var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
   // var contentString = '<div id="content">'+'<div id="siteNotice">'+'</div>'+'<h1 id="firstHeading" class="firstHeading">Area name</h1>'+'<div id="bodyContent">'+'<p align="left" >P.O.Box : 666666 - Dubai, UAE <br> Tel : (+971) 789789789</p>'+'</div>'+'</div>';
	/*var infowindow = new google.maps.InfoWindow({
		content: contentString
	});*/
		 google.maps.event.trigger(map, 'resize'); 
	var companyImage = new google.maps.MarkerImage('images/marker.png',
		new google.maps.Size(100,50),
		new google.maps.Point(0,0),
		new google.maps.Point(50,50)
	);
	//var companyPos = new google.maps.LatLng(25.2894045564648903, 51.49017333985673);
	var companyPos = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
	var companyMarker = new google.maps.Marker({
		position: companyPos,
		map: map,
		icon: companyImage,
		title:"Location Name",
		zIndex: 3});

	google.maps.event.addListener(companyMarker, 'click', function() {
		infowindow.open(map,companyMarker);
	});
});
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<div id="map_canvas" style="width:620px; height:320px;" align="left" valin="top"></div>