<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kurulum</title>

    <base href="<?php echo $base ?>/" />
    <link rel="stylesheet" type="text/css" href="public/admin/plugin/bootstrap/css/bootstrap.min.css" />
    <style>
        body {font-size: 13px;}
    </style>

</head>
<body>
<div class="container">
    <h1>Kurulum Hatası</h1><hr />
    <p class="lead text-danger">Modül kurulum sırasında şu hatalar oluştu:</p>

    <div class="well">
        <?php foreach ($this->messages as $message): ?>
            <div style="margin-bottom: 5px;">&bull; <?php echo $message ?></div>
        <?php endforeach; ?>
    </div>

    <a class="btn btn-success" href="<?php echo $current ?>">Tekrar Dene</a>

</div>


</body>
</html>
