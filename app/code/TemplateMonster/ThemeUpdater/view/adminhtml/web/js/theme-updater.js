define([
    "jquery"
], function($){
    "use strict";

    $.widget('TemplateMonster.themeUpdater', {

        options: {
            updateButton: '#check_updates',
            downloadButton: '#download',
            loader : '#row_themeupdater_general_check_updates .loader',
            updateCheckUrl: '',
            messageSelector: '#row_themeupdater_general_check_updates .messages',
            config_form: '#config-edit-form'
        },

        _create: function() {
            var obj = this;

            $(this.options.updateButton).on('click', function(){
                obj._getUpdates();
            });

            $(this.options.downloadButton).on('click', function(e){
                obj._downloadUpdate(e);
            });
        },

        _getUpdates: function(){
            var obj = this,
                updateData = '',
                options = this.options,
                loader = $(options.loader),
                updateButton = $(options.updateButton),
                downloadButton = $(options.downloadButton),
                formKey = $(options.config_form).find("input[name='form_key']").val();;

            updateButton.attr('disabled', 'disabled').addClass('disabled');
            loader.css('opacity', '.5');

            $.ajax(options.updateCheckUrl, {
                method: 'POST',
                data: formKey
            }).done(function(updateData){
                //console.log(updateData);

                var updateUrl = updateData.download_url;

                if(updateData.update_required == true){

                    if(updateUrl.indexOf('http://') == -1){
                        obj._showMessage(updateUrl);
                        updateButton.removeAttr('disabled').removeClass('disabled');
                    } else {
                        obj._showMessage('Update available!');
                        downloadButton.attr('data-href', updateUrl).removeAttr('disabled').removeClass('disabled');
                    }

                } else {
                    obj._showMessage('You have the most recent theme version.');
                    updateButton.removeAttr('disabled').removeClass('disabled');
                }
            }).always(function(){
                loader.css('opacity', '0');
            });
        },

        _downloadUpdate: function(e){
            var options = this.options,
                downloadButton = $(options.downloadButton),
                url = downloadButton.attr('data-href');

            //console.log('Download clicked');

            if(url.length <= 0){
                return false;
            }

            e.preventDefault();
            window.location.href = url;
            downloadButton.attr('disabled', 'disabled');
        },

        _showMessage: function(message){
            var messageContainer = $(this.options.messageSelector);

            messageContainer.html(message).fadeIn();
            messageContainer.delay(4000).fadeOut();
        }

    });

    return $.TemplateMonster.themeUpdater;
});

