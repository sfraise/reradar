<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 8/5/2016
 * Time: 8:07 PM
 *
 * Include plugin classes
 * require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';
 */

function convertQuotes($str) {
    $str = str_replace("'", "\\'", $str);
    $str = str_replace('"', '\\"', $str);
    $str = html_entity_decode($str, ENT_QUOTES | ENT_XML1, 'UTF-8');

    return $str;
}

function cleanString($in,$offset=null) {
    $out = trim($in);
    if (!empty($out)) {
        $entity_start = strpos($out,'&',$offset);
        if ($entity_start === false) {
            // ideal
            return $out;
        }
        else {
            $entity_end = strpos($out,';',$entity_start);
            if ($entity_end === false) {
                return $out;
            } else if ($entity_end > $entity_start+7) {
                // und weiter gehts
                $out = cleanString($out,$entity_start+1);
            } else {
                $clean = substr($out,0,$entity_start);
                $subst = substr($out,$entity_start+1,1);
                // &scaron; => "s" / &#353; => "_"
                $clean .= ($subst != "#") ? $subst : "_";
                $clean .= substr($out,$entity_end+1);
                // und weiter gehts
                $out = cleanString($clean,$entity_start+1);
            }
        }
    }
    return $out;
}

function is_utf8( $str ) {
    return preg_match( "/^(
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
      )*$/x",
        $str
    );
}

function force_utf8( $str, $inputEnc='WINDOWS-1252' ) {
    if ( is_utf8( $str ) ) // Nothing to do.
        return $str;

    if ( strtoupper( $inputEnc ) === 'ISO-8859-1' )
        return utf8_encode( $str );

    if ( function_exists( 'mb_convert_encoding' ) )
        return mb_convert_encoding( $str, 'UTF-8', $inputEnc );

    if ( function_exists( 'iconv' ) )
        return iconv( $inputEnc, 'UTF-8', $str );

    // You could also just return the original string.
    trigger_error(
        'Cannot convert string to UTF-8 in file '
        . __FILE__ . ', line ' . __LINE__ . '!',
        E_USER_ERROR
    );
}

function summarizeContentUrl($url, $restrictionNumber, $restrictionType) {
    require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';

    // CRAWL URL
    $contentClass = new getContent;
    $result = $contentClass->summarizeContent($url, $restrictionNumber, $restrictionType);
    $result = json_decode($result, true);

    $contentItems = $result['items'];

    $content = array();
    foreach($contentItems as $contentItem) {
        $content[] = '<p>' . trim(json_encode(utf8_decode($contentItem['text'])), '"') . '</p>';
    }

    $headline = $content[0];

    array_shift($content);
    $content = implode('', $content);

    $data = array(
        "headline" => $headline,
        "content" => $content
    );

    return $data;
}

function summarizeContentText($text, $restrictionNumber, $restrictionType) {
    require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';

    // CRAWL URL
    $contentClass = new getContent;
    $result = $contentClass->reSummarizeContent($text, $restrictionNumber, $restrictionType);
    $result = json_decode($result, true);

    $contentItems = $result['items'];

    $content = array();
    foreach($contentItems as $contentItem) {
        $content[] = '<p>' . trim(json_encode(utf8_decode($contentItem['text'])), '"') . '</p>';
    }

    $headline = $content[0];

    array_shift($content);
    $content = implode('', $content);

    $data = array(
        "headline" => $headline,
        "content" => $content
    );

    return $data;
}

function estimateReadTime($text) {
    $text = strip_tags($text); // STRIP HTML TAGS
    $count = str_word_count($text); // GET TOTAL CHARACTER COUNT
    $readTime = round($count / 150, 1); // ESTIMATE TO 1 DECIMAL POINT

    return $readTime;
}

/*** AJAX FUNCTIONS ***/
function get_content() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $url = $_REQUEST['url'];

        require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';

        // CRAWL URL
        $contentClass = new getContent;
        $content = $contentClass->crawlUrl($url); // GET ORIGINAL CONTENT

        $summaryResults = $contentClass->summarizeContent($url, 20, 'percent');
        $summaryResults = json_decode($summaryResults, true);

        $summaryError = $summaryResults['summarizerDoc']['error'];
        $summaryItems = $summaryResults['items'];

        $summary = array();
        foreach($summaryItems as $summaryItem) {
            $summary[] = '<p>' . trim(json_encode(utf8_decode($summaryItem['text'])), '"') . '</p>';
        }

        $headline = $summary[0];

        array_shift($summary);
        $summary = implode('', $summary);

        $data = array(
            "content" => $content,
            "headline" => $headline,
            "summary" => $summary,
            "summaryError" => $summaryError
        );

        echo json_encode($data, JSON_HEX_APOS);
    }

    die();
}
add_action( 'wp_ajax_get_content', 'get_content' );
add_action( 'wp_ajax_nopriv_get_content', 'get_content' );

