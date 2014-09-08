jQuery(document).ready(function($) 
{

	$('.showlogin').click(function(e)
	{
		e.preventDefault();
		$('#loginModal').modal({
			show: true
		});
	});


	$('#login-submit').click(function(e)
	{
		e.preventDefault();
		var action = $(this).attr('rel');
		var email = $.trim($('#email').val());
		var password = $.trim($('#password').val());
		var remember = 0;
		if ($('#remember').prop('checked')) remember = 1;

		processLoginForm(action, email, password, remember);		

	});

	function processLoginForm(action, email, password, remember)
	{
        $.post(action, {email: email, password:password, remember: remember}, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$('.login-alert').removeClass('hide').removeClass('alert-danger').addClass('alert-success');
            	$('.login-alert').text(data['success']);

            	// setTimeout(function()
            	// {
            	// 	window.location.replace(data['url']);
            	// }, 3000);
            }   

            if (data['message'] !=undefined)
            {
           		$('.login-alert').removeClass('hide').addClass('alert-danger').removeClass('alert-success');
            	$('.login-alert').text(data['message']);
            } 

        }, 'json'); 		
	}



});