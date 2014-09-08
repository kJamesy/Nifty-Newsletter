@extends('backend._template')

@section('title')Edit Draft @stop

@section('page-css')
    {{ HTML::style('assets/select2-3.4.5/select2.css') }}
    {{ HTML::style('assets/select2-3.4.5/select2-bootstrap.css') }}
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
        form .row {
            margin-left: 0 !important;
        }        
    </style>
@stop

@section('page-title') <h3><i class="fa fa-envelope-o"></i> Emails</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Edit Draft</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/emails/create')}}"><span class="fa fa-pencil"></span> New Email</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-md-12">
        {{ Form::open(['url' => 'dashboard/emails/send', 'class' => 'form-horizontal']) }}  
            <div class="col-md-12 col-lg-9"> 
                <div class="form-group {{ $errors->first('subscribers') ? 'has-error' : '' }}">
                    {{ Form::label('subscribers', $errors->first('subscribers'), ['class' => 'control-label']) }}
                    {{ Form::select('subscribers[]', $subscribers, null, ['multiple' => true, 'class' => 'form-control select-fix', 'id' => 'subscribers']) }}   
                    {{ Form::hidden('was_draft', $email->id) }}                  
                </div> 
                <div class="form-group {{ $errors->first('mail_lists') ? 'has-error' : '' }}">
                    {{ Form::label('mail_lists', $errors->first('mail_lists'), ['class' => 'control-label']) }}
                    {{ Form::select('mail_lists[]', $mail_lists, null, ['multiple' => true, 'class' => 'form-control select-fix', 'id' => 'mail_lists']) }}                       
                </div>                               
                <div class="form-group {{ $errors->first('subject') ? 'has-error' : '' }}">
                    {{ Form::label('subject', $errors->first('subject'), ['class' => 'control-label']) }}
                    {{ Form::text('subject', $email->subject, ['id' => 'subject', 'class' => 'form-control']) }}
                </div>                    
                <div class="form-group {{ $errors->first('email_body') ? 'has-error' : '' }}">
                    {{ Form::label('email_body', $errors->first('email_body'), ['class' => 'control-label']) }}
                    {{ Form::textarea('email_body', $email->email_body, ['id' => 'email_body', 'class' => 'form-control', 'rows' => '3'])}}              
                </div>   
            </div>
            <div class="col-md-12 col-lg-2 col-lg-offset-1">
                <div class="form-group">
                    {{ Form::label('tag_id', 'Email Tag', ['class' => 'control-label']) }}
                    {{ Form::select('tag_id', $tag_list, $email->tag_id, ['class' => 'form-control', 'id' => 'tag_id']) }}
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('save_draft', Input::old('save_draft'), ['checked' => 'checked']) }}
                            Save Draft
                        </label>
                    </div>
                </div>                 
                <div class="form-group">
                    From: <strong>{{ $email_configs['from_name'] }} ({{ $email_configs['from_email'] }})</strong> <br /><br />
                    Reply To: <strong>{{ $email_configs['from_name'] }} ({{ $email_configs['reply_to_email'] }})</strong> <br /><br />
                    <em>You can change these parameters in Settings</em>
                </div>          
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-metis-3 btn-grad btn-rect btn-lg pull-left" id="submit_btn">Send</button>
                    <a href="{{ URL::to('dashboard') }}" class="btn btn-metis-1 btn-rect btn-grad btn-lg pull-right">Cancel</a>
                </div> 
            </div>
        {{ Form::close() }}        
    </div>
@stop

@section('page-js')
    {{ HTML::script('assets/ckfinder2.4/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor4.4.3/ckeditor.js') }}
    {{ HTML::script('assets/select2-3.4.5/select2.min.js')}}
    <script>
        var editor = CKEDITOR.replace('email_body', 
            {
                // width: 600,
                // height: 450
            });

        CKFinder.setupCKEditor(editor, '{{asset("assets/ckfinder2.4")}}');

        jQuery(document).ready(function($) {
            $('#subscribers').select2({
                width: 'resolve'
            });
            $('#mail_lists').select2({
                width: 'resolve'
            }); 

            if ( $('[name="save_draft"]').prop('checked') )
                $('#submit_btn').text('Save').removeClass('btn-metis-3').addClass('btn-metis-6');
            else
                $('#submit_btn').text('Send').addClass('btn-metis-3').removeClass('btn-metis-6');

            $('[name="save_draft"]').change(function(event) {
                if ( $(this).prop('checked') )
                    $('#submit_btn').text('Save').removeClass('btn-metis-3').addClass('btn-metis-6');
                else
                    $('#submit_btn').text('Send').addClass('btn-metis-3').removeClass('btn-metis-6');
            });
        });

    </script>

@stop