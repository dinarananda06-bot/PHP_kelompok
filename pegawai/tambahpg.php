<?php
include 'koneksidb.php';
$page_title = "Tambah Pegawai";  

function clean_input($data){
    global $koneksidb;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($koneksidb, $data);
}

// Proses Simpan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_pegawai = clean_input($_POST['nama_pegawai']);
    $jabatan      = clean_input($_POST['jabatan']);
    $email        = clean_input($_POST['email']);

    // ===========================
    //  PROSES UPLOAD FOTO
    // ===========================
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_size = $_FILES['foto']['size'];

    // Validasi: wajib pilih foto
    if ($foto_name == "") {
        $_SESSION['pesan'] = "Foto wajib diunggah!";
        $_SESSION['tipe']  = "error";
        header("Location: index.php?page=tambah_pegawai");
        exit();
    }

    // Validasi: hanya file gambar
    $allowed_ext = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        $_SESSION['pesan'] = "Format foto tidak valid! (jpg, jpeg, png, gif)";
        $_SESSION['tipe']  = "error";
        header("Location: index.php?page=tambah_pegawai");
        exit();
    }

    // Validasi ukuran
    if ($foto_size > 2*1024*1024) { // 2 MB
        $_SESSION['pesan'] = "Ukuran foto maksimal 2MB!";
        $_SESSION['tipe']  = "error";
        header("Location: index.php?page=tambah_pegawai");
        exit();
    }

    // Nama file aman
    $folder = "uploads/foto_pegawai/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $new_foto_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $foto_name);

    // Upload file
    if (!move_uploaded_file($foto_tmp, $folder . $new_foto_name)) {
        $_SESSION['pesan'] = "Gagal upload foto!";
        $_SESSION['tipe']  = "error";
        header("Location: index.php?page=tambah_pegawai");
        exit();
    }

    // Simpan data
    $query = "INSERT INTO pegawai (foto, nama_pegawai, jabatan, email) 
              VALUES ('$new_foto_name', '$nama_pegawai', '$jabatan', '$email')";

    if (mysqli_query($koneksidb, $query)) {
        $_SESSION['pesan'] = "Pegawai berhasil ditambahkan!";
        $_SESSION['tipe']  = "success";
        header("Location: index.php?page=data_pegawai");
        exit();
    } 
    else {
        $_SESSION['pesan'] = "Gagal menambahkan pegawai: " . mysqli_error($koneksidb);
        $_SESSION['tipe']  = "error";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Tambah Pegawai</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Pegawai</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Pegawai</h3>
                    <a href="index.php?page=data_pegawai" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" class="form-vertical">

                        <div class="form-group">
                            <label><i class="fas fa-image"></i> Foto *</label>
                            <input type="file" name="foto" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nama Pegawai *</label>
                            <input type="text" name="nama_pegawai" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-briefcase"></i> Jabatan *</label>
                            <input type="text" name="jabatan" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email *</label>
                            <input type="email" name="email" required>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pegawai
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
