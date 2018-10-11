<?PHP
require_once(__DIR__ . "/php/userdata_get_set.php");
require_once(__DIR__ . "/php/session.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Swapit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="17Ia-Aotx4mQG7ayi2W9l97hAJcgF8GB9FCrU34zdA0" />
    <link rel="shortcut icon" href="/assets/img/icons/swapitg_icon.png" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/dh-navbar-inverse.css">
    <link rel="stylesheet" href="assets/css/global_var.css">
    <style>
        .navbar {
            box-shadow: 0px 3px 3px rgba(0,0,0,0.5);
            background-color:var(--light-black) !important;
        }
    </style>
</head>
<body style="font-size:12;">
    <nav style="position:inherit" class="navbar navbar-light navbar-expand-md fixed-top navigation-clean navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <a class="navbar-brand" href="https://swapitg.com" style="padding:0px;margin-left:0px;height:75px;">
           <img class="img-fluid" src="assets/img/SwapIT_Logo.png"></a>
           <button onclick="showMenu()" class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon" style="background-color:rgba(255,255,255,0);"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1" style="height:50px;margin-bottom:10px;">
                <ul class="nav navbar-nav ml-auto" style="margin-top:13px;">
                    <?php include "login.php" ?>
                </ul>
        </div>
        </div>
    </nav>
    <a style="opacity:0" href="https://swapitg.com/account">Account</a>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
