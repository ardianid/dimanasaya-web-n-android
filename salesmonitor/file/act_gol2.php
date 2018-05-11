<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$kode=$_POST['kode'];
$nama=$_POST['nama'];
$tipe=$_POST['tipe'];

if ($tipe=='add'){
	
	$sql="select * from ms_golongan2 where kode='$kode'";
	$result =mysql_query($sql) or die ("salah sql");
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	
	if ($count >=1) {
		echo "gagal";
	}else{ 
	
		$sql_insert="insert into ms_golongan2 (kode,nama) values('$kode','$nama')";
		$result= mysql_query($sql_insert) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }
		
	}
	
}
elseif ($tipe=='edit') {
	
		$sql_update="update ms_golongan2 set nama='$nama' where kode='$kode'";
		$result= mysql_query($sql_update) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }

}


?>