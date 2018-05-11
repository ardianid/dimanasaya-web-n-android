<?php
include("koneksi.php");

$nama = $_POST['nama'];
$notelp = $_POST['notelp'];

$sql="select * from ms_pengguna where (nama_pengguna='$nama' or notelp='$notelp')";
$result= mysql_query($sql) or die("0");

while($row=mysql_fetch_assoc($result))
$output[]=$row;
 print(json_encode($output));


?>
