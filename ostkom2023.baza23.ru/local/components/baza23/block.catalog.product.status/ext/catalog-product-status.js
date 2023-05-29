'use strict';

let jso_block_catalogProductStatus = {
    i_productClassPrefix: 'product-in-basket',

    jsf_productAdded: function (p_productId, p_elem) {
        if (p_productId) {
            $(p_elem).addClass(
                    jso_block_catalogProductStatus.i_productClassPrefix + '-' + p_productId
                    + ' ' + jso_block_catalogProductStatus.i_productClassPrefix);
        }
    },
    jsf_productRemoved: function (p_productId) {
        if (p_productId) {
            let productClass = jso_block_catalogProductStatus.i_productClassPrefix
                    + '-' + p_productId;

            $('.' + productClass).removeClass(
                    productClass + ' '
                    + jso_block_catalogProductStatus.i_productClassPrefix);
        }
    }
};