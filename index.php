<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Footer-Big-Logo.css">
    <link rel="stylesheet" href="assets/css/dh-navbar-inverse.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Pretty-Footer.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
    div {
        border-color:white;
        border-width:1px;
        color:white;
    }

    .contentUserPostHeaderTable {
        border-collapse:collapse;
        border-width:1px;
        width:100%;
        min-width: 900px;
        border-spacing: 0;
    }

    .contentUserPostHeaderTable th {
        border-right:solid;
        width:16.6%;
        text-align: center;
        border-width:1px;
    }

    .contentUserPostTable {
        border-collapse: collapse;
        width:100%;
        box-shadow: 0px 4px 4px #000;
        height:100px;
        border-radius:4px;
        background-color:#222;
        margin-bottom:15px
    }

    .contentUserPostTable td {
        width:16.6%;
        border-width:1px;
        text-align: center;
        border-spacing:0px;
        padding:5px;
        padding-bottom:25px;
    }

    .contentUserPostDIV {
        overflow-x: auto;
    }

    .contentUserPostNameDIV {
        text-align:left;
        color:orange;
        font-size:13px;
        padding-left:15px;
        margin-bottom:-1px;
        margin-left:-1px;
        border-top-right-radius: 6px;
        border-top-left-radius: 6px;
    }

    .paginationDiv {
        margin-top:25px;
        margin-bottom:-25px;
        color:black;
    }

    #contentHeader th {
        background-color:rgba(255, 154, 50, 1);
        height:35px;
    }

    .contentHeaderSticky {
        position: fixed;
        top: 0;
        width: 100%;
    }
    </style>
</head>
<body style="background-color:#1b1c19;">
<?php include "header.php" ?>
    <!-- Placeholder -->
    <div style="height:15vh">
    </div>
    <!-- Filter Area -->
    <div>
      <table style="width:100%">
        <tr>
        <!-- Search Bar -->
          <td style="width:300px">
              <form class="search-form">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-search"></i></span></div><input class="form-control" type="text" placeholder="I am looking for.." />
                  <div class="input-group-append"><button class="btn btn-light" type="button">Search</button></div>
                </div>
              </form>
          </td>
          <!-- Game Selection -->
          <td style="padding-left:25px">
              <select style="width:125px;">
                <optgroup label="Select your game">
                  <option value="12" selected>Rust</option>
                  <option value="13">Rocket League</option>
                  <option value="14">Counter Strike GO</option>
                </optgroup>
              </select>
          </td>
          <!-- Extended Filter -->
          <td style="float:right">
              <a style="width:125px" class="btn btn-outline-info pulse animated" data-toggle="collapse" aria-expanded="false" aria-controls="collapse-1" role="button" href="#collapse-1">ext. Filter</a>
          </td>
        </tr>
      </table>
      <div class="collapse" id="collapse-1">
        <div class="filter">
          <form>
            <input placeholder="e.g Blue Chair, Sofa or Post Modern"></input><br>
            <select>
              <option value="">Type</option>
            </select>
            <select>
              <option value="">Colours</option>
            </select>
            <select>
              <option value="">Size</option>
            </select>
            <select>
              <option value="">Price</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
          </form>
        </div>
      </div>
    </div>
    <!-- User Requests -->
    <div id="contentHeader">
        <table class="contentUserPostHeaderTable">
            <thead>
            <tr>
                <th> Game
                <th> Message
                <th> Time
                <th> Platform
                <th> Typ
            </tr>
            </thead>
        </table>
    </div>
    <div style="overflow-x:auto">
        <table class="contentUserPostHeaderTable">
            <tbody>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border:none" colspan="6">
                    <div>
                        <table class="contentUserPostTable">
                            <tr>
                                <td style="padding:0px;background-image:none;border:none">
                                    <div class="contentUserPostNameDIV">
                                        FloX45aaaaa
                                    </div>
                                </td>
                                <td style="background-image:none;border:none" colspan="5">
                            </tr>
                            <tr>
                                <td>Rocket League
                                <td style="font-size:13px">I would like to trade some wheels
                                <td>2m ago
                                <td>Steam
                                <td>Item
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="paginationDiv" style="height:10vh">
      <nav style="margin-left:calc(50% - 133px)">
          <ul class="pagination" style="position:inherit">
              <li class="page-item"><a class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
              <li class="page-item"><a class="page-link">1</a></li>
              <li class="page-item"><a class="page-link">2</a></li>
              <li class="page-item"><a class="page-link">3</a></li>
              <li class="page-item"><a class="page-link">4</a></li>
              <li class="page-item"><a class="page-link">5</a></li>
              <li class="page-item"><a class="page-link" aria-label="Next"><span aria-hidden="true">»</span></a></li>
          </ul>
      </nav>
    </div>
    <?php include "footer.php" ?>
</body>
</html>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.getElementById("contentHeader");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("contentHeaderSticky")
  } else {
    navbar.classList.remove("contentHeaderSticky");
  }
}
</script>
