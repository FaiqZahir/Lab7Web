<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
</head>

<body>
    <?= $this->include('template/header.php'); ?>
    <h1><? $title; ?></h1>
    <hr>
    <p><?= $content; ?></p>
    <?= $this->include('template/footer.php'); ?>
</body>

</html>