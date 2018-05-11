<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$page = 1;	// The current page
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = 'nama_pengguna';	 // Sort column
$sortorder = 'asc';	 // Sort order
$qtype = '';	 // Search column
$query = '';	 // Search string
$pengguna = isset($_POST['pengguna']) ? $_POST['pengguna'] : 'xxx1';
$tgl1 = isset($_POST['tgl1']) ? $_POST['tgl1'] : '17/07/2013';
$tgl2 = isset($_POST['tgl2']) ? $_POST['tgl2'] : '17/07/2013';
$jam1 = isset($_POST['jam1']) ? $_POST['jam1'] : '00:00';
$jam2 = isset($_POST['jam2']) ? $_POST['jam2'] : '23:59';
$gol1 = isset($_POST['gol1']) ? $_POST['gol1'] : '';
$gol2 = isset($_POST['gol2']) ? $_POST['gol2'] : '';

// Get posted data
if (isset($_POST['page'])) {
$page = mysql_real_escape_string($_POST['page']);
}
if (isset($_POST['sortname'])) {
$sortname = mysql_real_escape_string($_POST['sortname']);
}
if (isset($_POST['sortorder'])) {
$sortorder = mysql_real_escape_string($_POST['sortorder']);
}
if (isset($_POST['qtype'])) {
$qtype = mysql_real_escape_string($_POST['qtype']);
}
if (isset($_POST['query'])) {
$query = mysql_real_escape_string($_POST['query']);
}
if (isset($_POST['rp'])) {
$rp = mysql_real_escape_string($_POST['rp']);
}

$tgl1=str_replace('/', '-',$tgl1);
$tgl1= date('Y-m-d',strtotime($tgl1));

$tgl2=str_replace('/', '-',$tgl2);
$tgl2= date('Y-m-d',strtotime($tgl2));

$jam1=$jam1 .':00';
$jam2=$jam2 .':00';	

// Setup sort and search SQL using posted data
$sortSql = "order by $sortname $sortorder";
//$searchSql = ($qtype != '' && $query != '') ? "where $qtype = '$query'" : '';

$searchSql ="where a.tanggal >='$tgl1' and a.tanggal <='$tgl2' and a.jam>='$jam1' and a.jam<='$jam2'" ;

if ($pengguna !='') {
	$searchSql= $searchSql . " and b.nama_pengguna like '%$pengguna%'";
}

if ($gol1 !='') {
	$searchSql= $searchSql . " and c.kode='$gol1'";
}

if ($gol2 !='') {
	$searchSql= $searchSql . " and d.kode='$gol2'";
}

// Get total count of records
$sql = "select count(*)
from tr_lokasi a inner join ms_pengguna b
	on a.nama_pengguna=b.nama_pengguna
	inner join ms_golongan1 c
	on b.kdgol1=c.kode
	inner join ms_golongan2 d
	on b.kdgol2=d.kode
$searchSql";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$total = $row[0];
// Setup paging SQL
$pageStart = ($page-1)*$rp;
$limitSql = "limit $pageStart, $rp";
// Return JSON data
$data = array();
$data['page'] = $page;
$data['total'] = $total;
$data['rows'] = array();
$sql = "select b.nama_pengguna,c.nama as namagol1,d.nama as namagol2,a.longi,a.loti,a.tanggal,a.jam,a.alamat
from tr_lokasi a inner join ms_pengguna b
	on a.nama_pengguna=b.nama_pengguna
	inner join ms_golongan1 c
	on b.kdgol1=c.kode
	inner join ms_golongan2 d
	on b.kdgol2=d.kode
$searchSql
$sortSql
$limitSql";
$results = mysql_query($sql);
while ($row = mysql_fetch_assoc($results)) {
$data['rows'][] = array(
'id' => $row['nama_pengguna'],
'cell' => array($row['nama_pengguna'],$row['namagol1'],$row['namagol2'],$row['tanggal'],$row['jam'],$row['longi'],$row['loti'],$row['alamat'])
);
}
echo json_encode($data);
?>