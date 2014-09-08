@extends('backend._template')

@section('title')New User @stop

@section('page-css')
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
    </style>
@stop

@section('page-title') <h3><i class="fa fa-group"></i> Users</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>New User</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/users/create')}}"><span class="fa fa-pencil"></span> New User</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif 

        {{ Form::open( ['url' => 'dashboard/users/store', 'class' => 'form-horizontal', 'role' => 'form'] ) }}  
            <div class="col-md-9">                 
                <div class="form-group {{ $errors->first('first_name') ? 'has-error' : '' }}">
                    {{ Form::label('first_name', $errors->first('first_name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('first_name', Input::old('first_name'), ['id' => 'first_name', 'class' => 'form-control']) }}
                    </div>                    
                </div> 
                <div class="form-group {{ $errors->first('last_name') ? 'has-error' : '' }}">
                    {{ Form::label('last_name', $errors->first('last_name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('last_name', Input::old('last_name'), ['id' => 'last_name', 'class' => 'form-control']) }}
                    </div>                    
                </div>
                <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                    {{ Form::label('email', $errors->first('email'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('email', Input::old('email'), ['id' => 'email', 'class' => 'form-control']) }}
                    </div>                    
                </div>  
                <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
                    {{ Form::label('password', $errors->first('password'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control']) }}
                    </div>                    
                </div>
                <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
                    {{ Form::label('password_confirmation', $errors->first('password') ?  $errors->first('password') : 'Password Confirmation', ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control']) }}
                    </div>                    
                </div> 
                <div class="form-group {{ $errors->first('role') ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">{{ $errors->first('role') ? $errors->first('role') : 'Role' }}</label>
                    <div class="col-sm-10">
                        @foreach ( $groups as $group )
                            <div class="radio">
                                <label>
                                    {{ Form::radio('role', $group->id) }}
                                    {{ $group->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>                    
                </div>
                
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <p class="form-control-static">
                            <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg">Save</button>
                        </p>
                    </div>                    
                </div> 
            </div>
        {{Form::close()}}   
    </div>
@stop

@section('page-js')

@stop