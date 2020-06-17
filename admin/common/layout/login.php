<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0, initial-scale=1, user-scalable=0">
    <title><?= \App::$config['site_info']['web_name']?></title>
    <?php foreach ($this->cssList as $css): ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
    <link rel="stylesheet" href="/static/css/login.css">
</head>
<body>
<div class="custom-bg">
    <?php include $view ?>
</div>
</body>
<?php foreach ($this->scriptList as $script): ?>
    <script src="<?= $script ?>"></script>
<?php endforeach; ?>
</html>
