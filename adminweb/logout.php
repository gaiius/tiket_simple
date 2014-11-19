<?php
  session_start();
  session_destroy();
  echo "<script>alert('Anda telah keluar dari Halaman Admin'); window.location = '../index.php'</script>";
?>
