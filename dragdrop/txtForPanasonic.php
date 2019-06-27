<?php

$cantidadTarifa = "";
$valorAgregado = "";
$arrayOfThe551s = [];
$arrayOfSizes = []; // array with the separate sizes of the pdf different pages
$count = 0;
$tempNewArray = [];
$description = "";
$temp = $arrayOfExplodes[2];
$arrayTempString = implode($temp, " ");
$testArray = []; // Testing inserting specific adress when $j changes
$arrayOfWords = []; // array with the words of the description stored
$explodedStripped = []; // array with the data already store without too many spaces, just one
$whatIWantFor551Descr = substr($arrayTempString, strpos($arrayTempString, "PRIMAAGREGADOUNITARIOTOTAL") + 27);
$whatIreallyWantFor551Descr = strstr($whatIWantFor551Descr, 'DECLARO', true);
$tempExplode = explode("\r", $whatIreallyWantFor551Descr); // array with the sentences independent
$explode551Fraccion = [];
$arrayValuesOfFraccion = [];
$arrayofNumbers551 = [];
$fractionOf551 = "";
$tipo = "";
$valorMercancia = "";
$cantidadComercial = "";
//print_r($tempExplode);
$myfile = fopen("testfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, implode("", $arrayFor501));
// print_r($arrayOfExplodes);
for ($k = 0; $k <= sizeof($tempExplode) - 2; $k++) {
    $stripped = preg_replace('/\s+/', ' ', $tempExplode[$k]); // replace the white spaces in the sentence
    $res = preg_split('/\s+/', $stripped); // look for spaces in the array
    $count = count($res); // returns the number of times spaces appear in the string to use as counter for the other for loop

    $count = $count - 2; // fix the amount given by 2
    $explodedStripped = explode(" ", $stripped); // creates the array for the words in the individual sentence
    $explodedStrippedTrimed = array_shift($explodedStripped); // eliminates the first array that is empty and shifts the rest of the array up
    array_push($tempNewArray, $explodedStripped); // stores the arrays created by the explotion
    //print_r($tempNewArray);
    foreach ($tempNewArray as $data) {

        foreach ($data as $valuesOfWords) {
            $firstCharacter = $valuesOfWords[0];

            if (ctype_alpha($firstCharacter) == true && $valuesOfWords != "MEX") {
                array_push($arrayOfWords, $valuesOfWords);
            } else { // Find all the numbers in th
                array_push($arrayofNumbers551, $valuesOfWords);
            }

        }

        error_reporting(0);

        // Cantidad Comercial

        if (strpos($arrayofNumbers551[2], "PZ") == true) {
            $cantidadComercial = $arrayofNumbers551[2];
            $cantidadComercial = substr($cantidadComercial, 0, strpos($cantidadComercial, "PZ"));
        }
    }

    //Print array of numbers
    // print_r($arrayofNumbers551);

    //Cantidad Tarifa

    if (preg_match("/[a-z,]/i", $arrayofNumbers551[1]) == false) {

        $cantidadTarifa = $arrayofNumbers551[1];
    }

    // eliminate unnecessary section of the array
    if (sizeof($arrayofNumbers551) < 4) {
        unset($arrayofNumbers551);
    }

    // Valor Agregado
    // print_r($arrayofNumbers551);

    for ($x = 0; $x <= sizeof($arrayofNumbers551) - 1; $x++) {
        if ($arrayofNumbers551[$x] == "MEX") {
            $valorAgregado = $arrayofNumbers551[4];
            $valorAgregado = str_replace(",", "", $valorAgregado);
            // echo $valorAgregado. "</br>";
        } else if ($arrayofNumbers551[5] == "MEX") {
            $valorAgregado = $arrayofNumbers551[1];
            $valorAgregado = str_replace(",", "", $valorAgregado);
        } else if (in_array("MEX", $arrayofNumbers551) == false) {
            $valorAgregado = $arrayofNumbers551[4];
            $valorAgregado = str_replace(",", "", $valorAgregado);
        }
    }

    ////////

    // Valor de la mercancia
    if ($arrayofNumbers551[6] == "0") {
        $valorMercancia = $arrayofNumbers551[3];
    } else if ($arrayofNumbers551[6] != "0") {
        $valorMercancia = $arrayofNumbers551[6];
    }

    for ($x = 0; $x <= sizeof($arrayofNumbers551) - 1; $x++) {
        if ($arrayofNumbers551[$x] == "MEX") {
            $fractionOf551 = $arrayofNumbers551[$x - 1];
            if (strlen($fractionOf551) > 10) {
                $fractionOf551 = substr($fractionOf551, -10);
                //echo $fractionOf551 . "</br>";
            }
        }
        $fractionOf551 = str_replace(".", "", $fractionOf551);
    }

    // echo sizeof($arrayofNumbers551). "</br>";
    error_reporting(0);

    $description = implode(" ", $arrayOfWords);
    $arrayOfWords = [];
    $arrayofNumbers551 = [];

    $tempDesc = explode(" ", $description);

    if ($tempDesc[0] == null) {
        array_shift($tempDesc);
    }

    $sizeOfDesc = sizeof($tempDesc);
    if (end($tempDesc) == "CHN") {
        $removed = array_pop($tempDesc);
        $description = implode(" ", $tempDesc);
    }

    $tipo = "551";
// inserting 551
    array_push($arrayFor551, $tipo, "|", $fractionOf551 . "|", $description, "||", $valorMercancia, "|", $cantidadComercial, "|", "6", "|"); // insert to $arrayFor551 items
    array_push($arrayFor551, $cantidadTarifa . "|", $valorAgregado, "|", "0", "|", "0", "|", "|", "|", "USA", "|", "USA"); // insert to $arrayFor551 items
    array_push($arrayFor551, "|", "|", "|", "|", "|", "|", "0", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|");

    $tempSize = strlen(end($arrayFor551));
    if ($tempSize == 0) {
        $arrayFor551 = [];
    }

    $imploded551 = implode(" ", $arrayFor551);
    $explode551 = explode(" ", $imploded551);
    $implodedDescription = "";

    error_reporting(0);
    if ($explode551[0] == null) {
        unset($explode551);
        array_pop($explode551);
        unset($arrayFor551);
    }
    if (!preg_match('/^[a-z]+$/i', $explode551[3])) {

        $explode551[3] = $explode551[4];
        array_pop($explode551);
        $implodedDescription = implode(" ", $explode551);
        $tempExplodDescription = explode(" ", $implodedDescription);

        $arrayFor551[3] = $tempExplodDescription[3];
    }

    if (end($arrayFor551) == null) {
        unset($arrayFor551);
    }

    if ($arrayFor551[3] == "BULTOS" || $arrayFor551[3] == "DLLS") {

        unset($arrayFor551);

    }

    $tempForTarima = explode(" ", $arrayFor551[3]);
    if ($tempForTarima[0] == "TARIMAS") {
        $tempForTarima[0] = $tempForTarima[1];
        array_pop($tempForTarima);

        $arrayFor551[3] = $tempForTarima[0];
    }

    $implotingThe551 = implode("", $arrayFor551);

    if (empty($imploded551) == false && $description != "") {
        $insertToFile = $implotingThe551 . PHP_EOL;

        fwrite($myfile, $insertToFile);
    }

    $explode551 = []; // reset the exploted
    $arrayOfWords = []; // reset array of words
    array_shift($tempNewArray);
    $arrayFor551 = [];
}

fclose($myfile); // close the txt;
