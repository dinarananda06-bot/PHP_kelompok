<?php
include '../koneksi.php';


if (!function_exists('clean_input')) {
    function clean_input($data) {
        global $koneksi;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return mysqli_real_escape_string($koneksi, $data);
    }
}

$page_title = "Tambah Pegawai";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pegawai = clean_input($_POST['nama_pegawai']);
    $jabatan      = clean_input($_POST['jabatan']);
    $kontak       = clean_input($_POST['kontak']);
    $email        = clean_input($_POST['email']);
    $gaji         = clean_input($_POST['gaji']);
    
    $foto_nama = ""; 
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_name = $_FILES['foto']['name'];
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed_ext)) {
            $foto_nama = time() . "_" . $file_name;
            $target_file = $target_dir . $foto_nama;
            
            if (!move_uploaded_file($file_tmp, $target_file)) {
                echo "<script>alert('Gagal upload foto');</script>";
            }
        } else {
            echo "<script>alert('Format foto salah!'); window.history.back();</script>";
            exit();
        }
    }

    // Insert data ke database
    $query = "INSERT INTO pegawai (foto, nama_pegawai, jabatan, kontak, email, gaji, status) 
              VALUES ('$foto_nama', '$nama_pegawai', '$jabatan', '$kontak', '$email', '$gaji', 'aktif')";
    
    if (mysqli_query($koneksi, $query)) {
        session_start();
        $_SESSION['pesan'] = "Pegawai berhasil ditambahkan!";
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
            <h2>Tambah Pegawai Baru</h2>
            <div class="breadcrumb">
                <a href="../index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="../index.php?page=data_pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Pegawai</span>
            </div>
        </div>
        
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Pegawai</h3>
                    <a href="../index.php?page=data_pegawai" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" class="form-vertical" enctype="multipart/form-data">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text" name="nama_pegawai" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Foto Pegawai</label>
                                <input type="file" name="foto" accept="image/*" class="form-control-file">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Jabatan *</label>
                                <select name="jabatan" required class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Staff Admin">Staff Admin</option>
                                    <option value="Marketing">Marketing</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Gaji (Rp) *</label>
                                <input type="number" name="gaji" min="0" required class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>No. HP/WA *</label>
                                <input type="text" name="kontak" required class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>