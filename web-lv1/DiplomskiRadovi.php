<?php
include 'DiplomskiRadoviDBHelper.php';
include 'MyPDO.php';
interface iRadovi
{
    public function create($name, $text, $link, $oib);
    public function read();
    public function save();
}

class diplomskiRadovi implements iRadovi
{
    //Stvaranje varijable
    private $diplomskiPdo;
    private $naziv_rada;
    private $tekst_rada;
    private $link_rada;
    private $oib_tvrtke;

    // Konstruktor
    function __construct()
    {
        $this->diplomskiPdo = DiplomskiRadoviDBHelper::getInstance(MyPDO::getInstance());
    }

    // metode create save i read iz iRadovi
    public function create($name, $text, $link, $oib)
    {
        $this->naziv_rada = $name;
        $this->tekst_rada = $text;
        $this->link_rada = $link;
        $this->oib_tvrtke = $oib;
    }

    public function save()
    {
        $this->diplomskiPdo->insert($this->naziv_rada, $this->tekst_rada, $this->link_rada, $this->oib_tvrtke);
    }

    public function read()
    {
        return $this->diplomskiPdo->findAll();
    }


    //Funkcija za postavljanje vrijednosti 
    function postavi_naziv_rada($novo_ime)
    {
        $this->naziv_rada = $novo_ime;
    }
    //Funkcija za dohvat vrijednosti
    function dohvati_naziv_rada()
    {
        return $this->naziv_rada;
    }
    //Funkcija za postavljanje vrijednosti
    function postavi_tekst_rada($novo_tekst_rada)
    {
        $this->tekst_rada = $novo_tekst_rada;
    }
    //Funkcija za dohvat vrijednosti
    function dohvati_tekst_rada()
    {
        return $this->tekst_rada;
    }
    //Funkcija za postavljanje vrijednosti
    function postavi_oib_tvrtke($novo_oib_tvrtke)
    {
        $this->oib_tvrtke = $novo_oib_tvrtke;
    }
    //Funkcija za dohvat vrijednosti
    function dohvati_oib_tvrtke()
    {
        return $this->oib_tvrtke;
    }
    //Funkcija za postavljanje vrijednosti
    function postavi_link_rada($novo_link_rada)
    {
        $this->link_rada = $novo_link_rada;
    }
    //Funkcija za dohvat vrijednosti
    function dohvati_link_rada()
    {
        return $this->link_rada;
    }
}
