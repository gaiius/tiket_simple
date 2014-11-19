

    <div class="title_box">Kategori</div>    
      <ul class="left_menu">
            <?php
            $kategori=mysql_query("select nama_kategori, kategori.id_kategori, kategori_seo,  
                                  count(tiket.id_tiket) as jml 
                                  from kategori left join tiket 
                                  on tiket.id_kategori=kategori.id_kategori 
                                  group by nama_kategori");
            $no=1;
            while($k=mysql_fetch_array($kategori)){
              if(($no % 2)==0){
                echo "<li class='genap'><a href='kategori-$k[id_kategori]-$k[kategori_seo].html'> $k[nama_kategori] ($k[jml])</a></li>";
              }
              else{
                echo "<li class='ganjil'><a href='kategori-$k[id_kategori]-$k[kategori_seo].html'> $k[nama_kategori] ($k[jml])</a></li>";              
              }
              $no++;
            }
            ?>
      </ul>
       
     <div class="title_box">Tiket</div>  
     <div class="border_box">

<?php
$banner=mysql_query("SELECT * FROM banner ORDER BY id_banner DESC LIMIT 4");
while($b=mysql_fetch_array($banner)){
  echo "<p align='center'><a href='$b[url]'' target='_blank' title='$b[judul]'><img src='foto_banner/$b[gambar]' border=0></a></p>";
}

?>
      </div>

     <div class="banner_adds"></div>     