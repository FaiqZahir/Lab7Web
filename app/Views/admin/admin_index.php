<?= $this->include('template/admin_header.php'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<form method="get" class="form-search">
    <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari data">
    <input type="submit" value="Cari" class="btn btn-primary">
</form>

<table class="table">
    <div class="container">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($artikel): foreach ($artikel as $row): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td>
                            <b><?= $row['judul']; ?></b>
                            <p><small><?= substr($row['isi'], 0, 50); ?></small></p>
                        </td>
                        <td><?= $row['tanggal']; ?></td>
                        <td>
                            <a class="btn" href="<?= base_url('/admin/edit/' . $row['id']); ?>">Ubah</a>
                            <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');"
                                href="<?= base_url('/admin/delete/' . $row['id']); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="4">Belum ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </div>
    
</table>

<?= $pager->only(['q'])->links(); ?>

<?= $this->include('template/admin_footer.php'); ?>