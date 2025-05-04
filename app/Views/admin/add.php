<?= $this->include('template/admin_header'); ?>


<form action="" method="post" enctype="multipart/form-data">
    <h2><?= $title; ?></h2>
    <p>
        <input type="text" name="judul" placeholder="judul">
    </p>
    <p>
        <input type="text" name="kategori" placeholder="kategori">
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10"></textarea>
    </p>
    <p>
        <input type="file" name="gambar">
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn-large">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>