<?php
include 'koneksidb.php';

$page_title = "Detail Pegawai";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pegawai
$query  = "SELECT * FROM pegawai WHERE id = $id";
$result = mysqli_query($koneksidb, $query);
$pg = mysqli_fetch_assoc($result);

if (!$pg) {
    $_SESSION['pesan'] = "Pegawai tidak ditemukan!";
    $_SESSION['tipe']  = "error";
    header("Location: pegawai.php");
    exit();
}

?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Detail Pegawai</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="pegawai.php">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail Pegawai</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Data Pegawai</h3>
                    <a href="pegawai.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">

                    <div class="form-vertical">

                        <!-- Foto Pegawai -->
                        <div class="form-group">
                            <label><i class="fas fa-image"></i> Foto Pegawai</label><br>

                            <?php if (!empty($pg['foto'])): ?>
                                <img src="uploads/<?= $pg['foto']; ?>" 
                                     width="150" 
                                     style="border-radius:10px; margin-bottom:10px;">
                            <?php else: ?>
                                <p>Tidak ada foto.</p>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">

                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Nama Pegawai</label>
                                <input type="text" value="<?= htmlspecialchars($pg['nama_pegawai']); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-briefcase"></i> Jabatan</label>
                                <input type="text" value="<?= htmlspecialchars($pg['jabatan']); ?>" readonly>
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="text" value="<?= htmlspecialchars($pg['email']); ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-toggle-on"></i> Status</label>
                                <input type="text" 
                                       value="<?= ucfirst($pg['status']); ?>" 
                                       readonly>
                            </div>

                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> Dibuat Pada</label>
                            <input type="text"
                                   value="<?= $pg['created_at']; ?>"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-clock"></i> Terakhir Update</label>
                            <input type="text"
                                   value="<?= $pg['updated_at']; ?>"
                                   readonly>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>
