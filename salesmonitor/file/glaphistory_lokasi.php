<?php
session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

//header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment;filename=Laporan Historical Lokasi.xlsx');
//header('Cache-Control: max-age=0');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once '../lib/Classes/PHPExcel.php';

include("koneksi.php");

$pengguna = isset($_POST['pengguna']) ? $_POST['pengguna'] : '';
$tgl1 = trim($_POST['tgl1']);
$tgl2 = trim($_POST['tgl2']);
$jam1 = trim($_POST['jam1']);
$jam2 = trim($_POST['jam2']);
$gol1 = trim($_POST['gol1']);
$gol2 = trim($_POST['gol2']);

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Ardian")
                             ->setLastModifiedBy("Ardian")
                             ->setTitle("Historical Lokasi")
                             ->setSubject("Historical Lokasi")
                             ->setDescription("Laporan Historical Lokasi")
                             ->setKeywords("Laporan Historical Lokasi")
                             ->setCategory("Historical Lokasi");
							 
//Tabel akan kita mulai dari Kolom B10 dan seterusnya
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Nama');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Gol I');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Gol II');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Tanggal');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Jam');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Lat');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Long');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Lokasi');

// Add some data
//echo date('H:i:s') , "Tampilkan Data History Lokasi" , EOL;


$tgl1=str_replace('/', '-',$tgl1);
$tgl1= date('Y-m-d',strtotime($tgl1));

$tgl2=str_replace('/', '-',$tgl2);
$tgl2= date('Y-m-d',strtotime($tgl2));

$jam1=$jam1 .':00';
$jam2=$jam2 .':00';	

$sql = "select b.nama_pengguna,c.nama as namagol1,d.nama as namagol2,a.longi,a.loti,a.tanggal,a.jam,a.alamat
	from tr_lokasi a inner join ms_pengguna b
	on a.nama_pengguna=b.nama_pengguna
	inner join ms_golongan1 c
	on b.kdgol1=c.kode
	inner join ms_golongan2 d
	on b.kdgol2=d.kode where a.tanggal >='$tgl1' and a.tanggal <='$tgl2' and a.jam>='$jam1' and a.jam<='$jam2'";

if ($pengguna !='') {
	$sql .= " and b.nama_pengguna like '%$pengguna%'";
}

if ($gol1 !='') {
	$sql .= " and c.kode='$gol1'";
}

if ($gol2 !='') {
	$sql .= " and d.kode='$gol2'";
}

//echo $sql;

$query = mysql_query($sql);

$i = 3;
$no= 1;
while($data=mysql_fetch_array($query)){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $data['nama_pengguna']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $data['namagol1']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $data['namagol2']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $data['tanggal']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $data['jam']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $data['longi']);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $data['loti']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $data['alamat']);
    $i++;
    $no++;
}

//Mengatur lebar cell pada documen excel
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);

// Set sheet yang aktif pada documen excel
$objPHPExcel->setActiveSheetIndex(0);
 
// Menambahkan file gambar pada document excel pada kolom B2
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Media Kreatif Indonesia');
$objDrawing->setDescription('Logo Media Kreatif');
$objDrawing->setPath('images/logo.jpg');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); */
 
//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


$objWriter->save("../download/Laporan Historical Lokasi.xlsx");
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
//$objWriter->save('php://output');
//echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
 
// Echo done
//echo date('H:i:s') , " Done writing file" , EOL;
echo "../download/Laporan Historical Lokasi.xlsx";

?>
