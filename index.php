<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <style>
        div td {
            border-width:1px;
            border:solid;
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
            border:solid;
            width:16.6%;
            text-align: center;
            border-width:1px;
            background-color:#ccc;
        }

        .contentUserPostTable {
            border-collapse: collapse;
            width:100%;
            height:100px;
            border-radius:4px;
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
        }

        .paginationDiv {
            margin-top:25px;
            margin-bottom:-25px;
        }

        #contentHeader th {
            height:35px;
        }

        .contentHeaderSticky {
            position: fixed;
            top: 0;
            width: 100%;
        }
    </style>
</head>
<body>
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
            <?php
            for ($i=0;$i<8;$i++) {
              echo '<tr>
                  <td style="border:none" colspan="6">
                      <div>
                          <table class="contentUserPostTable">
                              <tr>
                                  <td style="">
                                      <div class="contentUserPostNameDIV">
                                          FloX'.($i+12).'aaaaa
                                      </div>
                                  </td>
                                  <td style="background-image:none;border:none" colspan="5">
                              </tr>
                              <tr>
                                  <td>Rocket League
                                  <td style="font-size:13px">I would like to trade some wheels
                                  <td>'.$i.'m ago
                                  <td>Steam
                                  <td>Item
                              </tr>
                          </table>
                      </div>
                  </td>
              </tr> ';
            }
            ?>
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
