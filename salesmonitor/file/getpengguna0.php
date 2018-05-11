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
// Setup sort and search SQL using posted data
$sortSql = "order by $sortname $sortorder";
$searchSql = ($qtype != '' && $query != '') ? "where $qtype = '$query'" : '';
// Get total count of records
$sql = "select count(ms_pengguna.nama_pengguna)
from ms_pengguna inner join ms_golongan1 on ms_pengguna.kdgol1=ms_golongan1.kode
inner join ms_golongan2 on ms_pengguna.kdgol2=ms_golongan2.kode
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
$sql = "SELECT ms_pengguna.nama_pengguna,ms_pengguna.notelp,ms_golongan1.nama as namagol1,ms_golongan2.nama as namagol2,ms_pengguna.jnismonitor,
CASE ms_pengguna.jnismonitor
WHEN  'Jarak'
THEN ms_pengguna.jarak
ELSE ms_pengguna.waktu
END as rentang,ms_pengguna.kdgol1,ms_pengguna.kdgol2,ms_pengguna.aktif FROM ms_pengguna inner join ms_golongan1 on ms_pengguna.kdgol1=ms_golongan1.kode
inner join ms_golongan2 on ms_pengguna.kdgol2=ms_golongan2.kode
$searchSql
$sortSql
$limitSql";
$results = mysql_query($sql);
while ($row = mysql_fetch_assoc($results)) {
$data['rows'][] = array(
'id' => $row['nama_pengguna'],
'cell' => array($row['nama_pengguna'], $row['notelp'],$row['aktif'],$row['kdgol1'],$row['kdgol2'],$row['namagol1'],$row['namagol2'],
	$row['jnismonitor'],$row['rentang'])
);
}
echo json_encode($data);
?>