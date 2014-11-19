<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../config/koneksi.php";
include "../../../config/library.php";
include "../../../config/fungsi_thumb.php";
include "../../../config/fungsi_seo.php";

$module=$_GET[module];
$act=$_GET[act];

// Hapus tiket
if ($module=='tiket' AND $act=='hapus'){
  $data=mysql_fetch_array(mysql_query("SELECT gambar FROM tiket WHERE id_tiket='$_GET[id]'"));
  if ($data['gambar']!=''){
     mysql_query("DELETE FROM tiket WHERE id_tiket='$_GET[id]'");
     unlink("../../../foto_tiket/$_GET[namafile]");   
     unlink("../../../foto_tiket/small_$_GET[namafile]");   
  }
  else{
     mysql_query("DELETE FROM tiket WHERE id_tiket='$_GET[id]'");
  }
  header('location:../../bagian.php?module='.$module);


  mysql_query("DELETE FROM tiket WHERE id_tiket='$_GET[id]'");
  header('location:../../bagian.php?module='.$module);
}

// Input tiket
elseif ($module=='tiket' AND $act=='input'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $acak           = rand(1,99);
  $nama_file_unik = $acak.$nama_file; 

  $tiket_seo      = seo_title($_POST[nama_tiket]);

  // Apabila ada gambar yang diupload
  if (!empty($lokasi_file)){
    if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg"){
    echo "<script>window.alert('Upload Gagal, Pastikan File yang di Upload bertipe *.JPG');
        window.location=('../../bagian.php?module=tiket)</script>";
    }
    else{
    UploadImage($nama_file_unik);

    mysql_query("INSERT INTO tiket(nama_tiket,
                                    tiket_seo,
                                    id_kategori,
                                    kota_tujuan,
									tanggal_berangkat,
									jam_berangkat,
                                    harga,
                                    diskon,
                                    stok,
                                    deskripsi,
                                    tgl_masuk,
                                    gambar) 
                            VALUES('$_POST[nama_tiket]',
                                   '$tiket_seo',
                                   '$_POST[kategori]',
                                   '$_POST[kota_tujuan]',
								   '$_POST[tanggal_berangkat]',
								   '$_POST[jam_berangkat]',
                                   '$_POST[harga]',
                                   '$_POST[diskon]',
                                   '$_POST[stok]',
                                   '$_POST[deskripsi]',
                                   '$tgl_sekarang',
                                   '$nama_file_unik')");
  header('location:../../bagian.php?module='.$module);
  }
  }
  else{
    mysql_query("INSERT INTO tiket(nama_tiket,
                                    tiket_seo,
                                    id_kategori,
                                    kota_tujuan,
									tanggal_berangkat,
									jam_berangkat,
                                    harga,
                                    diskon,
                                    stok,
                                    deskripsi,
                                    tgl_posting) 
                            VALUES('$_POST[nama_tiket]',
                                   '$tiket_seo',
                                   '$_POST[kategori]',
                                   '$_POST[kota_tujuan]',
								   '$_POST[tanggal_berangkat]',
								   '$_POST[jam_berangkat]',
                                   '$_POST[harga]',
                                   '$_POST[harga]',
                                   '$_POST[stok]',
                                   '$_POST[deskripsi]',
                                   '$tgl_sekarang')");
  header('location:../../bagian.php?module='.$module);
  }
}

// Update tiket
elseif ($module=='tiket' AND $act=='update'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $acak           = rand(1,99);
  $nama_file_unik = $acak.$nama_file; 

  $tiket_seo      = seo_title($_POST[nama_tiket]);

  // Apabila gambar tidak diganti
  if (empty($lokasi_file)){
    mysql_query("UPDATE tiket SET nama_tiket = '$_POST[nama_tiket]',
                                   tiket_seo  = '$tiket_seo', 
                                   id_kategori = '$_POST[kategori]',
                                   kota_tujuan = '$_POST[kota_tujuan]',
								   tanggal_berangkat = '$_POST[tanggal_berangkat]',
								   jam_berangkat = '$_POST[jam_berangkat]',
                                   harga       = '$_POST[harga]',
                                   diskon      = '$_POST[diskon]',
                                   stok        = '$_POST[stok]',
                                   deskripsi   = '$_POST[deskripsi]'
                             WHERE id_tiket   = '$_POST[id]'");
  header('location:../../bagian.php?module='.$module);
  }
  else{
    if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg"){
    echo "<script>window.alert('Upload Gagal, Pastikan File yang di Upload bertipe *.JPG');
        window.location=('../../bagian.php?module=tiket)</script>";
    }
    else{
    UploadImage($nama_file_unik);
    mysql_query("UPDATE tiket SET nama_tiket = '$_POST[nama_tiket]',
                                   tiket_seo  = '$tiket_seo', 
                                   id_kategori = '$_POST[kategori]',
                                   kota_tujuan       = '$_POST[kota_tujuan]',
								   tanggal_berangkat = '$_POST[tanggal_berangkat]',
								   jam_berangkat = '$_POST[jam_berangkat]',
                                   harga       = '$_POST[harga]',
                                   diskon      = '$_POST[diskon]',
                                   stok        = '$_POST[stok]',
                                   deskripsi   = '$_POST[deskripsi]',
                                   gambar      = '$nama_file_unik'   
                             WHERE id_tiket   = '$_POST[id]'");
    header('location:../../bagian.php?module='.$module);
    }
  }
}
}
?>
