@extends('layouts.master')
@section('title', 'QU - Edit Review Questionnaires')
@section('content')

<div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               Edit Review Questionnaires
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="{{ url('review/reviewList') }}">Manage Review Questionnaires</a></li>
                <li class="active">Edit Review Questionnaires</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Form controls-->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Review Questionnaires</h3>
                        </div>
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if(Session::has('message'))
                        <p class="alert alert-danger">{{ Session::get('message') }}</p>
                        @endif
                        <form role="form" id="Review_Questionnaires_form" method="post" action="{{ url('/review/update/'.$review_questionnaires->id) }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Questionnaires Title<span class="asterisk red">*</span></label>
                                            <input class="form-control" placeholder="Questionnaires Title" type="text" name="questionnaires_title" minlength="3" value="{{ !empty($review_questionnaires->questionnaires_title) ? $review_questionnaires->questionnaires_title : '' }}">
                                        </div>
                                    </div> 
                                
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>Status<span class="asterisk red">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="">Choose Status</option>
                                                <option {{ $review_questionnaires->status == 'Active' ? 'selected="selected"' : '' }} value="Active">Active</option>
                                                <option {{ $review_questionnaires->status == 'Inactive' ? 'selected="selected"' : '' }} value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <a href="{{ url('review/reviewList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
                                <button class="btn btn-primary pull-right" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
@endsection

@push('scripts')
<script type="text/javascript">
    // set up select2
    $('#Review_Questionnaires_form').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            questionnaires_title: {
                validators: {
                    notEmpty: {
                        message: 'The Questionnaires Title is required'
                    }
                }
            }, 
          /*  user_type: {
                validators: {
                    notEmpty: {
                        message: 'The User Type is required'
                    }
                }
            }, */
            status: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }
                }
            },
        }
    });
 </script>
@endpush