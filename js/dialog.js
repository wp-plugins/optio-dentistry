(function($) {

    // Load products
    var products;
    $.getJSON('https://www.optiopublishing.com/api/products', function(result) {
        if (!result.error) {
            products = result;
            for (var i = 0; i < products.length; i++) {
                var productId = products[i].id.replace('/', '-');
                $('header').append('<a href="#" data-product-id="' + productId + '">' + products[i].title + '</a>');
                $('#videos').append('<ul id="' + productId + '"></div>');
                for (var j = 0; j < products[i].videos.length; j++) {
                    $('#' + productId).append(
                        '<li data-video-id="' + products[i].videos[j].id + '" data-video-title="' + products[i].videos[j].title + '">' +
                            '<img src="' + products[i].videos[j].thumbnail_url + '">' +
                            '<span>' + products[i].videos[j].title + '</span>' +
                        '</li>'
                    );
                }
            }
            $('header a:first-child, #videos ul:first-child').toggleClass('active', true);
            $('section').fadeIn();
        }
    });

    // Change selected product
    $('header').on('click', 'a', function() {
        var productId = $(this).attr('data-product-id');
        $('header a, #videos ul').toggleClass('active', false);
        $(this).toggleClass('active', true);
        $('#' + productId).toggleClass('active', true);
    });

    // Change selected video
    $('#videos').on('click', 'li', function() {
        $('#selected').val($(this).attr('data-video-id'));
        $('#selected-title').val($(this).attr('data-video-title'));
        $('li').toggleClass('selected', false);
        $(this).toggleClass('selected', true);
        $('#insert, #link').removeAttr('disabled');
    });

    // Insert video into editor
    $('#insert').click(function() {
        var return_text = '[optio-video id="' + $('#selected').val() + '"]';
        parent.tinymce.activeEditor.execCommand('mceInsertContent', 0, return_text);
        parent.tinymce.activeEditor.windowManager.close();
    });

    // Wrap selected text with video link
    $('#link').click(function() {
       var selected_text = parent.tinymce.activeEditor.selection.getContent();
       if (!selected_text) selected_text = 'Watch "' + $('#selected-title').val() + '" video';
       var return_text = '[optio-lightbox id="' + $('#selected').val() + '"]' + selected_text + '[/optio-lightbox]';
       parent.tinymce.activeEditor.execCommand('mceInsertContent', 0, return_text);
       parent.tinymce.activeEditor.windowManager.close();
    });

    // Cancel
    $('#close').click(function() {
        parent.tinymce.activeEditor.windowManager.close();
    });

})(jQuery);
