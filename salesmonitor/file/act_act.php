<?php

//session_start();
//if (!isset($_SESSION['username'])){
//header('Location:../index.html');
//}

include("koneksi.php");

$nama=trim($_POST['nama']);
$longi=trim($_POST['longi']);
$loti=trim($_POST['loti']);
$telp=trim($_POST['telp']);
$jnismonitor=trim($_POST['jnismonitor']);
$jarak=trim($_POST['jarak']);
$waktu=trim($_POST['waktu']);
$ceksms=trim($_POST['ceksms']);
$cektelp=trim($_POST['cektelp']);
$tanggal=trim($_POST['tanggal']);
$jam=trim($_POST['jam']);

$tanggal=str_replace('/', '-',$tanggal);
$tanggal= date('Y-m-d',strtotime($tanggal));

$add= Get_Address_From_Google_Maps($longi,$loti);

//if ($add['formatted_address']='') {
//	$alamat='';
//}else{
	$alamat=$add['formatted_address'];
//}

//$date =new DateTime('now'); //new DateTime('now', new DateTimeZone('Asia/Jakarta'));
//$tanggal= $date->format('Y/m/d');
//$jam= $date->format('H:i:s');

$sql="insert into tr_lokasi(nama_pengguna,longi,loti,tanggal,jam,alamat) values('$nama','$longi','$loti','$tanggal','$jam','$alamat')";
$hasil =  mysql_query($sql) or die("0");
			if($hasil) { 
			
			$sqlcek="select jnismonitor,jarak,waktu,ceksms,cektelp from ms_pengguna where (nama_pengguna='$nama' or notelp='$telp')";
			$cursor= mysql_query($sqlcek) or die("0");
			$row= mysql_fetch_row($cursor);
			
			$vjnismonitor=$row[0];
			$vjarak=$row[1];
			$vwaktu=$row[2];
			$ceksms=$row[3];
			$cektelp=$row[4];
			
			if (($vjnismonitor != $jnismonitor) || ($vjarak != $jarak) || ($vwaktu != $waktu) || ($ceksms != $ceksms) || ($cektelp != $cektelp)) {
				echo 2; 
			}else{
				echo 1;
			}
			
			}else{ echo 0; }


function Get_Address_From_Google_Maps($lat, $lon) {

	$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false";

	// Make the HTTP request
	$data = @file_get_contents($url);
	// Parse the json response
	$jsondata = json_decode($data,true);
	
	// If the json data is invalid, return empty array
	if (!check_status($jsondata))	return array();

	$address = array(
	    'country' => google_getCountry($jsondata),
	    'province' => google_getProvince($jsondata),
	    'city' => google_getCity($jsondata),
	    'street' => google_getStreet($jsondata),
	    'postal_code' => google_getPostalCode($jsondata),
	    'country_code' => google_getCountryCode($jsondata),
	    'formatted_address' => google_getAddress($jsondata),
	);

	return $address;
}

/* 
* Check if the json data from Google Geo is valid 
*/

function check_status($jsondata) {
	if ($jsondata["status"] == "OK") return true;
	return false;
}

/*
* Given Google Geocode json, return the value in the specified element of the array
*/

function google_getCountry($jsondata) {
	return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]);
}
function google_getProvince($jsondata) {
	return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true);
}
function google_getCity($jsondata) {
	return Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]);
}
function google_getStreet($jsondata) {
	return Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]);
}
function google_getPostalCode($jsondata) {
	return Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]);
}
function google_getCountryCode($jsondata) {
	return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true);
}
function google_getAddress($jsondata) {
	return $jsondata["results"][0]["formatted_address"];
}

/*
* Searching in Google Geo json, return the long name given the type. 
* (If short_name is true, return short name)
*/

function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
	foreach( $array as $value) {
		if (in_array($type, $value["types"])) {
			if ($short_name)	
				return $value["short_name"];
			return $value["long_name"];
		}
	}
}

/*
*  Print an array
*/

function d($a) {
	echo "<pre>";
	print_r($a);
	echo "</pre>";
}

?>