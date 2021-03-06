@extends('layouts.master')
@section('title', 'QU - Parking Spot Images View')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           parking Spot Images View
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
             <li><a href="{{ url('booking/managebooking') }}">Manage Booking</a></li>
            <li class="active">parking Spot Images View</li>
        </ol>
    </section>
    <div class="row hidden" style="margin-top: 15px;">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <form role="form" id="ParkingSpotForm" method="post" action="{{ url('/parkingspot/saveparkingspot/'.$parking_spot_id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Parking Spot Images</label>
                                    <input class="" placeholder="Parking Spot Images" type="file" name="uploaded_image[]" multiple>
                                </div>
                            </div>
                            <div style="margin: 20px">  
                                <a href="{{ url('parkingspot/parkingspotList') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
                                <button class="btn btn-primary pull-right" type="submit">Add More Images</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="box box-primary box-solid" style="margin-top: 15px;">
        <div class="row">
            <div class="col-xs-12">
                    @if(count($parking_spot_images) > 0)
                    @foreach($parking_spot_images as $image)
                    <div id="" style="margin: 10px;border: 1px solid #ccc;float: left;position:relative;">
                        <!--<a href="javascript:void(0)" onclick="javasript:deleterecord({{$image->id}})">
                            <span class="fa-stack fa-sm" style="position: absolute; right: -10px; top: -10px;">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                            </span>
                        <a>-->    
                        <img src="{{ asset('uploads/parkingspot_images/'.$image->uploaded_image)}}" width="200px" height="200px" style="border:5px solid #999999;">
                    </div>
                    @endforeach
                    @else
                        <div id="" style="float: left;position:relative;font-size: 16px;margin-left: 10px;">
                           Images not available yet.
                        </div>
                    @endif
            </div>
            <div style="margin: 20px ">  
                <a href="{{ url('booking/managebooking') }}" class="btn btn-default pull-right" style="margin:0px 10px 10px 10px">Back</a>
            </div>
        </div>
    </div>    
</div>
@endsection

@push('scripts')
<script type="text/javascript">
 $('#ParkingSpotForm').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'uploaded_image[]': {
                validators: {
                    notEmpty: {
                        message: 'Please select an image'
                    },
                    file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        message: 'The selected file is not valid'
                    }
                }
            }
        }
    });    
    
var DeleteImageAjaxSource = '/parkingspot/deleteparkingspotGallery';
function deleterecord(id) {
    if (confirm("Are you sure you want to delete?")) {
       $.ajax({
           type: "POST",
           url: DeleteImageAjaxSource,
           data: "id=" + id,
           headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           success: function(data) {
               if (data == 1) {
                   window.location.href = window.location.href;
               }
           }
       });
       }
 }
 
</script>
@endpush