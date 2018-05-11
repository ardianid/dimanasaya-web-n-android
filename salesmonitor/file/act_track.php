<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

include("koneksi.php");

$tanggal= isset($_GET['tanggal'])? $_GET['tanggal'] : '26/07/2013';
$jam1= isset($_GET['jam1'])? $_GET['jam1'] : '00:00';
$jam2=isset($_GET['jam2'])? $_GET['jam2'] : '23:59';
$user=isset($_GET['pengguna'])? $_GET['pengguna'] : 'dian';

$tanggal=str_replace('/', '-',$tanggal);
$tanggal= date('Y-m-d',strtotime($tanggal));
$jam1=$jam1 .':00';
$jam2=$jam2 .':00';	

$sql="select longi,loti,jam from tr_lokasi where tanggal='$tanggal' and jam>='$jam1' and jam<='$jam2' and nama_pengguna='$user' order by jam asc";
$sqlcount = "select count(*) as jml from tr_lokasi where tanggal='$tanggal' and jam>='$jam1' and jam<='$jam2' and nama_pengguna='$user'";
$result= mysql_query($sql) or die("terjadi kesalahan saat eksekusi query");	

$resultcount= mysql_query($sqlcount) or die("terjadi kesalahan saat eksekusi query");	
$row = mysql_fetch_array($resultcount,MYSQL_ASSOC);
$count = $row['jml'];

if ($count >=1){
	while($row = mysql_fetch_array($result))
		{
			$results[] = array('longi' => $row['longi'],'loti' => $row['loti'],'jam' => $row['jam'],'jml'=> $count );
		}
}else{
	$results[] = array('longi' => '0','loti' => '0','jam' => '0','jml'=>'0');
}

echo json_encode($results);

?>