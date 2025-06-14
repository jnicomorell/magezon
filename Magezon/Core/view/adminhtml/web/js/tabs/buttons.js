/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/form/form',
    'Magento_Ui/js/form/adapter',
    'mage/translate'
], function ($, confirm, form, adapter) {
    'use strict';

    /**
     * @param {String} url
     * @returns {Object}
     */
    function getForm(url) {
        return $('<form>', {
            'action': url,
            'method': 'POST'
        }).append($('<input>', {
            'name': 'form_key',
            'value': window.FORM_KEY,
            'type': 'hidden'
        }));
    }

    $('#save-button').on('click', function () {
        // $('.page-content').trigger('save');
        // console.log(adapter);

        //form.save('http://google.com', {'demo': 1});
    });    

    $('#customer-edit-delete-button').on('click', function () {
        var msg = $.mage.__('Are you sure you want to do this?'),
            url = $('#customer-edit-delete-button').data('url');

        confirm({
            'content': msg,
            'actions': {

                /**
                 * 'Confirm' action handler.
                 */
                confirm: function () {
                    getForm(url).appendTo('body').submit();
                }
            }
        });

        return false;
    });
});