/*** SUMMARIZE CONTENT ***/
function summarize_content() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $url = $_REQUEST['url'];

        require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';

        // CRAWL URL
        $contentClass = new getContent;
        $result = $contentClass->summarizeContent($url, 20);
        $result = json_decode($result, true);

        $contentItems = $result['items'];

        $content = array();
        foreach($contentItems as $contentItem) {
            $content[] = '<p>' . json_encode(utf8_decode($contentItem['text'])) . '</p>';
        }

        $headline = $content[0];

        $content = implode('', $content);

        $data = array(
            "headline" => $headline,
            "content" => $content
        );

        echo json_encode($data);
    }

    die();
}
add_action( 'wp_ajax_summarize_content', 'summarize_content' );
add_action( 'wp_ajax_nopriv_summarize_content', 'summarize_content' );

/*** RESUMMARIZE CONTENT ***/
function resummarize_content() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $url = $_REQUEST['url'];
        $text = $_REQUEST['text'];
        $restrictionNumber = $_REQUEST['restrictionNumber'];
        $restrictionType = $_REQUEST['restrictionType'];
        $resummarizeFrom = $_REQUEST['resummarizeFrom'];

        if($resummarizeFrom == 'url' && $url) {
            $data = summarizeContentUrl($url, $restrictionNumber, $restrictionType);
        } else if($resummarizeFrom == 'oc' && $text) {
            $data = summarizeContentText($text, $restrictionNumber, $restrictionType);
        } else {
            $data = array(
                'Error' => 'Error: Make sure either URL or Text is selected!'
            );
        }

        echo json_encode($data);
    }

    die();
}
add_action( 'wp_ajax_resummarize_content', 'resummarize_content' );
add_action( 'wp_ajax_nopriv_resummarize_content', 'resummarize_content' );

/*** ESTIMATE READ TIME ***/
function estimate_read_time() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $text = $_REQUEST['text'];

        echo estimateReadTime($text);
    }

    die();
}
add_action( 'wp_ajax_estimate_read_time', 'estimate_read_time' );
add_action( 'wp_ajax_nopriv_estimate_read_time', 'estimate_read_time' );


/*** NOT USED CURRENTLY, REMOVED WORDAI
function spin_content() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $text = $_REQUEST['text'];

        require plugin_dir_path( __FILE__ ) . 'includes/class-curation.php';

        // CRAWL URL
        //'Unique', 'Very Unique', 'Readable'
        $contentClass = new getContent;
        $version1Json = $contentClass->spinContent($text, 'Very Unique');
        $version1 = json_decode($version1Json);
        $version1Uniqueness = $version1->uniqueness;
        $spunText1 = $version1->text;
        $spunText1 = str_replace(array("\r","\n"), '<br />', $spunText1);

        $version2Json = $contentClass->spinContent($text, 'Unique');
        $version2 = json_decode($version2Json);
        $version2Uniqueness = $version2->uniqueness;
        $spunText2 = $version2->text;
        $spunText2 = str_replace(array("\r","\n"), '<br />', $spunText2);

        $version3Json = $contentClass->spinContent($text, 'Readable');
        $version3 = json_decode($version3Json);
        $version3Uniqueness = $version3->uniqueness;
        $spunText3 = $version3->text;
        $spunText3 = str_replace(array("\r","\n"), '<br />', $spunText3);

        $version1Text = '
            <div class="spun-content-1 spun-content-item">
                <div class="spun-content">
                    ' . $spunText1 . '
                </div>
                <div class="spun-content-submit submit-button">Select<div>
                <div style="clear:both;"></div>
            </div>
        ';

        $version2Text = '
            <div class="spun-content-1 spun-content-item">
                <div class="spun-content">
                    ' . $spunText2 . '
                </div>
                <div class="spun-content-submit submit-button">Select<div>
                <div style="clear:both;"></div>
            </div>
        ';

        $version3Text = '
            <div class="spun-content-1 spun-content-item">
                <div class="spun-content">
                    ' . $spunText3 . '
                </div>
                <div class="spun-content-submit submit-button">Select<div>
                <div style="clear:both;"></div>
            </div>
        ';

        $data = array(
            "version1Uniqueness" => $version1Uniqueness,
            "version1Text" => $version1Text,
            "version2Uniqueness" => $version2Uniqueness,
            "version2Text" => $version2Text,
            "version3Uniqueness" => $version3Uniqueness,
            "version3Text" => $version3Text
        );

        echo json_encode($data);
    }

    die();
}
add_action( 'wp_ajax_spin_content', 'spin_content' );
add_action( 'wp_ajax_nopriv_spin_content', 'spin_content' );
*/