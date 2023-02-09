'use strict';

let jso_screen = {
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200
};

$(function () {
    jso_local.suppressionClick.jsf_init();
});

let jso_local = {

    suppressionClick: {
        jsf_init: function () {
            $('[href="#"]').on('click', function (e) {
                e.preventDefault();
            });
        }
    },
}