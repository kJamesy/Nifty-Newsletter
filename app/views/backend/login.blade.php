<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Configure new site" name="description">
        <meta content="James Ilaki" name="author">
        <title>{{ $setting->sitename }}::Login </title>
        <link href="{{asset('favicon.png')}}" rel="shortcut icon">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
        {{ HTML::style('assets/template/css/main.css') }}
        {{ HTML::style('assets/template/lib/magic/magic.css') }}
        <!--[if lt IE 9]>
            {{ HTML::script('assets/bootstrap/js/html5shiv.js') }}
            {{ HTML::script('assets/bootstrap/js/respond.min.js') }}
        <![endif]-->
        <style>
            .login .form-signin #email {
                border-radius: 4px 4px 0 0;
                margin-bottom: -1px;
            }
        </style>
    </head>
    <body class="login">
        <div class="container">
            <div class="text-center">
                <img src="{{asset('assets/template/img/logo.png')}}" alt="Nifty">
            </div>
            <div class="tab-content">
                <div id="login" class="tab-pane active">
                    {{ Form::open(['url' => 'dashboard/login', 'class' => 'form-signin', 'id' => 'loginForm']) }}
                        <p class="text-muted text-center">Please login to continue</p>
                        <div class="alert alert-dismissable hidden">
                            <div class="alert-feedback"> </div>
                        </div>                        
                        {{ Form::email('email', Input::old('email'), ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']) }}
                        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required']) }}
                        <div class="checkbox">
                            <label class="text-muted">
                                {{ Form::checkbox('remember') }} Remember me
                            </label>
                        </div>
                        <button class="btn btn-lg btn-block btn-metis-6 btn-rect btn-grad" type="submit">Sign in</button>
                    {{ Form::close() }} 
                </div>
                <div id="forgot" class="tab-pane">
                    {{ Form::open(['url' => 'dashboard/request-pass', 'class' => 'form-signin', 'id' => 'request_pass_form']) }}
                        <p class="text-muted text-center">Please enter your e-mail</p>
                        <div class="alert alert-dismissable hidden">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <div class="alert-feedback"> </div>
                        </div>                         
                        {{ Form::email('request_pass_email', '', ['class' => 'form-control', 'placeholder' => 'Your email', 'required' => 'required']) }}
                        <br>
                        <button class="btn btn-lg btn-block btn-metis-1 btn-rect btn-grad" type="submit" id="forgot-pass-submit">Recover Password</button>
                    {{ Form::close() }} 
                </div>
            </div>
            <div class="text-center">
                <ul class="list-inline">
                    <li> <a class="text-muted" href="#login" data-toggle="tab">Login</a>  </li>
                    <li> <a class="text-muted" href="#forgot" data-toggle="tab">Forgot Password</a>  </li>
                </ul>
            </div>
        </div><!-- /container -->
        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        {{ HTML::script('assets/bootstrap/js/bootstrap.min.js') }}
        <script>
            jQuery(document).ready(function($) {
                $('.list-inline li > a').click(function() {
                    var activeForm = $(this).attr('href') + ' > form';
                    //console.log(activeForm);
                    $(activeForm).addClass('magictime swap');
                    //set timer to 1 seconds, after that, unload the magic animation
                    setTimeout(function() {
                        $(activeForm).removeClass('magictime swap');
                    }, 1000);
                });                
                $('form#loginForm').submit(function(e)
                {
                    e.preventDefault(); $this = $(this);

                    var action = $this.attr('action');
                    var token = $('#loginForm input[name="_token"]').val(); 
                    var email = $.trim($('#email').val());
                    var password = $('#password').val();
                    var remember = 0;
                        if ($('#loginForm input[name="remember"]').prop('checked')) remember = 1;

                    $.post(action, {_token: token, email: email, password:password, remember: remember}, function(data)
                    {
                        if (data['success'] !=undefined)
                        {
                            $('#login .alert').removeClass('alert-danger hidden').addClass('alert-success').text(data['success']);

                            setTimeout(function()
                            {
                                window.location.replace(data['url']);
                            }, 500);
                        }   

                        if (data['email'] !=undefined)
                        {
                            $('#login .alert').removeClass('alert-success hidden').addClass('alert-danger').text(data['email']);
                        } 

                        if (data['password'] !=undefined)
                        {
                            $('#login .alert').removeClass('alert-success hidden').addClass('alert-danger').text(data['password']);
                        } 
                    }, 'json'); 
                  
                });   

                $('#request_pass_form').submit(function(event) {
                    event.preventDefault();
                    var url = $(this).attr('action');

                    $('#forgot-pass-submit').attr('disabled', 'disabled');

                    $.post(url, $(this).serialize(), function(data) {

                        $('#forgot-pass-submit').removeAttr('disabled');

                        if (data['success'] !=undefined)
                        {
                            $('#forgot .alert').removeClass('alert-danger hidden').addClass('alert-success').text(data['success']);

                            setTimeout(function()
                            {
                                window.location.reload();
                            }, 5000);
                        } 

                        else if (data['error'] !=undefined)
                        {
                            $('#forgot .alert').removeClass('alert-success hidden').addClass('alert-danger').text(data['error']);
                        } 

                        else 
                        {
                            $('#forgot .alert').removeClass('alert-success hidden').addClass('alert-danger').text('An error occurred. Kindly contact admin.');
                        }

                    }, 'json');

                });                       
            })            
        </script>
    </body>    
</html>