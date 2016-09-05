<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/res.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/function.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <title>轻直播</title>
    
      <link href="css/toolkit-inverse.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <?php echo load_js('/js/jquery.min.js', '//cdn.bootcss.com/jquery/1.11.2/jquery.min.js');?>
  </head>
  <body>
  <div class="with-iconav">
	  <nav class="iconav">
    <a class="iconav-brand" href="/">
      <img src="images/logo.svg" alt="轻直播" style="max-width:140px">
    </a>
    <div class="iconav-slider">
      <ul class="nav nav-pills iconav-nav">
        <li >
          <a href="#" onclick="load_list('douyu','all',0,'斗鱼')">
            <img src="https://shark.douyucdn.cn/app/douyu/res/com/logo.png" alt="douyu" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">斗鱼</small>
          </a>
        </li>
        <li >
          <a href="#" onclick="load_list('panda','all',0,'熊猫')">
            <img src="http://i5.pdim.gs/d15fe0d28cfac83bd077fb40067f4bb9.png" alt="panda" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">熊猫</small>
          </a>
        </li>
        <li >
          <a href="#" onclick="load_list('zhanqi','all',0,'战旗')">
            <img src="https://static.zhanqi.tv/assets/web/static/i/logo-6.png" alt="zanqi" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">战旗</small>
          </a>
        </li>
        <li>
          <a href="#" onclick="load_list('quanmin','all',0,'全民')">
            <img src="http://static.quanmin.tv/static/v2/module/widget/header/img/logo_bb4cbb1.svg" alt="quanmin" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">全民</small>
          </a>
        </li>
        <li >
          <a href="#" onclick="load_list('huomao','all',0,'火猫')">
            <img src="http://www.huomao.com/static/web/images/logo.png" alt="huomao" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">火猫</small>
          </a>
        </li>
        <li >
          <a href="#" onclick="load_list('bilibili','all',0,'bilibili')">
            <img src="http://static.hdslb.com/live-static/common/images/logo/logo-150.png" alt="bilibili" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">bilibili</small>
          </a>
        </li>
        <li >
          <a href="#" onclick="load_list('longzhu','all',0,'龙珠')">
            <img src="http://r.plures.net/lg/images/star/live/topbar-logo-large.png" alt="bilibili" class="img-responsive">
            <small class="iconav-nav-label visible-xs-block">bilibili</small>
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container-fluid">