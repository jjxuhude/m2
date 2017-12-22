/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'matchMedia',
    'mage/tabs',
    'domReady!',
    'dropdownDialog',
    'Magento_Theme/js/device.min',
    'stickUpNav',
    'wowJs'
], function ($, mediaCheck) {
    'use strict';

    mediaCheck({
        media: '(min-width: 992px)',
// Switch to Desktop Version
        entry: function () {
            
            // Smart header
            
            var _body = $('body');
            if($(_body).hasClass('sm-mobile')){
                _body.removeClass('sm-mobile')
            }
            _body.addClass('sm-desktop');

            if($( ".navigation" ).hasClass('ui-dialog-content')){
                $( ".navigation" ).dropdownDialog( "destroy" );
                $('.sm-header-nav-wrap').removeClass('active');
                $('.sm-header-nav-toggle').removeClass('active');
            }
            if($( "#search_mini_form .search-control_wrapper" ).hasClass('ui-dialog-content')){
                $( "#search_mini_form .search .control" ).dropdownDialog( "destroy" );
                $('#search_mini_form > .field.search').removeClass('active');
                $('#search_mini_form > .field.search > .label').removeClass('active');
            }

            if($('.sm-header-nav-wrap').length){
                $('.sm-header-nav-wrap').stickUp({
                    scrollHide:true,
                    zIndex: 8888
                });
            }

            // Smart header end


            (function () {

                var productInfoMain = $('.product-info-main'),
                    productInfoAdditional = $('#product-info-additional');

                if (productInfoAdditional.length) {
                    productInfoAdditional.addClass('hidden');
                    productInfoMain.removeClass('responsive');
                }

            })();

            var galleryElement = $('[data-role=media-gallery]');

            if (galleryElement.length && galleryElement.data('mageZoom')) {
                galleryElement.zoom('enable');
            }

            if (galleryElement.length && galleryElement.data('mageGallery')) {
                galleryElement.gallery('option', 'disableLinks', true);
                galleryElement.gallery('option', 'showNav', false);
                galleryElement.gallery('option', 'showThumbs', true);
            }

            if ($(".wow").length) {
                var wow = new WOW(
                    {
                        offset:       0,
                        mobile:       false
                    }
                )
                wow.init();
            }
            
            $(window).load(function () {
                setTimeout(function () {
                    $('.product.data.items').tabs('option', 'openOnFocus', true);
                }, 500);
            });

            var _footerBlock = $('.footer');
            var _footerColTitles = $('.footer-col h4');
            var _footerCols = $('.footer-col');
            var _footerConts = $('.footer-col-content');

            _footerBlock.find('.footer-col h4').unbind('click');
            if(_footerColTitles.hasClass('active')){
                $(this).removeClass('active');
            }
            if(_footerCols.hasClass('active')){
                $(this).removeClass('active');
            }
            if(_footerConts.hasClass('active')){
                $(this).removeClass('active');
            }
            _footerConts.each(function() {
                $(this).css("display", "block");
            });
            if(_footerBlock.hasClass('has-active')){
                _footerBlock.removeClass('has-active');
            }
        },

// Switch to Mobile Version
        exit: function () {
            // Smart header
            var _body = $('body');
            if($(_body).hasClass('sm-desktop')){
                _body.removeClass('sm-desktop')
            }
            _body.addClass('sm-mobile');

            $( ".navigation" ).dropdownDialog({
                appendTo: ".sm-header-nav-wrap",
                triggerTarget: ".sm-header-nav-toggle",
                defaultDialogClass: "sm-header-menu-wrap",
                closeOnMouseLeave: false,
                closeOnClickOutside: true,
                parentClass: "active",
                triggerClass: "active",
                position: { my: "left top", at: "left bottom", of: "sm-header-nav-toggle" }
            });

            $( "#search_mini_form .search .control" ).dropdownDialog({
                appendTo: "#search_mini_form .search",
                triggerTarget: "#search_mini_form .search .label",
                defaultDialogClass: "search-control_wrapper",
                closeOnMouseLeave: false,
                closeOnClickOutside: true,
                parentClass: "active",
                triggerClass: "active"
            });

            $(".sm-header-nav-toggle").click(function () {
                var _smHeaderNavToggle = $(this);
                if((_smHeaderNavToggle).hasClass('active')){
                    if($(".sm-header_customer-menu-container").hasClass('active')){
                        $(".sm-header_customer-menu").dropdownDialog("close");
                    }
                    if($(".page-header__content .minicart-wrapper").hasClass('active')){
                        $('[data-role="dropdownDialog"]').dropdownDialog("close");
                    }
                }
            });
            $(".sm-header_customer-menu-toggle").click(function () {
                var _smHeaderCustomerMenuToggle = $(this);
                if((_smHeaderCustomerMenuToggle).hasClass('active')){
                    if($(".sm-header-nav-wrap").hasClass('active')){
                        $(".navigation").dropdownDialog("close");
                    }
                    if($(".page-header__content .minicart-wrapper").hasClass('active')){
                        $('[data-role="dropdownDialog"]').dropdownDialog("close");
                    }
                }
            });
            $(".page-header__content .action.showcart").click(function () {
                var _pageHeaderContentActionShowcart = $(this);
                if(!(_pageHeaderContentActionShowcart).hasClass('active')){
                    if($(".sm-header-nav-wrap").hasClass('active')){
                        $(".navigation").dropdownDialog("close");
                    }
                    if($(".sm-header_customer-menu-container").hasClass('active')){
                        $(".sm-header_customer-menu").dropdownDialog("close");
                    }
                }
            });
            $("#search_mini_form .search .label").click(function () {
                var _searchMiniFormSearchLabel = $(this);
                if((_searchMiniFormSearchLabel).hasClass('active')){
                    if($(".sm-header-nav-wrap").hasClass('active')){
                        $(".navigation").dropdownDialog("close");
                    }
                    if($(".sm-header_customer-menu-container").hasClass('active')){
                        $(".sm-header_customer-menu").dropdownDialog("close");
                    }
                    if($(".page-header__content .minicart-wrapper").hasClass('active')){
                        $('[data-role="dropdownDialog"]').dropdownDialog("close");
                    }
                }
            });

            // Smart header end

            $('.action.toggle.checkout.progress').on('click.gotoCheckoutProgress', function () {
                var myWrapper = '#checkout-progress-wrapper';
                scrollTo(myWrapper + ' .title');
                $(myWrapper + ' .title').addClass('active');
                $(myWrapper + ' .content').show();
            });

            $('body').on('click.checkoutProgress', '#checkout-progress-wrapper .title', function () {
                $(this).toggleClass('active');
                $('#checkout-progress-wrapper .content').toggle();
            });

            $('.footer-col-content').each(function() {
                $(this).css("display", "none");
            });
            $('.footer-col h4').click(function () {
                var _footerColTitle = $(this);
                var _footerCol = _footerColTitle.parent('.footer-col');
                var _footerCont = _footerColTitle.parent('.footer-col').find('.footer-col-content');
                var _footerBlock = _footerColTitle.parents('.footer');

                if(_footerColTitle.hasClass('active')){
                    _footerColTitle.removeClass('active');
                    _footerCol.removeClass('active');
                    _footerCont.slideUp();
                    _footerBlock.removeClass('has-active');
                }else{
                    if(_footerBlock.hasClass('has-active')){
                        _footerBlock.find('.footer-col.active').each(function () {
                            $(this).removeClass('active');
                            $(this).find('h4').removeClass('active');
                            $(this).find('.footer-col-content').stop().slideUp();
                        });
                        _footerBlock.removeClass('has-active');
                    }
                    _footerBlock.addClass('has-active');
                    _footerColTitle.addClass('active');
                    _footerCol.addClass('active');
                    _footerCont.stop().slideDown();
                }
            });

            var galleryElement = $('[data-role=media-gallery]');
            setTimeout(function () {
                if (galleryElement.length && galleryElement.data('mageZoom')) {
                    galleryElement.zoom('disable');
                }

                if (galleryElement.length && galleryElement.data('mageGallery')) {
                    galleryElement.gallery('option', 'disableLinks', false);
                    galleryElement.gallery('option', 'showNav', true);
                    galleryElement.gallery('option', 'showThumbs', false);
                }
            }, 2000);

            setTimeout(function () {
                $('.product.data.items').tabs('option', 'openOnFocus', false);
            }, 500);
        }
    });
});
