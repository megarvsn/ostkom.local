'use strict';

let jso_html_selectCss = {
    jsf_init: function(p_elem) {
        let select = $(p_elem);

        select.find('.select-title').on('click', function() {
            jso_html_selectCss.jsf_toggleContent(p_elem);
            return false;
        });

        select.find('.select-content label').on('click', function() {
            select.find('.select-title span').text($(this).text());
            jso_html_selectCss.jsf_hideContent(p_elem);
        });

        $('body').on('click', function() {
            jso_html_selectCss.jsf_hideAll();
        });
    },
    jsf_toggleContent: function(p_elem) {
        if ($(p_elem).hasClass('select-active')) {
            jso_html_selectCss.jsf_hideContent(p_elem);
        } else {
            jso_html_selectCss.jsf_hideAll();
            jso_html_selectCss.jsf_showContent(p_elem);
        }
    },
    jsf_hideContent: function(p_elem) {
        $(p_elem).removeClass('select-active');
    },
    jsf_showContent: function(p_elem) {
        $(p_elem).addClass('select-active');
    },
    jsf_hideAll: function() {
        jso_html_selectCss.jsf_hideContent('.form--select');
    },
    jsf_reset: function(p_elem) {
        let parent;
        if ($(p_elem).hasClass('form--select')) parent = $(p_elem);
        else parent = $(p_elem).find('.form--select');

        parent.each(function(index) {
            let defaultOpt = $(this).find('.select-content .option-default');
            if (defaultOpt.length) {
                defaultOpt.trigger("click");
                return true;
            }

            let elem = $(this).find('.select-title'),
                attr = elem.data('default');
            if (typeof attr !== 'undefined' && attr !== false) {
                elem.find('span').html(attr);
            }
        });
    }
};