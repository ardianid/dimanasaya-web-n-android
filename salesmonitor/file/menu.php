<?php

session_start();
if (!isset($_SESSION['username'])){
header('Location:../index.html');
}

//require_once('recaptchalib.php');
include("koneksi.php");


$query = "SELECT kode, nama FROM ms_golongan1 ORDER BY nama";
$sql = mysql_query($query) or die("gagal");
$arrgolongan1 = array();
while ($row = mysql_fetch_assoc($sql)) {
	$arrgolongan1 [ $row['kode'] ] = $row['nama'];
}

$query2 = "SELECT kode, nama FROM ms_golongan2 ORDER BY nama";
$sql2 = mysql_query($query2) or die("gagal");
$arrgolongan2 = array();
while ($row = mysql_fetch_assoc($sql2)) {	
	$arrgolongan2 [ $row['kode'] ] = $row['nama'];
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
		<title>Sales Monitoring</title>
        <meta charset="utf-8">
	

		<link rel="stylesheet" type="text/css" href="../css/flexigrid.css" />
		<link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/>
		<link rel="stylesheet" href="../themes/start/jquery-ui.css" type="text/css"/>
		<link rel="stylesheet" href="../themes/start/jquery.ui.all.css" type="text/css"/>
		<link rel="stylesheet" href="../css/jquery.timepicker.css" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="../css/jmenu.css" media="screen" />
		
		<script type="text/javascript" src="../lib/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../lib/jquery-ui.min.js"></script>
		
		<script src="../lib/jquery.ui.position.js"></script>
		<script src="../lib/jquery.ui.core.min.js"></script>
		<script src="../lib/jquery.ui.widget.min.js"></script>
		<script src="../lib/jquery.ui.position.js"></script>
		<script src="../lib/jquery.ui.tabs.js"></script>
		
		<script type="text/javascript" src="../lib/jquery.sizes.js"></script>
		<script type="text/javascript" src="../lib/jlayout.border.js"></script>
		<script type="text/javascript" src="../lib/jlayout.grid.js"></script>
		<script type="text/javascript" src="../lib/jlayout.flexgrid.js"></script>
		<script type="text/javascript" src="../lib/jlayout.flow.js"></script>
		<script type="text/javascript" src="../lib/jquery.jlayout.js"></script>
		<script type="text/javascript" src="../lib/jMenu.jquery.js"></script>
		
		<script type="text/javascript" src="../lib/flexigrid.js"></script>
		<script type="text/javascript" src="../lib/jquery.timepicker.js"></script>
		
		<script type="text/javascript" src="../lib/jquery.blockUI.js"></script>
		
		<script src="../lib/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
		<script src="../lib/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
		
		
		<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script src="../lib/gmap3.js" type="text/javascript"></script>

	
		<script type="text/javascript">
		
			jQuery(function($) {
							
				$.blockUI({
				 message: '<h3> <img src="../lib/wait16trans.gif" /> Just a moment... </h3>',
				 ccss: { 
            		border: 'none', 
           			 padding: '15px', 
           			 backgroundColor: '#000', 
           				 '-webkit-border-radius': '10px', 
          				  '-moz-border-radius': '10px', 
           			 opacity: .5, 
           			 color: '#fff' 
        			} }); 
 
        		setTimeout($.unblockUI, 3700);
								
				$( "#ttglc" ).datepicker({
					showOn: "button",
					buttonImage: "../themes/icon/calendar.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy'
				});
				
				$( "#ttgl1_hs" ).datepicker({
					showOn: "button",
					buttonImage: "../themes/icon/calendar.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy'
				});
				
				$( "#ttgl2_hs" ).datepicker({
					showOn: "button",
					buttonImage: "../themes/icon/calendar.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy'
				});
				
			//	$('#copyright').hide();
				
				//$('#ttglc').datepicker({ dateFormat: 'dd/mm/yy' });
				
				$('#tjamc1').timepicker({ 'timeFormat': 'H:i' });
				$('#tjamc2').timepicker({ 'timeFormat': 'H:i' });
				
				$('#tjam1_hs').timepicker({ 'timeFormat': 'H:i' });
				$('#tjam2_hs').timepicker({ 'timeFormat': 'H:i' });
				
				var currentdate = new Date(); 
					var tanggal=currentdate.getDate();
					if (tanggal <=9){
						tanggal = "0"+ tanggal; }
				
					var bulan= parseInt(currentdate.getMonth())+ 1;
					if (bulan <=9){
						bulan = "0"+ bulan; }
				
					$('#ttglc').val(tanggal + "/"+  (bulan)
  						 + "/" + currentdate.getFullYear());
					
					$('#ttgl1_hs').val('01/01/2013');
					$('#ttgl2_hs').val('01/01/2013');
					
					$('#tjamc1').val("00:00");
					$('#tjamc2').val("23:59");
					
					$('#tjam1_hs').val("00:00");
					$('#tjam2_hs').val("23:59");
					
					$('#jmlakhir').val('0');
					
				var refreshdoc;
				
				function endis_ajax(stat) {
					if (stat=='1'){
						refreshdoc =setInterval(
							function (){
								ajaxreload();
							}, 5000);
					}else{
						clearInterval(refreshdoc);
					}
				}
				
				function clearmap(){
					$('#tabs1').gmap3({clear:"marker"});
					$('#tabs1').gmap3({clear:"directionsrenderer"
							});
					$('#istrack').val('0');

				}
				
				function ajaxreload() {
					
					var namapengguna=$('#penggunaterpilih').val();
					var vtanggal = $('#ttglc').val();
					var vjam1 = $('#tjamc1').val();
					var vjam2 = $('#tjamc2').val();
					
					if (namapengguna=='') { return; }
					
					$.ajax({
							type : "GET",
							url : "act_cekjml_lokasi.php",
							data :{tanggal: vtanggal, jam1: vjam1, jam2: vjam2,pengguna: namapengguna },
							success: function(data) {
								if (data !="gagal"){
									
									var jmlsekarang = data;
									var jmllalu = $('#jmlakhir').val();
									
									if (jmllalu=='') { jmllalu=0; }
									
									if (jmlsekarang > jmllalu) {
										
										$('#jmlakhir').val(jmlsekarang);
										
										var namapengguna=$('#penggunaterpilih').val();
										var tanggal = $('#ttglc').val();
										var jam1 = $('#tjamc1').val();
										var jam2 = $('#tjamc2').val();
						
										var query = [{name: 'pengguna', value:namapengguna},{name: 'tanggal', value:tanggal},{name: 'jam1', value:jam1},{name: 'jam2', value:jam2}];
				
										$('#flexme5').flexOptions({params :query}).flexReload();
						
										setloc('1',namapengguna,tanggal,jam1,jam2);
											
												
									}
									
									
								}
							}
						})
					
				}
				
				$("#dialogkonfirm").dialog({
						autoOpen: false,
						height: 100,
						modal: true
					}).css("font-size", "12px");
				
				$("#dialogadd").dialog({
						autoOpen: false,
						height: 430,
						width: 320,
						modal: true
					}).css("font-size", "12px");
					
					
				$("#rubahpwd_dlg").dialog({
						autoOpen: false,
						height: 200,
						width: 400,
						modal: true
					}).css("font-size", "12px");
					
				$("#dialoggol1").dialog({
						autoOpen: false,
						height: 250,
						width: 320,
						modal: true
					}).css("font-size", "12px");
					
				$("#dialoggol2").dialog({
						autoOpen: false,
						height: 250,
						width: 320,
						modal: true
					}).css("font-size", "12px");
				
				$("#dialoguss").dialog({
						autoOpen: false,
						height: 280,
						width: 350,
						modal: true
					}).css("font-size", "12px");
				
				$("#settingsearch").dialog({
						autoOpen: false,
						height: 170,
						width: 275,
						modal: true
					}).css("font-size", "12px");
				
				$("#setsearch_history").dialog({
						autoOpen: false,
						height: 315,
						width: 307,
						modal: true
					}).css("font-size", "12px");
				
				$("#konfirmdelpengguna").dialog({
						autoOpen: false,
						height: 125,
						modal: true
					}).css("font-size", "12px");
				
				$("#delgol1").dialog({
									autoOpen: false,
									height: 125,
									modal: true
				}).css("font-size", "12px");
				
				$("#delgol2").dialog({
									autoOpen: false,
									height: 125,
									modal: true
				}).css("font-size", "12px");
				
				$("#konfirmrubahpwd").dialog({
									autoOpen: false,
									height: 120,
									modal: true
				}).css("font-size", "12px");
				
				$("#konfrubahtidaksama").dialog({
									autoOpen: false,
									height: 120,
									modal: true
				}).css("font-size", "12px");
				
				
				$("#konftidakberhasil").dialog({
									autoOpen: false,
									height: 120,
									modal: true
				}).css("font-size", "12px");
				
				$("#del_uss").dialog({
									autoOpen: false,
									height: 125,
									modal: true
				}).css("font-size", "12px");
				
				$("#konfirmadduss").dialog({
									autoOpen: false,
									height: 120,
									modal: true
				}).css("font-size", "12px");
				
				$("#formadd").submit(function(){
							
							var vnama= $("#nama").val();
							var vtelp= $("#telp").val();
							var vaktif= $("#aktif").val();
							var vtipe= $("#tipe").val();
							var vgol1 = $("#cbgol1").val();
							var vgol2 = $("#cbgol2").val();
							var vjnismonitor = $("#cbjenismonitor").val();
								vjnismonitor = vjnismonitor.trim();
							var vrentang = $("#trentang").val();
							
							var vjarak;
							var vwaktu;
							
							if (vjnismonitor=='Jarak') {
								vjarak = vrentang;
								vwaktu= 0;
								}else {
								vjarak=0;
								vwaktu=vrentang;
								}
							
						//	challengeField = $("input#recaptcha_challenge_field").val();
						//    responseField = $("input#recaptcha_response_field").val();
							
						//	var html = $.ajax({
						 //       type: "POST",
						  //      url: "capchame.php",
						   //     data:{changefield: challengeField ,responsefield: responseField},
						    //    async: false
						     //   }).responseText;
						
						//	if(html == "success") {
								
								$.ajax({
									type : "POST",
									url : "act_pengguna.php",
									data :{ nama: vnama, telp: vtelp, aktif: vaktif, tipe: vtipe, jnismonitor: vjnismonitor, jarak: vjarak, waktu:  vwaktu,kdgol1: vgol1, kdgol2: vgol2 },
									success: function(data) {
									if (data !="gagal") {
										$(idtabpengguna).flexReload();
										$('#flexme4').flexReload();
										$('#dialogadd').dialog('close');
									}else{
										$('#konftidakberhasil').dialog('open');
										//alert ("data gagal");
									}}
								})
					
							//} else {
								//alert(html);
								//logconfirm = "Security code salah. coba lagi";
								//$('#dialogkonfirm').dialog('open');
						        //$("#captcha-status").html("<p class=\"red bold\">Security code salah. coba lagi.</p>");
						        //Recaptcha.reload();
						   // }
							
								
					return false;
				});
				
				$("#formgol1add").submit(function(){
				
							var vkode= $("#kodegol").val();
							var vnama= $("#namagol").val();
							var vtipe= $("#tipegol1").val();
							
							$.ajax({
								type : "POST",
								url : "act_gol1.php",
								data :{ kode: vkode, nama: vnama, tipe: vtipe },
								success: function(data) {
								if (data !="gagal") {
									$(idtabgol1).flexReload();
									$('#dialoggol1').dialog('close');
								}else{
									$('#konftidakberhasil').dialog('open');
									//alert ("data gagal");
								}}
							})
					
					return false;
				});
				
				
				$("#frubah_pwd").submit(function(){
					
					var user_rbh =  "<?php echo $_SESSION['username']; ?>";
					var pwd = $("#tpwd_lama").val();
					var pwd_baru = $("#tpwd_baru").val();
					var pwd_baru2 = $("#tpwd_baru2").val();
					
					if (pwd_baru != pwd_baru2){
								$('#konfirmadduss').dialog('open');
					}else{
							
							$.ajax({
								type : "POST",
								url : "act_rpwd.php",
								data :{ nama: user_rbh, pwd: pwd_baru, pwdlama: pwd },
								success: function(data) {
								if (data =="ok") {
									$('#konfirmrubahpwd').dialog('open');
									$('#rubahpwd_dlg').dialog('close');
								}else if (data =="pwdlama_salah") {
									$('#konfrubahtidaksama').dialog('open');
								}else{
									$('#konftidakberhasil').dialog('open');
									//alert ("data gagal");
								}}
							})
							
					}					
							
					return false;
					
				});
				
				$("#formgol2add").submit(function(){
				
							var vkode= $("#kodegol2").val();
							var vnama= $("#namagol2").val();
							var vtipe= $("#tipegol2").val();
							
							$.ajax({
								type : "POST",
								url : "act_gol2.php",
								data :{ kode: vkode, nama: vnama, tipe: vtipe },
								success: function(data) {
								if (data !="gagal") {
									$(idtabgol2).flexReload();
									$('#dialoggol2').dialog('close');
								}else{
									$('#konftidakberhasil').dialog('open');
									//alert ("data gagal");
								}}
							})
					
					return false;
				});
				
				$("#formuss").submit(function(){
				
							var vnama= $("#namauss").val();
							var vpwd= $("#pwduss").val();
							var vpwd2= $("#pwduss2").val();
							var vtipe= 'add';
							
							if (vpwd != vpwd2){
								$('#konfirmadduss').dialog('open');
							}else{
							
							$.ajax({
								type : "POST",
								url : "act_user.php",
								data :{ nama: vnama, pwd: vpwd, tipe: vtipe },
								success: function(data) {
								if (data !="gagal") {
									$(idtab_uss).flexReload();
									$('#dialoguss').dialog('close');
								}else{
									$('#konftidakberhasil').dialog('open');
									//alert ("data gagal");
								}}
							})
							
							}					
							
					return false;
					
				}); 
				
				jQuery("#formadd").validationEngine();
				jQuery("#formgol1add").validationEngine();
				jQuery("#formgol2add").validationEngine();
				jQuery("#formuss").validationEngine();
				//jQuery("#frubah_pwd").validationEngine();
				
				$("#btsimpan").button();
				$("#btcancel").button();
				$("#btok").button();
				$("#btno").button();
				$("#btcari").button();
				$("#btclose_sett").button();
				$("#bttrack").button();
				
				$("#btload_hs").button();
				$("#btclose_hs").button();
				
				$("#btsimpan_gol1").button();
				$("#btcancel_gol1").button();
				
				$("#btsimpan_gol2").button();
				$("#btcancel_gol2").button();
				
				$("#btok_delgol1").button();
				$("#btno_delgol1").button();
				
				$("#btok_delgol2").button();
				$("#btno_delgol2").button();
				
				$("#btsimpan_uss").button();
				$("#btcancel_uss").button();
				
				$("#btok_deluss").button();
				$("#btno_deluss").button();
				
				$("#btok_rb").button();
				$("#rb_closebtn").button();
				
				$("#btok_konfuss").button();
				
				$('#btcari').button({
					 icons: {primary: 'ui-icon-search', secondary: null}
				  });
				
				$('#bttrack').button({
					 icons: {primary: 'ui-icon-search', secondary: null}
				  });
				
				$('#btload_hs').button({
					 icons: {primary: 'ui-icon-search', secondary: null}
				  });
				
				$("#bttrack").click(function(){
					
					var namapengguna=$('#penggunaterpilih').val();
					var tanggal = $('#ttglc').val();
					var jam1 = $('#tjamc1').val();
					var jam2 = $('#tjamc2').val();
					
					var query = [{name: 'pengguna', value:namapengguna},{name: 'tanggal', value:tanggal},{name: 'jam1', value:jam1},{name: 'jam2', value:jam2}];
				
					$('#flexme5').flexOptions({params :query}).flexReload();
					
					trackmapx();
					
					$('#settingsearch').dialog('close');
					
				});
				
				$("#btload_hs").click(function(){
					
					var namapengguna=$('#tnama_hs').val();
					var vtgl1 = $('#ttgl1_hs').val();
					var vtgl2 = $('#ttgl2_hs').val();
					var vjam1 = $('#tjam1_hs').val();
					var vjam2 = $('#tjam2_hs').val();
					var vgol1 = $('#cbgol1_hs').val();
					var vgol2 = $('#cbgol2_hs').val();
					
					
					var query = [{name: 'pengguna', value:namapengguna},{name: 'tgl1', value:vtgl1},{name: 'tgl2', value:vtgl2},{name: 'jam1', value:vjam1},{name: 'jam2', value:vjam2},{name: 'gol1', value:vgol1},{name: 'gol2', value:vgol2}];
				
					$(idtab_hist).flexOptions({params :query}).flexReload();
					
					//trackmapx();
					
					$('#setsearch_history').dialog('close');
					
				});
				
				function trackmapx(){
					
					clearmap();
					
					$('#istrack').val('1');
					
					endis_ajax('0');
					
					
					var i=0;
					var i2=1;
					var pathmark;
					var pathmarkold;
					var jum=0;
					
					var lgi,lti;
					var lgi_old,lti_old;
					
					var namapenggunax=$('#penggunaterpilih').val();
					var tanggalx = $('#ttglc').val();
					var jam1x = $('#tjamc1').val();
					var jam2x = $('#tjamc2').val();
					
					
					$.ajax({
							type:"GET",
							url:"act_track.php",
							datatype: "json",
							data: {tanggal: tanggalx,jam1: jam1x,jam2: jam2x,pengguna: namapenggunax},
							success:function(data){

								$.each($.parseJSON(data), function() {
								
									i = i + 1;
									
									if (i==1){
									
										lgi = this.longi;
										lti = this.loti;
									
										jum=this.jml;	
									
									}
									
									if (i>1) {
										lgi_old = lgi;
										lti_old = lti;
										
										lgi = this.longi;
										lti = this.loti;
									}
									
								/*	var marker = new Object();
										marker.lat = this.longi;
										marker.lng = this.loti;
										marker.options = new Object();
										marker.options.icon = new google.maps.MarkerImage(pathmark);
										markers.push(marker);
										
									$("#tabs1").gmap3({
										marker:{
											values:markers,
											options: {
												draggable: false,
												//icon:new google.maps.MarkerImage(pathmark)
												}
										},
										autofit:{},
									}); */
									
									//alert(JSON.stringify(marker));
									
									if (i>1){
										
									$("#tabs1").gmap3({
										/*map: {
										options:{
											zoom : 10,
											center:[lgi,lti]
										}
										},*/
										getroute:{
											options:{
												origin:[lgi_old,lti_old],
												destination:[lgi,lti],
												travelMode: google.maps.DirectionsTravelMode.DRIVING
											},
											callback: function(results){
												
												var legs = results.routes[0].legs[0];
												var startPos = legs.start_location;
												var endPos = legs.end_location;
													//latLng for start and end of directions
												var startLatLng = [startPos.lat(), startPos.lng()];
												var endLatLng = [endPos.lat(), endPos.lng()];

												i2 = i2 +1;
												
												pathmark="http://maps.google.com/mapfiles/marker.png";
												pathmarkold="http://maps.google.com/mapfiles/marker.png";
												
												if (i2==2) {
													pathmarkold="http://maps.google.com/mapfiles/marker_greenA.png";
												}
										
												if (i2 == jum) {
													pathmarkold="http://maps.google.com/mapfiles/marker.png";
													pathmark="http://maps.google.com/mapfiles/marker_greenB.png";
												}
												
												if (jum==2) {
													pathmarkold="http://maps.google.com/mapfiles/marker_greenA.png";
													pathmark="http://maps.google.com/mapfiles/marker_greenB.png";
												}
												
												if (!results) return;

													/*if (!jQuery("#dircontainer").length>0) {
														jQuery("<div id='dircontainer' class='googlemap'></div>").insertAfter("#googleMap");
													}
													else {
														jQuery("#dircontainer").html("");
													}*/
													
													 $(this).gmap3({
														directionsrenderer:{
															container: $(document.createElement("div")).addClass("googlemap").insertAfter($("#test")) ,
														options:{
															//preserveViewport: true,
															//draggable: false,
															suppressMarkers: true,
															directions:results
														} 
														}
													}, {
														marker:{
															
														latLng : startPos,
														options : {
															draggable: false,
															icon : pathmarkold
														}}
													}, {
														marker:{
															
														latLng : endPos,
														options : {
															draggable: false,
															icon : pathmark
													}} 
													}); 
											}
											}
											}); 
									} 

									
								});

							} 
						}); 
					
				}  
				
				
				$("#btcari").click(function(){
					
					clearmap();
					
					var namapengguna=$('#penggunaterpilih').val();
					var tanggal = $('#ttglc').val();
					var jam1 = $('#tjamc1').val();
					var jam2 = $('#tjamc2').val();
					
					var query = [{name: 'pengguna', value:namapengguna},{name: 'tanggal', value:tanggal},{name: 'jam1', value:jam1},{name: 'jam2', value:jam2}];
				
					$('#flexme5').flexOptions({params :query}).flexReload();
					
					setloc('1',namapengguna,tanggal,jam1,jam2);
					
					$('#settingsearch').dialog('close');
					
				});
				
				$("#btclose_sett").click(function(){
					$('#settingsearch').dialog('close');
				});
				
				$("#btclose_hs").click(function(){
					$('#setsearch_history').dialog('close');
				});
				
				$("#btcancel").click(function(){
					$('#dialogadd').dialog('close');
				});
				
				$("#btcancel_gol1").click(function(){
					$('#dialoggol1').dialog('close');
				});
				
				$("#btcancel_gol2").click(function(){
					$('#dialoggol2').dialog('close');
				});
				
				$("#btcancel_uss").click(function(){
					$('#dialoguss').dialog('close');
				});
				
				$("#btno").click(function(){
					$('#konfirmdelpengguna').dialog('close');
				});
				
				$("#btno_delgol1").click(function(){
					$('#delgol1').dialog('close');
				});
				
				$("#btno_delgol2").click(function(){
					$('#delgol2').dialog('close');
				});
				
				$("#btno_deluss").click(function(){
					$('#del_uss').dialog('close');
				});
				
				$("#btok_konfuss").click(function(){
					$('#konfirmadduss').dialog('close');
				});
				
				$("#rb_closebtn").click(function(){
					$('#rubahpwd_dlg').dialog('close');
					return false;
				});
				
				$("#btok").click(function(){
				
						var iddelete = $("#iddel").val();
						
						$.ajax({
							type : "GET",
							url : "act_pengguna2.php",
							data :"id="+iddelete,
							success: function(data) {
						if (data !="gagal") {
							$(idtabpengguna).flexReload();
							$('#flexme4').flexReload();
							$('#konfirmdelpengguna').dialog('close');
							}else{
								$('#konftidakberhasil').dialog('open');
								//alert ("data gagal");
							}}
						})
				});
				
				$("#btok_delgol1").click(function(){
				
						var iddelete = $("#iddelgol1").val();
						
						$.ajax({
							type : "GET",
							url : "act_golongan12.php",
							data :"id="+iddelete,
							success: function(data) {
						if (data !="gagal") {
							$(idtabgol1).flexReload();
							$('#delgol1').dialog('close');
							}else{
								$('#konftidakberhasil').dialog('open');
								//alert ("data gagal");
							}}
						})
				});
				
				$("#btok_delgol2").click(function(){
				
						var iddelete = $("#iddelgol2").val();
						
						$.ajax({
							type : "GET",
							url : "act_golongan22.php",
							data :"id="+iddelete,
							success: function(data) {
						if (data !="gagal") {
							$(idtabgol2).flexReload();
							$('#delgol2').dialog('close');
							}else{
								$('#konftidakberhasil').dialog('open');
								//alert ("data gagal");
							}}
						})
				});
				
				$("#btok_deluss").click(function(){
				
						var iddeluss2 = $("#iddeluss").val();
						
						$.ajax({
							type : "GET",
							url : "act_user2.php",
							data :"id="+iddeluss2,
							success: function(data) {
							if (data !="gagal") {
								$(idtab_uss).flexReload();
								$('#del_uss').dialog('close');
							}else{
								$('#konftidakberhasil').dialog('open');
								//alert ("data gagal");
							}}
						})
				});
				
				$('.checkIt').bind('click', function() {
        			if($(this).is(":checked")) {
            			$('#aktif').val('1');
        			} else {
						$('#aktif').val('0');
        			}
    			});
				
				$('#flexme4').click(function(event){
    				$('.trSelected', this).each( function(){
						//alert($(this).attr('id').substr(3));
						$('#penggunaterpilih').val($(this).attr('id').substr(3));
						
						var currentdate = new Date(); 
						var tanggal=currentdate.getDate();
						if (tanggal <=9){
							tanggal = "0"+ tanggal; }
				
						var bulan= parseInt(currentdate.getMonth())+ 1;
							if (bulan <=9){
							bulan = "0"+ bulan; }
				
						$('#ttglc').val(tanggal + "/"+  (bulan)
  							 + "/" + currentdate.getFullYear());
				
						$('#tjamc1').val("00:00");
				
						$('#tjamc2').val("23:59");
						
						$('#jmlakhir').val('0');
						//endis_ajax('1');
						
						var namapengguna=$('#penggunaterpilih').val();
						var tanggal = $('#ttglc').val();
						var jam1 = $('#tjamc1').val();
						var jam2 = $('#tjamc2').val();
						
						var query = [{name: 'pengguna', value:namapengguna},{name: 'tanggal', value:tanggal},{name: 'jam1', value:jam1},{name: 'jam2', value:jam2}];
				
						$('#flexme5').flexOptions({params :query}).flexReload();
						
						setloc('1',namapengguna,tanggal,jam1,jam2);
						
					});
				});
				
				$('#flexme5').click(function(event){
				$.each($('.trSelected', this),
                            function(key, value){
							
						var pengguna =$('#penggunaterpilih').val();
						var tanggal = $('#ttglc').val();
						var jam = value.children[1].innerText;
						var longi = value.children[2].innerText;
						var loti = value.children[3].innerText;
						
						endis_ajax('1');
						
						if ($('#istrack').val()=='1') {
						
							$('#tabs1').gmap3({
								map:{
									options:{
									center:[longi,loti],	
									zoom: 15
								}
							}
							});
						
						}else{
							setloc2(longi,loti,pengguna,tanggal,jam);
						}
						
						
							
				});
				});
				
				
				
				$('#tabs1').gmap3({
				  map:{
            		options:{
					center:[-5.441717,105.301616],	
             			 zoom: 15
           			 		}
          				}
        			});
				
				function setloc(searchi,namapenggunax,tanggalx,jam1x,jam2x){
					
					if (searchi=='1'){
					
						$.ajax({
							type:"GET",
							url:"get_lastacc.php",
							datatype: "json",
							data: {tanggal: tanggalx,jam1: jam1x,jam2: jam2x,pengguna: namapenggunax},
							success:function(data){
								
								$.each($.parseJSON(data), function() {
									if (this.longi==0){
										}else{
									setloc2(this.longi,this.loti,namapenggunax,tanggalx,this.jam);
									}
								});
								
								
								
							} 
						}); 
					}	
				}
				
				
				function setloc2(longi,loti,nama,tanggal,jam){
					
					if ($('#istrack').val()=='1'){ return; }
					
					clearmap();
					
					$("#tabs1").gmap3({
						marker:{
						latLng: [longi,loti],
					options:{
						draggable:false,
						icon: "../themes/icon/male-2.png"
							},
					events:{
						mouseover: function(marker){
						$(this).gmap3({
						getaddress:{
							latLng:[longi,loti],
							callback:function(results){
							var map = $(this).gmap3("get"),
							infowindow = $(this).gmap3({get:"infowindow"}),
							content = results && results[1] ? results && results[1].formatted_address : "no address";
							var isi = "<h3> Info </h3>" + "<hr>" 
								+ "Nama" + "\t\t\t" + " : " + nama 
								+ "<br />" + "LatLong" + "\t\t\t" + " : " + longi + "," + loti 
								+ "<br />" + "Tgl" + "\t\t\t" + " : " + tanggal 
								+ "<br />" + "Jam" + "\t\t\t" + " : " +  jam 
								+ "<br />" + "Lokasi" + "\t\t\t" + " : " + content ;
							
							if (infowindow){
								infowindow.open(map, marker);
								infowindow.setContent(isi);
							} else {
								$(this).gmap3({
								infowindow:{
								anchor:marker, 
								options:{content: isi}
											}
											});
									}
													}
									}
									});
												},
						mouseout: function(){
							var infowindow = $(this).gmap3({get:{name:"infowindow"}});
							if (infowindow){
								infowindow.close();
											}
							}
							}
								},
					map:{
						options:{
						zoom: 15,
						center:[longi,loti]
					}}
					});
					
					
				} 
				
				
			//	var yourStartLatLng = new google.maps.LatLng(-5.440595, 105.272785);
              //  $('#tabs1').gmap({'center': yourStartLatLng,'zoom':15});
			
				var container = $('body'),
					west = $('body > .west'),
					east = $('body > .east'),
					center = $('body > .center');

				
				function layout() {
					container.layout();
					$('#accordion').accordion('resize');

					// This ensures that the center is never smaller than 400 pixels.
					east.resizable('option', 'maxWidth', (center.width() + east.width()) - 400);
					west.resizable('option', 'maxWidth', (center.width() + west.width()) - 400);
				}

				// Make the west and east panels resizable
				west.resizable({
					handles: 'e',
					stop: layout,
					helper: 'ui-resizable-helper-west',
					minWidth: 200
				});

				east.resizable({
					handles: 'w',
					stop: layout,
					helper: 'ui-resizable-helper-east',
					minWidth: 200
				});
				
				// Lay out the west panel first
				west.layout();
				
				// Then do the main layout.
				layout();

				// Hook up the re-layout to the window resize event.
				$(window).resize(layout);



				/**
				 * Below here is all demo code, which has no relation to the layout.
				 */
				$('#accordion').accordion({header: 'h3', fillSpace: true});

				// Set up the tabs in the center panel and remove the unwanted corners class
				//center.tabs();
				var tabs = $( "#tabs" ).tabs();
				//center.children('ul').removeClass('ui-corner-all');

				$.extend($.ui.slider.defaults, {
					range: "min",
					animate: true,
					orientation: "vertical"
				});

				$("#eq > span").each(function() {
					var value = parseInt($(this).text());
					$(this).empty();
					$(this).slider({
						value: value
					});
				});				

				$("#progressbar").progressbar({
					value: 59
				});

				var tabCounter = 2,
					idtabgol1,
					idtabgol2,
					idtabpengguna,
					idtab_uss,
					idtab_hist;
				
				// close icon: removing the tab on click
				tabs.delegate( "span.ui-icon-close", "click", function() {
					var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
					$( "#" + panelId ).remove();
					tabs.tabs( "refresh" );
				});

				tabs.bind( "keyup", function( event ) {
					if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
						var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
						$( "#" + panelId ).remove();
						tabs.tabs( "refresh" );
					}
				});
				
				$('#mgol1').click(function() {
					
					
				var	 id2 = "tabl-" + tabCounter,
						id = "tablx-" + tabCounter,
						li =$("<li style=><a href=#" + id2 + ">" + 'Master Golongan I' + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
					
					tabs.find( ".ui-tabs-nav" ).append( li );
					tabs.append("<div id='" + id2 + "'> <table border=1 id='"+ id +"' style='display:none'></table> </div>" );
					tabs.tabs( "refresh" );
					
					idtabgol1="#" +id;
					setgrid_gol1("#" +id);
					
					tabCounter++;
					
				});
				
				$('#mgol2').click(function() {
					
					
				var	 id2 = "tab2-" + tabCounter,
						id = "tab2x-" + tabCounter,
						li =$("<li><a href=#" + id2 + ">" + 'Master Golongan II' + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
					
					tabs.find( ".ui-tabs-nav" ).append( li );
					tabs.append("<div id='" + id2 + "'> <table border=1 id='"+ id +"' style='display:none'></table> </div>" );
					tabs.tabs( "refresh" );
					
					idtabgol2="#" +id;
					setgrid_gol2("#" +id);
					
					tabCounter++;
					
				});
				
				$('#mpengguna').click(function() {
					
					
				var	 id2 = "tabpg-" + tabCounter,
						id = "tabpgx-" + tabCounter,
						li =$("<li><a href=#" + id2 + ">" + 'Pengguna' + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
					
					tabs.find( ".ui-tabs-nav" ).append( li );
					tabs.append("<div id='" + id2 + "'> <table border=1 id='"+ id +"' style='display:none'></table> </div>" );
					tabs.tabs( "refresh" );
					
					idtabpengguna="#" +id;
					setgrid_pengguna("#" +id);
					
					tabCounter++;
					
				});
				
				$('#muser').click(function() {
					
					
				var	 id2 = "tabuss-" + tabCounter,
						id = "tabussx-" + tabCounter,
						li =$("<li><a href=#" + id2 + ">" + 'User' + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
					
					tabs.find( ".ui-tabs-nav" ).append( li );
					tabs.append("<div id='" + id2 + "'> <table border=1 id='"+ id +"' style='display:none'></table> </div>" );
					tabs.tabs( "refresh" );
					
					idtab_uss="#" +id;
					setgrid_user("#" +id);
					
					tabCounter++;
					
				});
				
				$('#mgantipwd').click(function() {
					$('#tpwd_lama').val('');
					$('#tpwd_baru').val('');
					$('#tpwd_baru2').val('');
								
					$('#rubahpwd_dlg').dialog('open');
					
				});
				
				
				$('#mllokasi').click(function() {
					
					
				var	 id2 = "tabhist-" + tabCounter,
						id = "tabhistx-" + tabCounter,
						li =$("<li><a href=#" + id2 + ">" + 'History Lokasi' + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
					
					tabs.find( ".ui-tabs-nav" ).append( li );
					tabs.append("<div id='" + id2 + "'> <table border=1 id='"+ id +"' style='display:none'></table> </div>" );
					tabs.tabs( "refresh" );
					
					idtab_hist="#" +id;
					setgrid_hist("#" +id);
					
					tabCounter++;
					
				});
				
				$('#mlogout').click(function() {
					$.ajax({
						url : "logut.php",
						success: function(data) {
						if (data !="gagal") {
							location.href = data;
						}}
						})
				});
				
				function setgrid_hist(id2) {
				
					var namapengguna=$('#tnama_hs').val();
					var vtgl1 = $('#ttgl1_hs').val();
					var vtgl2 = $('#ttgl2_hs').val();
					var vjam1 = $('#tjam1_hs').val();
					var vjam2 = $('#tjam2_hs').val();
					var vgol1 = $('#cbgol1_hs').val();
					var vgol2 = $('#cbgol2_hs').val();
					
					var query = [{name: 'pengguna', value:namapengguna},{name: 'tgl1', value:vtgl1},{name: 'tgl2', value:vtgl2},{name: 'jam1', value:vjam1},{name: 'jam2', value:vjam2},{name: 'gol1', value:vgol1},{name: 'gol2', value:vgol2}];
					
					$(id2).flexigrid({
		                url : 'get_laphistory.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Nama',
                	    name : 'nama_pengguna',
            	        width : 175,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Gol I',
                	    name : 'namagol1',
            	        width : 75,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Gol II',
                	    name : 'namagol2',
            	        width : 75,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Tanggal',
                	    name : 'tanggal',
            	        width : 75,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Jam',
                	    name : 'jam',
            	        width : 50,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Longitude',
                	    name : 'longi',
            	        width : 80,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Latitude',
                	    name : 'loti',
            	        width : 80,
        	            sortable : true,
    	                align : 'left'
	                    },
						{
                    	display : 'Lokasi',
                	    name : 'alamat',
            	        width : 250,
        	            sortable : true,
    	                align : 'left'
	                    }],
						buttons : [ {
		                    name : 'Search',
							bimage: '../css/images/sett.png',
		                    bclass : 'srcc',
		                    onpress : onpress_search
	                    },
						{
		                    name : 'Export To Excell',
							bimage: '../css/images/savetoexcell.png',
		                    bclass : 'ExpToexcel',
		                    onpress : onpress_search
	                    }],
							singleSelect: true,
			                sortname : "nama_pengguna",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
							params: [query],
							striped:true,
							height:460
							
           				 });
						 
					function onpress_search(com, grid) {
                		if (com == 'Search') {
						
								
								$('#tnama_hs').val('');
								$('#cbgol1_hs').val('');
								$('#cbgol2_hs').val('');
								
								$('#setsearch_history').dialog('open');
								
								
                	}
					 else if (com == 'Export To Excell') {
					 		
							var namapengguna_hl=$('#tnama_hs').val();
							var vtgl1_hl = $('#ttgl1_hs').val();
							var vtgl2_hl = $('#ttgl2_hs').val();
							var vjam1_hl = $('#tjam1_hs').val();
							var vjam2_hl = $('#tjam2_hs').val();
							var vgol1_hl = $('#cbgol1_hs').val();
							var vgol2_hl = $('#cbgol2_hs').val(); 
							
							
							$.ajax({
								type : "POST",
						        url: "glaphistory_lokasi.php", 
        						data: {pengguna: namapengguna_hl,tgl1: vtgl1_hl,tgl2: vtgl2_hl,jam1: vjam1_hl,jam2: vjam2_hl,gol1: vgol1_hl,gol2: vgol2_hl}, 
       							 success: function(response){
						          //  alert(response);
									
								window.location.href = response;
									
						        }
						    })
							
						
					 }
					}
						 
					
				}
				// sampe sini history
				
				function setgrid_user(id2) {
					$(id2).flexigrid({
		                url : 'getusys.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Nama',
                	    name : 'nama_user',
            	        width : 500,
        	            sortable : true,
    	                align : 'left'
	                    }],
                	buttons : [ {
                    name : 'Add',
					bimage: '../css/images/add.png',
                    bclass : 'add',
                    onpress : onpressuser
                    }
                    ,
                    {
                        name : 'Delete',
						bimage: '../css/images/close.png',
                        bclass : 'delete',
                        onpress : onpressuser
                    }
                    ,
                    {
                        separator : true
                    } 
                	],searchitems : [
							{display: 'Nama', name : 'nama_user', isdefault: true}
						],
							singleSelect: true,
			                sortname : "nama_user",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
							striped:true,
							height:460
							
           				 });
					
			function onpressuser(com, grid) {
                if (com == 'Delete') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
								
								var vnama =  value.firstChild.innerText;
								$("#iddeluss").val(vnama);
								
								$('#del_uss').dialog('open');
								
								
                        });    
                    }
                }
                
                else if (com == 'Add') {
					
					$('#namauss').val('');
					$('#pwduss').val('');
					$('#pwduss2').val('');
					$('#namauss').focus();
					
					$('#formuss').validationEngine('hideAll');
				//	Recaptcha.reload();
					
					$('#dialoguss').dialog('open');
					
                }
            }
					
				}
				// sampe sini user
				
				function setgrid_pengguna(id2) {
					$(id2).flexigrid({
		                url : 'getpengguna0.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Nama',
                	    name : 'nama_pengguna',
            	        width : 90,
        	            sortable : true,
    	                align : 'left'
	                    }, {
                    	    display : 'No Telp',
                	        name : 'notelp',
            	            width : 85,
        	                sortable : true,
    	                    align : 'left'
	                    },{
                    	    display : 'Aktif',
                	        name : 'aktif',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'kdgol1',
                	        name : 'kdgol1',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'kdgol2',
                	        name : 'kdgol2',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'Golongan I',
                	        name : 'namagol1',
            	            width : 100,
        	                sortable : true,
    	                    align : 'left',
							hide: false
	                    },
						{
                    	    display : 'Golongan II',
                	        name : 'namagol2',
            	            width : 100,
        	                sortable : true,
    	                    align : 'left',
							hide: false
	                    },
						{
                    	    display : 'Jenis Monitoring',
                	        name : 'jnismonitor',
            	            width : 130,
        	                sortable : true,
    	                    align : 'left',
							hide: false
	                    },
						{
                    	    display : 'Val-Monitor',
                	        name : 'rentang',
            	            width : 50,
        	                sortable : true,
    	                    align : 'right',
							hide: false
	                    }],
                	buttons : [ {
                    name : 'Add',
					bimage: '../css/images/add.png',
                    bclass : 'add',
                    onpress : onpresspengguna
                    }
                    ,
                    {
                        name : 'Edit',
						bimage: '../css/images/edit.png',
                        bclass : 'edit',
                        onpress : onpresspengguna
                    }
                    ,
                    {
                        name : 'Delete',
						bimage: '../css/images/close.png',
                        bclass : 'delete',
                        onpress : onpresspengguna
                    }
                    ,
                    {
                        separator : true
                    } 
                	],searchitems : [
							{display: 'Nama', name : 'nama_pengguna', isdefault: true},
							{display: 'No Telp', name : 'notelp'}
						],
							singleSelect: true,
			                sortname : "nama_pengguna",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
			                showToggleBtn: false,
							striped:true,
							height:460
							
           				 });
					
			function onpresspengguna(com, grid) {
                if (com == 'Delete') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
								
								var vnama =  value.firstChild.innerText;
								$("#iddel").val(vnama);
								
								$('#konfirmdelpengguna').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Edit') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                // collect the data
                                var nama = value.children[0].innerText; // in case we're changing the key
                                var notelp = value.children[1].innerText;
                                var aktif = value.children[2].innerText;
                                var kdgol1 = value.children[3].innerText;
								var kdgol2 = value.children[4].innerText;
								var jnmonitor = value.children[7].innerText;
									jnmonitor = jnmonitor.trim();
								var rentang = value.children[8].innerText;
								
								$('#nama').val(nama);
								$('#nama').prop("disabled",true);
								$('#telp').val(notelp);
								
								if (aktif=="1"){ 
									$('#aktif1').prop("checked", true); 
									$('#aktif').val('1');
								} else { 
									$('#aktif1').prop("checked", false); 
									$('#aktif').val('0');
								}
								
								$('#tipe').val('edit');
								
								$('#cbgol1').val(kdgol1);
								$('#cbgol2').val(kdgol2);
								
								if (jnmonitor=='Jarak') {
									$("select#cbjenismonitor").prop('selectedIndex', 0);
									$('#ketrentang').text('* Meter');
								}else{
									$("select#cbjenismonitor").prop('selectedIndex', 1);
									$('#ketrentang').text('* Menit');
								}  
								
								$('#trentang').val(rentang);
								
								
								$('#formadd').validationEngine('hideAll');
								
						//		Recaptcha.reload();
								
								$('#dialogadd').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Add') {
					$('#nama').prop("disabled",false);
					$('#nama').val('');
					$('#telp').val('');
					$('#aktif1').prop("checked", true);
					$('#nama').focus();
					$('#tipe').val('add');
					$('#aktif').val('1');
					
					$('#cbgol1').val('');
					$('#cbgol2').val('');
					$('#cbjenismonitor').val('Jarak');
					$('#trentang').val(0);
					
					$('#formadd').validationEngine('hideAll');
					
				//	Recaptcha.reload();
					
					$('#dialogadd').dialog('open');
					
                }
            }
					
				}
				// sampe sini pengguna
				
				
				function setgrid_gol1(id2) {
					$(id2).flexigrid({
		                url : 'getgolongan1.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Kode',
                	    name : 'kode',
            	        width : 80,
        	            sortable : true,
    	                align : 'left'
	                    }, {
                    	    display : 'Nama',
                	        name : 'nama',
            	            width : 500,
        	                sortable : true,
    	                    align : 'left'
	                    }],
                	buttons : [ {
                    name : 'Add',
					bimage: '../css/images/add.png',
                    bclass : 'add',
                    onpress : pressgol1
                    }
                    ,
                    {
                        name : 'Edit',
						bimage: '../css/images/edit.png',
                        bclass : 'edit',
                        onpress : pressgol1
                    }
                    ,
                    {
                        name : 'Delete',
						bimage: '../css/images/close.png',
                        bclass : 'delete',
                        onpress : pressgol1
                    }
                    ,
                    {
                        separator : true
                    } 
                	],searchitems : [
							{display: 'Kode', name : 'kode', isdefault: true},
							{display: 'Nama', name : 'nama'}
						],
							singleSelect: true,
			                sortname : "kode",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
			                showTableToggleBtn : true,
							striped:true,
							height:460
							
           				 });
					
			function pressgol1(com, grid) {
                if (com == 'Delete') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
								
								var vnama =  value.firstChild.innerText;
								$("#iddelgol1").val(vnama);
								
								$('#delgol1').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Edit') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                // collect the data
                                var kode = value.children[0].innerText; // in case we're changing the key
                                var nama = value.children[1].innerText;
                                
								$('#kodegol').val(kode);
								$('#kodegol').prop("disabled",true);
								$('#namagol').val(nama);
								
								$('#tipegol1').val('edit');
								
								$('#formgol1add').validationEngine('hideAll');
					
								$('#dialoggol1').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Add') {
					$('#kodegol').prop("disabled",false);
					$('#kodegol').val('');
					$('#namagol').val('');
					$('#kodegol').focus();
					$('#tipegol1').val('add');
					$('#formgol1add').validationEngine('hideAll');
					
					$('#dialoggol1').dialog('open');
					
               		 }
           			 }
				}
				// sampe sini tabgol1
				
				function setgrid_gol2(id2) {
					$(id2).flexigrid({
		                url : 'getgolongan2.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Kode',
                	    name : 'kode',
            	        width : 80,
        	            sortable : true,
    	                align : 'left'
	                    }, {
                    	    display : 'Nama',
                	        name : 'nama',
            	            width : 500,
        	                sortable : true,
    	                    align : 'left'
	                    }],
                	buttons : [ {
                    name : 'Add',
					bimage: '../css/images/add.png',
                    bclass : 'add',
                    onpress : pressgol2
                    }
                    ,
                    {
                        name : 'Edit',
						bimage: '../css/images/edit.png',
                        bclass : 'edit',
                        onpress : pressgol2
                    }
                    ,
                    {
                        name : 'Delete',
						bimage: '../css/images/close.png',
                        bclass : 'delete',
                        onpress : pressgol2
                    }
                    ,
                    {
                        separator : true
                    } 
                	],searchitems : [
							{display: 'Kode', name : 'kode', isdefault: true},
							{display: 'Nama', name : 'nama'}
						],
							singleSelect: true,
			                sortname : "kode",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
			                showTableToggleBtn : true,
							striped:true,
							height:460
							
           				 });
					
			function pressgol2(com, grid) {
                if (com == 'Delete') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
								
								var vnama =  value.firstChild.innerText;
								$("#iddelgol2").val(vnama);
								
								$('#delgol2').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Edit') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                // collect the data
                                var kode = value.children[0].innerText; // in case we're changing the key
                                var nama = value.children[1].innerText;
                                
								$('#kodegol2').val(kode);
								$('#kodegol2').prop("disabled",true);
								$('#namagol2').val(nama);
								
								$('#tipegol2').val('edit');
								
								$('#formgol2add').validationEngine('hideAll');
					
								$('#dialoggol2').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Add') {
					$('#kodegol2').prop("disabled",false);
					$('#kodegol2').val('');
					$('#namagol2').val('');
					$('#kodegol2').focus();
					$('#tipegol2').val('add');
					$('#formgol2add').validationEngine('hideAll');
					
					$('#dialoggol2').dialog('open');
					
               		 }
           			 }
				}
				// sampe sini tabgol2
				
				
				//selected index change jenis monitoring
				$('#cbjenismonitor').change(function() {
    				var idx = this.selectedIndex;        
					if (idx==0) {
						$('#ketrentang').text('* Meter');
					}else{
						$('#ketrentang').text('* Menit');
					}
					
				});
				
				//selected index sampe sini

				
			
				
				
		});
			
		
		
		</script>
	

	
		<style>
			
			.ui-datepicker-calendar tr, .ui-datepicker-calendar td, .ui-datepicker-calendar td a, .ui-datepicker-calendar th{font-size:11px;}
			div.ui-datepicker{font-size:11px;}
			.ui-datepicker-title span{font-size:11px;}
			
			tabs { margin-top: 1em; }
			#tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0;}
			
			
			html, body {
				width: 100%;
				height: 100%;
				padding: 0;
				margin: 0;
				overflow: auto;
				font-size: 0.8em;
				font-family: Verdana, Arial, sans-serif;
			}
			
			#tabs1 {
   			 	height:552px;
				}
			
			.north, .south, .west, .east, .center {
				display: inline-block;
				background-color: #F8F8F8;
			}

			.north {
				height: 3.5em;
				background-color: #3F3F3F;
				border-bottom: 1px solid black;
			}

			.south {
				height: 2em;
				background-color: #3F3F3F;
				border-top: 1px solid black;
				font-size: 0.65em;
				color: rgb(200, 200, 200);
				padding: 0.5em 2em;
			}

			.east {
				width: 25em;				
				height: 100%;
				font-size: 0.78em;
			}

			.west {
				width: 40em;
				font-size: 0.78em;
			}

			.ui-resizable-handle {
				background-color: #BBBBBB;
				border-left: 1px solid #9A9A9A;
				border-right: 1px solid #9A9A9A;
			}

			.ui-resizable-handle:hover {
				background-color: #bbbbcd;
				border-left: 1px solid #afafc0;
				border-right: 1px solid #afafc0;
			}

            .ui-resizable-e {
                right: -6px;
				width: 4px;
            }

			.ui-resizable-helper-west {
				border-right: 6px solid #bbbbcd;
			}

			.ui-resizable-helper-east {
				border-left: 6px solid #bbbbcd;
			}

            .ui-resizable-w {
                left: -6px;
				width: 4px;
            }

            .ui-resizable-e {
                right: -6px;
				width: 4px;
            }

			.ui-resizable-helper-east {
				border-right: 6px solid #bbbbcd;
			}

			.center {
				font-size: 0.9em;
				overflow: auto;
				background-color: white;
			}

			.ui-tabs {
				padding: 0em;
				border: none;
			}

			.ui-tabs-nav {
				border-left: none;
				border-right: none;
			}

			.west .panel {
				width: 17em;
				display: inline-block;
				overflow: auto;
			}

			.panel p {
				margin: 1em;
			}

			a {
				color: lightblue;
			}

			.highlight {
				color: lightblue;
				font-size: 1em;
			}

			.north h1 {
				font-size: 1em;
				color: white;
				padding-left: 1em;
			}

			#eq span {
				font-size: 0.6em;
				height: 120px;
				float: left;
				margin: 15px;
			}

	
		.style1 {font-size: 0.8em}
        </style>
	</head>
	<body data-layout='{"type": "border", "resize": false, "hgap": 6}'>
	
		<div class="north">
		
		    <table width="100%" border="0">
              <tr>
                <td width="85%" ><h1><span class="highlight">Web MONITORING</span> Sales Application</h1></td>
                <td>
					
					<ul id="jMenu">
            			<li>
                		<a>File</a>
                		<ul>
                    		<li id="mgol1"><a>Golongan I</a></li>
							<li id="mgol2"><a>Golongan II</a></li>
							<li id="mpengguna"><a>Pengguna</a></li>
							<li id="muser"><a>User</a></li>
							<!--<li id="maktiv"><a>Aktivitas</a></li> -->
							<li id="mgantipwd"><a>Rubah Password</a></li>
						</ul>
						</li>
						
						<li>
							<a> Laporan </a>
							<ul>
							<!--	<li id="mlaktiv"><a> Aktivitas </a></li> -->
								<li id="mllokasi"><a> History </a></li>
							</ul>
						</li>
						
						<li id="mlogout"> <a> Logout </a> </li>
						
					</ul>
					
				</td>
              </tr>
            </table>
			
			<script type="text/javascript">
            $(document).ready(function() {
                $("#jMenu").jMenu({
                    openClick : false,
                    ulWidth :110,
                     TimeBeforeOpening : 100,
                    TimeBeforeClosing : 11,
                    animatedText : false,
                    paddingLeft: 1,
                    effects : {
                        effectSpeedOpen : 150,
                        effectSpeedClose : 150,
                        effectTypeOpen : 'slide',
                        effectTypeClose : 'slide',
                        effectOpen : 'swing',
                        effectClose : 'swing'
                    }

                });
            });
        </script>
			
	</div>
		
		<div class="center">
		<div id="tabs">
			<ul>
				<li><a href="#tabs1">Map</a></li>
				<!--<li><a href="#tabs-2">Proin dolor</a></li>
				<li><a href="#tabs-3">Aenean lacinia</a></li> -->
			</ul>
			
			<div id="tabs1"></div> 
			
			
		</div>
		
		</div>
		<div data-layout='{"type": "grid", "columns": 1, "resize": false}' class="west">
			<div class="panel">
				<div class="ui-state-default" style="padding:4px;">Pengguna</div>
				<table id="flexme4" style="display:none"></table>
				<script type="text/javascript">
					$("#flexme4").flexigrid({
		                url : 'getpengguna0.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'Nama',
                	    name : 'nama_pengguna',
            	        width : 90,
        	            sortable : true,
    	                align : 'left'
	                    }, {
                    	    display : 'No Telp',
                	        name : 'notelp',
            	            width : 85,
        	                sortable : true,
    	                    align : 'left'
	                    },{
                    	    display : 'Aktif',
                	        name : 'aktif',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'kdgol1',
                	        name : 'kdgol1',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'kdgol2',
                	        name : 'kdgol2',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'namagol1',
                	        name : 'namagol1',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'namagol2',
                	        name : 'namagol2',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'jnismonitor',
                	        name : 'jnismonitor',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    },
						{
                    	    display : 'rentang',
                	        name : 'rentang',
            	            width : 20,
        	                sortable : true,
    	                    align : 'left',
							hide: true
	                    }],
                	buttons : [ {
                    name : 'Add',
					bimage: '../css/images/add.png',
                    bclass : 'add',
                    onpress : Example4
                    }
                    ,
                    {
                        name : 'Edit',
						bimage: '../css/images/edit.png',
                        bclass : 'edit',
                        onpress : Example4
                    }
                    ,
                    {
                        name : 'Delete',
						bimage: '../css/images/close.png',
                        bclass : 'delete',
                        onpress : Example4
                    }
                    ,
                    {
                        separator : true
                    } 
                	],searchitems : [
							{display: 'Nama', name : 'nama_pengguna', isdefault: true},
							{display: 'No Telp', name : 'notelp'}
						],
							singleSelect: true,
			                sortname : "nama_pengguna",
			                sortorder : "asc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
			                showToggleBtn: false,
							striped:true,
							height:194
							
           				 });
					
			function Example4(com, grid) {
                if (com == 'Delete') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
								
								var vnama =  value.firstChild.innerText;
								$("#iddel").val(vnama);
								
								$('#konfirmdelpengguna').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Edit') {

                    if($('.trSelected', grid).length >=1 ){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                // collect the data
                                var nama = value.children[0].innerText; // in case we're changing the key
                                var notelp = value.children[1].innerText;
                                var aktif = value.children[2].innerText;
                                var kdgol1 = value.children[3].innerText;
								var kdgol2 = value.children[4].innerText;
								var jnismonitor = value.children[7].innerText;
									jnismonitor = jnismonitor.trim();
								var rentang = value.children[8].innerText;
								
								$('#nama').val(nama);
								$('#nama').prop("disabled",true);
								$('#telp').val(notelp);
								
								
								if (aktif=="1"){ 
									$('#aktif1').prop("checked", true); 
									$('#aktif').val('1');
								} else { 
									$('#aktif1').prop("checked", false); 
									$('#aktif').val('0');
								}
								
								$('#tipe').val('edit');
								
								$('#cbgol1').val(kdgol1);
								$('#cbgol2').val(kdgol2);
								
								
								if (jnismonitor=='Jarak') {
									$("select#cbjenismonitor").prop('selectedIndex', 0);
									$('#ketrentang').text('* Meter');
								}else{
									$("select#cbjenismonitor").prop('selectedIndex', 1);
									$('#ketrentang').text('* Menit');
								}  
								
								$('#trentang').val(rentang);
								
								$('#formadd').validationEngine('hideAll');
								 
								 
								 
								// Recaptcha.destroy();
								
							//	 Recaptcha.reload();
								
								$('#dialogadd').dialog('open');
								
								
                        });    
                    }
                }
                else if (com == 'Add') {
					$('#nama').prop("disabled",false);
					$('#nama').val('');
					$('#telp').val('');
					$('#aktif1').prop("checked", true);
					$('#nama').focus();
					$('#tipe').val('add');
					$('#aktif').val('1');
					
					$('#cbgol1').val('');
					$('#cbgol2').val('');
					$('#cbjenismonitor').val('Jarak');
					$('#trentang').val(0);
					
					$('#formadd').validationEngine('hideAll');
					 
					// Recaptcha.reload();
					
					$('#dialogadd').dialog('open');
					
                }
            }
					
					</script>
			</div>
			<div class="panel">
				<div class="ui-state-default" style="padding:4px;">Historical</div>
				
				 <table id="flexme5" style="display:none"></table>
				 <script type="text/javascript">
					
					var namapengguna=$('#penggunaterpilih').val();
					var tanggal = $('#ttglc').val();
					var jam1 = $('#tjamc1').val();
					var jam2 = $('#tjamc2').val();
					
					$("#flexme5").flexigrid({
		                url : 'get_hist.php',
        		        dataType : 'json',
		                colModel : [ {
                    	display : 'id',
                	    name : 'id',
            	        width : 90,
        	            sortable : true,
    	                align : 'left',
						hide: true
	                    }, {
                    	    display : 'Jam',
                	        name : 'jam',
            	            width : 40,
        	                sortable : true,
    	                    align : 'left'
	                    },{
                    	    display : 'Longitude',
                	        name : 'longi',
            	            width : 55,
        	                sortable : true,
    	                    align : 'left',
							
	                    },{
                    	    display : 'Latitude',
                	        name : 'loti',
            	            width : 55,
        	                sortable : true,
    	                    align : 'left',
							
	                    },{
                    	    display : 'Lokasi',
                	        name : 'alamat',
            	            width : 90,
        	                sortable : true,
    	                    align : 'left',
							
	                    }
						],
                	buttons : [ {
                    name : 'Set Historical',
					bimage: '../css/images/sett.png',
                    bclass : 'set',
                    onpress : Example5
                    },{
                        separator : true
                    },{
                    name : 'Set Track Jalur',
					bimage: '../css/images/direct.png',
                    bclass : 'track',
                    onpress : Example5
                    },
					{
                    name : 'Clear Track',
					bimage: '../css/images/clear_track.png',
                    bclass : 'clear',
                    onpress : Example5
                    }],
							singleSelect: true,
			                sortname : "jam",
			                sortorder : "desc",
			                usepager : true,
			                useRp : false,
			                rp : 10,
							params: [{name: 'pengguna', value:namapengguna},{name: 'tanggal', value:tanggal},{name: 'jam1', value:jam1},{name: 'jam2', value:jam2}],
			                showTableToggleBtn : true,
							striped:true,
							height:191
							
							
           				 });
						 
					function Example5(com, grid) {
                		if (com == 'Set Historical') {
							
							$("#bttrack").hide();
							$("#btcari").show();
							
							$("#settingsearch").dialog('open');
							
						}else if (com == 'Set Track Jalur') {
							
							$("#btcari").hide();
							$("#bttrack").show();
							
							$("#settingsearch").dialog('open');
							
						}else if (com == 'Clear Track') {
							$('#tabs1').gmap3({clear:"marker"});
							$('#tabs1').gmap3({clear:"directionsrenderer"
								});
							$('#istrack').val('0');
						}
						
					}
						 
				</script>
			</div>
			
		</div>
		
		<div class="south">
			Copyright 2013 - <a href="http://www.ard14n.com/">Ardian</a>
		</div>
		
		
		<div id="dialogadd" title="Create Pengguna">
			<form id="formadd">
			<fieldset>
					<table width="100%" border="0">
                      <tr>
                        <td><label for="label">Nama :</label></td>
                      </tr>
                      <tr>
                        <td><input type="text" name="nama" id="nama"  class="validate[required] text-input,text ui-widget-content ui-corner-all"/></td>
                      </tr>
                      <tr>
                        <td>No telp :</td>
                      </tr>
                      <tr>
                        <td><input type="text" name="telp" id="telp" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" /></td>
                      </tr>
					  
					  <tr>
                        <td>Golongan I :</td>
                      </tr>
                      <tr>
                        <td><select id="cbgol1" name="cbgol1" style="width:150px" class="text ui-widget-content ui-corner-all"> 
						<option value="">-Pilih-</option>
						<?php
							foreach ($arrgolongan1 as $kode=>$nama) {
								echo "<option value='$kode'>$nama</option>";
							}
						?>
						</select></td>
                      </tr>
					  
					  <tr>
                        <td>Golongan II :</td>
                      </tr>
                      <tr>
							<td><select id="cbgol2" name="cbgol2" style="width:150px" class="text ui-widget-content ui-corner-all"> 
						<option value="">-Pilih-</option>
						<?php
							foreach ($arrgolongan2 as $kode=>$nama) {
								echo "<option value='$kode'>$nama</option>";
							}
						?>
						</select></td>
                      </tr>
					  
					  <tr>
                        <td>Jenis Monitoring :</td>
                      </tr>
                      <tr>
                        <td><select id="cbjenismonitor" name="cbjenismonitor" style="width:150px" class="text ui-widget-content ui-corner-all"> 
						<option value="Jarak">Jarak</option>
						<option value="Waktu">Waktu</option>
						</select></td>
                      </tr>
					  
					  <tr>
                        <td>Val-Monitor :</td>
                      </tr>
					  <tr>
                        <td><input type="text" name="trentang" id="trentang" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" />
						<label id="ketrentang">* Meter</label>
						</td>
                      </tr>
					  
                      <tr>
                        <td><label>
                          <input id="aktif1" type="checkbox" name="checkbox" class="checkIt">
                        Aktif 
                        <input id="tipe" type="hidden" name="hiddenField">
                        <input id="aktif" type="hidden" name="hiddenField2">
                        <input id="jmlakhir" type="hidden" name="hiddenField3">
                        </label></td>
                      </tr>
                      <tr>
                        <td><hr></td>
                      </tr>
                      <tr>
                        <td><div align="right">
                          <input name="btsimpan" type="submit" id="btsimpan" value="Simpan">
                          <input name="btcancel" type="button" id="btcancel" value="Cancel">
                        </div></td>
                      </tr>
                    </table>
			  </fieldset>
			</form>
		</div>
		
	<div id="konfirmdelpengguna" title="Konfirmasi">
		    <table width="100%" border="0">
              <tr>
                <td>Yakin akan dihapus ?</td>
              </tr>
              <tr>
                <td><hr></td>
              </tr>
              <tr>
                <td><input name="btok" type="submit" id="btok" value="Yes">
                <input name="btno" type="submit" id="btno" value="No">
                <input type="hidden" id="iddel" name="iddel"></td>
              </tr>
            </table>
	</div>
	
		
	<div id="dialoggol1" title="Master Golongan I">
			<form id="formgol1add">
			<fieldset>
					<table width="100%" border="0">
                      <tr>
                        <td><label for="label">Kode :</label></td>
                      </tr>
                      <tr>
                        <td><input type="text" name="nama" id="kodegol"  class="validate[required] text-input,text ui-widget-content ui-corner-all"/></td>
                      </tr>
                      <tr>
                        <td>Nama Gol :</td>
                      </tr>
                      <tr>
                        <td><input type="text" name="telp" id="namagol" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" /></td>
                      </tr>
                      <tr>
                        <td><label>
                        <input id="tipegol1" type="hidden" name="hiddenField">
                        </label></td>
                      </tr>
                      <tr>
                        <td><hr></td>
                      </tr>
                      <tr>
                        <td><div align="right">
                          <input name="btsimpan" type="submit" id="btsimpan_gol1" value="Simpan">
                          <input name="btcancel" type="button" id="btcancel_gol1" value="Cancel">
                        </div></td>
                      </tr>
                    </table>
			  </fieldset>
			</form>
	</div>
	
	<div id="dialoggol2" title="Master Golongan I">
			<form id="formgol2add">
			<fieldset>
					<table width="100%" border="0">
                      <tr>
                        <td><label for="label">Kode :</label></td>
                      </tr>
                      <tr>
                        <td><input type="text" name="nama" id="kodegol2"  class="validate[required] text-input,text ui-widget-content ui-corner-all"/></td>
                      </tr>
                      <tr>
                        <td>Nama Gol :</td>
                      </tr>
                      <tr>
                        <td><input type="text" name="telp" id="namagol2" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" /></td>
                      </tr>
                      <tr>
                        <td><label>
                        <input id="tipegol2" type="hidden" name="hiddenField">
                        </label></td>
                      </tr>
                      <tr>
                        <td><hr></td>
                      </tr>
                      <tr>
                        <td><div align="right">
                          <input name="btsimpan" type="submit" id="btsimpan_gol2" value="Simpan">
                          <input name="btcancel" type="button" id="btcancel_gol2" value="Cancel">
                        </div></td>
                      </tr>
                    </table>
			  </fieldset>
			</form>
	</div>
	
	
	<div id="dialoguss" title="User">
			<form id="formuss">
			<fieldset>
					<table width="100%" border="0">
                      <tr>
                        <td><label for="label">Nama :</label></td>
                      </tr>
                      <tr>
                        <td><input type="text" name="nama" id="namauss"  class="validate[required] text-input,text ui-widget-content ui-corner-all"/></td>
                      </tr>
                      <tr>
                        <td>Pwd :</td>
                      </tr>
                      <tr>
                        <td><input type="password" name="pwwd" id="pwduss" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" /></td>
                      </tr>
					  <tr>
                        <td>Pwd Verifikasi :</td>
                      </tr>
                      <tr>
                        <td><input type="password" name="pwwd2" id="pwduss2" value="" class="validate[required] text-input,text ui-widget-content ui-corner-all" /></td>
                      </tr>
					  
                      <tr>
                        <td><label>
                        <input id="tipeuss" type="hidden" name="hiddenField">
                        </label></td>
                      </tr>
					  
                      <tr>
                        <td><hr></td>
                      </tr>
                      <tr>
                        <td><div align="right">
                          <input name="btsimpan" type="submit" id="btsimpan_uss" value="Simpan">
                          <input name="btcancel" type="button" id="btcancel_uss" value="Cancel">
                        </div></td>
                      </tr>
                    </table>
			  </fieldset>
			</form>
	</div>
	
	<div id="delgol1" title="Konfirmasi">
		    <table width="100%" border="0">
              <tr>
                <td>Yakin akan dihapus ?</td>
              </tr>
              <tr>
                <td><hr></td>
              </tr>
              <tr>
                <td><input name="btok" type="submit" id="btok_delgol1" value="Yes">
                <input name="btno" type="submit" id="btno_delgol1" value="No">
                <input type="hidden" id="iddelgol1" name="iddelgol1"></td>
              </tr>
            </table>
	</div>
	
	<div id="dialogkonfirm" title="Konfirmasi">
		<p>Security code salah. Coba lagi. </p>
	</div>
	
	<div id="delgol2" title="Konfirmasi">
		    <table width="100%" border="0">
              <tr>
                <td>Yakin akan dihapus ?</td>
              </tr>
              <tr>
                <td><hr></td>
              </tr>
              <tr>
                <td><input name="btok" type="submit" id="btok_delgol2" value="Yes">
                <input name="btno" type="submit" id="btno_delgol2" value="No">
                <input type="hidden" id="iddelgol2" name="iddelgol2"></td>
              </tr>
            </table>
	</div>
	
	<div id="del_uss" title="Konfirmasi">
		    <table width="100%" border="0">
              <tr>
                <td>Yakin akan dihapus ?</td>
              </tr>
              <tr>
                <td><hr></td>
              </tr>
              <tr>
                <td><input name="btok" type="submit" id="btok_deluss" value="Yes">
                <input name="btno" type="submit" id="btno_deluss" value="No">
                <input type="hidden" id="iddeluss" name="iddeluss"></td>
              </tr>
            </table>
	</div>
	
	<div id="konfirmadduss" title="Konfirmasi"> 
		 <table width="100%" border="0">
              <tr>
                <td>Password dan Ver-Password harus sama..!!!</td>
              </tr>
              <tr>
                <td><div align="right">
                  <input name="btok" type="submit" id="btok_konfuss" value="OK">
                  </div>
           </tr>
      </table>
	</div>
	
	<div id="settingsearch" title="Setting Historical">
		<table width="100%" border="0">
                      <tr>
					  <td width="40">Tgl</td>
					  	<td>:</td>
                        <td width="1270" >
							<input type="text" id="ttglc" name="textfield"  class="text ui-widget-content ui-corner-all"></td>
					  </tr>
                      <tr>
                        <td width="40">Jam</td>
						<td>:</td>
                        <td>
                          <input type="text" id="tjamc1" name="textfield2" style="width:50px"  class="text ui-widget-content ui-corner-all">
                        s.d
                        <input type="text" id="tjamc2" name="textfield3"  style="width:50px"  class="text ui-widget-content ui-corner-all">
                        </td>
                      </tr>
					  <tr>
                        <td colspan="3"><hr></td>
                      </tr>
                      <tr>
                        <td colspan="3"><div align="right">
							<button id="btcari">Simpan</button>
							<button id="bttrack">Tracking</button>
							<button id="btclose_sett">Cancel</button> </div></td>
                      </tr>
                      
      </table>
				
				<input id="penggunaterpilih" type="hidden" name="hiddenField">
				<input id="istrack" type="hidden" name="hiddenField">
				
	</div>
	
	<div id="setsearch_history" title="Search Historical">
		<table width="24%" border="0">
                      <tr>
					  <td width="46" align="left" valign="top">Tgl</td>
					  	<td width="9" valign="top">:</td>
                        <td width="250" >
						<input name="textfield" type="text"  class="text ui-widget-content ui-corner-all" id="ttgl1_hs" size="15">
						<div >S.d</div>
						<input name="textfield4"   type="text"  class="text ui-widget-content ui-corner-all" id="ttgl2_hs" size="15">
						</td>
                      </tr>
                      <tr>
                        <td width="46" valign="top">Jam</td>
						<td valign="top">:</td>
                        <td>
                          <input name="textfield2" type="text"  class="text ui-widget-content ui-corner-all" id="tjam1_hs" style="width:50px">
						  <div >S.d</div>
						  <input name="textfield32" type="text"  class="text ui-widget-content ui-corner-all" id="tjam2_hs"  style="width:50px">
					    </td>
                      </tr>
                      <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td colspan="3"><input name="textfield5" type="text"  class="text ui-widget-content ui-corner-all" id="tnama_hs" size="35"></td>
                      </tr>
                      <tr>
                        <td>Gol I </td>
                        <td>:</td>
							<td colspan="3"><select id="cbgol1_hs" name="select" style="width:150px" class="text ui-widget-content ui-corner-all">
                          <option value="">-Pilih-</option>
                          <?php
							foreach ($arrgolongan1 as $kode=>$nama) {
								echo "<option value='$kode'>$nama</option>";
							}
						?>
                        </select></td>
                      </tr>
                      <tr>
                        <td>Gol II </td>
                        <td>:</td>
                        <td colspan="3"><select id="cbgol2_hs" name="select2" style="width:150px" class="text ui-widget-content ui-corner-all">
                          <option value="">-Pilih-</option>
                          <?php
							foreach ($arrgolongan2 as $kode=>$nama) {
								echo "<option value='$kode'>$nama</option>";
							}
						?>
                        </select></td>
                      </tr>
					  <tr>
                        <td colspan="5"><hr></td>
                      </tr>
                      <tr>
                        <td colspan="5"><div align="right">
							<button id="btload_hs">Load</button>
							<button id="btclose_hs">Cancel</button> </div></td>
                      </tr>
      </table>
				
				<input id="penggunaterpilih" type="hidden" name="hiddenField">
				<input id="istrack" type="hidden" name="hiddenField">
	</div>
	
	<div id="rubahpwd_dlg" title="Rubah Password">
	<form id="frubah_pwd">
	  <table width="100%" border="0">
          <tr>
            <td width="91">Pwd Lama</td>
            <td width="9">:</td>
			<td ><input name="textfield5" type="password"  class="text ui-widget-content ui-corner-all" id="tpwd_lama" size="30"></td>
          </tr>
          <tr>
            <td>Pwd Baru</td>
            <td>:</td>
			<td ><input name="textfield5" type="password"  class="text ui-widget-content ui-corner-all" id="tpwd_baru" size="30"></td>
          </tr>
		  <tr>
            <td>Ver Pwd Baru</td>
            <td>:</td>
			<td ><input name="textfield5" type="password"  class="text ui-widget-content ui-corner-all" id="tpwd_baru2" size="30"></td>
          </tr>
		  <tr>
              <td colspan="3"><hr></td>
          </tr>
              <tr>
              <td colspan="3"><div align="right">
					<button id="btok_rb"> &nbsp; OK  &nbsp; </button>
					<button id="rb_closebtn">Cancel</button> </div></td>
              </tr>
        </table>
	</form>
	</div>
	
	<div id="konfirmrubahpwd"><p> Password Dirubah... </p></div>
	<div id="konfrubahtidaksama"><p> Password lama salah... </p></div>
	<div id="konftidakberhasil"><p> Proses Gagal... </p></div>
	
	
	</body>
</html>
