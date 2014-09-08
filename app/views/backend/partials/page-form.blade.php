           
<div class="col-md-12 col-lg-9">                 
    <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
        {{ Form::label('title', $errors->first('title'), ['class' => 'control-label']) }}
        {{ Form::text('title', Input::old('title'), ['id' => 'title', 'class' => 'form-control'])}}
    </div>
    <div class="form-group {{ $errors->first('anchors') ? 'has-error' : '' }}">
        {{ Form::label('anchors', $errors->first('anchors'), ['class' => 'control-label']) }}
        {{ Form::textarea('anchors', Input::old('anchors'), ['id' => 'anchors', 'class' => 'form-control', 'rows' => '3'])}}
    </div>     
    <div class="form-group {{ $errors->first('content') ? 'has-error' : '' }}">
        {{ Form::label('content', $errors->first('content'), ['class' => 'control-label']) }}
        {{ Form::textarea('content', Input::old('content'), ['id' => 'content', 'class' => 'form-control', 'rows' => '3'])}}
    </div>  
</div>
<div class="col-md-12 col-lg-12">
    <div class="form-group">
        <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg">Save</button>
    </div>
</div>
{{ HTML::script('assets/ckfinder2.4/ckfinder.js') }}
{{ HTML::script('assets/ckeditor4.4.3/ckeditor.js') }}
<script>
    var editor = CKEDITOR.replace('content', 
        {
            // width: 600,
            // height: 450
        });

    CKFinder.setupCKEditor(editor, '{{asset("assets/ckfinder2.4")}}');
</script>