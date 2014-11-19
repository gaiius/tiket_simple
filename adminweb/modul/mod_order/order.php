<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/mod_order/aksi_order.php";
switch($_GET[act]){
  // Tampil Order
  default:
    echo "<h2>Daftar Pesanan Tiket</h2>
          <table>
          <tr><th>no.Pesan</th><th>nama kustomer</th><th>tgl. order</th><th>jam</th><th>status</th><th>aksi</th></tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);

    $tampil = mysql_query("SELECT * FROM orders,kustomer WHERE orders.id_kustomer=kustomer.id_kustomer ORDER BY id_orders DESC LIMIT $posisi,$batas");
  
    while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_order]);
      echo "<tr><td align=center>$r[id_orders]</td>
                <td>$r[nama_lengkap]</td>
                <td>$tanggal</td>
                <td>$r[jam_order]</td>
                <td>$r[status_order]</td>
		            <td><a href=?module=order&act=detailorder&id=$r[id_orders]>Detail</a></td></tr>";
      $no++;
    }
    echo "</table>";

    $jmldata = mysql_num_rows(mysql_query("SELECT * FROM orders"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

    echo "<div id=paging>Hal: $linkHalaman</div><br>";
    break;
  
    
  case "detailorder":
    $edit = mysql_query("SELECT * FROM orders,kustomer WHERE orders.id_kustomer=kustomer.id_kustomer AND id_orders='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
    $tanggal=tgl_indo($r[tgl_order]);
    
    if ($r[status_order]=='Baru'){
        $pilihan_status = array('Baru', 'Lunas');
    }
    elseif ($r[status_order]=='Lunas'){
        $pilihan_status = array('Lunas', 'Batal');    
    }
    else{
        $pilihan_status = array('Baru', 'Lunas', 'Batal');    
    }

    $pilihan_order = '';
    foreach ($pilihan_status as $status) {
	   $pilihan_order .= "<option value=$status";
	   if ($status == $r[status_order]) {
		    $pilihan_order .= " selected";
	   }
	   $pilihan_order .= ">$status</option>\r\n";
    }

    echo "<h2>Rincian Pesanan</h2>
          <form method=POST action=$aksi?module=order&act=update>
          <input type=hidden name=id value=$r[id_orders]>

          <table>
          <tr><td>No. Order</td>        <td> : $r[id_orders]</td></tr>
          <tr><td>Tgl. & Jam Order</td> <td> : $tanggal & $r[jam_order]</td></tr>
          <tr><td>Status Order      </td><td>: <select name=status_order>$pilihan_order</select> 
          <input type=submit value='Ubah Status'></td></tr>
          </table></form>";

  // tampilkan rincian tiket yang di order
  $sql2=mysql_query("SELECT * FROM orders_detail, tiket 
                     WHERE orders_detail.id_tiket=tiket.id_tiket 
                     AND orders_detail.id_orders='$_GET[id]'");
  
  echo "<table border=0 width=600>
        <tr><th>Nama Tiket</th><th>Kota Tujuan</th><th>Jam Berangkat</th><th>Tgl Berangkat</th><th>Diskon</th><th></th><th>Jumlah</th><th>Harga Satuan</th><th>Sub Total</th></tr>";
  
  while($s=mysql_fetch_array($sql2)){
     // rumus untuk menghitung subtotal dan total		
   $disc        = ($s[diskon]/100)*$s[harga];
   $hargadisc   = number_format(($s[harga]-$disc),0,",","."); 
   $subtotal    = ($s[harga]-$disc) * $s[jumlah];

    $total       = $total + $subtotal;
    $subtotal_rp = format_rupiah($subtotal);    
    $total_rp    = format_rupiah($total);    
    $harga       = format_rupiah($s[harga]);

   $subtotalberat = $s[berat] * $s[jumlah]; // total berat per item tiket 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all tiket yang dibeli

    echo "<tr><td>$s[nama_tiket]</td><td>$s[kota_tujuan]</td><td align=center>$s[jam_berangkat]</td><td>$s[tanggal_berangkat]</td><td align=center>$s[diskon]%</td><td>$s[tgl_berangkat]</td><td align=center>$s[jumlah]</td>
              <td align=right>$harga</td><td align=right>$subtotal_rp</td></tr>";
  }

  $ongkos=mysql_fetch_array(mysql_query("SELECT * FROM kota,kustomer,orders 
          WHERE kustomer.id_kota=kota.id_kota AND orders.id_kustomer=kustomer.id_kustomer AND id_orders='$_GET[id]'"));
  $ongkoskirim1=$ongkos[ongkos_kirim];
  $ongkoskirim=$ongkoskirim1 * $totalberat;

  $grandtotal    = $total + $ongkoskirim; 

  $ongkoskirim_rp = format_rupiah($ongkoskirim);
  $ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
  $grandtotal_rp  = format_rupiah($grandtotal);    

echo "<tr><td colspan=8 align=right>Total              Rp. : </td><td align=right><b>$total_rp</b></td></tr>     
      <tr><td colspan=8 align=right>Grand Total        Rp. : </td><td align=right><b>$grandtotal_rp</b></td></tr>
      </table>";

  // tampilkan data kustomer
  echo "<table border=0 width=600>
        <tr><th colspan=2>Data Kustomer</th></tr>
        <tr><td>Nama Kustomer</td><td> : $r[nama_lengkap]</td></tr>
        <tr><td>Alamat Pengiriman</td><td> : $r[alamat]</td></tr>
        <tr><td>No. Telpon/HP</td><td> : $r[telpon]</td></tr>
        <tr><td>Email</td><td> : $r[email]</td></tr>
        </table>";

    break;  
}
}
?>
