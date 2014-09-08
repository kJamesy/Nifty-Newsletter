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
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/users/profile')}}"><span class="fa fa-user"></span> Profile</a>
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

        {{Form::open(['url' => 'dashboard/users/profile/password', 'class' => 'form-horizontal', 'role' => 'form'])}}  
            <div class="col-md-9">                 
                <div class="form-group {{ Session::has('existing_pass_error') ? 'has-error' : '' }}">
                    {{ Form::label('existing_password', Session::has('existing_pass_error') ? Session::get('existing_pass_error') : 'Existing Password', ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::password('existing_password', ['id' => 'existing_password', 'class' => 'form-control']) }}
                        {{ Form::hidden('id', $user->id) }}
                    </div>                    
                </div> 
                <div class="form-group {{ $errors->first('new_password') ? 'has-error' : '' }}">
                    {{ Form::label('new_password', $errors->first('new_password'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::password('new_password', ['id' => 'new_password', 'class' => 'form-control']) }}
                    </div>                    
                </div>
                <div class="form-group {{ $errors->first('new_password') ? 'has-error' : '' }}">
                    {{ Form::label('new_password_confirmation', $errors->first('new_password') ?  $errors->first('new_password') : 'New Password Confirmation', ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::password('new_password_confirmation', ['id' => 'new_password_confirmation', 'class' => 'form-control']) }}
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