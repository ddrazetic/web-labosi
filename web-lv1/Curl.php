<h2>cURL Rezultati:</h2>

<?php
include 'simple_html_dom.php';
include 'DiplomskiRadovi.php';
$url = 'https://stup.ferit.hr/zavrsni-radovi/page/';
$r = [];
$diplomskiRadovi = new DiplomskiRadovi();
function getText11($link)
{
    // dohvacanje tekstova iz pojedinog rada
    $textResult = [];
    $curl = curl_init($link);
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $results = curl_exec($curl);
    array_push($textResult, $results);
    curl_close($curl);

    $paragraphs = [];
    foreach ($textResult as $result) {
        $dom = new simple_html_dom();
        $dom->load($result);
        foreach ($dom->find("div.post-content") as $element) {
            foreach ($element->find('p') as $paragraph) {
                $paragraphs[] = strip_tags($paragraph->innertext);
            }
        }
    }

    return implode("\n", $paragraphs);
}



for ($i = 2; $i <= 6; $i++) {
    //Pokrećemo cURL spoj
    $fullUrl = $url . $i;
    $curl = curl_init($fullUrl);
    //Zaustavi ako se dogodi pogreška
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    //Dozvoli preusmjeravanja
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    //Spremi vraćene podatke u varijablu
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //Postavi timeout
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    //Izvrši transakciju
    $result = curl_exec($curl);
    // dodavanje svih stranica u listu 
    array_push($r, $result);
    //Zatvori spoj
    curl_close($curl);
}
// $output = html_entity_decode($r, ENT_QUOTES, 'UTF-8');

// Create a DOM object
$dom = new simple_html_dom();
// Load HTML from a string

for ($j = 0; $j <= 4; $j++) {
    $dom->load($r[$j]);

    $imagesUrl = [];
    $oibs = [];
    $hrefElements = [];
    $text = [];
    $textElements = [];

    // nađi slike u kojima je oib
    foreach ($dom->find('img') as $element) {
        if (strpos($element, "logos") !== false) {
            // print_r($element->src . '</br>');
            array_push($imagesUrl, $element->src);
        }
    }
    // nađi oib u slici
    foreach ($imagesUrl as $image) {
        $oibImg = explode("logos/", $image);
        array_push($oibs, substr($oibImg[1], 0, 11));
        // print_r(substr($oibImg[1], 0, 11) . '</br>');
    }

    // find link & name
    foreach ($dom->find('a') as $element) {
        array_push($hrefElements, $element->href);
        array_push($textElements, $element->plaintext);
    }

    // brisanje krivih linkova. pravi linkovi pocinju od 27
    for ($i = 0; $i <= 26; $i++) {
        unset($hrefElements[$i]);
        unset($textElements[$i]);
    }


    for ($i = 51; $i <= 61; $i++) {
        unset($hrefElements[$i]);
        unset($textElements[$i]);
    }




    $hrefElements = array_values($hrefElements);
    $textElements = array_values($textElements);


    $hrefFiltered = [];
    $titleFiltered = [];

    // svaki cetvrti je novi naslov
    for ($i = 0; $i < count($hrefElements) / 4; $i++) {
        $hrefFiltered[$i] = $hrefElements[$i * 4];
        $titleFiltered[$i] = $textElements[$i * 4];
    }

    // echo '<pre>', var_dump($hrefFiltered), '</pre>';
    // echo '<pre>', var_dump($titleFiltered), '</pre>';


    for ($i = 0; $i < count($imagesUrl); $i++) {
        array_push($text, getText11($hrefElements[$i]));
        $diplomskiRadovi->create($titleFiltered[$i], $text[$i], $hrefElements[$i], $oibs[$i]);
        $diplomskiRadovi->save();
        // echo '<pre>', $diplomskiRadovi->dohvati_oib_tvrtke();
        // '</pre>';
    }

    // echo '<pre>', var_dump($text), '</pre>';
    echo '<p><strong> ' . $j + 1 . '. STRANICA UCITANA</strong></p>';
}

?>