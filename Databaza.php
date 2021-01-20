<?php

// Navod: https://www.sqlitetutorial.net/sqlite-php/create-tables/
class Databaza {
    private $pdo;
    
    public function __construct($nazov) {
        $this->pdo = new \PDO("sqlite:" . $nazov);
    }
    
    
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . "databaza_na_web");
        }
        return $this->pdo;
    }
    
    public function vratPole($sql) {
        $stmt = $this->pdo->query($sql);
        $tabulka = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($tabulka, $row);
        }        
        //print_r($tabulka);
        return $tabulka;        
    }
    
    public function aktualizuj($sql) {
        $this->pdo->exec($sql);
    }    
    
}
