<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'nama_pengguna';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;


$usingSQL = true;
function runSQL($rsql) {
	
	$active_group = 'default';

	$base_url = "http://".$_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	
	$result = mysql_query($rsql) or die ($rsql);
	return $result;
}

function countRec($fname,$tname) {
	$sql = "SELECT count($fname) FROM $tname";
	$result = runSQL($sql);
	while ($row = mysql_fetch_array($result)) {
		return $row[0];
	}
}

$sort = "ORDER BY $sortname $sortorder";
$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$where = "";
if ($query) $where = " WHERE $qtype LIKE '%".mysql_real_escape_string($query)."%' ";

$sql = "SELECT nama_pengguna,notelp FROM ms_pengguna $where $sort $limit";

$result = runSQL($sql);

$total = countRec("nama_pengguna","ms_pengguna $where");

header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());

if (is_array($rows)) {
foreach($rows AS $row){
	//If cell's elements have named keys, they must match column names
	//Only cell's with named keys and matching columns are order independent.
	$entry = array('id'=>$row['nama_pengguna'],
		'cell'=>array(
			'nama_pengguna'=>$row['nama_pengguna'],
			'notelp'=>$row['notelp']
		),
	);
	$jsonData['rows'][] = $entry;
}}
echo json_encode($jsonData);