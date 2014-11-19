<?php 
  error_reporting(0);
  session_start();	
  include "config/koneksi.php";
	include "config/fungsi_indotgl.php";
	include "config/class_paging.php";
	include "config/fungsi_combobox.php";
	include "config/library.php";
  include "config/fungsi_autolink.php";
  include "config/fungsi_rupiah.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> Pemesanan Tiket Online </title>
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow">
<meta name="description" content="Pemesanan Tiket Online">
<meta name="keywords" content="Pemesanan Tiket Online">
<meta http-equiv="Copyright" content="skripsi-pranata">
<meta name="author" content="Risti">
<meta http-equiv="imagetoolbar" content="no">
<meta name="language" content="Indonesia">
<meta name="revisit-after" content="7">
<meta name="webcrawlers" content="all">
<meta name="rating" content="general">
<meta name="spiders" content="all">

<link rel="shortcut icon" href="favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://localhost/tokohp/rss.xml" />

<link href="style.css" rel="stylesheet" type="text/css" />

<link href="lightbox/themes/default/jquery.lightbox.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="jquery-1.4.js"></script>
<script type="text/javascript" src="lightbox/jquery.lightbox.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		    $('.lightbox').lightbox();		    
		});
  </script>

<style type="text/css">
<!--
#main_container .footer {
	text-align: center;
}
-->
</style>
</head>

<body>
<div id="wrapper">
<div id="main_container">
	<div class="top_bar">
    	<div class="top_search">
        	<div class="search_text">Cari Tiket</div>
        	<form method="POST" action="hasil-pencarian.html">
            <input class="search_input" name="kata" type="text">
            <input src="images/search.gif" class="search_bt" type="image">
          </form>
      </div>            
  </div>
  
	
    
  <div id="main_content"> 
       <div id="menu_tab">
            <div class="left_menu_corner"></div>
              <ul class="menu">
		            <li><a href="index.php?telo1=1" class="nav1">Home</a></li>
                <li class="divider"></li>
		            <li><a href="profil-kami.html" class="nav2">Profil</a></li>
                <li class="divider"></li>
		            <li><a href="cara-pesan.html" class="nav3">Cara Pesan</a></li>
                <li class="divider"></li>
		            <li><a href="semua-tiket.html" class="nav4">Semua Tiket</a></li>
                <li class="divider"></li>
		            <li><a href="hubungi-kami.html" class="nav6">Hubungi Kami</a></li>        
                <li class="divider"></li>
              </ul>
            <div class="right_menu_corner"></div>
    </div><!-- end of menu tab -->
<div id="header"></div>
  <div class="crumb_navigation">
    Anda sedang berada di: <?php include "breadcrumb.php";?>
  </div>        
        
  <div class="left_content"> 
      <?php include "bagian-kiri.php";?>         
  </div>
   
   <div class="center_content">
      <?php include "bagian-tengah.php";?>           
   </div>
   
   <div class="right_content">
      <?php include "bagian-kanan.php";?>                
   </div><!-- end of right content -->   
            
  </div><!-- end of main content -->
   
   <div class="footer">
        <br />
        <br />
        Copyright &copy; 2013. by Risty .
        
</div>
<!-- end of main_container -->
<div style="visibility: hidden; position: absolute;"><div></div></div>
</div>
</body>
</html>
