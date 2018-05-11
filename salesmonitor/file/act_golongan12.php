<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$id =$_REQUEST['id'];

$sqldel="delete from ms_golongan1 where kode='$id'";
$result= mysql_query($sqldel) or die("gagal");

if ($result){
	echo "ok";
}else{ 
	echo "gagal"; 
}
	

?>