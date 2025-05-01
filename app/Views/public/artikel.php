<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<article class="entry">
    <h1><?= $title; ?></h1>
    <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" alt="<?= $artikel['judul']; ?>">
    <p><?= $artikel['isi']; ?></p>
</article>

<?= $this->endSection() ?>