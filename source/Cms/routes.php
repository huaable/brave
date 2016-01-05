<?php

\Brave\Router::get('/hello', function () {
    echo 'hello';
});


//\Brave\Router::get('/site', \Brave\App::controller('\Cms\Controllers\DefaultController','getIndex'));