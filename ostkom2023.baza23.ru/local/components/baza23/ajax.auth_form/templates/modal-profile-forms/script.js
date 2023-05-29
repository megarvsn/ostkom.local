(function (window) {
    'use strict';

    if (window.jsc_AJAX_ModalProfileForm) return;

    window.jsc_AJAX_ModalProfileForm = function (arParams) {
        this.scriptUrl = '';
        this.ajaxUrl = '';
        this.modalId = '';

        this.locked = false;
        this.errorCode = 0;

        if (typeof arParams === 'object') {
            if (arParams.SCRIPT_URL) this.scriptUrl = arParams.SCRIPT_URL;
            else this.errorCode += -1;

            if (arParams.AJAX_URL) this.ajaxUrl = arParams.AJAX_URL;
            else this.errorCode += -2;

            if (arParams.MODAL_ID) this.modalId = arParams.MODAL_ID;
            else this.errorCode += -4;
        } else {
            this.errorCode += -16;
        }

        if (this.errorCode === 0) {
            BX.ready(BX.delegate(this.init, this));
        } else {
            console.log('jsc_AJAX_ModalProfileForm errorCode =', this.errorCode);
        }
    };

    window.jsc_AJAX_ModalProfileForm.prototype = {
        init: function () {
            let this_ = this;

            $('body').on('click', '.js-profile-modal', function (event) {
                event.stopPropagation();
                event.preventDefault();

                if (this_.locked) return false;
                this_.locked = true;

                if (!this_.jsf_isScriptLoaded()) {
                    this_.jsf_loadScript(event.currentTarget);
                } else {
                    this_.jsf_afterLoaded(event.currentTarget);
                }

                this_.locked = false;
                return false;
            });
        },
        jsf_afterLoaded: function (p_eventElem) {
            if (!this.jsf_isScriptLoaded()) return false;

            let data = $(p_eventElem).data(),
                href = data.href;

            return jso_web_form.modal.jsf_activate({
                modal: href,
                modalType: this.modalId,
                ajaxUrl: this.ajaxUrl,
                data: data
            });
        },
        jsf_loadScript: function (p_eventElem) {
            let this_ = this;

            jso_utilities.jsf_loadScript(this.scriptUrl).done(function(p_script, p_textStatus) {
                this_.jsf_afterLoaded(p_eventElem);
            });
        },
        jsf_isScriptLoaded: function () {
            return (typeof (jso_web_form) !== 'undefined');
        }
    };
})(window);