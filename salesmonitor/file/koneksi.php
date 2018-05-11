<?php

// Create connection
$koneksi=mysql_connect("localhost","root","");
mysql_select_db("salesmonitoring",$koneksi);

// Check connection
if (!$koneksi)
  {
  echo "Failed to connect to MySQL: " ;
  }
?>