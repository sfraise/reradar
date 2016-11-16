/**
 * Created by Spencer on 11/5/2016.
 *
 * Use curationScript.pluginUrl for the plugin url path (set at the top of curation.php)
 *
 */


jQuery(document).ready(function() {
    /*** GET CONTENT FROM URL ***/
    jQuery(document).on('click', '.curation-url-submit', function() {
        var url = jQuery('.curation-url input').val();
        var urlTrimmed = url.replace(/^https?\:\/\//i, "");
        urlTrimmed = urlTrimmed.replace(/^www\./i, "");
        urlTrimmed = urlTrimmed.split('/')[0];
        var noticeEle = jQuery('.curation-submit-notice');

        noticeEle.html('<div class="ajax-loading"><img src="' + curationScript.pluginUrl + 'images/loading/loading29.gif" alt="loading" /></div>');
        jQuery.ajax({
            method: 'POST',
            dataType: 'JSON',
            url: ajax.ajax_url,
            data: {
                'action' : 'get_content',
                'url' : url
            },
            success:function(data) {
                noticeEle.html('');
                //var contentData = JSON.parse(data);
                var contentData = data;

                jQuery('.curation-submit-notice').html(contentData.summaryError);

                var title = jQuery(contentData.headline).text();
                var headline = jQuery('.headline');

                // UPDATE FIELDS
                jQuery('.publisher input').val(urlTrimmed);
                jQuery('.publisher-url input').val(url);

                // GET WYSIWYG EDITOR IDS
                var editorId = jQuery('.original-content').find('textarea').attr('id');
                var headlineEditorId = headline.find('textarea').attr('id');
                var finalContentEditorId = jQuery('.final-content').find('textarea').attr('id');

                // ADD ORIGINAL CONTENT TO EDITOR
                if(!contentData.content || !contentData.content.trim()) {
                    noticeEle.append('No original content found<br />');
                    console.log('No original content found');
                } else {
                    tinyMCE.get(editorId).setContent(contentData.content);
                }

                // ADD HEADLINE TO EDITOR
                if(!contentData.headline || !contentData.headline.trim()) {
                    noticeEle.append('No headline found<br />');
                    console.log('No headline found');
                } else {
                    tinyMCE.get(headlineEditorId).setContent(contentData.headline);
                }

                // ADD SUMMARY TO EDITOR
                if(!contentData.summary.trim() || !contentData.summary.trim()) {
                    noticeEle.append('No summary found<br />');
                    console.log('No summary found');
                } else {
                    tinyMCE.get(finalContentEditorId).setContent(stripSlashes(contentData.summary));
                }

                if(!contentData.content || !contentData.content.trim() || !contentData.headline || !contentData.headline.trim() || !contentData.summary.trim() || !contentData.summary.trim()) {
                    console.log('No values returned');
                } else {
                    tinyMCE.triggerSave();

                    // UPDATE TITLE
                    jQuery('#title').val(title).trigger('click').focus();
                    jQuery('html, body').animate({
                        scrollTop: headline.offset().top
                    }, 1000);
                }
            },
            error: function(errorThrown){
                jQuery('.curation-submit-notice').html(errorThrown);
                console.log(errorThrown);
            }
        });
    });

    /*** SUMMARIZE CONTENT ***/
    jQuery(document).on('click', '.summarize-content-submit', function() {
        var url = jQuery('.curation-url input').val();

        jQuery('.spun-content-loading').html('<div class="ajax-loading"><img src="' + curationScript.pluginUrl + 'images/loading/loading29.gif" alt="loading" /></div>');
        jQuery.ajax({
            method: 'POST',
            url: ajax.ajax_url,
            data: {
                'action' : 'summarize_content',
                'url' : url
            },
            success:function(data) {
                jQuery('.spun-content-loading').html('');
                var summarizedData = JSON.parse(data);
                var title = jQuery(summarizedData.headline).text();

                // UPDATE TITLE
                jQuery('#title').val(title).trigger('click').focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery('.headline').offset().top
                }, 1000);

                // GET WYSIWYG EDITOR ID
                var headlineEditorId = jQuery('.headline').find('textarea').attr('id');
                var finalContentEditorId = jQuery('.final-content').find('textarea').attr('id');

                // ADD CONTENT TO EDITOR
                tinyMCE.get(headlineEditorId).setContent(summarizedData.headline);
                tinyMCE.get(finalContentEditorId).setContent(stripSlashes(summarizedData.content));
                tinyMCE.triggerSave();
            },
            error: function(errorThrown){
                jQuery('.spun-content-loading').html(errorThrown);
                console.log(errorThrown);
            }
        });
    });

    /*** RESUMMARIZE CONTENT ***/
    jQuery(document).on('click', '.resummarize-content-submit', function() {
        var url = jQuery('.curation-url input').val();
        var text = jQuery('.original-content').find('textarea').val();
        var restrictionNumber = jQuery('#resummarize-restriction-number').val();
        var restrictionType = jQuery('#resummarize-restriction-type').val();
        var resummarizeFrom = jQuery('#resummarize-from').val();
        var headlineEle = jQuery('.headline');

        jQuery('.resummarize-add-submit').fadeOut();
        jQuery('.resummarized-content-wrapper').slideUp();
        jQuery('.resummarize-content-loading').html('<div class="ajax-loading"><img src="' + curationScript.pluginUrl + 'images/loading/loading29.gif" alt="loading" /></div>');
        jQuery.ajax({
            dataType: 'JSON',
            method: 'POST',
            url: ajax.ajax_url,
            data: {
                'action' : 'resummarize_content',
                'url' : url,
                'text' : text,
                'restrictionNumber' : restrictionNumber,
                'restrictionType' : restrictionType,
                'resummarizeFrom' : resummarizeFrom
            },
            success:function(data) {
                if(data.Error) {
                    jQuery('.resummarize-content-loading').html(data.Error);
                } else {
                    // REMOVE SPINNER
                    jQuery('.resummarize-content-loading').html('');

                    var headline = stripSlashes(data.headline);
                    var content = stripSlashes(data.content);
                    var title = jQuery(headline).text();

                    // ADD NEW SUMMARY FOR REVIEW
                    jQuery('#resummarized-content-headline').html(headline);
                    jQuery('#resummarized-content-content').html(content);

                    // SHOW ADD SUMMARY BUTTON & CONTENT WRAPPER
                    jQuery('.resummarize-add-submit').fadeIn();
                    jQuery('.resummarized-content-wrapper').slideDown();
                }
            },
            error: function(errorThrown){
                jQuery('.spun-content-loading').html(errorThrown);
                console.log(errorThrown);
            }
        });
    });

    /*** SELECT SUMMARY ***/
    jQuery(document).on('click', '.resummarize-add-submit', function() {
        var headlineEle = jQuery('.headline');
        var headline = jQuery('#resummarized-content-headline').html();
        var content = jQuery('#resummarized-content-content').html();

        // UPDATE TITLE
        var title = jQuery(headline).text();
        jQuery('#title').val(title);

        jQuery('html, body').animate({
            scrollTop: headlineEle.offset().top
        }, 1000);

        // GET WYSIWYG EDITOR ID
        var headlineEditorId = headlineEle.find('textarea').attr('id');
        var finalContentEditorId = jQuery('.final-content').find('textarea').attr('id');

        // ADD CONTENT TO WYSIWYG EDITORS
        tinyMCE.get(headlineEditorId).setContent(headline);
        tinyMCE.get(finalContentEditorId).setContent(content);
        tinyMCE.triggerSave();
    });

    // GET INITIAL WYSIWYG ID (ACF WILL CHANGE THIS ON US AFTER INIT)
    var originalContentEditor = jQuery('.original-content').find('textarea').attr('id');
    acf.add_filter('wysiwyg_tinymce_settings', function( mceInit, originalContentEditor ){
        // do something to mceInit
        mceInit.setup = function( ed ){
            // RESET THE WYSIWYG ID NOW THAT ACF HAS CHANGED IT
            originalContentEditor = jQuery('.original-content').find('textarea').attr('id');
            if(ed.id == originalContentEditor) {
                // SET ON CHANGE EVENT ON OUR EDITOR
                ed.on('change keyup', function () {
                    // GET CONTENT FROM EDITOR
                    var text = tinyMCE.get(originalContentEditor).getContent();

                    // DO AJAX CALL
                    jQuery.ajax({
                        method: 'POST',
                        url: ajax.ajax_url,
                        data: {
                            'action' : 'estimate_read_time',
                            'text' : text
                        },
                        success:function(data) {
                            // UPDATE ESTIMATED READ TIME VALUE
                            jQuery('.estimated-read-time input').val(data);
                        },
                        error: function(errorThrown){
                            console.log(errorThrown);
                        }
                    });
                });
            }
        };
        // return
        return mceInit;

    });

    /* NO LONGER USING WORDAI
    /*** SPIN CONTENT
    jQuery(document).on('click', '.spin-content-submit', function() {
        var text = jQuery('.original-content').find('textarea').val();

        jQuery('.spun-content-loading').html('<div class="ajax-loading"><img src="' + curationScript.pluginUrl + 'images/loading/loading29.gif" alt="loading" /></div>');
        jQuery.ajax({
            method: 'POST',
            url: ajax.ajax_url,
            data: {
                'action' : 'spin_content',
                'text' : text
            },
            success:function(data) {
                jQuery('.spun-content-loading').html('');
                var spunData = JSON.parse(data);

                // UPDATE VERSION 1
                jQuery('.spun-content-1-uniqueness').html('Uniqueness: ' + spunData.version1Uniqueness);
                jQuery('.spun-content-1-text').html(spunData.version1Text);

                // UPDATE VERSION 2
                jQuery('.spun-content-2-uniqueness').html('Uniqueness: ' + spunData.version2Uniqueness);
                jQuery('.spun-content-2-text').html(spunData.version2Text);

                // UPDATE VERSION 3
                jQuery('.spun-content-3-uniqueness').html('Uniqueness: ' + spunData.version3Uniqueness);
                jQuery('.spun-content-3-text').html(spunData.version3Text);
            },
            error: function(errorThrown){
                jQuery('.spun-content-loading').html(errorThrown);
                console.log(errorThrown);
            }
        });
    });

    /*** SELECT SPUN VERSION
    jQuery(document).on('click', '.spun-content-submit', function() {
        var text = jQuery('.spun-content input').val();

        jQuery.ajax({
            url: ajax.ajax_url,
            data: {
                'action' : 'select_content',
                'url' : text
            },
            success:function(data) {
                var contentData = JSON.parse(data);

                // GET WYSIWYG EDITOR ID
                var editorId = jQuery('.original-content').find('textarea').attr('id');

                // ADD CONTENT TO EDITOR
                tinyMCE.get(editorId).setContent(contentData.content);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
    */
});

function stripSlashes(str) {
    return str.replace(/\\/g, '');
}