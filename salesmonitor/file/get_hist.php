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
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '17/07/2013';
$jam1 = isset($_POST['jam1']) ? $_POST['jam1'] : '00:00';
$jam2 = isset($_POST['jam2']) ? $_POST['jam2'] : '23:59';

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
if (isset($_POST['pengguna'])) {
$pengguna = mysql_real_escape_string($_POST['pengguna']);
}
if (isset($_POST['tanggal'])) {
$tanggal = mysql_real_escape_string($_POST['tanggal']);
}
if (isset($_POST['jam1'])) {
$jam1 = mysql_real_escape_string($_POST['jam1']);
}
if (isset($_POST['jam2'])) {
$jam2 = mysql_real_escape_string($_POST['jam2']);
}

$tanggal=str_replace('/', '-',$tanggal);
$tanggal= date('Y-m-d',strtotime($tanggal));
$jam1=$jam1 .':00';
$jam2=$jam2 .':00';	

// Setup sort and search SQL using posted data
$sortSql = "order by $sortname $sortorder";
$searchSql = "where nama_pengguna='$pengguna' and tanggal='$tanggal' and jam>='$jam1' and jam<='$jam2'" ;
// Get total count of records
$sql = "select count(*)
from tr_lokasi
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
$sql = "select id,jam,longi,loti,alamat
from tr_lokasi
$searchSql
$sortSql
$limitSql";

$results = mysql_query($sql);
while ($row = mysql_fetch_assoc($results)) {
$data['rows'][] = array(
'id' => $row['id'],
'cell' => array($row['id'],$row['jam'], $row['longi'],$row['loti'],$row['alamat'])
);
}
echo json_encode($data);
?>