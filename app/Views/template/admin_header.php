<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header>
            <h1>Admin Controllers</h1>
        </header>
            <!-- Tambahkan ini di bagian atas <body> -->
            <!-- Navbar Bootstrap 5 -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url('/admin'); ?>">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/add'); ?>">Tambah Artikel</a>
                    </li>
                </ul>
                <form class="d-flex" action="<?= base_url('/user/logout'); ?>" method="post">
                    <button class="btn btn-outline-light" type="submit">Logout</button>
                </form>
                </div>
            </div>
            </nav>
        <section id="wrapper">
            <section id="main-admin">

            