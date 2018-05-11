<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$page = 1;	// The current page
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = 'kode';	 // Sort column
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
$sql = "select count(*)
from ms_golongan1
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
$sql = "select kode,nama
from ms_golongan1
$searchSql
$sortSql
$limitSql";
$results = mysql_query($sql);
while ($row = mysql_fetch_assoc($results)) {
$data['rows'][] = array(
'id' => $row['kode'],
'cell' => array($row['kode'],$row['nama'])
);
}
echo json_encode($data);
?>