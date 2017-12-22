/**
 * Copyright © 2015. All rights reserved.
 */

define([
    'jquery'
], function($){
    "use strict";

    $.widget('TemplateMonster.smartLabels', {

        options: {
            container: '.product-item',
            catalogParent: '.product-item',
            productContainer: '.product.media',
            productParent: '.product.media',
        },

        _create: function() {
            this._productLabel();
            this._categoryLabel();
        },

        _categoryLabel: function () {
            var container = this.options.container;
            var parent = this.options.catalogParent;

            var labelParent = $(container).parents(parent);
            var defineParent = Boolean(labelParent.length);
            var wrapper = this.element.parents(parent);
            if(!defineParent) container = wrapper;
            var anchor = (!container.length) ? wrapper : $(container, wrapper);
            this.element.prependTo(anchor).show();
        },

        _productLabel: function () {
            var productContainer = this.options.productContainer;
            var productParent = this.options.productParent;
            if(!productContainer.length) {
                productContainer = productParent;
            }
            this.element.prependTo($(productContainer, '.catalog-product-view')).show();
        }
    });

    return $.TemplateMonster.smartLabels;

});
