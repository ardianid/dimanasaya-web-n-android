<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$id =$_REQUEST['id'];

$sqldel="delete from ms_pengguna where nama_pengguna='$id'";
$result= mysql_query($sqldel) or die("gagal");

if ($result){
	echo "ok";
}else{ 
	echo "gagal"; 
}
	

?>