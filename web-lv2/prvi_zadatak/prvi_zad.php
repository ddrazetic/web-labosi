<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2 - 1.zadatak</title>
</head>

<body>
    <?php
    //Naziv baze podataka
    $db_name = "radovi";
    //Direktorij za backup
    $dir = "backup1/$db_name";

    //Ako direktorij ne postoji stvori ga 
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die("<p>Ne mozemo stvoriti direktorij $dir.</p></body></html>");
        }
    }

    $columnName = function ($value) {
        return $value->name;
    };
    $files = [];

    //Trenutno vrijeme
    $time = time();

    //Spoj na bazu podataka
    $dbc = @mysqli_connect('localhost', 'root', '', $db_name) or die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");

    //Pokazi sve tablice iz baze podataka
    $r = mysqli_query($dbc, 'SHOW TABLES');

    //Radimo backup ako postoji barem jedna tablica
    if (mysqli_num_rows($r) > 0) {

        //Poruka da se radi backup
        echo "<p>Backup za bazu podataka '$db_name'.</p>";

        //Dohvati ime svake tablice
        while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {

            //Dohvati podatke iz tablice
            $q = "SELECT * FROM $table";
            // imena stupaca
            $columns = array_map($columnName, $dbc->query($q)->fetch_fields());
            $r2 = mysqli_query($dbc, $q);

            //Ako postoje podaci
            if (mysqli_num_rows($r2) > 0) {

                $fileName = "{$table}_{$time}";
                //Otvori datoteku
                if ($fp = fopen("$dir/$fileName.txt", "w9")) {
                    array_push($files, $fileName);
                    //Dohvat podataka iz tablice
                    while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                        // zapisivanje podataka po zadanom formatu zapisa
                        $rowText = "INSERT INTO $table (";

                        for ($i = 0; $i < count($columns); $i++) {
                            // ako nije zadnji element
                            if ($i + 1 != count($columns)) {
                                $rowText .= "$columns[$i], ";
                            } else {
                                $rowText .= "$columns[$i]";
                            }
                        }

                        $rowText .= ") VALUES (";

                        for ($i = 0; $i < count($row); $i++) {
                            // ako nije zadnji element
                            if ($i + 1 != count($row)) {
                                $rowText .= "'$row[$i]', ";
                            } else {
                                $rowText .= "'$row[$i]'";
                            }
                        }
                        //Novi redak za svaki redak iz baze 
                        $rowText .= ");\n";
                        fwrite($fp, $rowText);
                    } //Kraj while petlje
                    //Zatvori datoteku
                    fclose($fp);
                    //Ispiši da je backup uspješno izvršen
                    echo "<p>Tablica '$table' je pohranjena.</p>";
                    //Otvori sažetu datoteku 
                    if ($fp = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
                        // dohvati sadrzaj iz txt datoteke
                        $content = file_get_contents("backup1/radovi/$fileName.txt");
                        // zapisi sadrzaj u sazetu datoteku
                        gzwrite($fp, $content);
                        //    zatvori datoteku
                        gzclose($fp);

                        echo "<p>Tablica '$table' je sažeta.</p>";
                    } else {
                        echo "<p>Greška prilikom sažimanja tablice '$table'.</p>";
                    }
                } else {
                    echo "<p>Datoteka $dir/{$table}_{$time}.txt se ne može otvoriti.</p>";
                    break;
                }
            } //Kraj mysqli_num_rows() 
        } //Kraj while petlje
    } else {
        echo "<p>Baza $dbName ne sadrži tablice.</p>";
    }



    ?>
</body>

</html>