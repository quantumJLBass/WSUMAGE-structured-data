<?php
class Wsu_StructuredData_Model_Observer {
    public function submitSemanticWebData() {
        $this->debug("Cronjob gestartet");
        $this->notifySWSE();
        $this->debug("Cronjob beendet");
        return $this;
    }
    protected function notifySWSE($submission_url = "http://gr-notify.appspot.com/submit?uri=") {
        $email       = Mage::getStoreConfig('trans_email/ident_general/email');
        $base_url    = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $sitemap_url = $submission_url . $base_url . "sitemap.xml" . "&contact=" . $email . "&agent=wsuseo";
        $this->_httpGet($sitemap_url);
    }
    /**
     * for PTSW
     */
    protected function jsEscape($str) {
        $r            = array(
            ' ' => '%20',
            '!' => '%21',
            '@' => '@',
            '#' => '%23',
            '$' => '%24',
            '%' => '%25',
            '^' => '%5E',
            '&' => '%26',
            '*' => '*',
            '(' => '%28',
            ')' => '%29',
            '-' => '-',
            '_' => '_',
            '=' => '%3D',
            '+' => '+',
            ':' => '%3A',
            ';' => '%3B',
            '.' => '.',
            '"' => '%22',
            "'" => '%27',
            '\\' => '%5C',
            '/' => '/',
            '?' => '%3F',
            '<' => '%3C',
            '>' => '%3E',
            '~' => '%7E',
            '[' => '%5B',
            ']' => '%5D',
            '{' => '%7B',
            '}' => '%7D',
            '`' => '%60',
            '€' => '%u20AC'
        );
        $needles      = array_keys($r);
        $replacements = array_values($r);
        $output       = $str;
        $output       = str_replace($needles, $replacements, $output);
        return $output;
    }
    protected function debug($content) {
		$debug	= Mage::app()->getRequest()->getParam('debug');
        if (!isset($debug) || !$debug){
            return;
		}
        $type = gettype($content);
        echo '<div style="background-color: #FFF; border: solid 1px #000; padding: 5px 10px; font: 11px/15px Arial,sans-serif; display: block; color: #444; text-align: left; width: 960px; margin: 0px auto;">';
        echo "<b> | </b>";
        echo "<i>($type)</i>";
        switch (gettype($content)) {
            case 'array':
                echo "<pre>";
                print_r($content);
                echo "</pre>";
                break;
            case 'object':
                echo "<pre>";
                print_r($content);
                echo "</pre>";
                break;
            case 'boolean':
                if ($content == TRUE)
                    echo "<b> TRUE </b>";
                else
                    echo "<b> FALSE </b>";
                break;
            default:
                echo "<pre>$content</pre>";
                break;
        }
        echo "<b> | </b></div>";
    }
    protected function _httpGet($url) {
        // file operations are allowed
        if (ini_get('allow_url_fopen') == '1') {
            $str = file_get_contents($url);
            if ($str === false) {
                $http_status_code = "";
                for ($i = 0; $i < count($http_response_header); $i++) {
                    if (strncasecmp("HTTP", $http_response_header[$i], 4) == 0) {
                        // determine HTTP response code
                        $http_status_code = preg_replace("/^.{0,9}([0-9]{3})/i", "$1", $http_response_header[$i]);
                        break;
                    }
                }
                echo "<p class=\"error\">Submission failed: " . $http_status_code . "</p>";
            }
            return $str;
        }
        // file operations are disallowed, try it like curl
        else {
            $url  = parse_url($url);
            $port = isset($url['port']) ? $url['port'] : 80;
            $fp   = fsockopen($url['host'], $port);
            if (!$fp) {
                echo "<p class=\"error\">Cannot retrieve $url</p>";
                return false;
            } else {
                // send the necessary headers to get the file
                fwrite($fp, "GET " . $url['path'] . "?" . $url['query'] . " HTTP/1.0\r\n" . "Host:" . $url['host'] . "\r\n" . "Accept: text/html\r\n" . "User-Agent: WSUSD v2\r\n" . "Connection: close\r\n\r\n");
                // retrieve response from server
                $buffer            = "";
                $status_code_found = false;
                $is_error          = false;
                while ($line = fread($fp, 4096)) {
                    $buffer .= $line;
                    if (!$status_code_found && ($pos = strpos($line, "HTTP")) >= 0) {
                        // extract HTTP response code
                        $response          = explode("\n", substr($line, $pos));
                        $http_status_code  = preg_replace("/^.{0,9}([0-9]{3})/i", "$1", $response[0]);
                        $is_error          = !preg_match("/(200|406)/i", $http_status_code); // accepted status codes not resulting in error are 200 and 406
                        $status_code_found = true;
                    }
                }
                fclose($fp);
                $pos = strpos($buffer, "\r\n\r\n");
                if ($is_error)
                    echo "<p class=\"error\">Submission failed: " . $http_status_code . "</p>";
                return substr($buffer, $pos);
            }
        }
    }
}
?>
	
