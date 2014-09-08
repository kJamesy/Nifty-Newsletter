@extends('backend._template')

@section('title')Edit Subscriber @stop

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

@section('page-title') <h3><i class="fa fa-rss"></i> Subscribers</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Edit Subscriber</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/subscribers/create')}}"><span class="fa fa-pencil"></span> New Subscriber</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-lg-12">
        {{ Form::model($subscriber, ["url" => "dashboard/subscribers/$subscriber->id/update", 'class' => 'form-horizontal']) }}
            <div class="row"> 
                <div class="col-md-6 col-xs-12">                  
                    <div class="form-group {{ $errors->first('first_name') ? 'has-error' : '' }}">
                        {{ Form::label('first_name', $errors->first('first_name'), ['class' => 'control-label']) }}
                        {{ Form::text('first_name', Input::old('first_name'), ['id' => 'first_name', 'class' => 'form-control'])}}
                    </div>
                    <div class="form-group {{ $errors->first('last_name') ? 'has-error' : '' }}">
                        {{ Form::label('last_name', $errors->first('last_name'), ['class' => 'control-label']) }}
                        {{ Form::text('last_name', Input::old('last_name'), ['id' => 'last_name', 'class' => 'form-control'])}}
                    </div>   
                    <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                        {{ Form::label('email', $errors->first('email'), ['class' => 'control-label']) }}
                        {{ Form::text('email', Input::old('email'), ['id' => 'email', 'class' => 'form-control'])}}
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
                        <strong>Lists</strong>
                        {{ Jamesy\ModelCheckboxGroup::generateHtml($listsList, count(Input::old('lists')) ? Input::old('lists') : $subscriber->maillists, 'lists[]') }} 
                    </div>
                </div>                
            </div>
            <div class="row"> 
                <div class="col-md-6 col-sm-9 col-xs-12">         
                    <div class="form-group">
                        <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg pull-left">Save</button>
                        <a href="{{ URL::to('dashboard/subscribers') }}" class="btn btn-metis-1 btn-rect btn-grad btn-lg pull-right">Cancel</a>
                    </div>
                </div>
            </div>
        {{Form::close()}}        
    </div>
@stop