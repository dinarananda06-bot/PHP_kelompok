<?php
    // Query data pegawai
    include 'koneksi.php';
    // Pastikan nama tabel di database Anda adalah 'pegawai'
    $query = "SELECT * FROM pegawai ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);
?>

<div class="card">
    <div class="card-header">
        <h3>DATA PEGAWAI</h3>
        <div class="card-actions">
            <a href="pegawai/tambahpg.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Tambah Pegawai
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
                        <th>Kontak & Email</th>
                        <th>Gaji</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if(!empty($row['foto'])): ?>
                                    <img src="uploads/<?php echo $row['foto']; ?>" alt="Foto" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="No Image" style="border-radius: 50%;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nama_pegawai']); ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo htmlspecialchars($row['jabatan']); ?>
                                </span>
                            </td>
                            <td>
                                <div><i class="fas fa-phone-alt fa-xs"></i> <?php echo htmlspecialchars($row['kontak']); ?></div>
                                <div class="text-muted"><small><i class="fas fa-envelope fa-xs"></i> <?php echo htmlspecialchars($row['email']); ?></small></div>
                            </td>
                            <td>
                                <span class="text-success font-weight-bold">
                                    Rp <?php echo number_format($row['gaji'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status <?php echo $row['status'] == 'aktif' ? 'status-active' : 'status-inactive'; ?>">
                                    <i class="fas fa-circle"></i>
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="pegawai/editpg.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="pegawai/hapuspg.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-action btn-delete" 
                                   title="Hapus"
                                   onclick="return confirm('Yakin hapus data pegawai ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="#" class="btn-action btn-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash fa-3x"></i>
                                    <h4>Belum ada data pegawai</h4>
                                    <p>Mulai dengan menambahkan pegawai baru</p>
                                    <a href="pegawai/tambahpg.php" class="btn btn-primary">
                                        <i class="fas fa-user-plus"></i> Tambah Pegawai Pertama
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