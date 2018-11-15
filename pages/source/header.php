<?PHP
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");
require_once(__DIR__ . "../../../php/session.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="17Ia-Aotx4mQG7ayi2W9l97hAJcgF8GB9FCrU34zdA0" />
    <link rel="shortcut icon" href="/assets/img/icons/swapitg_icon.png" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/dh-navbar-inverse.css">
    <style>
        .navbar {
            box-shadow: 0px 3px 3px rgba(0,0,0,0.5);
            background-color:var(--light-black) !important;
            z-index:1 !important;
        }
    </style>
</head>
<body style="font-size:12;">
    <nav style="position:inherit" class="navbar navbar-light navbar-expand-md fixed-top navigation-clean navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <a class="navbar-brand" href="https://swapitg.com" style="padding:0px;margin-left:0px;height:75px;">
           <img class="img-fluid" src="assets/img/SwapIT_Logo.png"></a>
            <div>
                <ul class="nav navbar-nav ml-auto" style="margin-top:13px;">
                    <?php include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/login.php") ?>
                </ul>
        </div>
        </div>
    </nav>
    <a style="opacity:0" href="https://swapitg.com/account">Account</a>
</body>
</html>
