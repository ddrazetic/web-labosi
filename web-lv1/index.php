<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OOP in PHP</title>
    <?php include("DiplomskiRadovi.php"); ?>

</head>

<body>
    <?php
    $radovi = new diplomskiRadovi('');
    if (!$radovi->read()) {
        exec("php Curl.php");
    }
    echo '<pre>', var_dump($radovi->read()), '</pre>';

    ?>
</body>

</html>

<!-- potrebno je rucno kreirati bazu podataka radovi
    potrebno je prvo pokrenuti skriptu curl.php 
da se ucitaju sve vrijednosti preko cURL-a i spreme u bazu podataka
svi podatci se prikazuju na stranici http://localhost/web-lv1/
metodom read() koja cita iz baze podataka  -->