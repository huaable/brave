<?php
use \Brave\App;
use \Brave\View;

View::render('@views/layout/_header.php'); ?>
<div class="main">
    <ul class="list">
        <?php App::$app->baseUrl ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
        <?php View::render('@views/common/_list-article.php'); ?>
    </ul>
    <?php View::render('@views/common/_page.php'); ?>
</div>
<?php View::render('@views/layout/_footer.php'); ?>
