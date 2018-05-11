<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$nama=$_POST['nama'];
$pwd=$_POST['pwd'];
$tipe=$_POST['tipe'];

if ($tipe=='add'){
	
	$sql="select * from ms_usersys where nama_user='$nama'";
	$result =mysql_query($sql) or die ("salah sql");
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	
	if ($count >=1) {
		echo "gagal";
	}else{ 
	
		$sql_insert="insert into ms_usersys (nama_user,password) values('$nama',MD5(PASSWORD('$pwd')))";
		$result= mysql_query($sql_insert) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }
		
	}
	
}
elseif ($tipe=='del') {
	
		$sql_update="delete from ms_usersys where nama_user='$nama'";
		$result= mysql_query($sql_update) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }

}



?>