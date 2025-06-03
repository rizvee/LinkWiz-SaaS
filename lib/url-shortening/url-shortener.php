<?php
// lib/url-shortening/url-shortener.php

class LinkWiz_URLShortener { // Renamed class for clarity and WP naming conventions
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $this->wpdb->prefix . 'linkwiz_urls';
    }

    public function shortenURL($longURL, $user_id = null) {
        if (empty($longURL) || !filter_var($longURL, FILTER_VALIDATE_URL)) {
            return new WP_Error('invalid_url', 'Please enter a valid URL.');
        }

        $max_attempts = 10; // Max attempts to generate a unique key
        $attempt = 0;
        $shortKey = '';

        do {
            $shortKey = $this->generateShortKey();
            $attempt++;
            if ($attempt > $max_attempts) {
                // Log this error or handle it more gracefully
                error_log("LinkWiz SaaS: Could not generate a unique short key after $max_attempts attempts.");
                return new WP_Error('generation_failed', 'Could not generate a unique short key. Please try again.');
            }
        } while (!$this->isShortKeyUnique($shortKey));

        $data = array(
            'original_url' => esc_url_raw($longURL),
            'short_key' => $shortKey,
            'created_at' => current_time('mysql', 1), // GMT time
            'user_id' => $user_id ? absint($user_id) : null,
        );
        $format = array('%s', '%s', '%s', '%d');

        $result = $this->wpdb->insert($this->table_name, $data, $format);

        if ($result === false) {
            // Log this error
            error_log("LinkWiz SaaS: Failed to insert short URL into database. DB Error: " . $this->wpdb->last_error);
            return new WP_Error('db_error', 'Could not save the short URL. Database error.');
        }

        return array('short_url' => $this->getShortURLFromKey($shortKey), 'short_key' => $shortKey);
    }

    private function generateShortKey($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortKey = '';
        $char_length = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $shortKey .= $characters[wp_rand(0, $char_length - 1)];
        }
        return $shortKey;
    }

    private function isShortKeyUnique($shortKey) {
        $count = $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} WHERE short_key = %s",
            $shortKey
        ));
        return $count == 0;
    }

    public function getShortURLFromKey($shortKey) {
        // Retrieve the configured domain or use site_url as a fallback
        $domain = get_option('linkwiz_saas_short_url_domain', site_url());
        // Ensure domain has a trailing slash
        $domain = rtrim($domain, '/') . '/';
        return $domain . $shortKey;
    }

    // Optional: A method to get the long URL from a short key
    public function getLongURL($shortKey) {
        $original_url = $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT original_url FROM {$this->table_name} WHERE short_key = %s",
            $shortKey
        ));
        if ($original_url) {
            // Optionally, update visit count here or in the redirection logic
            // $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->table_name} SET visit_count = visit_count + 1 WHERE short_key = %s", $shortKey));
        }
        return $original_url;
    }
}
?>
