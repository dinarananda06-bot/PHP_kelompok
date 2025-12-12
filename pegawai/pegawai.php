<?php 
// Query data pegawai
include 'koneksidb.php';
$query  = "SELECT * FROM pegawai ORDER BY id DESC";
$result = mysqli_query($koneksidb, $query);
?>

<div class="card">
    <div class="card-header">
        <h3>DATA PEGAWAI</h3>
        <div class="card-actions">
            <a href="tambahpg.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pegawai
            </a>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>

                            <?php
                                // Path foto
                                $foto_path = "uploads/foto_pegawai/" . $row['foto'];

                                // Jika foto tidak ada → gunakan default
                                if (!file_exists($foto_path) || $row['foto'] == "") {
                                    $foto_path = "uploads/default.png"; // silakan ganti jika mau
                                }
                            ?>

                            <tr>
                                <td><?php echo $no++; ?></td>

                                <td>
                                    <img src="<?php echo $foto_path; ?>" 
                                         alt="Foto Pegawai" 
                                         style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                </td>

                                <td><?php echo htmlspecialchars($row['nama_pegawai']); ?></td>
                                <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>

                                <td>
                                    <span class="status <?php echo $row['status'] == 'aktif' ? 'status-active' : 'status-inactive'; ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>

                                <td class="action-buttons">
                                    <a href="pegawai/editpg.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-edit" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="pegawai/hapuspg.php?id=<?php echo $row['id']; ?>"
                                       class="btn-action btn-delete"
                                       title="Hapus"
                                       onclick="return confirm('Yakin hapus pegawai ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <a href="pegawai/detailpg.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-view" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-user-times fa-3x"></i>
                                    <h4>Belum ada data pegawai</h4>
                                    <p>Mulai dengan menambahkan pegawai baru</p>
                                    <a href="tambahpg.php" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Pegawai Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
