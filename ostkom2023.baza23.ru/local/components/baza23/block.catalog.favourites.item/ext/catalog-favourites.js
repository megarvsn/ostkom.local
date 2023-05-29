(function (window) {
    'use strict';

    if (window.jsc_CatalogFavouritesItem) return;

    window.jsc_CatalogFavouritesItem = function (arParams) {
        this.ajax = {
            URL: '/local/components/baza23/block.catalog.favourites.item/ext/ajax.php',
            MODULE: 'module=favourites',
            ACTION_VARIABLE: 'action',
            ID_VARIABLE: 'id',
            OUTPUT: 'console'
        };

        this.cssClass = 'product-item-favourites';

        this.visual = {
            ID: ''
        };

        this.obProduct = null;

        this.errorCode = 0;

        if (typeof arParams === 'object') {
            if (arParams.URL) this.ajax.URL = arParams.URL;

            this.visual.ID = arParams.CSS_ID;
            if (this.visual.ID === '') this.errorCode = -2;
        }

        if (this.errorCode === 0) {
            BX.ready(BX.delegate(this.init, this));
        } else {
            console.log('jsc_CatalogFavouritesItem errorCode =', this.errorCode);
        }
    };

    window.jsc_CatalogFavouritesItem.prototype = {
        init: function () {
            this.obProduct = $('#' + this.visual.ID);
            if (!this.obProduct.length) {
                this.errorCode = -4;
                console.log('jsc_CatalogFavouritesItem errorCode =', this.errorCode,
                        '(#' + this.visual.ID + ')');
                return this.errorCode;
            }

            let this_ = this,
                selector = '.' + this.cssClass + ' input[type="checkbox"]';

            this.obProduct.on("change", selector, function() {
                let l_productId = $(this).data('productId');
                if (l_productId) {
                    if ($(this).is(':checked')) {
                        if (! this_.jsf_addProductId(l_productId)) {
                            $(this).removeAttr("checked");
                        }
                    } else {
                        this_.jsf_removeProductId(l_productId);
                    }
                }
                return false;
            });
        },
        jsf_getProductIds: function() {
            let this_ = this,
                l_param = this.ajax.MODULE
                    + '&SITE_ID=' + jso_local.SITE_ID
                    + '&' + this.ajax.ACTION_VARIABLE + "=GET_FAVOURITE_LIST",
                l_arProductIds = [];

            $.ajax({
                url: this.ajax.URL,
                type: "GET",
                data: l_param,
                dataType: 'json',
                async: false,

                error: function (jqXHR, textStatus, errorThrown) {
                    if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_getProductIds', l_param, textStatus, errorThrown);
                    else jso_utilities.jsf_ajaxError(jqXHR, textStatus, errorThrown);
                },
                success: function (p_data) {
                    if (p_data.success) {
                        if (p_data.productIds != null && p_data.productIds != undefined) {
                            l_arProductIds = p_data.productIds;
                        }
                    } else {
                        if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_getProductIds', p_data);
                        else alert(p_data.text);
                    }
                }
            });
            return l_arProductIds;
        },
        jsf_addProductId: function(p_productId) {
            let this_ = this,
                l_param = this.ajax.MODULE
                    + '&SITE_ID=' + jso_local.SITE_ID
                    + '&' + this.ajax.ID_VARIABLE + '=' + p_productId
                    + '&' + this.ajax.ACTION_VARIABLE + "=ADD_TO_FAVOURITE_LIST",
                l_ret = false;

            $.ajax({
                url: this.ajax.URL,
                type: "GET",
                data: l_param,
                dataType: 'json',
                async: false,
                error: function (jqXHR, textStatus, errorThrown) {
                    if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_addProductId', l_param, textStatus, errorThrown);
                    else jso_utilities.jsf_ajaxError(jqXHR, textStatus, errorThrown);
                },
                success: function (p_data) {
                    if (p_data.success) {
                        l_ret = true;
                        $('.' + this_.cssClass + ' input[data-product-id="' + p_productId + '"]').attr("checked", "checked");
                        this_.jsf_changeFavouriteCount();
                    } else {
                        if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_addProductId', l_param, p_data);
                        else alert(p_data.text);
                    }
                }
            });
            return l_ret;
        },
        jsf_removeProductId: function(p_productId) {
            let this_ = this,
                l_param = this.ajax.MODULE
                    + '&SITE_ID=' + jso_local.SITE_ID
                    + '&' + this.ajax.ID_VARIABLE + '=' + p_productId
                    + '&' + this.ajax.ACTION_VARIABLE + "=DELETE_FROM_FAVOURITE_LIST",
                l_ret = false;

            $.ajax({
                url: this.ajax.URL,
                type: "GET",
                data: l_param,
                dataType: 'json',
                async: false,
                error: function (jqXHR, textStatus, errorThrown) {
                    if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_removeProductId', l_param, textStatus, errorThrown);
                    else jso_utilities.jsf_ajaxError(jqXHR, textStatus, errorThrown);
                },
                success: function (p_data) {
                    if (p_data.success) {
                        l_ret = true;
                        $('.' + this_.cssClass + ' input[data-product-id="' + p_productId + '"]').removeAttr("checked");
                        this_.jsf_changeFavouriteCount();
                    } else {
                        if (this_.ajax.OUTPUT == 'console') console.log('jsc_CatalogFavouritesItem.jsf_removeProductId', l_param, p_data);
                        else alert(p_data.text);
                    }
                }
            });
            return l_ret;
        },
        jsf_changeFavouriteCount: function() {
            let l_arProductIds = this.jsf_getProductIds(),
                count = 0,
                parent = $('.catalog-favourites-link');

            if (typeof l_arProductIds == 'object') count = Object.keys(l_arProductIds).length;
            else if (typeof l_arProductIds == 'array') count = l_arProductIds.length;

            parent.find('.item-count').html(count);

            if (l_arProductIds.length <= 0) parent.addClass("empty-list");
            else parent.removeClass("empty-list");
        },
        jsf_checkFavourites: function(p_elem) {
            let l_arProductIds = this.jsf_getProductIds(),
                selector = '.' + this.cssClass + ' input[type="checkbox"]';
            if ($(p_elem).hasClass(this.cssClass)) selector = 'input[type="checkbox"]';

            if (l_arProductIds) {
                $(p_elem).find(selector).each(function(i,elem) {
                    let l_productId = $(this).data('productId');
                    if ($.inArray(l_productId, l_arProductIds) >= 0) {
                        $(this).attr("checked", "checked");
                    } else {
                        $(this).removeAttr("checked");
                    }
                });
            }
        }
    };
})(window);