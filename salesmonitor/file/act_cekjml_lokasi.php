<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$tanggal= isset($_GET['tanggal'])? $_GET['tanggal'] : '25/07/2013';
$jam1= isset($_GET['jam1'])? $_GET['jam1'] : '00:00';
$jam2=isset($_GET['jam2'])? $_GET['jam2'] : '23:59';
$user=isset($_GET['pengguna'])? $_GET['pengguna'] : 'dian';

$tanggal=str_replace('/', '-',$tanggal);
$tanggal= date('Y-m-d',strtotime($tanggal));

$searchSql = "select count(*) as jml from tr_lokasi where nama_pengguna='$user' and tanggal='$tanggal' and jam>='$jam1' and jam<='$jam2'";
$resultcount= mysql_query($searchSql) or die("gagal");	
$row = mysql_fetch_array($resultcount,MYSQL_ASSOC);
$count = $row['jml'];

echo $count;

?>