<?= $this->section('content-terkini') ?>

<ul>
    <?php foreach ($artikel as $row): ?>
        <li>
            <a href="<?= base_url('page/artikel/' . $row['slug']) ?>">
                <h4><?= $row['judul'] ?></h4>
                <small>(<?= date('d M Y', strtotime($row['tanggal'])) ?>)</small>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?= $this->endSection() ?>