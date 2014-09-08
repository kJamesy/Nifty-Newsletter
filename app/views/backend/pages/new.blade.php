@extends('backend._template')

@section('title')New Page @stop

@section('page-css')
    {{ HTML::style('assets/select2-3.4.5/select2.css') }}
    {{ HTML::style('assets/select2-3.4.5/select2-bootstrap.css') }}
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }    
    </style>
@stop

@section('page-title') <h3><i class="fa fa-folder-open"></i> Pages</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>New Page</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/pages/create')}}"><span class="fa fa-pencil"></span> New Page</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-lg-12">
        {{Form::open(['url' => 'dashboard/pages/create', 'class' => 'form-horizontal'])}}  
            @include('backend.partials.page-form')
        {{Form::close()}}  

    </div>
@stop

@section('page-js')
    {{ HTML::script('assets/select2-3.4.5/select2.min.js') }}
    <script>
        jQuery(document).ready(function($) {
            $("#anchors").select2({
                tags:['Type in your anchors separated by a space or comma'],
                tokenSeparators: [",", " "]
            });
        });        
    </script>
@stop