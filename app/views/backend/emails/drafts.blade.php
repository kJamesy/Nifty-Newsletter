@extends('backend._template')

@section('title')Drafts @stop

@section('page-css')
    {{ HTML::style('assets/css/emails.css') }}
@stop

@section('head-js')
    {{ HTML::script('assets/angular/lib/angular.js') }}
@stop

@section('page-title') <h3><i class="fa fa-envelope-o"></i> Emails</h3> @stop

@section('page')
    <div data-ng-app="draftsApp" data-ng-controller="DraftsController">
        <div class="col-lg-12">
            <div class="box info">
                <header>
                    <div class="icons">
                        <i class="fa fa-flag-o"></i>
                    </div>
                    <h5>Drafts</h5>
                    <div class="toolbar">
                        <a class="btn btn-default btn-sm btn-flat" href="{{ URL::to('dashboard/emails/create') }}"><span class="fa fa-pencil"></span> New Email</a>
                    </div>
                </header>
            </div><!-- /.box -->
        </div> 
        <div class="col-lg-12">
            <?php include('assets/angular/partials/drafts.html'); ?>
        </div>      
    </div>
@stop

@section('page-js')  
    {{ HTML::script('assets/js/malihu-scrollbar.min.js') }}
    {{ HTML::script('assets/angular/lib/angular-ui-router.min.js') }}
    {{ HTML::script('assets/angular/lib/angular-resource.js') }}
    {{ HTML::script('assets/angular/lib/angular-sanitize.min.js') }}
    {{ HTML::script('assets/angular/lib/angular-animate.min.js') }}    
    {{ HTML::script('assets/angular/js/app.js') }}
    {{ HTML::script('assets/angular/js/controllers.js') }}
    {{ HTML::script('assets/angular/js/services.js') }}
    {{ HTML::script('assets/angular/js/filters.js') }}
    {{ HTML::script('assets/angular/js/angular-locale_en-gb.js') }}    
@stop