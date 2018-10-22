<?PHP
require_once($_SERVER['DOCUMENT_ROOT']  . "/php/userdata_get_set.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/session.php");

if ($_POST["submit"] == "upload") {
    unset($_POST["submit"]);
    $error = setImage("image");
}

switch ($error) {
    case "0":
      $error = "<span style='color:#7F7' id='error'>succesfull !</span>";
      break;
    case 1:
      $error = "<span id='error'>no file selected</span>";
      break;
    case 2:
      $error = "<span id='error'>file over 1MB </span>";
      break;
    case 3:
      $error = "<span id='error'>only PNG/JPG allowed</span>";
      break;
    case 4:
      $error = "<span id='error'>you must be logged in</span>";
      break;
}
?>
<html>
  <head>
    <style>
    :root {
    --pic-size:55px;
    --frame-size:60px;
    }
    #picPreview {
      object-fit: cover;
      width:var(--pic-size);
      height:var(--pic-size);
      margin-left:calc((var(--frame-size) - var(--pic-size))/2);
      margin-top:calc((var(--frame-size) - var(--pic-size))/2);
      border-radius:6px;
    }
    #picFrame {
      width:var(--frame-size);
      height:var(--frame-size);
      background-color:#555;
      border-radius:2px;
      box-shadow:0px 0px 5px #000;
      margin-bottom:10px;
    }
    #uploadButtons {
      border-right:solid;
      border-bottom:solid;
      border-color:rgba(255,255,255,0.15);
      border-radius:4px;
      border-width:1px;
      box-shadow: inset 1px 1px 2px #222;
      width:275px;
      padding-top:10px;
      height:50px;
      padding-left:15px;
    }
    #fileName {
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
      width:250px;
      display:inline-block;
      margin-top:5px;
      color:grey;
      font-size:14px;
    }
    #error {
      color:#F55;
      font-size:14px;
      padding-left:2px;
    }
    #loading {
      display:none;
      width:27px;
      float:right;
      padding-right:20px;
    }
    </style>
  </head>
  <body>
    <div class="accountEdit" id="accountProfilePic">
        <div id="picFrame"><img id="picPreview" type="text" src="
        <?PHP
        if(getImage() == ""){
          echo "../../assets/img/defaultPic.jpg";
        }else {
          echo getImage();
        }
       ?>
        " /></div>
        <div id="uploadButtons">
          <form method="POST" action="" enctype="multipart/form-data">
            <button type="button" class="submitButton" id="selectFileButton" onclick="selectFile()">choose file</button>
            <input onchange="setText()" style="display:none" id="fileInput" name="image" type="file"/>
            <input onclick="showLoading()" class="submitButton" name="submit" type="submit" value="upload" />
            <img id="loading" src="/assets/img/loading.svg" />
            <br>
            <a id="fileName"><? echo $error ?></a>
          </form>
        </div>
    </div>
  </body>
</html>
<script>
var fileUpload = document.getElementById("fileInput");
var fileName = document.getElementById("fileName");
var loader = document.getElementById("loading");

function selectFile() {
    fileUpload.click();
}

function setText() {
    fileName.innerHTML = getName(fileUpload.value);
}

function getName(str) {
    return str.split('\\').pop().split('/').pop();
}

function showLoading() {
    loader.style.display = "inherit";
}
</script>
