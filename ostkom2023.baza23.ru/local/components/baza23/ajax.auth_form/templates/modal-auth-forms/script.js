(function (window) {
    'use strict';

    if (window.jsc_AJAX_ModalAuthForm) return;

    window.jsc_AJAX_ModalAuthForm = function (arParams) {
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
            console.log('jsc_AJAX_ModalAuthForm errorCode =', this.errorCode);
        }
    };

    window.jsc_AJAX_ModalAuthForm.prototype = {
        init: function () {
            let this_ = this;

            $('.js-auth-modal').on('click', function (event) {
                event.stopPropagation();
                event.preventDefault();

                this_.jsf_run($(event.currentTarget).data());
                return false;
            });
        },
        jsf_afterLoaded: function (p_data) {
            if (!this.jsf_isScriptLoaded()) return false;

            let this_ = this;

            if (jso_web_form.modal.jsf_activate({
                    modal: p_data.href,
                    modalType: this.modalId,
                    ajaxUrl: this.ajaxUrl,
                    data: p_data
            })) {
                $('div.popup-window').on('click', '.js-auth-modal', function (event) {
                    event.stopPropagation();
                    event.preventDefault();

                    this_.jsf_run($(event.currentTarget).data());
                    return false;
                });
            }
        },
        jsf_loadScript: function (p_data) {
            let this_ = this;

            jso_utilities.jsf_loadScript(this.scriptUrl).done(function(p_script, p_textStatus) {
                this_.jsf_afterLoaded(p_data);
            });
        },
        jsf_isScriptLoaded: function () {
            return (typeof (jso_web_form) !== 'undefined');
        },
        jsf_run: function (p_data) {
            if (typeof p_data !== 'object' || !("href" in p_data)) {
                console.log('jsc_AJAX_ModalAuthForm.jsf_run: href', p_data);
                return false;
            }

            if (this.locked) return false;
            this.locked = true;

            if (!this.jsf_isScriptLoaded()) {
                this.jsf_loadScript(p_data);
            } else {
                this.jsf_afterLoaded(p_data);
            }

            this.locked = false;
        }
    };
})(window);