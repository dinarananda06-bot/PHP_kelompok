<?php
// Naik satu level ke atas (../) untuk koneksi
include '../koneksi.php';

// Fungsi keamanan data
if (!function_exists('clean_input')) {
    function clean_input($data) {
        global $koneksi;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return mysqli_real_escape_string($koneksi, $data);
    }
}

$page_title = "Edit Pegawai";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pegawai yang ada di database
$query = "SELECT * FROM pegawai WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$pegawai = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$pegawai) {
    session_start();
    $_SESSION['pesan'] = "Pegawai tidak ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: ../index.php?page=data_pegawai");
    exit();
}

// PROSES SIMPAN PERUBAHAN
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pegawai = clean_input($_POST['nama_pegawai']);
    $jabatan      = clean_input($_POST['jabatan']);
    $kontak       = clean_input($_POST['kontak']);
    $email        = clean_input($_POST['email']);
    $gaji         = clean_input($_POST['gaji']);
    $status       = clean_input($_POST['status']);
    
    // Default foto adalah foto lama
    $foto_nama = $pegawai['foto'];
    
    // LOGIKA GANTI FOTO
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_name = $_FILES['foto']['name'];
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed_ext)) {
            // Nama file baru
            $foto_baru = time() . "_" . $file_name;
            $target_file = $target_dir . $foto_baru;
            
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Hapus foto lama jika ada & bukan placeholder
                if (!empty($pegawai['foto']) && file_exists($target_dir . $pegawai['foto'])) {
                    unlink($target_dir . $pegawai['foto']);
                }
                // Update variabel nama foto
                $foto_nama = $foto_baru;
            }
        } else {
            echo "<script>alert('Format foto harus JPG/PNG!');</script>";
        }
    }

    // Query Update
    $query = "UPDATE pegawai SET 
              nama_pegawai = '$nama_pegawai',
              jabatan = '$jabatan',
              kontak = '$kontak',
              email = '$email',
              gaji = '$gaji',
              foto = '$foto_nama',
              status = '$status'
              WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        session_start();
        $_SESSION['pesan'] = "Data pegawai berhasil diperbarui!";
        $_SESSION['tipe'] = "success";
        header("Location: ../index.php?page=data_pegawai");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="content-wrapper">
    <main class="main-content">
        <div class="page-header">
            <h2>Edit Pegawai</h2>
            <div class="breadcrumb">
                <a href="../index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="../index.php?page=data_pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Pegawai</span>
            </div>
        </div>
        
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Data Pegawai</h3>
                </div>
                
                <div class="card-body">
                    <form method="POST" class="form-vertical" enctype="multipart/form-data">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Foto Pegawai</label>
                                <div style="margin-bottom: 10px;">
                                    <?php if(!empty($pegawai['foto'])): ?>
                                        <img src="../uploads/<?php echo $pegawai['foto']; ?>" width="300" style="border-radius: 5px;">
                                        <div style="font-size: 12px; color: #666;">Foto saat ini</div>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Belum ada foto</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text" name="nama_pegawai" class="form-control"
                                       value="<?php echo htmlspecialchars($pegawai['nama_pegawai']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Jabatan *</label>
                                <select name="jabatan" required class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Manager" <?php echo $pegawai['jabatan'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
                                    <option value="Supervisor" <?php echo $pegawai['jabatan'] == 'Supervisor' ? 'selected' : ''; ?>>Supervisor</option>
                                    <option value="Staff Admin" <?php echo $pegawai['jabatan'] == 'Staff Admin' ? 'selected' : ''; ?>>Staff Admin</option>
                                    <option value="Staff Gudang" <?php echo $pegawai['jabatan'] == 'Staff Gudang' ? 'selected' : ''; ?>>Staff Gudang</option>
                                    <option value="Marketing" <?php echo $pegawai['jabatan'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                    <option value="IT Support" <?php echo $pegawai['jabatan'] == 'IT Support' ? 'selected' : ''; ?>>IT Support</option>
                                    <option value="Lainnya" <?php echo $pegawai['jabatan'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Gaji (Rp) *</label>
                                <input type="number" name="gaji" min="0" required class="form-control"
                                       value="<?php echo $pegawai['gaji']; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>No. Telepon / WA *</label>
                                <input type="text" name="kontak" required class="form-control"
                                       value="<?php echo htmlspecialchars($pegawai['kontak']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?php echo htmlspecialchars($pegawai['email']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Status Pegawai</label>
                                <select name="status" required class="form-control">
                                    <option value="aktif" <?php echo $pegawai['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="nonaktif" <?php echo $pegawai['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="card-header">
                            <a href="index.php?page=data_barang" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>