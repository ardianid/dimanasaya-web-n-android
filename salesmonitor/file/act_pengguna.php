<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

$nama=$_POST['nama'];
$notelp=$_POST['telp'];
$aktif=$_POST['aktif'];
$tipe=$_POST['tipe'];
$jnismonitor=$_POST['jnismonitor'];
$jarak=$_POST['jarak'];
$waktu=$_POST['waktu'];
$kdgol1=$_POST['kdgol1'];
$kdgol2=$_POST['kdgol2'];


include("koneksi.php");


if ($tipe=='add'){
	
	$sql="select * from ms_pengguna where nama_pengguna='$nama' or notelp='$notelp'";
	$result =mysql_query($sql) or die ("salah sql");
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	
	if ($count >=1) {
		echo "gagal";
	}else{ 
	
		$sql_insert="insert into ms_pengguna (nama_pengguna,notelp,aktif,kdgol1,kdgol2,jnismonitor,jarak,waktu) values('$nama','$notelp','$aktif','$kdgol1','$kdgol2','$jnismonitor','$jarak','$waktu')";
		$result= mysql_query($sql_insert) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }
		
	}
	
}
elseif ($tipe=='edit') {
	
		$sql_update="update ms_pengguna set notelp='$notelp',aktif='$aktif',jnismonitor='$jnismonitor',jarak='$jarak',waktu='$waktu',kdgol1='$kdgol1',kdgol2='$kdgol2' where nama_pengguna='$nama'";
		$result= mysql_query($sql_update) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }

}


?>