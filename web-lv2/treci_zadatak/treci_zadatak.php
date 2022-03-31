<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2 - 3.zadatak</title>
</head>

<body>
    <?php
    //Učitaj datoteku
    $xml = simplexml_load_file("LV2.xml");
    //  funkcija provjerava ako ima vrijednost vraca vrijednost inace vraca prazan string
    function valueOrEmpty($value)
    {
        if (isset($value)) {
            return $value;
        } else {
            return "";
        }
    }
    // glavni kontejner
    $content = "<div class='bigContainer'>";
    //Iteracija kroz XML
    foreach ($xml->record as $person) {
        $id = valueOrEmpty($person->id);
        $firstName = valueOrEmpty($person->ime);
        $lastName = valueOrEmpty($person->prezime);
        $email = valueOrEmpty($person->email);
        $sex = valueOrEmpty($person->spol);
        $image = valueOrEmpty($person->slika);
        $bio = valueOrEmpty($person->zivotopis);
        // svaki pojedinacni profil
        $content .=

            "<div class='profile'>
                <img src=$image alt='Profile image' width='150'>
                <div class='textProfile'>
                    <h4>$firstName $lastName </h4>
                    <p class=''>$sex</p>
                    <p class=''>$email</p>
                    <p class=''>$bio</p>
                </div>
            </div>
             ";
    }
    // zatvaranje glavnog kontejnera
    $content .=
        "</div>";

    // ispis svih profila
    echo $content;
    ?>
    <!-- stiliziranje profila -->
    <style>
        .bigContainer {
            display: grid;
            gap: 10px;
            grid-template-columns: 1fr 1fr 1fr 1fr;

        }

        img {
            margin-left: auto;
            margin-right: auto;
        }

        .profile {
            /* width: 300px; */
            background-color: #e7e7e7;
            display: grid;

        }

        .textProfile {
            text-align: center;
        }

        @media only screen and (max-width: 868px) {
            .bigContainer {

                grid-template-columns: 1fr 1fr;

            }
        }

        @media only screen and (max-width: 400px) {
            .bigContainer {

                grid-template-columns: 1fr;

            }
        }
    </style>
</body>

</html>