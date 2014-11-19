<?php
	$sid = session_id();
	$sql = mysql_query("SELECT SUM(jumlah*(harga-(diskon/100)*harga)) as total,SUM(jumlah) as totaljumlah FROM orders_temp, tiket 
			                WHERE id_session='$sid' AND orders_temp.id_tiket=tiket.id_tiket");
	
    //$disc        = ($r[diskon]/100)*$r[harga];
    //$subtotal    = ($r[harga]-$disc) * $r[jumlah];
		                
	while($r=mysql_fetch_array($sql)){

  if ($r['totaljumlah'] != ""){
    $total_rp    = format_rupiah($r['total']);

    echo "($r[totaljumlah]) item tiket <br />
          <span class='border_cart'></span>
          Total: <span class='price'>Rp. $total_rp</span><br />
          <i><a href='keranjang-belanja.html'>Keranjang</a></i> | <i><a href='selesai-belanja.html'>Selesai</a></i><br />";
  }
  else{
    echo "<i>0 item tiket</i><br />
          <span class='border_cart'></span>
          Total: <span class='price'>Rp. 0</span>";
  }
  }
?>

