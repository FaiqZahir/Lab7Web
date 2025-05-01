<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <div id="container">
        <header>
            <h1>Admin Controllers</h1>
        </header>
        <nav>
            <a href="<?= base_url('/admin'); ?>">Dashboard</a>
            <a href="<?= base_url('/admin/add'); ?>">Tambah Artikel</a>
            <form action="<?= base_url('/user/logout'); ?>" method="post">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </nav>
        <section id="wrapper">
            <section id="main-admin">