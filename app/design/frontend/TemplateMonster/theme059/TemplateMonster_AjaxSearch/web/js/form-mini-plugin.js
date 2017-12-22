define([
    'jquery',
    'jquery/ui',
    'Magento_Search/form-mini',
    'TemplateMonster_AjaxSearch/js/tm-search-ajax'
], function($) {
    'use strict';

    $.widget('TemplateMonster.formMiniPlugin', $.tm.quickSearchAjax, {

        setActiveState: function (isActive) {
            
        },

        _create: function() {
            this._super();
        }

    });

    return $.TemplateMonster.formMiniPlugin;
});