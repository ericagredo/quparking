@extends('layouts.master')
@section('title', 'QU - User & Host Review & Rating')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           User & Host Review & Rating
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="{{ url('booking/managebooking') }}">Manage Booking</a></li>
            <li class="active">User & Host Review & Rating</li>
        </ol>
    </section>
    
   <section class="content">
        <!-- Search -->
        
        @if(Session::has('message'))
        <p class="alert alert-danger">{{ Session::get('message') }}</p>
        @endif
        @if(Session::has('success'))
        <p class="alert alert-success">{{ Session::get('success') }}</p>
        @endif
        
        <!--Data Table-->
        <div id="success_message" style="color:#00A65A;font-weight: bolder;"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6" >
                                <h3 class="box-title" style="vertical-align: middle;">User Questionnaires List and Details</h3>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('booking/managebooking') }}" class="btn btn-default btn-sm pull-right">Back</a>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php /*echo '<pre>'; print_r($review_reting_details); exit;*/?>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Renter Name : </label>
                                    {{$review_reting_details[0]->firstname}}&nbsp;{{$review_reting_details[0]->lastname}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Created Date : </label>
                                    {{date('m-d-Y',strtotime($review_reting_details[0]->created_date))}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Status : </label>
                                    {{$review_reting_details[0]->status}}
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Ratting : </label>
                                    <span id="stars-existing" class="starrr" data-rating='{{$review_reting_details[0]->rating}}' style="color: #3c8dbc;"></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-8">
                                <div class="form-group">
                                    <label>Feedback : </label>
                                    {{$review_reting_details[0]->feedback_description}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="StateList" class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php
                                            if(isset($review_reting_details[0]->questions_answer) && !empty($review_reting_details[0]->questions_answer)){
                                            foreach ($review_reting_details[0]->questions_answer as $key=>$data){ 
                                        ?>
                                    <tr>
                                            <td>{{$data[0]->id}}</td>
                                            <td>{{$data[0]->questionnaires_title}}</td>
                                            <td>{{$data[0]->answer}}</td>
                                            <td>{{$data[0]->status}}</td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>    
                            </table>
                        </div>
                        <div style="margin: 0px ">  
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6" >
                                <h3 class="box-title" style="vertical-align: middle;">Host Questionnaires List and Details</h3>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('booking/managebooking') }}" class="btn btn-default btn-sm pull-right">Back</a>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php /*echo '<pre>'; print_r($review_reting_details); exit;*/?>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Host Name : </label>
                                    <?php if(isset($review_reting_details_host[0]->firstname) && !empty($review_reting_details_host[0]->firstname)){ 
                                        echo $review_reting_details_host[0]->firstname.'&nbsp;'.$review_reting_details_host[0]->lastname;
                                    } ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Created Date : </label>
                                    <?php if(isset($review_reting_details_host[0]->created_date) && !empty($review_reting_details_host[0]->created_date)){ 
                                        echo date('m-d-Y',strtotime($review_reting_details_host[0]->created_date));
                                    } ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Status : </label>
                                     <?php if(isset($review_reting_details_host[0]->status) && !empty($review_reting_details_host[0]->status)){ 
                                        echo $review_reting_details_host[0]->status;
                                    } ?>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Ratting : </label>
                                    <span id="stars-existing" class="starrr" 
                                          data-rating='<?php if(isset($review_reting_details_host[0]->rating) && !empty($review_reting_details_host[0]->rating)){ 
                                                    echo $review_reting_details_host[0]->rating;
                                            } ?>' style="color: #3c8dbc;"></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-8">
                                <div class="form-group">
                                    <label>Feedback : </label>
                                    <?php if(isset($review_reting_details_host[0]->feedback_description) && !empty($review_reting_details_host[0]->feedback_description)){ 
                                        echo $review_reting_details_host[0]->feedback_description;
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="StateList" class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php
                                            if(isset($review_reting_details_host[0]->questions_answer) && !empty($review_reting_details_host[0]->questions_answer)){
                                            foreach ($review_reting_details_host[0]->questions_answer as $key=>$data2){ 
                                        ?>
                                    <tr>
                                            <td>{{$data2[0]->id}}</td>
                                            <td>{{$data2[0]->questionnaires_title}}</td>
                                            <td>{{$data2[0]->answer}}</td>
                                            <td>{{$data2[0]->status}}</td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>    
                            </table>
                        </div>
                        <div style="margin: 0px ">  
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    //https://bootsnipp.com/snippets/eNNKy
    var __slice = [].slice;

(function($, window) {
    var Starrr;

    Starrr = (function() {
        Starrr.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function(e, value) {}
        };

        function Starrr($el, options) {
            var i, _, _ref,
                _this = this;

            this.options = $.extend({}, this.defaults, options);
            this.$el = $el;
            _ref = this.defaults;
            for (i in _ref) {
                _ = _ref[i];
                if (this.$el.data(i) != null) {
                    this.options[i] = this.$el.data(i);
                }
            }
            this.createStars();
            this.syncRating();
            /*this.$el.on('mouseover.starrr', 'i', function(e) {
                return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('mouseout.starrr', function() {
                return _this.syncRating();
            });
            this.$el.on('click.starrr', 'i', function(e) {
                return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('starrr:change', this.options.change);*/
        }

        Starrr.prototype.createStars = function() {
            var _i, _ref, _results;

            _results = [];
            for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                _results.push(this.$el.append("<i class='fa fa-star-o'></i>"));
            }
            return _results;
        };

        Starrr.prototype.setRating = function(rating) {
            if (this.options.rating === rating) {
                rating = void 0;
            }
            this.options.rating = rating;
            this.syncRating();
            return this.$el.trigger('starrr:change', rating);
        };

        Starrr.prototype.syncRating = function(rating) {
            var i, _i, _j, _ref;

            rating || (rating = this.options.rating);
            if (rating) {
                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                    this.$el.find('i').eq(i).removeClass('fa-star-o').addClass('fa-star');
                }
            }
            if (rating && rating < 5) {
                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                    this.$el.find('i').eq(i).removeClass('fa-star').addClass('fa-star-o');
                }
            }
            if (!rating) {
                return this.$el.find('i').removeClass('fa-star').addClass('fa-star-o');
            }
        };

        return Starrr;

    })();
    return $.fn.extend({
        starrr: function() {
            var args, option;

            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var data;

                data = $(this).data('star-rating');
                if (!data) {
                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);

$(function() {
    return $(".starrr").starrr();
});
</script>    
@endpush

 


