@extends('layouts.master')
@section('title', 'QU - Booking Refund Receipt')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Booking Refund Receipt
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Booking Refund Receipt</li>
        </ol>
    </section>
    <div class="row" style="margin-top: 15px">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <form role="form" id="ReceiptForm" method="post" action="{{ url('/refund/Savereceipt/'.$booking_refund_id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Booking Refund Receipt</label>
                                    <input class="" placeholder="Booking Refund Receipt" type="file" name="uploaded_receipt[]" multiple>
                                </div>
                            </div>
                            <div style="margin: 20px">  
                                <a href="{{ url('refund/RefundListAdmin') }}" class="btn btn-default pull-right" style="margin:0 0 0 5px">Cancel</a>
                                <button class="btn btn-primary pull-right" type="submit">Add Receipt</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="box box-primary box-solid">
        <div class="row">
            <div class="col-xs-12">
                    @if(count($receipt) > 0)
                    @foreach($receipt as $rece)
                    <div id="" style="margin: 10px;border: 1px solid #ccc;float: left;position:relative;">
                        <a href="javascript:void(0)" onclick="javasript:deleterecord({{$rece->id}})">
                            <span class="fa-stack fa-sm" style="position: absolute; right: -10px; top: -10px;">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                            </span>
                        <a>    
                        <img src="{{ asset('uploads/bank_receipt/'.$rece->uploaded_receipt)}}" width="200px" height="200px" style="border:5px solid #999999;">
                    </div>
                    @endforeach
                    @else
                        <div id="" style="float: left;position:relative;font-size: 16px;margin-left: 10px;">
                           Receipt not available yet.
                        </div>
                    @endif
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
    
var DeleteImageAjaxSource = '/refund/deletereceiptGallery';
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