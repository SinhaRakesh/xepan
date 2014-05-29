$.each({
    developer_setup: function() {
        $('#side_menu').toggle();
        if ($('#side_menu').css('display') == 'none')
            $('#wrapper').css('padding-left', 0);
        else
            $('#wrapper').css('padding-left', 225);
    }

}, $.univ._import);