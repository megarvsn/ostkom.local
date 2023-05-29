(function (window) {
    'use strict';

    if (window.jsc_AJAX_AuthForm) return;

    window.jsc_AJAX_AuthForm = function (arParams) {
        this.scriptUrl = '';
        this.ajaxUrl = '';
        this.formId = '';

        this.errorCode = 0;

        if (typeof arParams === 'object') {
            if (arParams.SCRIPT_URL) this.scriptUrl = arParams.SCRIPT_URL;
            else this.errorCode += -1;

            if (arParams.AJAX_URL) this.ajaxUrl = arParams.AJAX_URL;
            else this.errorCode += -2;

            if (arParams.FORM_ID) this.formId = arParams.FORM_ID;
            else this.errorCode += -4;
        } else {
            this.errorCode += -16;
        }

        if (this.errorCode === 0) {
            BX.ready(BX.delegate(this.init, this));
        } else {
            console.log('jsc_AJAX_AuthForm errorCode =', this.errorCode);
        }
    };

    window.jsc_AJAX_AuthForm.prototype = {
        init: function () {
            if (!this.jsf_isScriptLoaded()) {
                if (!this.jsf_isScriptLoaded()) {
                    this.jsf_loadScript();
                } else {
                    this.jsf_afterLoaded();
                }
            }
        },
        jsf_afterLoaded: function () {
            if (!this.jsf_isScriptLoaded()) return false;

            jso_web_form.validator.jsf_initSelector('#' + this.formId);
        },
        jsf_loadScript: function () {
            let this_ = this;

            jso_utilities.jsf_loadScript(this.scriptUrl).done(function(p_script, p_textStatus) {
                this_.jsf_afterLoaded();
            });
        },
        jsf_isScriptLoaded: function () {
            return (typeof (jso_web_form) !== 'undefined');
        }
    };
})(window);