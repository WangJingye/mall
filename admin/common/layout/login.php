<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0, initial-scale=1, user-scalable=0">
    <title><?= \App::$config['site_info']['web_name'] ?></title>
    <?php foreach ($this->cssList as $css): ?>
        <link rel="stylesheet" href="<?= \App::$urlManager->staticUrl($css) ?>">
    <?php endforeach; ?>
</head>
<body>
<div class="custom-bg"
     style="background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.3)), url(<?= \App::$urlManager->staticUrl('/static/images/login-background.jpg') ?>);">
    <?php include $view ?>
</div>
</body>
<?php foreach ($this->scriptList as $script): ?>
    <script src="<?= \App::$urlManager->staticUrl($script) ?>"></script>
<?php endforeach; ?>
</html>
