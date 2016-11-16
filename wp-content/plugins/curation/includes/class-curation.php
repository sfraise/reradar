<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 10/24/2016
 * Time: 4:24 PM
 */


/*** db example
$querystr = "
SELECT " . $wpdb->prefix . "table_name.*
FROM " . $wpdb->prefix . "table_name
WHERE " . $wpdb->prefix . "table_name.id = 1
ORDER BY " . $wpdb->prefix . "table_name.id DESC
";

$results = $wpdb->get_results($querystr, OBJECT);

foreach($results as $result) {
echo $result->column_name;
}
 */

class getContent {
    function crawlUrl($url) {
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('log_errors', true);
        ini_set('error_log', '/var/log/error.log');
        ini_set('display_errors', 1);

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $output = curl_exec($ch);

        $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        // GET NAME
        preg_match('/<body(.*)/', $output, $bodyMatch);
        $body_start = strpos($output, "<body$bodyMatch[1]");
        $body_end = strpos($output, '</body>', $body_start);
        $body_length = $body_end - $body_start;
        $body = substr($output, $body_start, $body_length);
        $body = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $body);
        $body = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $body);

        return strip_tags($body, '<p><br><img>');
    }

    function spinContent($text, $quality) {
        $email = 'brucepeterson@me.com';
        $pass = 'rush7615';

        $text = strip_tags($text, '<p><br>');

        if(isset($text) && isset($quality)) {
            $text = urlencode($text);
            $ch = curl_init('http://wordai.com/users/turing-api.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json&title=on&sentence=on&nooriginal=on&returnspin=true");
            $result = curl_exec($ch);
            curl_close ($ch);

            return $result;
        } else {
            return 'Error: Not All Variables Set!';
        }

        //The variable quality can be a value between 0 and 100. 0 is more unique and 100 is more readable.
        //echo spinContent(stripslashes('Here is an example.'),30,60,90);
    }

    function summarizeContent($url, $restrictionNumber, $restrictionType) {
        $apiKey = 'fae6929a-7dc3-4413-95f1-7dda97262bf4';
        if($restrictionNumber) {
            $summaryRestriction = $restrictionNumber;
        } else {
            $summaryRestriction = 20;
        }
        if($restrictionType == 'percent') {
            $usePercentRestriction = 'true';
        } else {
            $usePercentRestriction = 'false';
        }
        $fullTextTrees = 'true';
        $returnedTopicsCount = 2;
        $structure = 'NewsArticle';
        $conceptsRestrictions = 7;
        $textStreamLength = 1000;

        if(isset($url)) {
            header('Content-Type: text/plain');
            $link = "http://api.intellexer.com/summarize?apikey=$apiKey&url=$url&summaryRestriction=$summaryRestriction&conceptRestrictions=$conceptsRestrictions&returnedTopicsCount=$returnedTopicsCount&fullTextTree=$fullTextTrees&structure=$structure&textStreamLength=$textStreamLength&usePercentRestriction=$usePercentRestriction";
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $link,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => false,
                CURLOPT_HEADER => 'Content-type: text/html; charset=UTF-8',
                CURLOPT_FOLLOWLOCATION => true
            ));
            $results = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'CURL error: ' . curl_error($ch);
            } else {
                # parse JSON results
                $json_results = $results;

                return $json_results;
            }
        } else {
            return 'Error: No Url Found!';
        }
    }

    function reSummarizeContent($text, $restrictionNumber, $restrictionType) {
        $apiKey = 'fae6929a-7dc3-4413-95f1-7dda97262bf4';
        if($restrictionNumber) {
            $summaryRestriction = $restrictionNumber;
        } else {
            $summaryRestriction = 20;
        }
        if($restrictionType == 'percent') {
            $usePercentRestriction = 'true';
        } else {
            $usePercentRestriction = 'false';
        }
        $fullTextTrees = 'true';
        $returnedTopicsCount = 2;
        $structure = 'NewsArticle';
        $conceptsRestrictions = 7;
        $textStreamLength = 1000;

        if(isset($text)) {
            header('Content-Type: text/plain');
            $header = array('Content-type: application/octet-stream');
            $link = "http://api.intellexer.com/summarizeText?apikey=$apiKey&summaryRestriction=$summaryRestriction&conceptRestrictions=$conceptsRestrictions&returnedTopicsCount=$returnedTopicsCount&fullTextTree=$fullTextTrees&structure=$structure&textStreamLength=$textStreamLength&usePercentRestriction=$usePercentRestriction";
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $link,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => $text,
                CURLOPT_FOLLOWLOCATION => true
            ));
            $results = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'CURL error: ' . curl_error($ch);
            } else {
                # parse JSON results
                $json_results = $results;

                return $json_results;
            }
        } else {
            return 'Error: No Url Found!';
        }
    }
}