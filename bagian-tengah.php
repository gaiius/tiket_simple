<script language="javascript">
function validasi(form){
  if (form.nama.value == ""){
    alert("Anda belum mengisikan Nama.");
    form.nama.focus();
    return (false);
  }    
  if (form.alamat.value == ""){
    alert("Anda belum mengisikan Alamat.");
    form.alamat.focus();
    return (false);
  }
  if (form.telpon.value == ""){
    alert("Anda belum mengisikan Telpon.");
    form.telpon.focus();
    return (false);
  }
  if (form.email.value == ""){
    alert("Anda belum mengisikan Email.");
    form.email.focus();
    return (false);
  }
  if (form.kota.value == 0){
    alert("Anda belum mengisikan Kota.");
    form.kota.focus();
    return (false);
  }
  if (form.kode.value == ""){
    alert("Anda belum mengisikan Kode.");
    form.kode.focus();
    return (false);
  }
  return (true);
}

function validasi2(form2){
  if (form2.email.value == ""){
    alert("Anda belum mengisikan Email.");
    form2.email.focus();
    return (false);
  }
  if (form2.password.value == ""){
    alert("Anda belum mengisikan Password.");
    form2.password.focus();
    return (false);
  }
  return (true);
}

function harusangka(jumlah){
  var karakter = (jumlah.which) ? jumlah.which : event.keyCode
  if (karakter > 31 && (karakter < 48 || karakter > 57))
    return false;
  return true;
}
</script>

<?php
// Halaman utama (Home)
if ($_GET['telo1'] == '1'){
  echo "<div class='center_title_bar'>Tiket Terbaru</div>";
  $sql=mysql_query("SELECT * FROM tiket ORDER BY id_tiket DESC LIMIT 6");
  while ($r=mysql_fetch_array($sql)){
    
    include "diskon_stok.php";

     echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'>$r[nama_tiket]</a></div>
          <div class='prod_price'>$divharga</div>
		  <div class='kursi'>Sisa Kursi : $stok</div>
		  <div class='product_title'>Tujuan  : 	$r[kota_tujuan]</a></div>
		  <div class='tgl_jam'> Date: $r[tanggal_berangkat]</a></div>
		  <div class='tgl_jam'> Time: $r[jam_berangkat]</a></div>
            </div>          
          <div class='prod_details_tab'>
             $tombol                    
          </div> 
          </div>";
  }
}

// Modul hasil pencarian tiket 
elseif ($_GET['module']=='hasilcari'){
  echo "<span class=judul_head>&#187; <b>Hasil Pencarian</b></span><br />";
  // menghilangkan spasi di kiri dan kanannya
  $kata = trim($_POST['kata']);
  // mencegah XSS
  $kata = htmlentities(htmlspecialchars($kata), ENT_QUOTES);

  // pisahkan kata per kalimat lalu hitung jumlah kata
  $pisah_kata = explode(" ",$kata);
  $jml_katakan = (integer)count($pisah_kata);
  $jml_kata = $jml_katakan-1;

  $cari = "SELECT * FROM tiket WHERE " ;
    for ($i=0; $i<=$jml_kata; $i++){
      $cari .= "deskripsi LIKE '%$pisah_kata[$i]%' OR nama_tiket LIKE '%$pisah_kata[$i]%'";
      if ($i < $jml_kata ){
        $cari .= " OR ";
      }
    }
  $cari .= " ORDER BY id_tiket DESC LIMIT 7";
  $hasil  = mysql_query($cari);
  $ketemu = mysql_num_rows($hasil);

  if ($ketemu > 0){
    echo "<p>Ditemukan <b>$ketemu</b> tiket dengan kata <font style='background-color:#00FFFF'><b>$kata</b></font> : </p>"; 
    while($t=mysql_fetch_array($hasil)){
		echo "<table><tr><td><span class=judul><a href=tiket-$t[id_tiket]-$t[tiket_seo].html>$t[nama_tiket]</a></span><br />";
      // Tampilkan hanya sebagian isi tiket
      $isi_tiket = htmlentities(strip_tags($t['deskripsi'])); // mengabaikan tag html
      $isi = substr($isi_tiket,0,250); // ambil sebanyak 250 karakter
      $isi = substr($isi_tiket,0,strrpos($isi," ")); // potong per spasi kalimat

      echo "$isi ... <a href=tiket-$t[id_tiket]-$t[tiket_seo].html>Selengkapnya</a>
            <br /><br /></td></tr>
            </table>";
    }                                                          
  }
  else{
    echo "<p>Tidak ditemukan tiket dengan kata <b>$kata</b></p>";
  }
}



