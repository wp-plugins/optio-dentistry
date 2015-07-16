(function() {

    tinymce.create('tinymce.plugins.OptioDentistry', {

        init: function(ed, url) {
            ed.addButton('optio-library', {
                title: 'Insert Optio Dentistry library',
                cmd: 'optio-library',
                classes: 'widget btn optio-library'
            });

            ed.addButton('optio-video', {
                title: 'Insert Optio Dentistry video',
                cmd: 'optio-video',
                classes: 'widget btn optio-video'
            });

            ed.addButton('optio-lightbox', {
                title: 'Link to Optio Dentistry video',
                cmd: 'optio-lightbox',
                classes: 'widget btn optio-lightbox'
            });

            ed.addCommand('optio-library', function() {
                ed.execCommand('mceInsertContent', 0, '[optio-library]');
            });

            ed.addCommand('optio-video', function() {
                ed.windowManager.open({
                    title: 'Select a video',
                    file: ajaxurl + '?action=optio_insert_video_dialog',
                    width: 600,
                    height: 420,
                    classes: 'optio-dialog',
                    popup_css: url + '/../css/dialog.css'
                });
            });

            ed.addCommand('optio-lightbox', function() {
                ed.windowManager.open({
                    title: 'Select a video',
                    file: ajaxurl + '?action=optio_insert_link_dialog',
                    width: 600,
                    height: 420,
                    classes: 'optio-dialog',
                    popup_css: url + '/../css/dialog.css'
                });
            });
        },

        getInfo: function() {
            return {
                longname: 'Optio Dentistry WP Buttons',
                author: 'Optio Publishing Inc.',
                authorurl: 'http://www.optiopublishing.com',
                version: '1.2'
            };
        }
    });

    tinymce.PluginManager.add( 'optio_dentistry', tinymce.plugins.OptioDentistry );

})();
