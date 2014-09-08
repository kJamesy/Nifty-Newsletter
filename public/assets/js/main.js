jQuery(document).ready(function($) {
	$('#menu li.active a:first-child').click(function(event) {
		// event.preventDefault();
	});

    $('.hover-row').hover(function() {
        $this = $(this);
        $this.find('.more-options').removeClass('visibility');
    }, function() {
        $this = $(this);
        $this.find('.more-options').addClass('visibility');
    });

    if ( !$.trim( $('tbody').html() ) ) {
        $('.checkEmpty').html('No items');
    }

    var state;
    
    $('#checkAll').click(function(event) {
        $this = $(this);

        state = !state;
        if (state) 
        {
           $(':checkbox.acheckbox').each(function()
            {
                $(this).prop('checked', true);
            });
        }

        else
        {
           $(':checkbox.acheckbox').each(function()
            {
                $(this).prop('checked', false);
            }); 
        }
    });

    //Settings Modal
    $('#sModalSubmit').click(function(event) {
        event.preventDefault();

        processModalForm();
    });

    $('#modalSettingsForm').submit(function(event) {
        event.preventDefault();

        processModalForm();
    });


    function processModalForm() {
        $this = $('#modalSettingsForm');
        var action = $this.attr('action');

        var _token = $('input[name="_token"]').val();
        var sitename = $.trim( $('#modalSitename').val() );
        var sender_name = $.trim( $('#modalFromName').val() );
        var sender_email = $.trim( $('#modalFromEmail').val() );
        var reply_to_email = $.trim( $('#modalReplyEmail').val() );


        $.post(action, {_token:_token, sitename:sitename, sender_name:sender_name, sender_email: sender_email, reply_to_email:reply_to_email}, function(response) {
            if ( response.validation ) {
                var html = '';

                $.each(response.validation, function(index, val) {
                    html += '<i class="fa fa-info"></i> ' + val + "<br />";
                });

                $('#modalFeedback').removeClass().addClass('text-danger').html(html);
            }

            if ( response.success ) {
                $('#modalFeedback').removeClass().addClass('text-success').html('<i class="fa fa-info"></i> ' + response.success);
            }

        }, 'json');

    }

});