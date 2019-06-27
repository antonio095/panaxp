<?php

require_once "MergePdf.class.php";

$arrayToBeMerged = [];

if (isset($_POST['submit'])) {

    // Count total files
    $countfiles = count($_FILES['file']['name']);

    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES['file']['name'][$i];
        $files = $_FILES['file']['tmp_name'][$i];

        array_push($arrayToBeMerged, $files);

        // echo gettype($files);

        // Upload file
        // move_uploaded_file($_FILES['file']['tmp_name'][$i], 'upload/' . $filename);

    }

    MergePdf::merge($arrayToBeMerged);

    header("Location: indexBackup.php");
}

// array_push($arrayToBeMerged, "test/file-a.pdf", "test/file-b.pdf", "test/file-c.pdf", "test/file-d.pdf", "test/file-e.pdf");

?>

<!-- <form method='post' action='' enctype='multipart/form-data'>
 <input type="file" name="file[]" id="file" multiple>

 <input type='submit' name='submit' value='Upload'>
</form> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="dragdrop.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            updateList = function () {
                var input = document.getElementById('file1');
                var output = document.getElementById('output');

                output.innerHTML = '<ul>';
                for (var i = 0; i < input.files.length; ++i) {
                    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
                }

                // localStorage.setItem("storageName", getInput);
                output.innerHTML += '</ul>';

            }


        });
    </script>

</head>

<body>
    <div class="box zone">
        <form method="post" class="text-center" enctype="multipart/form-data">
            <div class="margin-bottom-20">
                <img class="thumbnail box-center margin-top-20" alt="No image" src="images/file.png">
            </div>
            <div class="row" style="padding-top: 100px;">
                <div class="col-sm-10">
                    <span class="control-fileupload">
                        <label for="file1" class="text-left">Selecciona archivo</label>
                        <input type="file" name="file[]" id="file1" multiple onchange="javascript:updateList()">
                    </span>
                </div>

                <div class="col-sm-2">
                    <button type="submit" name="submit" class="btn btn-primary btn-block">
                        <i class="icon-upload icon-white"></i> Crear
                    </button>
                </div>

            </div>

        </form>
    </div>



    <h3>Archivos Adjuntos</h3>
    <p id="output" style="color: red;"></p>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../vendor/animsition/js/animsition.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/select2/select2.min.js"></script>
    <script src="../vendor/daterangepicker/moment.min.js"></script>
    <script src="../vendor/daterangepicker/daterangepicker.js"></script>
    <script src="../vendor/countdowntime/countdowntime.js"></script>
    <script src="js/main.js"></script>
</body>

</html>