<html lang="en">
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type"> 
        <meta charset="utf-8">
        <title>{{ $configs->sitename }} - Unsubscribe</title>
        <meta content="Bootply" name="generator">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <style type="text/css">
            body {
                font-family: 'Raleway', cursive;
                font-size: 25px;
            }
            .modal-footer {   border-top: 0px; }
        </style>
    </head>    
    <body>
        <div aria-hidden="true" role="dialog" tabindex="-1" class="modal show" id="loginModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center"><i class="fa fa-legal fa-2x"></i></h1>
                    </div>
                    <div class="modal-body">
                        <p>Hey {{ $subscriber->first_name }},</p>
                        <p>You sadly did it!</p>
                        <p>You (<span style="text-decoration: underline">{{$subscriber->email}}</span>) will no longer receive emails from us.</p>
                    </div>
                    <div class="modal-footer">
	                   <small style="font-size: 18px;">&copy;{{ date('Y') . ' ' . $configs->sitename }} </small>
                    </div>
                </div>
            </div>
        </div> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>