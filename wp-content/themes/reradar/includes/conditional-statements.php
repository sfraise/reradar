<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 12/2/2016
 * Time: 4:00 PM
 */

// SET NEWS HERO IMAGE CONDITIONAL TAG
function is_news_hero() {
    global $post;

    $hero_image = get_field('hero_image', get_the_ID());
    if($hero_image) {
        return true;
    } else {
        return false;
    }
}
add_action('get_header', 'is_news_hero');

// SET IF CONDITIONS FOR NEWS HERO IMAGE
function news_hero_evaluator($value) {
    $evaluate = is_news_hero();

    return $evaluate;
}
add_filter($if_shortcode_filter_prefix.'news_hero','news_hero_evaluator');