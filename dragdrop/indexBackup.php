<pre>
<?php
require_once "MergePdf.class.php";
include 'vendor/autoload.php';
include 'PdfToText.phpclass';

// include 'connection.php';

function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

if (isset($_POST["submit"])) {

    $nameOfFile = ""; // name of the file being uploaded
    $arrayOfPages = []; // pages separaded in each array
    $arrayOfExplodes = []; //pages exploded into each array separated
    $arrayFor501 = []; // this array will store the information from the 501 section
    $arrayFor551 = []; // this array will store the information from the 551 section
    $i = 0; // used to loop and explode independent pages into arrays of string
    $arrayofdate = [];
    $arrayofSeparatedPages = [];
    $valorTotalMercancia = "";

    $files = "test/merged-files.pdf";
    $numeroDePedimentos = $_POST["pedimento"];

    $pdf = new PdfToText($files);
    foreach ($pdf->Pages as $page_number => $page_contents) {

        array_push($arrayOfPages, $page_contents);
        array_push($arrayOfExplodes, explode("\n", $arrayOfPages[$i]));
        $i = $i + 1;
    }

    // $rfcFromPdf = $arrayOfExplodes[0][12];
    // $arrayOfExplotedItems = explode(" ", $rfcFromPdf);
    // unset($arrayOfExplotedItems[0], $arrayOfExplotedItems[1]);
    // $rfcNeeded = implode("", $arrayOfExplotedItems);
    $tipoDeOperacion = $_POST["tipoDeOperacion"];
    $claveDeDocumento = $_POST["claveDeDocumento"];
    $tempExplForArray551 = [];

    array_push($arrayFor501, "501", "|", $tipoDeOperacion, "|", $claveDeDocumento, "|", $numeroDePedimentos);
    array_push($arrayFor501, "|", "006041", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|");
    array_push($arrayFor501, "7", "|", "7", "|", "7", "|", "7", "|", "|", "|", "|", "|", "|", "|", "|", "|" . PHP_EOL);

    $sizeOfArrayOfExplodes = sizeof($arrayOfExplodes);

    $myfile = fopen("test.txt", "w") or die("Unable to open file!");

    fwrite($myfile, implode("", $arrayFor501));

    for ($w = 0; $w <= $sizeOfArrayOfExplodes - 1; $w++) {

        error_reporting(0);
        $secondPage = $arrayOfExplodes[$w + 1]; // array oF PAGES
        $arrayTempStringSecond = implode($secondPage, " ");
        $whatIWantFor551DescrSecond = substr($arrayTempStringSecond, strpos($arrayTempStringSecond, "EXPORTACION") + 27);
        $whatIreallyWantFor551DescrSecond = strstr($whatIWantFor551DescrSecond, 'DESTINATARIO', true);
        $tempExplodeSecond = explode("\r", $whatIreallyWantFor551DescrSecond); // array with the sentences independent

        $wordsSecond = explode(" ", implode(" ", $tempExplodeSecond));

        $secondPageData = $arrayOfExplodes[$w + 1]; // array oF PAGES
        $arrayTempStringSecondData = implode($secondPageData, " ");
        $whatIWantFor551DescrSecondData = substr($arrayTempStringSecondData, strpos($arrayTempStringSecondData, "PRIMAAGREGADOUNITARIOTOTAL") + 27);
        $whatIreallyWantFor551DescrSecondData = strstr($whatIWantFor551DescrSecondData, 'DECLARO', true);
        $tempExplodeSecondData = explode("\r", $whatIreallyWantFor551DescrSecondData); // array with the sentences independent

        $cantidadTarifa = "";
        $valorAgregado = "";
        $arrayOfThe551s = [];
        $arrayOfSizes = []; // array with the separate sizes of the pdf different pages
        $count = 0;
        $tempNewArray = [];
        $description = "";
        $temp = $arrayOfExplodes[$w]; // array oF PAGES
        $arrayTempString = implode($temp, " ");
        $testArray = []; // Testing inserting specific adress when $j changes
        $arrayOfWords = []; // array with the words of the description stored
        $explodedStripped = []; // array with the data already store without too many spaces, just one
        $whatIWantFor551Descr = substr($arrayTempString, strpos($arrayTempString, "PRIMAAGREGADOUNITARIOTOTAL") + 27);
        $whatIreallyWantFor551Descr = strstr($whatIWantFor551Descr, 'DECLARO', true);

        // if (strpos($wordsSecond[8], ":2") || strpos($wordsSecond[8], ":3")) {

        //     $whatIreallyWantFor551Descr .= $whatIreallyWantFor551DescrSecondData;
        // }

        $tempExplode = explode("\r", $whatIreallyWantFor551Descr); // array with the sentences independent
        $arrayFor505 = [];

        $firstPage = $arrayOfExplodes[$w]; // array oF PAGES
        $arrayTempStringfirst = implode($firstPage, " ");
        $whatIWantFor551Descrfirst = substr($arrayTempStringfirst, strpos($arrayTempStringfirst, "EXPORTACION") + 27);
        $whatIreallyWantFor551Descrfirst = strstr($whatIWantFor551Descrfirst, 'DESTINATARIO', true);
        $tempExplodefirst = explode("\r", $whatIreallyWantFor551Descrfirst); // array with the sentences independent
        $wordsFirst = explode(" ", implode(" ", $tempExplodefirst));

        array_push($arrayofdate, $wordsFirst[2], $wordsFirst[3], $wordsFirst[4], $wordsFirst[5], $wordsFirst[6]);

        $implodedData = implode(" ", $tempExplode);
        $implodedSecondData = implode(" ", $tempExplodeSecondData);
        $explode551Fraccion = [];
        $arrayValuesOfFraccion = [];
        $arrayofNumbers551 = [];
        $fractionOf551 = "";
        $tipo = "";
        $valorMercancia = "";
        $cantidadComercial = "";
        $keyword = "";
        $arrayOfCantidadComercial = [];
        $arrayOfValorMercancial = [];
        $arrayOfValorMercancialStored = [];
        $arrayOfValorAgregadoStored = [];
        $arrayOfValorAgregado = [];
        $arrayOfArrays551 = [];
        $myArray = [];
        $myNewArray = [];
        $myNewstArray = [];
        $flagsTemp = false;
        error_reporting(0);

        for ($x = 0; $x <= sizeof($tempExplode) - 1; $x++) {
            $superTemp = explode(" ", $tempExplode[$x]);
            $superTemp = array_filter($superTemp);
            $superTemp = array_values($superTemp);

            $fixingSpaces = $cleanStr = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $tempExplode[$x])));
            $tempExplode[$x] = $fixingSpaces;

            // $fixingNumbersTogether = preg_replace("/([[:alpha:]])([[:digit:]])/", "\\1 \\2", $tempExplode[$x]);
            $fixingNumbersTogether = preg_replace("/([[:digit:]])([[:alpha:]])/", "\\1 \\2", $tempExplode[$x]);

            $tempExplode[$x] = $fixingNumbersTogether;

        }

        for ($x = 0; $x <= sizeof($tempExplode) - 1; $x++) {

            $firstRow = $tempExplode[$x];
            $secondRow = $tempExplode[$x + 1];
            $thirdRow = $tempExplode[$x + 2];
            $forthRow = $tempExplode[$x + 3];

            if (strpos($firstRow, "MEX") == false && strpos($secondRow, "MEX") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow;
                unset($tempExplode[$x + 1]);
                $tempExplode = array_values($tempExplode);
            }

            if (strpos($firstRow, "MEX") == false && strpos($secondRow, "MEX") == false && strpos($thirdRow, "MEX") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false && strpos($thirdRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow . " " . $thirdRow;
                unset($tempExplode[$x + 1]);
                unset($tempExplode[$x + 2]);
                $tempExplode = array_values($tempExplode);
            }

            if (strpos($firstRow, "MEX") == false && strpos($secondRow, "MEX") == false && strpos($thirdRow, "MEX") == false && strpos($forthRow, "MEX") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false && strpos($thirdRow, "CHN") == false && strpos($forthRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow . " " . $thirdRow . " " . $forthRow;
                unset($tempExplode[$x + 1]);
                unset($tempExplode[$x + 2]);
                unset($tempExplode[$x + 3]);
                $tempExplode = array_values($tempExplode);
            }
        }

        for ($x = 0; $x <= sizeof($tempExplode) - 1; $x++) {

            $firstRow = $tempExplode[$x];
            $secondRow = $tempExplode[$x + 1];
            $thirdRow = $tempExplode[$x + 2];
            $forthRow = $tempExplode[$x + 3];

            if (strpos($firstRow, "USA") == false && strpos($secondRow, "USA") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow;
                unset($tempExplode[$x + 1]);
                $tempExplode = array_values($tempExplode);
            }

            if (strpos($firstRow, "USA") == false && strpos($secondRow, "USA") == false && strpos($thirdRow, "USA") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false && strpos($thirdRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow . " " . $thirdRow;
                unset($tempExplode[$x + 1]);
                unset($tempExplode[$x + 2]);
                $tempExplode = array_values($tempExplode);
            }

            if (strpos($firstRow, "USA") == false && strpos($secondRow, "USA") == false && strpos($thirdRow, "USA") == false && strpos($forthRow, "USA") == true && strpos($firstRow, "CHN") == false && strpos($secondRow, "CHN") == false && strpos($thirdRow, "CHN") == false && strpos($forthRow, "CHN") == false) {
                $tempExplode[$x] = $firstRow . " " . $secondRow . " " . $thirdRow . " " . $forthRow;
                unset($tempExplode[$x + 1]);
                unset($tempExplode[$x + 2]);
                unset($tempExplode[$x + 3]);
                $tempExplode = array_values($tempExplode);
            }
        }

        for ($i = 0; $i <= sizeof($tempExplode) - 1; $i++) {

            if (substr_count($tempExplode[$i], "TARIMAS") > 1) {
                // echo "CHECK HERE </BR>";
                $tempToBeInserted = strstr($tempExplode[$i], 'MEX');
                $tempExplode[$i] = substr($tempExplode[$i], 0, strpos($tempExplode[$i], "MEX")) . "***";
                // echo  $tempToBeInserted . "\n";
                $tempToBeInserted = strstr($tempToBeInserted, 'TARIMAS');
                array_insert($tempExplode, $i + 1, $tempToBeInserted);
            }

        }

        for ($i = 0; $i <= sizeof($tempExplode) - 1; $i++) {

            if (substr_count($tempExplode[$i], "TARIMAS") > 1) {
                // echo "CHECK HERE </BR>";
                $tempToBeInserted = strstr($tempExplode[$i], 'USA');
                $tempExplode[$i] = substr($tempExplode[$i], 0, strpos($tempExplode[$i], "USA")) . "***";
                // echo  $tempToBeInserted . "\n";
                $tempToBeInserted = strstr($tempToBeInserted, 'TARIMAS');
                array_insert($tempExplode, $i + 1, $tempToBeInserted);
            }

        }

        for ($i = 0; $i <= sizeof($tempExplode) - 1; $i++) {

            if (substr_count($tempExplode[$i], "MEX") > 1) {

                $tempToBeInserted = strstr($tempExplode[$i], 'TARIMAS');
                $tempExplode[$i] = $tempToBeInserted;
            }

        }

        for ($i = 0; $i <= sizeof($tempExplode) - 1; $i++) {

            if (substr_count($tempExplode[$i], "USA") > 1) {

                $tempToBeInserted = strstr($tempExplode[$i], 'TARIMAS');
                $tempExplode[$i] = $tempToBeInserted;
            }

        }

        unset($tempExplode[sizeof($tempExplode) - 1]);

        $tempExplode = array_filter($tempExplode);

        // print_r($tempExplode);

        $tempLastItem = $tempExplode[count($tempExplode) - 2];

        $sizeOfLastItem = strlen($tempLastItem);

        if ($sizeOfLastItem < 70) {
            $valorTotalMercancia = $tempLastItem;
            $valorTotalMercancia = explode(" ", $valorTotalMercancia);
            // print_r($valorTotalMercancia);
            $valorTotalMercancia = end($valorTotalMercancia);

            // echo "VALOR TOTAL MERCANCIA: " . $valorTotalMercancia . "</br>";
        }

        for ($k = 0; $k <= sizeof($tempExplode) - 1; $k++) {

            $stripped = preg_replace('/\s+/', ' ', $tempExplode[$k]); // replace the white spaces in the sentence
            $res = preg_split('/\s+/', $stripped); // look for spaces in the array
            $count = count($res); // returns the number of times spaces appear in the string to use as counter for the other for loop
            $count = $count - 2; // fix the amount given by 2
            $explodedStripped = explode(" ", $stripped); // creates the array for the words in the individual sentence
            array_push($tempNewArray, $explodedStripped); // stores the arrays created by the explotion
            error_reporting(0);

            $temps = $arrayOfExplodes[25];
            $tempArrayTempString = implode($temps, " ");
            $tempWhatIWantFor551Descr = substr($tempArrayTempString, strpos($tempArrayTempString, "PRIMAAGREGADOUNITARIOTOTAL") + 27);
            $tempWhatIreallyWantFor551Descr = strstr($tempWhatIWantFor551Descr, 'DECLARO', true);
            $tempsExplode = explode("\r", $tempWhatIreallyWantFor551Descr);
            $watch = explode(" ", $tempsExplode[0]);
            $watch = array_filter($watch);
            $watch = array_values($watch);

            foreach ($tempNewArray as $data) {
                // echo $data[] . "</br>";
                foreach ($data as $valuesOfWords) {
                    $firstCharacter = $valuesOfWords[0];

                    // echo $valuesOfWords . "</br>";
                    if (ctype_alpha($firstCharacter) == true && $valuesOfWords != "MEX" && $valuesOfWords != "CHN" && $valuesOfWords[0] !== '.') {
                        array_push($arrayOfWords, $valuesOfWords);

                    } else { // Find all the number
                        array_push($arrayofNumbers551, $valuesOfWords);
                        // echo "Value of words: ". $valuesOfWords . "</br>";
                    }

                }

                error_reporting(0);

            }

            if (strlen($arrayofNumbers551[0]) < 4) {
                unset($arrayofNumbers551[0]);

                $arrayofNumbers551 = array_values($arrayofNumbers551);

            }

            if (empty($arrayofNumbers551)) {
                unset($arrayofNumbers551);
            }

            $sizeOfNumbers = sizeof($arrayofNumbers551);

            // print_r($arrayofNumbers551);

            for ($x = 0; $x <= $sizeOfNumbers - 1; $x++) {

                if (preg_match('/\..*\./', $arrayofNumbers551[$x])) {
                    $fractionOf551 = $arrayofNumbers551[$x];
                }

                if (strlen($fractionOf551) > 10) {
                    $fractionOf551 = substr($fractionOf551, -10);
                }
                $fractionOf551 = str_replace(".", "", $fractionOf551);
            }

            $valorMercancia = $arrayofNumbers551[5];

            $cantidadComercial = $arrayofNumbers551[1];

            $cantidadTarifa = $arrayofNumbers551[0];

            if (strlen($cantidadTarifa) < 5) {

                $cantidadTarifa = $cantidadComercial;

            }

            $valorAgregado = str_replace(",", "", $arrayofNumbers551[3]);

            $sizeOfWords = sizeof($arrayOfWords);

            if (preg_match('#[0-9]#', $arrayOfWords[0])) {
                unset($arrayOfWords[0]);
                // echo "has numbers";
                $arrayOfWords = array_values($arrayOfWords);
            }

            if ($arrayOfWords[0] == "BULTOS") {
                unset($arrayOfWords);
            }

            if ($arrayOfWords[0] == "CAJA" || $arrayOfWords[0] == "CAJAS") {
                unset($arrayOfWords[0]);
                $arrayOfWords = array_values($arrayOfWords);
            }

            for ($x = 0; $x <= $sizeOfWords - 1; $x++) {

                if (preg_match('#[0-9]#', $arrayOfWords[$x])) {
                    unset($arrayOfWords[$x]);
                    // echo "has numbers";
                    $arrayOfWords = array_values($arrayOfWords);
                }

                if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $arrayOfWords[$x])) {
                    unset($arrayOfWords[$x]);
                    // echo "has numbers";
                    $arrayOfWords = array_values($arrayOfWords);
                }

                if ($arrayOfWords[$x] == "TARIMAS" || $arrayOfWords[$x] == "BULTOS") {

                    unset($arrayOfWords[$x]);
                    // echo "has numbers";
                    $arrayOfWords = array_values($arrayOfWords);
                }

                if ($arrayOfWords[$x] == "PZ") {
                    unset($arrayOfWords[$x]);
                    // echo "has numbers";
                    $arrayOfWords = array_values($arrayOfWords);
                }

            }

            if (preg_match('#[0-9]#', $arrayOfWords[0])) {
                unset($arrayOfWords[0]);
                // echo "has numbers";
                $arrayOfWords = array_values($arrayOfWords);
            }

            if (strlen($arrayOfWords[0]) == 1) {
                unset($arrayOfWords[0]);
                $arrayOfWords = array_values($arrayOfWords);
            }

            if (empty($arrayOfWords)) {
                unset($arrayOfWords);
            }

            $lengthOfWord = strlen($arrayOfWords[0]);

            if ($lengthOfWord < 3) {
                unset($arrayOfWords[0]);
                // echo "has numbers";
                $arrayOfWords = array_values($arrayOfWords);

            }

            if ($arrayOfWords[0] == "NKAP") {
                unset($arrayOfWords[0]);
                // echo "has numbers";
                $arrayOfWords = array_values($arrayOfWords);

            }

            $description = implode(" ", $arrayOfWords);

            $description = str_replace("PZ", "", $description);
            $description = str_replace("USA", "", $description);

            if (strlen($cantidadTarifa < 5)) {
                $cantidadTarifa = $cantidadComercial;
            }

            $tipo = "551";
            array_push($arrayFor551, $tipo, "|", $fractionOf551 . "|", $description, "||", $valorMercancia, "|", $cantidadComercial, "|", "6", "|"); // insert to $arrayFor551 items
            array_push($arrayFor551, $cantidadTarifa . "|", $valorAgregado, "|", "0", "|", "0", "|", "|", "|", "USA", "|", "USA"); // insert to $arrayFor551 items
            array_push($arrayFor551, "|", "|", "|", "|", "|", "0", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|");

            $implotingThe551 = implode("", $arrayFor551);

            $implotingThe551 = strtoupper($implotingThe551);

            if ($description != "" && $cantidadComercial != "") {

                array_push($arrayOfArrays551, $arrayFor551);
            }

            $explode551 = []; // reset the exploted
            $arrayofNumbers551 = [];
            $arrayOfWords = []; // reset array of words
            $arrayOfValorMercancial = [];
            array_shift($tempNewArray);
            // array_push($arrayOfArrays551, $arrayFor551);
            $arrayFor551 = [];
        }

        $tipo = "505";
        $date = implode(" ", $arrayofdate);

        // print_r($date);
        $numeroDeFactura = str_replace(":", "", $wordsFirst[0]);
        $numeroDeFactura = str_replace("A", "", $numeroDeFactura);
        $fechaDeFactura = str_replace("FECHA:", "", $date);
        $fechaDeFactura = str_replace("de", "", $fechaDeFactura);
        if (strpos($fechaDeFactura, "Enero")) {
            $fechaDeFactura = str_replace("Enero", "01", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Febrero")) {
            $fechaDeFactura = str_replace("Febrero", "02", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Marzo")) {
            $fechaDeFactura = str_replace("Marzo", "03", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Abril")) {
            $fechaDeFactura = str_replace("Abril", "04", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Mayo")) {
            $fechaDeFactura = str_replace("Mayo", "05", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Junio")) {
            $fechaDeFactura = str_replace("Junio", "06", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Julio")) {
            $fechaDeFactura = str_replace("Julio", "07", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Agosto")) {
            $fechaDeFactura = str_replace("Agosto", "08", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Septiembre")) {
            $fechaDeFactura = str_replace("Septiembre", "09", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Octubre")) {
            $fechaDeFactura = str_replace("Octubre", "10", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Noviembre")) {
            $fechaDeFactura = str_replace("Noviembre", "11", $fechaDeFactura);
        }
        if (strpos($fechaDeFactura, "Diciembre")) {
            $fechaDeFactura = str_replace("Diciembre", "12", $fechaDeFactura);
        }

        // echo $fechaDeFactura . "</br>";

        $str = $fechaDeFactura;
        $exp = explode(' ', $str);
        // print_r($exp);
        $newStr = trim($exp[4]) . '' . trim($exp[2]) . '' . trim($exp[0]);
        // echo $newStr . "</br>";

        $fechaDeFactura = $newStr;

        // echo "FECHA FACTURA" . $fechaDeFactura . "</br>";

        $arrayofdate = [];

        // $valorTotalMercancia = "39860.96";

        $store505 = $tipo . "|" . $numeroDeFactura . "|" . $fechaDeFactura . "|DAP|MEX|" . $valorTotalMercancia . "|" . $valorTotalMercancia . "|PANA||MEX||||||||||||||||||||||||||||||||" . PHP_EOL; // insert to $arrayFor551 items
        fwrite($myfile, $store505);

        // $arrayOfArrays551 = array_unique($arrayOfArrays551);

        // print_r($arrayOfArrays551);

        for ($x = 0; $x <= sizeof($arrayOfArrays551) - 1; $x++) {

            $implotingThe551 = implode("", $arrayOfArrays551[$x]);

            if (strpos($implotingThe551, "DLLS")) {

            } else {
                $insertToFile = $implotingThe551 . PHP_EOL;
                $insertToFile = strtoupper($insertToFile);

                fwrite($myfile, $insertToFile);
            }

        }

    }

    // print_r($arrayofSeparatedPages);

    fclose($myfile); // close the txt;

    // header( "refresh:0.2; url=index.php" );
    // echo '<script type="text/javascript">alert("TXT FUE CREADO");</script>';

    $file = "test.txt";

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
    // header("Location: index.php");
}

?>
</pre>

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

</head>

<body>
    <div class="box zone">
        <form method="post" class="text-center" enctype="multipart/form-data">
            <div class="margin-bottom-20">
                <img class="thumbnail box-center margin-top-20" alt="No image" src="images/file.png">
            </div>
            <div class="row" style="padding-top: 100px;">
            </div>
            <div class="col-sm-10">
                <span class="control-fileupload">
                    <input type="text" name="tipoDeOperacion" id="file1" placeholder="Tipo de operacion">
                </span>
            </div>
            <div class="col-sm-10">
                <span class="control-fileupload">
                    <input type="text" name="claveDeDocumento" id="file1" placeholder="Clave de documento">
                </span>
            </div>
            <div class="col-sm-10">
                <span class="control-fileupload">
                    <input type="text" name="pedimento" id="file1" placeholder="No. pedimento">
                </span>
            </div>
            <div class="col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary btn-block">
                    <i class="icon-upload icon-white"></i> Crear
                </button>
            </div>

    </div>

    </form>
    </div>


    <h3>PDF de Facturas</h3>
    <p id="output" style="color: red;"> merged-files.pdf</p>


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