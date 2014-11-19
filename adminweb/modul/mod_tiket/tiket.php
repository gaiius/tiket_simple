<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/mod_tiket/aksi_tiket.php";
switch($_GET[act]){
  // Tampil Tiket
  default:
    echo "<h2>Tiket</h2>
          <input type=button value='Tambah Tiket' onclick=\"window.location.href='?module=tiket&act=tambahtiket';\">
          <table>
          <tr><th>no</th><th>nama BUS</th><th>Kota Tujuan</th><th>Tanggal Berangkat</th><th>Jam Berangkat</th><th>harga</th><th>diskon(%)</th><th>Kursi Tersedia</th><th>tgl.masuk</th><th>aksi</th></tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);

    $tampil = mysql_query("SELECT * FROM tiket ORDER BY id_tiket DESC LIMIT $posisi,$batas");
  
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_masuk]);
      $harga=format_rupiah($r[harga]);
      echo "<tr><td>$no</td>
                <td>$r[nama_tiket]</td>
                <td align=center>$r[kota_tujuan]</td>
				<td align=center>$r[tanggal_berangkat]</td>
				<td align=center>$r[jam_berangkat]</td>
                <td>$harga</td>
                <td align=center>$r[diskon]</td>
                <td align=center>$r[stok]</td>
                <td>$tanggal</td>
		            <td><a href=?module=tiket&act=edittiket&id=$r[id_tiket]>Edit</a> | 
		                <a href='$aksi?module=tiket&act=hapus&id=$r[id_tiket]&namafile=$r[gambar]'>Hapus</a></td>
		        </tr>";
      $no++;
    }
    echo "</table>";

    $jmldata = mysql_num_rows(mysql_query("SELECT * FROM tiket"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

    echo "<div id=paging>Hal: $linkHalaman</div><br>";
 
    break;
  
  case "tambahtiket":
    echo "<h2>Tambah Tiket</h2>
          <form method=POST action='$aksi?module=tiket&act=input' enctype='multipart/form-data'>
          <table>
          <tr><td width=100>Nama BUS</td>     <td> : <input type=text name='nama_tiket' size=60></td></tr>
          <tr><td>Kategori BUS</td>  <td> : 
          <select name='kategori'>
            <option value=0 selected>- Pilih Kategori -</option>";
            $tampil=mysql_query("SELECT * FROM kategori ORDER BY nama_kategori");
            while($r=mysql_fetch_array($tampil)){
              echo "<option value=$r[id_kategori]>$r[nama_kategori]</option>";
            }
    echo "</select></td></tr>
          <tr><td>Kota Tujuan</td>     <td> : <input type=text name='kota_tujuan' size=20></td></tr>
		  <tr><td>Tanggal Berangkat</td>     <td> : <input type=text name='tanggal_berangkat' size=20> dd-mm-yyyy</td></tr>
		  <tr><td>Jam Berangkat</td>     <td> : <input type=text name='jam_berangkat' size=20>HH:MM</td></tr>
          <tr><td>Harga</td>     <td> : <input type=text name='harga' size=10></td></tr>
          <tr><td>Diskon</td>     <td> : <input type=text name='diskon' size=2> %</td></tr>
          <tr><td>Kursi Tersedia</td>     <td> : <input type=text name='stok' size=3></td></tr>
          <tr><td>Deskripsi</td>  <td> <textarea name='deskripsi' style='width: 580px; height: 150px;'></textarea></td></tr>
          <tr><td>Logo Bus</td>      <td> : <input type=file name='fupload' size=40> 
                                          <br>Tipe gambar harus JPG/JPEG dan ukuran lebar maks: 400 px</td></tr>
          <tr><td colspan=2><input type=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;
    
  case "edittiket":
    $edit = mysql_query("SELECT * FROM tiket WHERE id_tiket='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Tiket</h2>
          <form method=POST enctype='multipart/form-data' action=$aksi?module=tiket&act=update>
          <input type=hidden name=id value=$r[id_tiket]>
          <table>
          <tr><td width=70>Nama Tiket</td>     <td> : <input type=text name='nama_tiket' size=60 value='$r[nama_tiket]'></td></tr>
          <tr><td>Kategori</td>  <td> : <select name='kategori'>";
 
          $tampil=mysql_query("SELECT * FROM kategori ORDER BY nama_kategori");
          if ($r[id_kategori]==0){
            echo "<option value=0 selected>- Pilih Kategori -</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_kategori]==$w[id_kategori]){
              echo "<option value=$w[id_kategori] selected>$w[nama_kategori]</option>";
            }
            else{
              echo "<option value=$w[id_kategori]>$w[nama_kategori]</option>";
            }
          }
    echo "</select></td></tr>
          <tr><td>Kota Tujuan</td>     <td> : <input type=text name='kota_tujuan' value=$r[kota_tujuan] size=20></td></tr>
		  <tr><td>Tanggal Berangkat</td>     <td> : <input type=text name='tanggal_berangkat' size=20></td></tr>
		  <tr><td>Jam Berangkat</td>     <td> : <input type=text name='jam_berangkat' size=20></td></tr>
          <tr><td>Harga</td>     <td> : <input type=text name='harga' value=$r[harga] size=10></td></tr>
          <tr><td>Diskon</td>     <td> : <input type=text name='diskon' value=$r[diskon] size=2></td></tr>
          <tr><td>Stok</td>     <td> : <input type=text name='stok' value=$r[stok] size=3></td></tr>
          <tr><td>Deskripsi</td>   <td> <textarea name='deskripsi' style='width: 600px; height: 350px;'>$r[deskripsi]</textarea></td></tr>
          <tr><td>Gambar</td>       <td> :  
          <img src='../foto_tiket/small_$r[gambar]'></td></tr>
          <tr><td>Ganti Gbr</td>    <td> : <input type=file name='fupload' size=30> *)</td></tr>
          <tr><td colspan=2>*) Apabila gambar tidak diubah, dikosongkan saja.</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
         </table></form>";
    break;  
}
}
?>
