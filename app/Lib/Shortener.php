<?php

namespace App\Lib;

class Shortener
{
    protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";

    protected static $longURL;
    
    protected static $historyLong;
    
    public bool $URLInHistory;
    
    public bool $isURLvalid;
    
    public bool $isURLExist;
    
    public string $shortToken;
    
    public function __construct($longURL, $historyURL){
        $this->isURLvalid = false;
        $this->isURLExist = false;
        
        self::$longURL = $longURL;
        self::$historyLong = $historyURL;
    }
    
    protected function isURLHistory(): bool {
        if (!isset(self::$historyLong[0])) {
            $this->URLInHistory = false;
            return false;
            
        }
        $this->shortToken = self::$historyLong[0]['short'];
        $this->URLInHistory = true;
        return true;
    }
    
    protected function checkTheUrlForValidity(): bool {
        $this->isURLvalid = filter_var(self::$longURL, FILTER_VALIDATE_URL);
        return $this->isURLvalid;
    }
    
    protected function verifyUrlExists(){
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_URL, self::$longURL);
        curl_setopt($ci, CURLOPT_NOBODY, true);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ci);
        $response = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        curl_close($ci);
        $this->isURLExist = (!empty($response) && $response != 404);
        return $this->isURLExist;
    }
    
    protected function getRandomToken() {
        $collections = explode('|', self::$chars);
        $token = '';
        for ($i = 0; $i < 2; $i++) {
            foreach($collections as $collection) {
               $token .= $collection[array_rand(str_split($collection))];
            }
        }
        $this->token = str_shuffle($token);
        return $this->token;
    }
    
    public function longToShort() {
        if ($this->checkTheUrlForValidity() && $this->verifyUrlExists() && !$this->isURLHistory()) {
            $this->shortToken = $this->getRandomToken();
        }
    }
}
