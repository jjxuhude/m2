define([
    'jquery',
    'jquery/ui',
    'googleMapPagePlugin'
], function($, mageTemplate ,pluginTemplate) {
    'use strict';

    $.widget('TemplateMonster.googleMapOptions', $.TemplateMonster.googleMapPagePlugin, {

        options: {
            contactSelector: '.google-map-wrapper'
        },

        _create: function() {
            this._super();
        }

    });

    return $.TemplateMonster.googleMapOptions;
});