<?php
// url-shortening/url-shortener.php

class URLShortener {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function shortenURL($longURL) {
        $shortKey = $this->generateShortKey();
        
        // Store the mapping of short key to long URL in the database
        $stmt = $this->db->prepare("INSERT INTO short_urls (short_key, long_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $shortKey, $longURL);
        $stmt->execute();
        
        return $this->getShortURL($shortKey);
    }
    
    private function generateShortKey() {
        // Generate a unique short key, e.g., using base62 encoding
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortKey = '';
        $length = 6;
        
        for ($i = 0; $i < $length; $i++) {
            $shortKey .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $shortKey;
    }
    
    private function getShortURL($shortKey) {
        // Construct the short URL based on your domain
        $domain = 'https://your-domain.com/'; // Replace with your actual domain
        return $domain . $shortKey;
    }
}


// $db = new mysqli('localhost', 'username', 'password', 'linkwiz');
// $shortener = new URLShortener($db);
// $shortURL = $shortener->shortenURL('https://example.com/long-url-to-be-shortened');
// echo 'Short URL: ' . $shortURL;
