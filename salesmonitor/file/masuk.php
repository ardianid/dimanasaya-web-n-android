<?php
include("koneksi.php");

$user=$_POST['tuser'];
$pwd=$_POST['tpwd'];

$sql="select count(*) as count from ms_usersys where nama_user='$user' and password=MD5(PASSWORD('$pwd'))";

$result =mysql_query($sql) or die ("salah sql");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if ($count >=1){

	session_start();
	
	$_SESSION['username']=$user;
		
			echo "file/menu.php";
	
}else{
echo "gagal";
}

?>
