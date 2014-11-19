<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../config/koneksi.php";

$module=$_GET[module];
$act=$_GET[act];

if ($module=='order' AND $act=='update'){
   // Update stok barang saat transaksi sukses (Lunas)
   if ($_POST[status_order]=='Lunas'){ 
    
      // Update untuk mengurangi stok 
      mysql_query("UPDATE tiket,orders_detail SET tiket.stok=tiket.stok-orders_detail.jumlah WHERE tiket.id_tiket=orders_detail.id_tiket and orders_detail.id_orders='$_POST[id]'");
	  
	  // Update untuk menambahkan tiket yang dibeli (best seller = tiket yang paling laris)
      mysql_query("UPDATE tiket,orders_detail SET tiket.dibeli=tiket.dibeli+orders_detail.jumlah WHERE tiket.id_tiket=orders_detail.id_tiket and orders_detail.id_orders='$_POST[id]'");

      // Update status order
      mysql_query("UPDATE orders SET status_order='$_POST[status_order]' where id_orders='$_POST[id]'");

      // Update status order
      mysql_query("UPDATE orders SET status_order='$_POST[status_order]' where id_orders='$_POST[id]'");

      header('location:../../bagian.php?module='.$module);
    }	  
	  elseif($_POST[status_order]=='Batal'){
	    // Update untuk menambah stok
	    mysql_query("UPDATE tiket,orders_detail SET tiket.stok=tiket.stok+orders_detail.jumlah WHERE tiket.id_tiket=orders_detail.id_tiket and orders_detail.id_orders='$_POST[id]'"); 
	    
	    // Update untuk menambahkan tiket yang tidak jadi dibeli (tidak jd best seller)
      mysql_query("UPDATE tiket,orders_detail SET tiket.dibeli=tiket.dibeli-orders_detail.jumlah WHERE tiket.id_tiket=orders_detail.id_tiket and orders_detail.id_orders='$_POST[id]'");

	    // Update status order Batal
      mysql_query("UPDATE orders SET status_order='$_POST[status_order]' where id_orders='$_POST[id]'");

	    header('location:../../bagian.php?module='.$module);
	  }
    else{
      mysql_query("UPDATE orders SET status_order='$_POST[status_order]' where id_orders='$_POST[id]'");
      header('location:../../bagian.php?module='.$module);
    }
}
}
?>
