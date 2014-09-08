@extends('backend._template')

@section('title')All Tags @stop

@section('page-css')
    <style>
        table {
            font-size: 13px;
        }
        .more-options {
            /*margin-top: 5px;*/
        }
        .visibility {
            visibility: hidden;
        }
        a.red {
            color: #D54E21;
        }
        a:hover {
            color: #D54E21;
            text-decoration: none;
        }
        .page-options {
            margin: 10px 0;
        }
        .opacity {
            opacity: 0.3;
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
                <h5>All Tags</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{ URL::to('dashboard/tags/create') }}"><span class="fa fa-pencil"></span> New Tag</a>
                </div>
            </header>
        </div><!-- /.box -->
    </div> 
    <div class="col-md-4 optionsDiv opacity">
        {{ Form::open(['url' => '#', 'id' => 'bulk-options-form']) }}
        <div class="row">
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <select name="bulk-options" id="bulk-options" class="form-control" disabled="disabled">
                        <option value='1'>Delete Permanently</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="bulkDeleteUrl" value = "{{ URL::to('dashboard/tags/bulk-destroy') }}" />
                    <div class="appendTarget"></div>
                </div>                
            </div>
            <div class="col-sm-6 col-md-4">                     
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-rect" id="bulk-submit" disabled="disabled">Submit</button> 
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-4 hidden-xs hidden-sm">
        {{ Form::open(['url' => URL::to('dashboard/tags/set-records-per-page'), 'id' => 'set-records-form', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('number', 'Records Per Page') }} 
                <select name="number" id="number" class="form-control">
                    <option value='10'>Select</option>
                    <option value='10' {{ $records == 10 ? 'selected' : '' }}>10</option>
                    <option value='20' {{ $records == 20 ? 'selected' : '' }}>20</option>
                    <option value='50' {{ $records == 50 ? 'selected' : '' }}>40</option>
                    <option value='100' {{ $records == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>               
        {{ Form::close() }}
    </div>
    <div class="col-md-4 hidden-xs hidden-sm">
        {{ Form::open(['url' => URL::to('dashboard/tags/set-order-by'), 'id' => 'set-order-by-form', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('order-by', 'Sort') }} 
                <select name="order-by" id="order-by" class="form-control">
                    <option value='1'>Select</option>
                    <option value='1' {{ $orderBy == 1 ? 'selected' : '' }}>Oldest First</option>
                    <option value='2' {{ $orderBy == 2 ? 'selected' : '' }}>Latest First</option>
                    <option value='3' {{ $orderBy == 3 ? 'selected' : '' }}>A-Z (Name)</option>
                    <option value='4' {{ $orderBy == 4 ? 'selected' : '' }}>Z-A (Name)</option>
                </select>
            </div>               
        {{ Form::close() }}
    </div>

    <div class="col-md-12"> 
        @if(Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif 
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type='checkbox' id="checkAll" name='allposts'></th>
                        <th>Name</th>
                        <th>Emails</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                   {{ $tagsHtml }}
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        {{ $links }}
    </div>
@stop

@section('page-js')  
    <script>
        jQuery(document).ready(function($) {

            //Records per page
            $('select#number').change(function(event) {
                $('form#set-records-form').submit();
            });

            //Order By
            $('select#order-by').change(function(event) {
                $('form#set-order-by-form').submit();
            });

            //Chekboxes
            unCheckAll(); //reset
            $(':checkbox#checkAll').prop('checked', false); //reset

            $(':checkbox#checkAll').click(function(event) {
                if (this.checked) { checkAll(); }
                else { unCheckAll(); }
            });

            $(':checkbox.acheckbox').click(function(event) {
                toggleOptions();
            });

            function checkAll() {
                $(':checkbox.acheckbox').each(function(event) {
                    this.checked = true;
                });

                toggleOptions();
            }

            function unCheckAll() {
                $(':checkbox.acheckbox').each(function(event) {
                    this.checked = false;
                });

                toggleOptions();
            }

            function toggleOptions() {
                if ( $(':checkbox.acheckbox:checked').size() > 0 ) { unHideOptions(); }
                else { hideOptions(); }
            }

            function hideOptions() {
                $('.optionsDiv').addClass('opacity'); 
                $('#bulk-options-form .form-group .appendTarget').html('');
                $('#bulk-options').attr('disabled', 'disabled');
                $('#bulk-submit').attr('disabled', 'disabled').removeClass('btn-metis-1 btn-metis-2 btn-metis-5').addClass('btn-default').text('Submit'); 
                $('#bulk-options-form').attr('action', '#');               
            }

            function unHideOptions() {
                $('.optionsDiv').removeClass('opacity');      

                var html = '';
                var numSelected = 0;
                $(':checkbox.acheckbox:checked').each(function() {
                    html += "<input type='checkbox' name='lists[]' value='" + $(this).val() + "' class='hidden' checked='checked'>";
                    numSelected++;
                });

                $('#bulk-options-form .form-group .appendTarget').html(html);                
                $('#bulk-options').removeAttr('disabled');
                $('#bulk-submit').removeAttr('disabled').removeClass('btn-default btn-metis-2 btn-metis-5').addClass('btn-metis-1').text('Destroy ' + numSelected);
                $('#bulk-options-form').attr( 'action', $('#bulkDeleteUrl').val() );             
            }


        });
    </script>
@stop