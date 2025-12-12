<?php
include 'koneksidb.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM pegawai WHERE id = $id";

    if (mysqli_query($koneksidb, $query)) {
        $_SESSION['pesan'] = "Data pegawai berhasil dihapus!";
        $_SESSION['tipe']  = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus data Pegawai: " . mysqli_error($koneksi);
        $_SESSION['tipe']  = "error";
    }
}

header("Location: index.php?page=data_barang");
exit();
?>
