<?php
$aksi="modul/mod_profil/aksi_profil.php";
switch($_GET[act]){
  // Tampil Profil
  default:
    $sql  = mysql_query("SELECT * FROM modul WHERE id_modul='43'");
    $r    = mysql_fetch_array($sql);

    echo "<h2>Profil Toko Online</h2>
          <form method=POST enctype='multipart/form-data' action=$aksi?module=profil&act=update>
          <input type=hidden name=id value=$r[id_modul]>
          <table>
          <tr><td>Nama Toko Online</td><td> : <input type=text name='nama_toko' value='$r[nama_toko]' size=50></td></tr>
          <tr><td>Meta Deskripsi</td><td> : <input type=text name='meta_deskripsi' value='$r[meta_deskripsi]' size=100></td></tr>
          <tr><td>Meta Keyword</td><td> : <input type=text name='meta_keyword' value='$r[meta_keyword]' size=100></td></tr>
          <tr><td>Email Pengelola</td><td> : <input type=text name='email_pengelola' value='$r[email_pengelola]' size=40></td></tr>
          <tr><td>No.HP Pengelola</td><td> : <input type=text name='nomor_hp' value='$r[nomor_hp]'></td></tr>
          <tr><td>Nomor Rekening</td><td> : <input type=text name='nomor_rekening' value='$r[nomor_rekening]' size=100></td></tr>
          <tr><td>Gambar</td><td> : <img src=../foto_banner/$r[gambar]></td></tr>
         <tr><td>Ganti Foto</td><td> : <input type=file size=30 name=fupload></td></tr>
         <tr><td>Isi Profil Toko</td><td><textarea name='isi' style='width: 560px; height: 250px;'>$r[static_content]</textarea></td></tr>
         <tr><td colspan=2><input type=submit value=Update>
                           <input type=button value=Batal onclick=self.history.back()></td></tr>
         </form></table>";
    break;  
}
?>
