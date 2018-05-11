<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$nama=$_POST['nama'];
$pwd=$_POST['pwd'];
$pwdlama =$_POST['pwdlama'];

$sql_sel = "select count(*) as count from ms_usersys where nama_user='$nama' and password=MD5(PASSWORD('$pwdlama'))";

	$result =mysql_query($sql_sel) or die ("salah sql");
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	
	
	if ($count <1) {
		echo "pwdlama_salah";
	}else{


	$sql_insert="update ms_usersys set password=MD5(PASSWORD('$pwd')) where  nama_user='$nama'";
		$result= mysql_query($sql_insert) or die("gagal");		
		
		if ($result){
			echo "ok";
			}else{ echo "gagal"; }
			
	}

?>