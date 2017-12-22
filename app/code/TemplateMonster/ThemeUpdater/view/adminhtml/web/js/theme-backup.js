define([
    "jquery"
], function($){
    "use strict";

    $.widget('TemplateMonster.themeBackup', {

        options: {
            backupButton: '#backup',
            loader : '#row_themeupdater_general_backup .loader',
            backupControllerUrl: '', //Defined in TemplateMonster/ThemeUpdater/Block/Adminhtml/System/Config/Field/CheckUpdates->getBackupControllerUrl
            messageSelector: '#row_themeupdater_general_backup .messages',
            config_form: '#config-edit-form'
        },

        _create: function() {
            var obj = this;

            $(this.options.backupButton).on('click', function(e){
                obj._backupTheme(e);
            });
        },

        _backupTheme: function(e){
            var obj = this,
                options = this.options,
                backupButton = $(options.backupButton),
                loader = $(options.loader),
                formKey = $(options.config_form).find("input[name='form_key']").val();

            //console.log(formKey);

            backupButton.attr('disabled', 'disabled').addClass('disabled');
            loader.css('opacity', '.5');

            $.ajax(options.backupControllerUrl, {
                method: 'POST',
                data: formKey
            }).done(function(backupData){
                //console.log(backupData);

                if(backupData.status == true){
                    obj._showMessage(backupData.message, 'positive');
                    backupButton.removeAttr('disabled').removeClass('disabled');
                } else {
                    obj._showMessage(backupData.message, 'negative');
                }
            }).always(function(){
                loader.css('opacity', '0');
            });
        },

        _showMessage: function(message, status){
            var messageContainer = $(this.options.messageSelector);

            messageContainer.addClass(status);
            messageContainer.html(message).fadeIn();
            messageContainer.delay(4000).fadeOut();
        }

    });

    return $.TemplateMonster.themeBackup;
});

