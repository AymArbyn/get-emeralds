<!DOCTYPE html>
<html class="no-js <?php hasnavbar($include_nav); hassidebar($include_side); currpage($title) ?>" lang="en-us">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <?php gentitle($title) ?>

        <!-- <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,400italic"> -->
        <link rel="stylesheet" href="/css/normalize.css">

        <?php gencss($css) ?>
    </head>
    <body>
        <?php gennavbar($include_nav, $title) ?>
        <?php gensidebar($include_side, $title) ?>
        <div id="main">