jQuery(document).ready(function($) {

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

    //Checkboxes
    unCheckAll(); //reset
    $(':checkbox#checkAll').prop('checked', false); //reset

    $(':checkbox#checkAll').click(function(event) {
        if (this.checked) { checkAll(); }
        else { unCheckAll(); }
    });

    $(':checkbox.acheckbox').click(function(event) {
        toggleOptions();
    });

    $('#bulk-options').change(function(event) {
        handleOption( $(this).val() );
    });

    function checkAll() {
        $(':checkbox.acheckbox').each(function(event) {
            this.checked = true;
        });

        toggleOptions();
    }

    function unCheckAll() {
        $(':checkbox.acheckbox').each(function(event) {
            this.checked = false;
        });

        toggleOptions();
    }

    function toggleOptions() {
        if ( $(':checkbox.acheckbox:checked').size() > 0 ) { unHideOptions(); }
        else { hideOptions(); }
    }

    function hideOptions() {
        $(':checkbox#checkAll').prop('checked', false); 
        $('.optionsDiv').addClass('opacity'); 
        $('#bulk-options-form .form-group .appendTarget').html('');
        $('#bulk-options').attr('disabled', 'disabled');
        $('#bulk-submit').attr('disabled', 'disabled').removeClass('btn-metis-1 btn-metis-2 btn-metis-5').addClass('btn-default').text('Submit'); 
        $('#bulk-options-form').attr('action', '#');               
    }

});