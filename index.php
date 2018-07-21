<?php
$config = require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>АВТО ставка</title>
    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,300,100,100italic,300italic,400italic,700,700italic">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/media-queries.css">
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

</head>
<body>
<!-- Coming Soon -->
<div class="coming-soon">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="wow fadeInLeftBig">МЕНЮ | НАСТРОЙКИ приложения</h2>
                    <div class="wow fadeInLeftBig">
                        <p>
                            Логин на minebet - <?php echo $config['loginMinebet'];?> <br>
                            Пароль на minebet - <?php echo $config['passwordMinebet'];?><br>
                            Логин на vodds - <?php echo $config['loginVodds'];?><br>
                            Пароль на vodds - <?php echo $config['passwordVodds'];?><br>
                            id пользователя - <?php echo $config['idVk'];?><br>
                            token ВК - <?php echo $config['tokenVk'];?><br>

                           V Ниже можно изменить конфигурации приложения V
                        </p>
                    </div>
                        <form name="form"   action="./write.php" method="post">
                            <input type="text" name="loginMinebet" placeholder="Логин на minebet" /><br>
                            <input type="text" name="passwordMinebet" placeholder="Пароль на minebet"/><br>
                            <input type="text" name="loginVodds" placeholder="Логин на vodds"/><br>
                            <input type="text" name="passwordVodds" placeholder="Пароль на vodds"/><br>
                            <input type="text" name="token" placeholder="token ВК" /><br>
                            <input type="text" name="id" placeholder="id пользователя"/><br><br>
                            <input type="submit" name="submit" style="width: 250px;" class="btn btn-primary" value="Сохранить" /><br>
                        </form><br>
                    <a style="width: 250px;" class="btn btn-primary" href="/run.php">Запустить</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-7 footer-copyright">
                <p>&copy; Панель управления приложением<br>All rights reserved.</p>
            </div>
<!--            <div class="col-sm-5 footer-social">-->
<!--                <a class="social-icon twitter" href="#" target="_blank"></a>-->
<!--                <a class="social-icon dribbble" href="#" target="_blank"></a>-->
<!--                <a class="social-icon facebook" href="#" target="_blank"></a>-->
<!--                <a class="social-icon google-plus" href="#" target="_blank"></a>-->
<!--            </div>-->
        </div>
    </div>
</footer>
<script src="assets/js/jquery-1.10.2.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/jquery.countdown.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/placeholder.js"></script>
</body>
</html>
