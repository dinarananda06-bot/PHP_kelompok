<?php
include 'koneksidb.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM barang WHERE id = $id";

    if (mysqli_query($koneksidb, $query)) {
        $_SESSION['pesan'] = "Barang berhasil dihapus!";
        $_SESSION['tipe']  = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus barang: " . mysqli_error($koneksi);
        $_SESSION['tipe']  = "error";
    }
}

header("Location: index.php?page=data_barang");
exit();
?>
