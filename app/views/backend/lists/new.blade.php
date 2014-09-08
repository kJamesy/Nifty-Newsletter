@extends('backend._template')

@section('title')New List @stop

@section('page-css')
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
        form .row {
            margin-left: 0 !important;
        }    
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 999px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }              
    </style>
@stop

@section('page-title') <h3><i class="fa fa-book"></i> Lists</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>New List</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/lists/create')}}"><span class="fa fa-pencil"></span> New List</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-lg-12">
        @if(Session::has('issues'))
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('issues') }}
            </div>
        @endif   
        @if(Session::has('errors'))
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ $errors->first('file')}}
            </div>
        @endif
            
        {{Form::open(['url' => 'dashboard/lists/store', 'class' => 'form-horizontal', 'files' => true])}}  
            <div class="row">
                <div class="col-md-6 col-xs-12">                 
                    <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                        {{ Form::label('name', $errors->first('name'), ['class' => 'control-label']) }}
                        {{ Form::text('name', Input::old('name'), ['id' => 'name', 'class' => 'form-control'])}}
                    </div> 
                    <div class="form-group {{ $errors->first('active') ? 'has-error' : '' }}">
                        <label class="control-label">Active</label>
                        <div class="radio">
                            <label>
                                {{ Form::radio('active', 1) }}
                                Active
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {{ Form::radio('active', 0) }}
                                Inactive
                            </label>
                        </div>                                       
                    </div>                   
                </div>
                <div class="col-md-5 col-md-offset-1 col-xs-12">
                    <div class="form-group">
                        {{ Form::label('file', $errors->first('file'), ['class' => 'control-label']) }} <br />
                        <span class="btn btn-rect btn-default btn-file">
                            <span id="upload_text">Import Excel</span> <input type="file" name="file" id="file">
                        </span>
                    </div>
                </div>                
            </div>
            <div class="row">         
                <div class="col-md-6 col-sm-9 col-xs-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg pull-left">Save</button>
                        <a href="{{ URL::to('dashboard/lists') }}" class="btn btn-metis-1 btn-rect btn-grad btn-lg pull-right">Cancel</a>
                    </div>
                </div>
            </div>
        {{Form::close()}}        
    </div>
@stop

@section('page-js')
    <script>
        jQuery(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        jQuery(document).ready(function($) {
            if ( $('#file').val().length ) {
                readyForSubmit( $('#file').val() );
            }

            $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
                readyForSubmit(label);
            });

            function readyForSubmit(submitBtnText)
            {
                $('#upload_text').text(submitBtnText);               
            }
        });  
    </script>
@stop