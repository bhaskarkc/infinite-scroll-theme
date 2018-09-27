<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title><?php echo wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ); ?></title>
<?php wp_head(); ?>
</head>
<body ng-app="app" ng-controller="MainController">
