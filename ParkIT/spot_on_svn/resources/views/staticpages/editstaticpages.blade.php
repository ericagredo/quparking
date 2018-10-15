@extends('layouts.master')
@section('title', 'QU - Edit Static Pages')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Edit Static Pages
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="{{ url('/staticpages/staticpagelist') }}">Static Pages</a></li>
            <li class="active"> Edit Static Pages </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!--Form controls-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"> Edit Static Pages </h3>
                    </div>
                    <!--  Success flash-->
                    <form role="form" id="frm_staticpages" method="post" action="{{ url('/staticpages/update/'.$page_content->id) }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Page Name <span class="asterisk red">*</span></label>
                                    <input class="form-control" placeholder="Page Name" type="text" name="page_name" value="{{ !empty($page_content->page_name) ? $page_content->page_name : '' }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Page Title <span class="asterisk red">*</span></label> 
                                    <input class="form-control" placeholder="Page Title" type="text" name="page_title" value="{{ !empty($page_content->page_title) ? $page_content->page_title : '' }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label> Meta Title  <span class="asterisk red">*</span></label>
                                    <input class="form-control" placeholder="Meta Title" type="text" name="meta_title" value="{{ !empty($page_content->meta_title) ? $page_content->meta_title : '' }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label> Meta Keyword  <span class="asterisk red">*</span></label>
                                    <input class="form-control" placeholder="Meta Keyword" type="text" name="meta_keyword" value="{{ !empty($page_content->meta_keyword) ? $page_content->meta_keyword : '' }}">
                               </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Status <span class="asterisk red">*</span></label>
                                    <select class="form-control select2" style="width: 100%;" name="status">
                                        <option value="">Choose Status</option>
                                        <option {{ $page_content->status == 'Active' ? 'selected="selected"' : '' }} value="Active">Active</option>
                                        <option {{ $page_content->status == 'Inactive' ? 'selected="selected"' : '' }} value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label> Meta Description  <span class="asterisk red">*</span></label>
                                    <textarea class="form-control" rows="3" placeholder="Meta Description" name="meta_description">{{ !empty($page_content->meta_description) ? $page_content->meta_description : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label> Page Description <span class="asterisk red custom_hide">*</span></label>
                                    <textarea class="form-control" rows="3" placeholder="Page Description" name="page_description" id="content">{{ !empty($page_content->page_description) ? $page_content->page_description : '' }}</textarea>
                                   </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-default pull-right" onclick="window.location = '{{ url('staticpages/staticpagelist') }}';" type="button">Cancel</button>
                        <button class="btn btn-primary pull-right" type="submit">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
@push('scripts')
<script>
//
    $(document).ready(function () {
        $('#frm_staticpages')
                .bootstrapValidator({
                    excluded: [':disabled'],
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        page_name: {
                            validators: {
                                notEmpty: {
                                    message: 'The Key Name is required'
                                }
                            }
                        },
                        page_title: {
                            validators: {
                                notEmpty: {
                                    message: 'The  Name is required'
                                }
                            }
                        },
                        meta_title: {
                            validators: {
                                notEmpty: {
                                    message: 'The  Meta title is required'
                                }
                            }
                        },
                        meta_keyword: {
                            validators: {
                                notEmpty: {
                                    message: 'The  Meta Keyword is required'
                                }
                            }
                        },
                        meta_description: {
                            validators: {
                                notEmpty: {
                                    message: 'The  Meta description is required'
                                }
                            }
                        },
                        status: {
                            validators: {
                                notEmpty: {
                                    message: 'The Status is required'
                                }
                            }
                        }, 
                    }
                });

    });
</script>
<script src="{{asset('/plugins/ckeditor/ckeditor.js')}}"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('content');
    //bootstrap WYSIHTML5 - text editor
    
  });
</script>  
@endpush