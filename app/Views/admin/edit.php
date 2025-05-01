<?= $this->include('template/admin_header'); ?>

<form action="" method="post">
    <h2><?= $title; ?></h2>
    <p>
        <input type="text" name="judul" value="<?= $data['judul']; ?>" required>
    </p>
    <p>
        <input type="text" name="kategori" value="<?= $data['kategori']; ?>" required>
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10"><?= $data['isi']; ?></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-large">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>