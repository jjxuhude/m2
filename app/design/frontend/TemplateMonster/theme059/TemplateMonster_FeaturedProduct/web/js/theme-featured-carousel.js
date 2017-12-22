/**
 * Copyright © 2015. All rights reserved.
 */

define([
    'jquery',
    'jquery/ui',
    'owlcarousel'
], function($){
    "use strict";

    $.widget('TemplateMonster.themeFeaturedCarousel', {

        options: {
            nav: true,
            navText: false,
            loop: true,
            responsive: {
                0: {
                    items: 1
                }
            }
        },

        _create: function() {
            this.element.owlCarousel(this.options);
        }
        
    });

    return $.TemplateMonster.themeFeaturedCarousel;

});
