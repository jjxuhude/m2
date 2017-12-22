/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
define([
    'jquery',
    'showCompareProduct'
],function($,$t) {
    'use strict';

    $.widget('tm.comparePopup', $.tm.showCompareProduct, {
        _actionCompare : function(){
            var self = this;
            $('body').on('click','.action.compare',function(e){
                e.preventDefault();

                var href = $(e.currentTarget).attr('href');

                $.ajaxSetup({showLoader: true});
                $.ajax({
                    method: 'POST',
                    url: href,
                    beforeSend: self._changeLoaderTemplate,
                    complete:  self._recoverLoaderTemplate
                }).done(function(data){
                    self.compareProductBox = $('#productComparePopup').html(data.content).modal({
                        "dialogClass": "page-footer",
                        "responsive": true,
                        "responsiveClass": "compare-popup",
                        "innerScroll": true
                    });
                    self.compareProductBox.modal('openModal');
                    $('[data-role=tocart-form]').catalogAddToCart();
                }).fail(function(){
                    alert({
                        content: $t('Can not finish request.Try again.')
                    });
                });
                return false;
            });
        },    
    });

    return $.tm.comparePopup;

});