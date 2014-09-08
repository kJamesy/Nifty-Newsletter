<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="Configure new site" name="description">
        <meta content="James Ilaki" name="author">
        <link href="{{asset('favicon.png')}}" rel="shortcut icon">
        <title>Nifty::@yield('title') </title>
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
        {{ HTML::style('assets/template/css/main.css') }}
        {{ HTML::style('assets/template/css/theme.css') }}
        @yield('page-css')
        <!--[if lt IE 9]>
            {{ HTML::script('assets/bootstrap/js/html5shiv.js') }}
            {{ HTML::script('assets/bootstrap/js/respond.min.js') }}
        <![endif]-->
        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        @yield('head-js')
    </head>
    <body class="padTop53">
        <div id="wrap">
            <div id="top">
                <nav class="navbar navbar-inverse navbar-fixed-top">
                    <header class="navbar-header">
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                        </button> -->
                        <a href="{{ URL::to('/') }}" class="navbar-brand">
                            <img src="{{asset('assets/template/img/logo.png')}}" alt="Nifty">
                        </a> 
                    </header>
                    <div class="topnav">
                        <div class="btn-toolbar">
                            <div class="btn-group">
                                <a data-toggle="modal" data-original-title="Settings" data-placement="bottom" class="btn btn-info btn-sm btn-grad" href="#settingsModal">
                                    <i class="fa fa-cogs"></i>
                                </a> 
                            </div>
                            <div class="btn-group">
                                <a href="{{ URL::to('dashboard/logout') }}" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom" class="btn btn-danger btn-sm btn-grad">
                                    <i class="fa fa-power-off"></i>
                                </a> 
                            </div>
                        </div>
                    </div><!-- /.topnav -->
                </nav><!-- /.navbar -->

                <header class="head">
                    <div class="search-bar" style="margin-top: 5px; overflow:hidden">
                        <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
                            <i class="fa fa-expand"></i>
                        </a> 
                    </div>
                    <div class="main-bar">
                        @yield('page-title')
                    </div><!-- /.main-bar -->
                </header>
            </div><!-- /#top -->
            <div id="left">
                @include('backend.partials.sidebar')
            </div><!-- /#left -->
            <div id="main-content">
                <div class="outer">
                    <div class="inner">
                        @yield('page')
                    </div><!-- end .inner -->
                </div> <!-- end .outer --> 
            </div> <!-- end #main-content -->
        </div><!-- /#wrap -->
        <div id="footer">
            <p><a href="http://acw.uk.com" target="_blank"> {{ date('Y') }} &copy;ACW</a></p>
        </div>

        <!-- #helpModal -->
        <div id="settingsModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-cogs fa-3x text-danger fa-spin"></i></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-sm-8 col-sm-offset-4" style="padding-bottom: 10px">
                            <span class="" id="modalFeedback"></span> 
                        </div> 
                        {{ Form::open(['url' => URL::to('dashboard/settings/set'), 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'modalSettingsForm']) }}
                            <div class="form-group">
                                <label for="modalSitename" class="col-sm-4 control-label">Sitename</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="modalSitename" name="modalSitename" value="{{ $configs->sitename }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="modalFromName" class="col-sm-4 control-label">Sender Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="modalFromName" name="modalFromName" value="{{ Setting::getFromName($user) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="modalFromEmail" class="col-sm-4 control-label">Sender Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="modalFromEmail" name="modalFromEmail" value="{{ Setting::getFromEmail($user) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="modalReplyEmail" class="col-sm-4 control-label">Reply-to Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="modalReplyEmail" name="modalReplyEmail" value="{{ Setting::getReplyToEmail($user) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-metis-5 btn-rect" id="sModalSubmit">Save</button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rect btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal --><!-- /#helpModal -->

        {{ HTML::script('assets/bootstrap/js/bootstrap.min.js') }}

        {{ HTML::script('assets/template/js/main.min.js') }}
        {{ HTML::script('assets/js/main.js') }}

        @yield('page-js')
    </body>
</html>