// Modul detail tiket
elseif ($_GET[module]=='detailtiket'){
  // Tampilkan detail tiket berdasarkan tiket yang dipilih
	$detail=mysql_query("SELECT * FROM tiket,kategori    
                      WHERE kategori.id_kategori=tiket.id_kategori 
                      AND id_tiket='$_GET[id]'");
	$r = mysql_fetch_array($detail);
  
  include "diskon_stok.php";
  
  echo "<div class='center_title_bar'>Kategori: <a href='kategori-$r[id_kategori]-$r[kategori_seo].html'>$r[nama_kategori]</a></div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <a href='#'><img src='foto_tiket/$r[gambar]' border='0' /></a>
            <div class='prod_price'>$divharga</div>
            <p align=center>(stok: $r[stok])</p>
            $tombol
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>$r[nama_tiket]</div>
              <div>$r[deskripsi]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
          
// Tiket Lainnya (random)          
  $sql=mysql_query("SELECT * FROM tiket ORDER BY rand() LIMIT 3");
      
  echo "<div class='center_title_bar'>Tiket Lainnya</div>";
      
  while ($r=mysql_fetch_array($sql)){

  include "diskon_stok.php";

    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'><a href='tiket-$r[id_tiket]-$r[tiket_seo].html'>$r[nama_tiket]</a></div>
             <div class='product_img'>
               <a href='tiket-$r[id_tiket]-$r[tiket_seo].html'>
               <img src='foto_tiket/small_$r[gambar]' border='0' height='110'></a>
             </div>
            <div class='prod_price'>$divharga</div>
          </div>
          <div class='bottom_prod_box'></div>
          <div class='prod_details_tab'>
             $tombol            
             <a href='tiket-$r[id_tiket]-$r[tiket_seo].html' class='prod_details'>selengkapnya</a>            
          </div> 
          </div>";
  }                                      
}


// Modul tiket per kategori
elseif ($_GET[module]=='detailkategori'){
  // Tampilkan nama kategori
  $sq = mysql_query("SELECT nama_kategori from kategori where id_kategori='$_GET[id]'");
  $n = mysql_fetch_array($sq);

  echo "<div class='center_title_bar'>Kategori: $n[nama_kategori]</div>";

  // Tentukan berapa data yang akan ditampilkan per halaman (paging)
  $p      = new Paging3;
  $batas  = 6;
  $posisi = $p->cariPosisi($batas);

  // Tampilkan daftar tiket yang sesuai dengan kategori yang dipilih
 	$sql = mysql_query("SELECT * FROM tiket WHERE id_kategori='$_GET[id]' 
            ORDER BY id_tiket DESC LIMIT $posisi,$batas");		 
	$jumlah = mysql_num_rows($sql);

	// Apabila ditemukan tiket dalam kategori
	if ($jumlah > 0){
  while ($r=mysql_fetch_array($sql)){

  include "diskon_stok.php";

     echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'>$r[nama_tiket]</a></div>
          <div class='prod_price'>$divharga</div>
		  <div class='kursi'>Sisa Kursi : $stok</div>
		  <div class='product_title'>Tujuan  : 	$r[kota_tujuan]</a></div>
		  <div class='tgl_jam'> Date: $r[tanggal_berangkat]</a></div>
		  <div class='tgl_jam'> Time: $r[jam_berangkat]</a></div>
            </div>          
          <div class='prod_details_tab'>
             $tombol                    
          </div> 
          </div>";
  }  
  
  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM tiket WHERE id_kategori='$_GET[id]'"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halkategori], $jmlhalaman);

  echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
  }
  else{
    echo "<p align=center>Belum ada tiket pada kategori ini.</p>";
  }
}

// Menu utama di header

// Modul profil
elseif ($_GET[module]=='profilkami'){
  // Data profil mengacu pada id_modul=43
	$profil = mysql_query("SELECT * FROM modul WHERE id_modul='43'");
	$r      = mysql_fetch_array($profil);

  echo "<div class='center_title_bar'>Profil</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/$r[gambar]' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Profil Perusahaan</div>
              <div>$r[static_content]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}


// Modul cara pembelian
elseif ($_GET[module]=='carabeli'){
  // Data cara pembelian mengacu pada id_modul=45
	$profil = mysql_query("SELECT * FROM modul WHERE id_modul='45'");
	$r      = mysql_fetch_array($profil);

  echo "<div class='center_title_bar'>Cara Pemesanan Tiket</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/$r[gambar]' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Prosedur Pemesanan Tiket</div>
              <div>$r[static_content]</div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}


// Modul semua tiket
elseif ($_GET[module]=='semuatiket'){

  echo "<div class='center_title_bar'>Semua Tiket</div>";
  // Tentukan berapa data yang akan ditampilkan per halaman (paging)
  $p      = new Paging2;
  $batas  = 12;
  $posisi = $p->cariPosisi($batas);

  // Tampilkan semua tiket
  $sql=mysql_query("SELECT * FROM tiket ORDER BY id_tiket DESC LIMIT $posisi,$batas");

  while ($r=mysql_fetch_array($sql)){
  
    include "diskon_stok.php";
	
    echo "<div class='prod_box'>
          <div class='top_prod_box'></div> 
          <div class='center_prod_box'>            
             <div class='product_title'>$r[nama_tiket]</a></div>
          <div class='prod_price'>$divharga</div>
		  <div class='kursi'>Sisa Kursi : $stok</div>
		  <div class='product_title'>Tujuan  : 	$r[kota_tujuan]</a></div>
		  <div class='tgl_jam'> Date: $r[tanggal_berangkat]</a></div>
		  <div class='tgl_jam'> Time: $r[jam_berangkat]</a></div>
            </div>          
          <div class='prod_details_tab'>
             $tombol                    
          </div> 
          </div>";
  }  
    
  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM tiket"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[haltiket], $jmlhalaman);

  echo "<div class='center_title_bar'>Halaman : $linkHalaman </div>";
}


// Modul keranjang belanja
elseif ($_GET[module]=='keranjangbelanja'){
  // Tampilkan tiket-tiket yang telah dimasukkan ke keranjang belanja
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM orders_temp, tiket 
			                WHERE id_session='$sid' AND orders_temp.id_tiket=tiket.id_tiket");
  $ketemu=mysql_num_rows($sql);
  if($ketemu < 1){
    echo "<script>window.alert('Keranjang Belanjanya Masih Kosong');
        window.location=('index.php')</script>";
    }
  else{  
    echo "<div class='center_title_bar'>Booking Tiket</div>
          <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
          <form method=post action=aksi.php?module=keranjang&act=update>
          <table border=0 cellpadding=3 align=center>
          <tbody>
          <tr bgcolor=#f90><th>No</th><th>Nama Bus</th><th>Kota Tujuan</th><th>Jam berangkat</th><th>Tanggal Berangkat</th><th>Banyaknya</th>
          <th>Harga</th><th>Sub Total</th><th>Hapus</th></tr>";  
  
  $no=1;
  while($r=mysql_fetch_array($sql)){
    $disc        = ($r[diskon]/100)*$r[harga];
    $hargadisc   = number_format(($r[harga]-$disc),0,",",".");

    $subtotal    = ($r[harga]-$disc) * $r[jumlah];
    $total       = $total + $subtotal;  
    $subtotal_rp = format_rupiah($subtotal);
    $total_rp    = format_rupiah($total);
    $harga       = format_rupiah($r[harga]);
    
    echo "<tr bgcolor=#dad0d0><td>$no</td><input type=hidden name=id[$no] value=$r[id_orders_temp]>
              
              <td>$r[nama_tiket]</td>
       			  <td align=center>$r[kota_tujuan]</td>
				  
				  <td align=center>$r[jam_berangkat]</td>
          		  <td align=center>$r[tanggal_berangkat]</td>
			  <td><select name='jml[$no]' value=$r[jumlah] onChange='this.form.submit()'>";
              for ($j=1;$j <= $r[stok];$j++){
                  if($j == $r[jumlah]){
                   echo "<option selected>$j</option>";
                  }else{
                   echo "<option>$j</option>";
                  }
              }
        echo "</select></td>
              <td>$hargadisc</td>
              <td>$subtotal_rp</td>
              <td align=center><a href='aksi.php?module=keranjang&act=hapus&id=$r[id_orders_temp]'>
              <img src=images/kali.png border=0 title=Hapus></a></td>
          </tr>";
    $no++; 
  } 
  echo "<tr><td colspan=6 align=right><br><b>Total</b>:</td><td colspan=2><br>Rp. <b>$total_rp</b></td></tr>
        <tr><td colspan=3><br /><a href='javascript:history.go(-1)' class='button'>Lanjut Pemesanan</a><br /></td>
        <td colspan=5 align=right><br /><a href='selesai-belanja.html' class='button'>Selesai Pemesanan</a></a><br /></td></tr>
        </tbody></table></form><br />
        
        </div>
        
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
  }

}

// Modul hubungi kami
elseif ($_GET[module]=='hubungikami'){
  echo "<div class='center_title_bar'>Hubungi Kami</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Hubungi Kami Secara Online:</div>
              <div>
        <table width=100% style='border: 1pt dashed #0000CC;padding: 10px;'>
        <form action=hubungi-aksi.html method=POST>
        <tr><td>Nama</td><td> : <input type=text name=nama size=30></td></tr>
        <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
        <tr><td>Subjek</td><td> : <input type=text name=subjek size=40></td></tr>
        <tr><td valign=top>Pesan</td><td> <textarea name=pesan  style='width: 270px; height: 100px;'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><img src='captcha.php'></td></tr>
        <tr><td>&nbsp;</td><td>(masukkan 6 kode di atas)<br /><input type=text name=kode size=6 maxlength=6><br /></td></tr>
        </td><td colspan=2><input type=submit name=submit value=Kirim></td></tr>
        </form></table>
          </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                              
}

// Modul hubungi aksi
elseif ($_GET[module]=='hubungiaksi'){
$nama=trim($_POST['nama']);
$email=trim($_POST['email']);
$subjek=trim($_POST['subjek']);
$pesan=trim($_POST['pesan']);

if (empty($nama)){
  echo "Anda belum mengisikan NAMA<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($email)){
  echo "Anda belum mengisikan EMAIL<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($subjek)){
  echo "Anda belum mengisikan SUBJEK<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
elseif (empty($pesan)){
  echo "Anda belum mengisikan PESAN<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b>";
}
else{
	if(!empty($_POST['kode'])){
		if($_POST['kode']==$_SESSION['captcha_session']){

  mysql_query("INSERT INTO hubungi(nama,
                                   email,
                                   subjek,
                                   pesan,
                                   tanggal) 
                        VALUES('$_POST[nama]',
                               '$_POST[email]',
                               '$_POST[subjek]',
                               '$_POST[pesan]',
                               '$tgl_sekarang')");

  echo "<div class='center_title_bar'>Hubungi Kami</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Terimakasih</div>
              <div>
              <br />Terimakasih telah menghubungi kami.<br /><br /> Kami akan segera membalasnya ke email Anda.
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
		}else{
			echo "Kode yang Anda masukkan tidak cocok<br />
			      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
		}
	}else{
		echo "Anda belum memasukkan kode<br />
  	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
	}
}                              
}


// Modul hasil pencarian tiket 
elseif ($_GET['module']=='hasilcari'){
  // menghilangkan spasi di kiri dan kanannya
  $kata = trim($_POST['kata']);
  // mencegah XSS
  $kata = htmlentities(htmlspecialchars($kata), ENT_QUOTES);

  // pisahkan kata per kalimat lalu hitung jumlah kata
  $pisah_kata = explode(" ",$kata);
  $jml_katakan = (integer)count($pisah_kata);
  $jml_kata = $jml_katakan-1;

  $cari = "SELECT * FROM tiket WHERE " ;
    for ($i=0; $i<=$jml_kata; $i++){
      $cari .= "deskripsi LIKE '%$pisah_kata[$i]%' OR nama_tiket LIKE '%$pisah_kata[$i]%'";
      if ($i < $jml_kata ){
        $cari .= " OR ";
      }
    }
  $cari .= " ORDER BY id_tiket DESC LIMIT 7";
  $hasil  = mysql_query($cari);
  $ketemu = mysql_num_rows($hasil);

  echo "<div class='center_title_bar'>Hasil Pencarian</div>";

  if ($ketemu > 0){
  echo "<div class='prod_details_cari'>Ditemukan <b>$ketemu</b> tiket dengan kata <font style='background-color:#00FFFF'><b>$kata</b></font> : </div>";
    while($t=mysql_fetch_array($hasil)){
      // Tampilkan hanya sebagian isi tiket
      $isi_tiket = htmlentities(strip_tags($t['deskripsi'])); // mengabaikan tag html
      $isi = substr($isi_tiket,0,250); // ambil sebanyak 250 karakter
      $isi = substr($isi_tiket,0,strrpos($isi," ")); // potong per spasi kalimat
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
            <div class='product_title_big'><a href=tiket-$t[id_tiket]-$t[tiket_seo].html>$t[nama_tiket]</a></div>
              <div>
              <br />$isi ... <a href=tiket-$t[id_tiket]-$t[tiket_seo].html>selengkapnya</a>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
      }        
    }                                                          
  else{
    echo "<p>Tidak ditemukan tiket dengan kata <b>$kata</b></p>";
  }
}


// Modul download katalog
elseif ($_GET['module']=='downloadkatalog'){
  echo "<div class='center_title_bar'>Download Katalog</div>";
  // Tampilkan daftar katalog download
 	$sql = mysql_query("SELECT * FROM download ORDER BY id_download DESC");		 

  echo "<br /><br /><ul>";   
   while($d=mysql_fetch_array($sql)){
      echo "<li><a href='downlot.php?file=$d[nama_file]'>$d[judul]</a></li>";
	 }
  echo "</ul><br />";	
}


// Modul selesai belanja
elseif ($_GET[module]=='selesaibelanja'){
  $sid = session_id();
  $sql = mysql_query("SELECT * FROM orders_temp, tiket 
			                WHERE id_session='$sid' AND orders_temp.id_tiket=tiket.id_tiket");
  $ketemu=mysql_num_rows($sql);
  if($ketemu < 1){
   echo "<script> alert('Keranjang belanja masih kosong');window.location='index.php'</script>\n";
   	 exit(0);
	}
	else{
  echo "<div class='center_title_bar'>Kustomer Lama</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form2 action=simpan-transaksi-member.html method=POST onSubmit=\"return validasi2(this)\">
      <table>
      <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
      <tr><td>Password</td><td> : <input type=password name=password size=30></td></tr>
      <tr><td><input type='submit' class='button' value='Login'></td><td align=right><a href='lupa-password.html'>Lupa Password?</a></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      

  echo "<div class='center_title_bar'>Kustomer Baru</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form action=simpan-transaksi.html method=POST onSubmit=\"return validasi(this)\">
      <table>
      <tr><td>Nama Lengkap</td><td> : <input type=text name=nama size=30></td></tr>
      <tr><td>Password</td><td> : <input type=text name=password></td></tr>
      <tr><td>Alamat Pengiriman</td><td> : <input type=text name=alamat size=80>
      <br />: Alamat pengiriman harus di isi lengkap, termasuk kota/kabupaten dan kode posnya.</td></tr>
      <tr><td>Telpon/HP</td><td> : <input type=text name=telpon></td></tr>
      <tr><td>Email</td><td> : <input type=text name=email size=30></td></tr>
       <tr><td>&nbsp;</td><td><img src='captcha.php'></td></tr>
        <tr><td>&nbsp;</td><td>(Masukkan 6 kode diatas)<br /><input type=text name=kode size=6 maxlength=6><br /></td></tr>
      <tr><td colspan=2><input type='submit' class='button' value='Daftar'></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
  }
}      


// Modul lupa password
elseif ($_GET[module]=='lupapassword'){
  echo "<div class='center_title_bar'>Lupa Password</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      <form name=form3 action=kirim-password.html method=POST>
      <table>
      <tr><td>Masukkan Email Anda</td><td> : <input type=text name=email size=30></td></tr>
      <tr><td colspan=2><input type='submit' class='button' value='Kirim'></td></td></tr>
      </table>
      </form>
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
}


// Modul kirim password
elseif ($_GET[module]=='kirimpassword'){

// Cek email kustomer di database
$cek_email=mysql_num_rows(mysql_query("SELECT email FROM kustomer WHERE email='$_POST[email]'"));
// Kalau email tidak ditemukan
if ($cek_email == 0){
  echo "Email <b>$_POST[email]</b> tidak terdaftar di database kami.<br />
        <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
else{

$password_baru = substr(md5(uniqid(rand(),1)),3,10);

// ganti password kustomer dengan password yang baru (reset password)
$query=mysql_query("update kustomer set password=md5('$password_baru') where email='$_POST[email]'");

// dapatkan email_pengelola dari database
$sql2 = mysql_query("select email_pengelola from modul where id_modul='43'");
$j2   = mysql_fetch_array($sql2);

$subjek="Password Baru";
$pesan="Password Anda yang baru adalah <b>$password_baru</b>";
// Kirim email dalam format HTML
$dari = "From: $j2[email_pengelola]\r\n";
$dari .= "Content-type: text/html\r\n";

// Kirim password ke email kustomer
mail($_POST[email],$subjek,$pesan,$dari);

  echo "<div class='center_title_bar'>Kirim Password</div>
    	  <div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
                 <div class='product_img_big'>
                 <img src='foto_banner/gedung.jpg' border='0' />
            </div>
          <div class='details_big_box'>
            <div class='product_title_big'>Password Sudah Terkirim</div>
              <div>
              <br />Silahkan cek email Anda.
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";
}                              
}


// Modul simpan transaksi
elseif ($_GET[module]=='simpantransaksi'){
$kar1=strstr($_POST[email], "@");
$kar2=strstr($_POST[email], ".");

// Cek email kustomer di database
$cek_email=mysql_num_rows(mysql_query("SELECT email FROM kustomer WHERE email='$_POST[email]'"));
// Kalau email sudah ada yang pakai
if ($cek_email > 0){
  echo "Email <b>$_POST[email]</b> sudah ada yang pakai.<br />
        <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
elseif (empty($_POST[nama]) || empty($_POST[password]) || empty($_POST[alamat]) || empty($_POST[telpon]) || empty($_POST[email]) || empty($_POST[kode])){
  echo "Data yang Anda isikan belum lengkap<br />
  	    <a href='selesai-belanja.html'><b>Ulangi Lagi</b>";
}
elseif (!ereg("[a-z|A-Z]","$_POST[nama]")){
  echo "Nama tidak boleh diisi dengan angka atau simbol.<br />
 	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
elseif (strlen($kar1)==0 OR strlen($kar2)==0){
  echo "Alamat email Anda tidak valid, mungkin kurang tanda titik (.) atau tanda @.<br />
 	      <a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
else{

// fungsi untuk mendapatkan isi keranjang belanja
function isi_keranjang(){
	$isikeranjang = array();
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
	
	while ($r=mysql_fetch_array($sql)) {
		$isikeranjang[] = $r;
	}
	return $isikeranjang;
}

$tgl_skrg = date("Ymd");
$jam_skrg = date("H:i:s");

if(!empty($_POST['kode'])){
  if($_POST['kode']==$_SESSION['captcha_session']){

function antiinjection($data){
  $filter_sql = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter_sql;
}

$nama   = antiinjection($_POST['nama']);
$alamat = antiinjection($_POST['alamat']);
$telpon = antiinjection($_POST['telpon']);
$email = antiinjection($_POST['email']);
$password=md5($_POST['password']);

// simpan data kustomer 
mysql_query("INSERT INTO kustomer(nama_lengkap, password, alamat, telpon, email, id_kota) 
             VALUES('$nama','$password','$alamat','$telpon','$email','$_POST[kota]')");

// mendapatkan nomor kustomer
$id_kustomer=mysql_insert_id();

// simpan data pemesanan 
mysql_query("INSERT INTO orders(tgl_order,jam_order,id_kustomer) VALUES('$tgl_skrg','$jam_skrg','$id_kustomer')");
  
// mendapatkan nomor orders
$id_orders=mysql_insert_id();

// panggil fungsi isi_keranjang dan hitung jumlah tiket yang dipesan
$isikeranjang = isi_keranjang();
$jml          = count($isikeranjang);

// simpan data detail pemesanan  
for ($i = 0; $i < $jml; $i++){
  mysql_query("INSERT INTO orders_detail(id_orders, id_tiket, jumlah) 
               VALUES('$id_orders',{$isikeranjang[$i]['id_tiket']}, {$isikeranjang[$i]['jumlah']})");
}
  
// setelah data pemesanan tersimpan, hapus data pemesanan di tabel pemesanan sementara (orders_temp)
for ($i = 0; $i < $jml; $i++) {
  mysql_query("DELETE FROM orders_temp
	  	         WHERE id_orders_temp = {$isikeranjang[$i]['id_orders_temp']}");
}

  echo "<div class='center_title_bar'>Proses Transaksi Selesai</div>";

    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      Data pemesan beserta ordernya adalah sebagai berikut: <br />
      <table>
      <tr><td>Nama           </td><td> : <b>$nama</b> </td></tr>
      <tr><td>Alamat Lengkap </td><td> : $alamat </td></tr>
      <tr><td>Telpon         </td><td> : $telpon </td></tr>
      <tr><td>E-mail         </td><td> : $email </td></tr>
      </table><hr /><br />
      
      Nomor Order: <b>$id_orders</b><br /><br />";

      $daftartiket=mysql_query("SELECT * FROM orders_detail,tiket 
                                 WHERE orders_detail.id_tiket=tiket.id_tiket 
                                 AND id_orders='$id_orders'");

echo "<table cellpadding=10>
      <tr bgcolor=#6da6b1><th>No</th><th>Nama Tiket</th><th>Kota Tujuan</th><th>Qty</th><th>Harga Satuan</th><th>Sub Total</th></tr>";
      
$pesan="Terimakasih telah melakukan pemesanan online di toko online kami <br /><br />  
        Nama: $nama <br />
        Password: $_POST[password]<br />
        Alamat: $alamat <br/>
        Telpon: $telpon <br /><hr />
        
        Nomor Order: $id_orders <br />
        Data order Anda adalah sebagai berikut: <br /><br />";
        
$no=1;
while ($d=mysql_fetch_array($daftartiket)){
   $disc        = ($d[diskon]/100)*$d[harga];
   $hargadisc   = number_format(($d[harga]-$disc),0,",","."); 
   $subtotal    = ($d[harga]-$disc) * $d[jumlah];

   $subtotalberat = $d[berat] * $d[jumlah]; // total berat per item tiket 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all tiket yang dibeli

   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($d[harga]);

   echo "<tr bgcolor=#dad0d0><td>$no</td><td>$d[nama_tiket]</td><td align=center>$d[kota_tujuan]</td><td align=center>$d[jumlah]</td>
                             <td align=right>$harga</td><td align=right>$subtotal_rp</td></tr>";

   $pesan.="$d[jumlah] $d[nama_tiket] -> Rp. $harga -> Subtotal: Rp. $subtotal_rp <br />";
   $no++;
}

$ongkos=mysql_fetch_array(mysql_query("SELECT ongkos_kirim FROM kota WHERE id_kota='$_POST[kota]'"));
$ongkoskirim1=$ongkos[ongkos_kirim];
$ongkoskirim = $ongkoskirim1 * $totalberat;

$grandtotal    = $total + $ongkoskirim; 

$ongkoskirim_rp = format_rupiah($ongkoskirim);
$ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
$grandtotal_rp  = format_rupiah($grandtotal);  

// dapatkan email_pengelola dan nomor rekening dari database
$sql2 = mysql_query("select email_pengelola,nomor_rekening,nomor_hp from modul where id_modul='43'");
$j2   = mysql_fetch_array($sql2);

$pesan.="<br /><br />Total : Rp. $total_rp 
        
         <br />Grand Total : Rp. $grandtotal_rp 
         <br /><br />Silahkan lakukan pembayaran sebanyak Grand Total yang tercantum, rekeningnya: $j2[nomor_rekening]
         <br />Apabila sudah transfer, konfirmasi ke nomor: $j2[nomor_hp]";

$subjek="Pemesanan Online";

// Kirim email dalam format HTML
$dari = "From: $j2[email_pengelola]\r\n";
$dari .= "Content-type: text/html\r\n";

// Kirim email ke kustomer
mail($email,$subjek,$pesan,$dari);

// Kirim email ke pengelola toko online
mail("$j2[email_pengelola]",$subjek,$pesan,$dari);

echo "<tr><td colspan=5 align=right>Total : Rp. </td><td align=right><b>$total_rp</b></td></tr>     
      <tr><td colspan=5 align=right>Grand Total : Rp. </td><td align=right><b>$grandtotal_rp</b></td></tr>
      </table>";
echo "<hr /><p>Data order dan nomor rekening transfer sudah terkirim ke email Anda. <br />
               Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p><br />      
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";                      
}
else{
echo "Kode yang Anda masukkan tidak cocok<br />
<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
}else{
echo "Anda belum memasukkan kode<br />
<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
}
}


// Modul simpan transaksi member
elseif ($_GET[module]=='simpantransaksimember'){
$email = $_POST['email'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM	kustomer WHERE email='$email' AND password='$password'";
$hasil = mysql_query($sql);
$r = mysql_fetch_array($hasil);

if(mysql_num_rows($hasil) == 0){
			 echo "Email atau Password Anda tidak benar<br />";
			 echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>";
}
else{
// fungsi untuk mendapatkan isi keranjang belanja
function isi_keranjang(){
	$isikeranjang = array();
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
	
	while ($r=mysql_fetch_array($sql)) {
		$isikeranjang[] = $r;
	}
	return $isikeranjang;
}

$tgl_skrg = date("Ymd");
$jam_skrg = date("H:i:s");

$id = mysql_fetch_array(mysql_query("SELECT id_kustomer FROM kustomer WHERE email='$email' AND password='$password'"));

// mendapatkan nomor kustomer
$id_kustomer=$id[id_kustomer];

// simpan data pemesanan 
mysql_query("INSERT INTO orders(tgl_order,jam_order,id_kustomer) VALUES('$tgl_skrg','$jam_skrg','$id_kustomer')");

  
// mendapatkan nomor orders
$id_orders=mysql_insert_id();

// panggil fungsi isi_keranjang dan hitung jumlah tiket yang dipesan
$isikeranjang = isi_keranjang();
$jml          = count($isikeranjang);

// simpan data detail pemesanan  
for ($i = 0; $i < $jml; $i++){
  mysql_query("INSERT INTO orders_detail(id_orders, id_tiket, jumlah) 
               VALUES('$id_orders',{$isikeranjang[$i]['id_tiket']}, {$isikeranjang[$i]['jumlah']})");
}
  
// setelah data pemesanan tersimpan, hapus data pemesanan di tabel pemesanan sementara (orders_temp)
for ($i = 0; $i < $jml; $i++) {
  mysql_query("DELETE FROM orders_temp
	  	         WHERE id_orders_temp = {$isikeranjang[$i]['id_orders_temp']}");
}

  echo "<div class='center_title_bar'>Proses Transaksi Selesai</div>";
    	  echo "<div class='prod_box_big'>
        	<div class='top_prod_box_big'></div>
        <div class='center_prod_box_big'>            
          <div class='details_big_cari'>
              <div>
      Data pemesan beserta ordernya adalah sebagai berikut: <br />
      <table>
      <tr><td>Nama Lengkap   </td><td> : <b>$r[nama_lengkap]</b> </td></tr>
      <tr><td>Alamat Lengkap </td><td> : $r[alamat] </td></tr>
      <tr><td>Telpon         </td><td> : $r[telpon] </td></tr>
      <tr><td>E-mail         </td><td> : $r[email] </td></tr></table><hr /><br />
      
      Nomor Order: <b>$id_orders</b><br /><br />";

      $daftartiket=mysql_query("SELECT * FROM orders_detail,tiket 
                                 WHERE orders_detail.id_tiket=tiket.id_tiket 
                                 AND id_orders='$id_orders'");

echo "<table cellpadding=10>
      <tr bgcolor=#F90><th>No</th><th>Nama Bus</th><th>Kota Tujuan</th><th>Qty</th><th>Harga Satuan</th><th>Sub Total</th></tr>";
      
$pesan="Terimakasih telah melakukan pemesanan tiket di toko online kami <br /><br />  
        Nama: $r[nama_lengkap] <br />
        Alamat: $r[alamat] <br/>
        Telpon: $r[telpon] <br /><hr />
        
        Nomor Order: $id_orders <br />
        Data order Anda adalah sebagai berikut: <br /><br />";
        
$no=1;
while ($d=mysql_fetch_array($daftartiket)){
   $disc        = ($d[diskon]/100)*$d[harga];
   $hargadisc   = number_format(($d[harga]-$disc),0,",","."); 
   $subtotal    = ($d[harga]-$disc) * $d[jumlah];

   $subtotalberat = $d[berat] * $d[jumlah]; // total berat per item tiket 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all tiket yang dibeli

   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($d[harga]);

   echo "<tr bgcolor=#dad0d0><td>$no</td><td>$d[nama_tiket]</td><td align=center>$d[kota_tujuan]</td><td align=center>$d[jumlah]</td>
                             <td align=right>$harga</td><td align=right>$subtotal_rp</td></tr>";

   $pesan.="$d[jumlah] $d[nama_tiket] -> Rp. $harga -> Subtotal: Rp. $subtotal_rp <br />";
   $no++;
}

$kota=$r[id_kota];

$ongkos=mysql_fetch_array(mysql_query("SELECT ongkos_kirim FROM kota WHERE id_kota='$kota'"));
$ongkoskirim1=$ongkos[ongkos_kirim];
$ongkoskirim = $ongkoskirim1 * $totalberat;

$grandtotal    = $total + $ongkoskirim; 

$ongkoskirim_rp = format_rupiah($ongkoskirim);
$ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
$grandtotal_rp  = format_rupiah($grandtotal);  

// dapatkan email_pengelola dan nomor rekening dari database
$sql2 = mysql_query("select email_pengelola,nomor_rekening,nomor_hp from modul where id_modul='43'");
$j2   = mysql_fetch_array($sql2);

$pesan.="<br /><br />Total : Rp. $total_rp 	 
         <br />Grand Total : Rp. $grandtotal_rp 
         <br /><br />Silahkan lakukan pembayaran sebanyak Grand Total yang tercantum, rekeningnya: $j2[nomor_rekening]
         <br />Apabila sudah transfer, konfirmasi ke nomor: $j2[nomor_hp]";

$subjek="Pemesanan Online";

// Kirim email dalam format HTML
$dari = "From: $j2[email_pengelola]\r\n";
$dari .= "Content-type: text/html\r\n";

// Kirim email ke kustomer
mail($email,$subjek,$pesan,$dari);

// Kirim email ke pengelola toko online
mail("$j2[email_pengelola]",$subjek,$pesan,$dari);

echo "<tr><td colspan=5 align=right>Total : Rp. </td><td align=right><b>$total_rp</b></td></tr>      
      <tr><td colspan=5 align=right>Grand Total : Rp. </td><td align=right><b>$grandtotal_rp</b></td></tr>
      </table>";
echo "<hr /><p>Data order dan nomor rekening transfer sudah terkirim ke email Anda. <br />
               Apabila Anda tidak melakukan pembayaran dalam 3 hari, maka transaksi dianggap batal.</p><br />      
              </div>
          </div>    
          </div>
            <div class='bottom_prod_box_big'></div>
          </div>";  
}                    
}
?>
