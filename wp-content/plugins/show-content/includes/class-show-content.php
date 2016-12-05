<?php
/**
 * Created by Code Monkeys LLC
 * http://www.codemonkeysllc.com
 * User: Spencer
 * Date: 11/30/2016
 * Time: 12:07 PM
 */


/*** wpdb example
global $wpdb;
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

/*
 * POST TYPES:      TAXONOMIES
 * agent-profile    (agent_profiles_categories, region)
 * broker-profile   (broker_profiles_categories, region)
 * directory        (directory_categories, region)
 * products         (products_categories, region)
 * resources        (resources_categories, region, content_type)
 * expert           (expert_categories, region, content_type)
 * news             (news_categories, region, content_type)
 */

function truncate($string,$length=100,$append="&hellip;") {
    $string = trim($string);

    if(strlen($string) > $length) {
        $string = wordwrap($string, $length);
        $string = explode("\n", $string, 2);
        $string = $string[0] . $append;
    }

    return $string;
}

class showContent {
    function getContent($postType, $contentTypes, $categories, $template, $limit, $orderBy, $paginate, $page) {
        $contentArr = array();
        $contentTypes = array_filter($contentTypes);
        $categories = array_filter($categories);

        // SET QUERY TAXONOMY ARGS
        $taxQuery = array('relation' => 'AND');

        if(!empty($contentTypes)) {
            $taxQuery[] = array(
                array(
                    'relation' => 'OR',
                    array(
                        'taxonomy'		=> 'content_type',
                        'field'		=> 'slug',
                        'terms'	=> $contentTypes,
                        'operator' => 'IN'
                    ),
                )
            );
        }

        if(!empty($categories)) {
            $taxQuery[] = array(
                array(
                    'relation' => 'OR',
                    array(
                        'taxonomy'		=> $postType . '_categories',
                        'field'		=> 'slug',
                        'terms'	=> $categories,
                        'operator' => 'IN'
                    ),
                )
            );
        }

        // SET QUERY ARGS
        $args = array(
            'post_type' => $postType,
            'tax_query' => $taxQuery,
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'paged' => $page,
            'no_found_rows' => true
        );

        // RUN QUERY
        $query = new WP_Query($args);
        //echo $query->request; // OUTPUT ACTUAL QUERY

        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_type = get_post_type();
            $post_link = get_permalink();
            $post_date = get_the_date();
            $categories = get_field($post_type . '-categories');
            $headline = get_field('headline');
            $final_content = get_field('final_content');
            $excerpt = truncate($final_content);
            $publisher = get_field('publisher');
            $publisher_url = get_field('publisher_url');
            $original_author = get_field('original_author');
            $content_types = get_field('content_type');
            $region = get_field('region');
            $estimated_read_time = get_field('estimated_read_time');
            $direct_link = get_field('direct_link');
            $hero_image_url = get_field('hero_image');
            $source_url = get_field('source_url');

            if($hero_image_url) {
                $hero_image = '<img src="' . $hero_image_url . '" alt="' . $post_title . '" />';
            }

            if($original_author) {
                $authorEle = '
                    <span class="show-content-original-author">' . $original_author . '</span>
                ';
            }

            if($publisher) {
                $publisherEle = '
                    <span class="show-content-publisher">' . $publisher . '</span>
                ';
            }

            $postDateEle = '
                <span class="show-content-post-date">' . $post_date . '</span>
            ';

            if($original_author && $publisher) {
                $footer_content = '
                    <a href="' . $publisher_url . '" target="_blank">
                        ' . $authorEle . ' @' . $publisherEle . '
                    </a>
                ';
                $footer_content_basic = '
                    <a href="' . $publisher_url . '" target="_blank">
                        ' . $authorEle . ' @' . $publisherEle . $postDateEle . '
                    </a>
                ';
            } else {
                $footer_content = '
                    <a href="' . $publisher_url . '" target="_blank">
                        ' . $authorEle . ' @' . $publisherEle . '
                    </a>
                ';
                $footer_content_basic = '
                    <a href="' . $publisher_url . '" target="_blank">
                        ' . $authorEle . ' @' . $publisherEle . $postDateEle . '
                    </a>
                ';
            }

            if($template == 'hero') {
                $contentArr[] = '
                    <div class="show-content-item ' . $post_type . ' ' . $template . '">
                        <div class="show-content-hero" style="background-image: url(' . $hero_image_url . ');">
                            <div class="show-content-headline">
                                <h2><a href="' . $post_link . '">' . $headline . '</a></h2>
                            </div>
                        </div>
                        <div class="show-content-excerpt">
                            ' . $excerpt . '
                        </div>
                        <div class="show-content-footer">
                            ' . $footer_content . '
                        </div>
                    </div>
                ';
            } else if($template == 'basic') {
                $contentArr[] = '
                    <div class="show-content-item ' . $post_type . ' ' . $template . '">
                        <div class="show-content-headline">
                            <h2><a href="' . $post_link . '">' . $headline . '</a></h2>
                        </div>
                        <div class="show-content-footer">
                            ' . $footer_content_basic . '
                        </div>
                    </div>
                ';
            } else if($template == 'default') {
                $contentArr[] = '
                    <div class="show-content-item ' . $post_type . ' ' . $template . '">
                        <div class="table">
                            <div class="table-row">
                                <div class="table-cell">
                                    <a href="' . $post_link . '">
                                        <div class="show-content-item-image" style="background-image: url(' . $hero_image_url . ');"></div>
                                    </a>
                                </div>
                                <div class="table-cell">
                                    <div class="show-content-headline">
                                        <h2><a href="' . $post_link . '">' . $headline . '</a></h2>
                                    </div>
                                    <div class="show-content-footer">
                                        ' . $footer_content . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            } else {
                $contentArr[] = '
                    <div class="show-content-item ' . $post_type . ' ' . $template . '">
                        <div class="table">
                            <div class="table-row">
                                <div class="table-cell">
                                    <a href="' . $post_link . '">
                                        <div class="show-content-item-image" style="background-image: url(' . $hero_image_url . ');"></div>
                                    </a>
                                </div>
                                <div class="table-cell">
                                    <div class="show-content-headline">
                                        <h2><a href="' . $post_link . '">' . $headline . '</a></h2>
                                    </div>
                                    <div class="show-content-footer">
                                        ' . $footer_content . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        }

        return implode('', $contentArr);
    }
}