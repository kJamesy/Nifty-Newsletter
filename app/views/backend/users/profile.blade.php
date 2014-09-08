@extends('backend._template')

@section('title')Your Profile @stop

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
                <h5>Your Profile</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/users/profile/password')}}"><span class="fa fa-lock"></span> Password</a>
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

        {{ Form::model($user, ['url' => 'dashboard/users/profile', 'class' => 'form-horizontal', 'role' => 'form']) }}  
            <div class="col-md-9">                 
                <div class="form-group {{ $errors->first('first_name') ? 'has-error' : '' }}">
                    {{ Form::label('first_name', $errors->first('first_name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('first_name', Input::old('first_name'), ['id' => 'first_name', 'class' => 'form-control']) }}
                        {{ Form::hidden('id') }}
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
                <div class="form-group">
                    <label class="col-sm-2 control-label">Role</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            @foreach ( $roles as $role )
                                {{ $role->name }} 
                            @endforeach
                        </p>
                    </div>                    
                </div>                 
                <div class="form-group">
                    <label class="col-sm-2 control-label">User Since</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            <abbr title='{{ $userCreatedAt }}'>{{ $userSince }}</abbr>
                        </p>
                    </div>                    
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">Logged-in Since</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            <abbr title='{{ $loggedInAt }}'>{{ $logged_in_for }}</abbr>
                        </p>
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