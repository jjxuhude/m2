/**
 * Copyright © 2015. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    'owlcarousel'
], function($){
    "use strict";

    $.widget('TemplateMonster.carouselInit', {

        options: {
            nav:true,
            navText: false,
            dotsSpeed: false,
            items: 3,
            autoPlay: false,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items:3
                }
            }
        },

        _create: function() {
            this.element.owlCarousel(this.options);
        },

        _setOption: function( key, value ) {
          this._super( "_setOption", key, value );
        },
        
    });

    return $.TemplateMonster.carouselInit;

});
