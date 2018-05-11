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

$sql = "SELECT ms_pengguna.nama_pengguna,ms_pengguna.notelp,ms_golongan1.nama as namagol1,ms_golongan2.nama as namagol2,ms_pengguna.jnismonitor,
CASE ms_pengguna.jnismonitor
WHEN  'Jarak'
THEN ms_pengguna.jarak
ELSE ms_pengguna.waktu
END as rentang,ms_pengguna.kdgol1,ms_pengguna.kdgol2 FROM ms_pengguna inner join ms_golongan1 where ms_pengguna.kdgol1=ms_golongan1.kode
inner join ms_golongan2 on ms_pengguna.kdgol2=ms_golongan2.kode $where $sort $limit";

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
			'notelp'=>$row['notelp'],
			'namagol1'=>$row['namagol1'],
			'namagol2'=>$row['namagol2'],
			'jnismonitor'=>$row['jnismonitor'],
			'rentang'=>$row['rentang']
		),
	);
	$jsonData['rows'][] = $entry;
}}
echo json_encode($jsonData);