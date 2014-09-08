@extends('backend._template')

@section('title')Edit Tag @stop

@section('page-css')
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
        form .row {
            margin-left: 0 !important;
        }        
    </style>
@stop

@section('page-title') <h3><i class="fa fa-tags"></i> Tags</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Edit Tag</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/tags/create')}}"><span class="fa fa-pencil"></span> New Tag</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-md-12">
        {{ Form::model($tag, ["url" => "dashboard/tags/$tag->id/update", 'class' => 'form-horizontal']) }}
            <div class="row"> 
                <div class="col-md-6 col-sm-9 col-xs-12">                  
                    <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                        {{ Form::label('name', $errors->first('name'), ['class' => 'control-label']) }}
                        {{ Form::text('name', Input::old('name'), ['id' => 'name', 'class' => 'form-control'])}}
                    </div>                  
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-6 col-sm-9 col-xs-12">         
                    <div class="form-group">
                        <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg pull-left">Save</button>
                        <a href="{{ URL::to('dashboard/tags') }}" class="btn btn-metis-1 btn-rect btn-grad btn-lg pull-right">Cancel</a>
                    </div>
                </div>
            </div>
        {{Form::close()}}        
    </div>
@stop
