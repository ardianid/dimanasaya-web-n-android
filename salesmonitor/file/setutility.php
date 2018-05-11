<?php
include("koneksi.php");

$nama=isset($_POST['nama']) ? $_POST['nama'] : 'kahfi';
$telp=isset($_POST['telp']) ? $_POST['telp'] : '+6281355175535';
$jarak=isset($_POST['jarak']) ? $_POST['jarak'] : '0';
$waktu=isset($_POST['waktu']) ? $_POST['waktu'] : '0';
$jnismonitor=isset($_POST['jnismonitor']) ? $_POST['jnismonitor'] : '0';
$ceksms=isset($_POST['ceksms']) ? $_POST['ceksms'] : '0';
$cektelp=isset($_POST['cektelp']) ? $_POST['cektelp'] : '0';


$sql="select count(*) as count from ms_pengguna where nama_pengguna='$nama' or notelp='$telp'";
$result= mysql_query($sql) or die("terjadi kesalahan saat eksekusi query");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if ($count >=1){
	
	$sqlup="update ms_pengguna set nama_pengguna='$nama',notelp='$telp',jarak='$jarak',waktu='$waktu',jnismonitor='$jnismonitor',ceksms='$ceksms',cektelp='$cektelp' where nama_pengguna='$nama' or notelp='$telp'";
	$hasil=mysql_query($sqlup);
	if ($hasil) {
		echo 1;
	}else{
		echo 0;
	}
	
}else{
	
	$sqlins="insert into ms_pengguna (notelp,jarak,waktu,jnismonitor,nama_pengguna,ceksms,cektelp) values($telp','$jarak','$waktu','$jnismonitor','$nama','$ceksms','$cektelp')";
	$hasil=mysql_query($sqlins);
	if ($hasil) {
		echo 1;
	}else{
		echo 0;
	}
	
}

?>