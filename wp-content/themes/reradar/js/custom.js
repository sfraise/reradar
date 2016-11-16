/**
 * Created by Spencer on 6/28/2016.
 */

jQuery(document).ready(function() {
    /*** MAIN MENU HOVER ***/
    jQuery('.menu-item').each(function() {
        var target = jQuery(this).find('.sub-menu');
        jQuery(this).hover(function() {
            target.stop(true, false, true).slideDown(100);
        },function () {
            target.slideUp(100);
        });
    });

    /*** SUB MENU FORMATTING ***/
    var subMenuEle = jQuery('.sub-menu');
    subMenuEle.prepend('<div class="submenu-inner"><div class="submenu-header-wrapper"><div class="submenu-header-text"></div><div class="submenu-header-buttons"></div></div><div class="menu-column-1-wrapper"></div><div class="menu-column-2-wrapper"></div><div class="menu-column-3-wrapper"></div></div><div class="submenu-footer"></div>');

    subMenuEle.each(function() {
        var submenuHeaderText = jQuery(this).find('.submenu-header-text');
        var submenuHeaderItem = jQuery(this).find('.submenu-header');
        var columnOneWrapper = jQuery(this).find('.menu-column-1-wrapper');
        var columnOneItems = jQuery(this).find('.menu-column-1');
        var columnTwoWrapper = jQuery(this).find('.menu-column-2-wrapper');
        var columnTwoItems = jQuery(this).find('.menu-column-2');
        var columnThreeWrapper = jQuery(this).find('.menu-column-3-wrapper');
        var columnThreeItems = jQuery(this).find('.menu-column-3');
        var submenuFooter = jQuery(this).find('.submenu-footer');
        var submenuFooterItems = jQuery(this).find('.submenu-footer-item');

        submenuHeaderItem.each(function() {
            jQuery(this).appendTo(submenuHeaderText);
        });
        columnOneItems.each(function() {
            jQuery(this).appendTo(columnOneWrapper);
        });
        columnTwoItems.each(function() {
            jQuery(this).appendTo(columnTwoWrapper);
        });
        columnThreeItems.each(function() {
            jQuery(this).appendTo(columnThreeWrapper);
        });
        submenuFooterItems.each(function() {
            jQuery(this).appendTo(submenuFooter);
        });
    });
});

jQuery(window).ready(function() {
    var windowWidth = jQuery(window).width();
    /*** DISABLE PARALLAX ON MOBILE ON LOAD ***/
    if(windowWidth <= 800) {
        jQuery('.vc_parallax-content-moving').each(function () {
            jQuery(this).removeClass('vc_parallax');
        });
    }

    /*** DISABLE PARALLAX ON MOBILE ON RESIZE ***/
    jQuery(window).on('resize', function () {
        var windowWidth = jQuery(window).width();
        /*** DISABLE PARALLAX ON MOBILE ON LOAD ***/
        if(windowWidth <= 800) {
            jQuery('.vc_parallax-content-moving').each(function () {
                jQuery(this).removeClass('vc_parallax');
            });
        } else {
            jQuery('.vc_parallax-content-moving').each(function () {
                jQuery(this).addClass('vc_parallax');
            });
        }
    });
});