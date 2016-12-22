@extends('layouts.frontend')

@section('content')
    <div class="container-fluid">
       <div class="row">
            <div class="col-md-12 category-container category-container-image" 
             style="background-image: url( {!! url('uploads/packages/'.$package->mainImage) !!} )">
                    <div class="package-container-title-left"> 
                        <h4>{!! $category->name !!}</h4>
                        <h3>{!! $package->name !!}</h3> 
                    </div>
            </div>    
        </div>
        <div class="row package-pricing">
                <div class="package-price col-md-4">
                    <p>Retail Price: <span id="retailPrice" class="text-muted">Select Travel Dates</span></p>
                </div>
                <div class="package-price col-md-4">
                    <p>Jet Set Go Price: <span id="jetSetGoPrice" class="text-muted">Select Travel Dates</span></p>
                </div>
                <div class="package-price col-md-4">
                    <p>Trpz Price: <span id="trpzPrice" class="text-muted">Select Travel Dates</span></p>
                </div>
           
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 {!! $nonav ? 'package-deal-ends-nonav' : 'package-deal-ends' !!}" >
                            <p>Deal Ends:</p>
                            <p id="dealEnds"></p>
                    </div>
                </div>
                @if(!$nonav)
                <div class="row">
                    <div class="col-md-12 package-learn-more">
                        <a href="#">
                                <p>Learn More</p>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div id="package-map"></div>
                </div>
            </div>
         
        </div>
        <div class="package-descriptions row">
            <?php
                if(!empty($package->amenities)){
                    echo "<div class='container package-description'><h3>Amenities</h3>{$package->amenities}</div>";
                }
                if(!empty($package->highlights)){
                    echo "<div class='container package-description'><h3>Highlights</h3>{$package->highlights}</div>";
                }
                if(!empty($package->finePrint)){
                    echo "<div class='container package-description'><h3>Fine Print</h3>{$package->finePrint}</div>";
                }
                if(!empty($package->tripItinerary)){
                    echo "<div class='container package-description'><h3>Trip Itinerary</h3>{$package->tripItinerary}</div>";
                }
                if(!empty($package->frequentlyAskedQuestions)){
                    echo "<div class='container package-description'><h3>Frequently Asked Questions</h3>{$package->frequentlyAskedQuestions}</div>";
                }
                if(!empty($package->otherNotes)){
                    echo "<div class='container package-description'><h3>Other Notes</h3>{$package->otherNotes}</div>";
                }
            ?>
        </div>
        @if(!$noinputs)
        <div class="row">
            {!! Form::open(['route' => ['cart.add'], 'method' => 'post', 'id'=>'package-form']) !!}
            <input type="hidden" value="{!! $package->id !!}" name="packageId" id="packageId"/>
            <input type="hidden" value="{!! $package->numberOfDays !!}" id="numberOfDays"/>
            <input type="hidden" value="{!! $package->numberOfPeople !!}" id="numberOfPeople"/>
            <input type="hidden" id="priceType" name="priceType"/>
            <input type="hidden" id="trpz" name="trpz"/>
            <input type="hidden" id="jetSetGo" name="jetSetGo"/>
            <input type="hidden" id="retail" name="retail"/>
            <input type="hidden" id="hotel" name="hotel"/>
            <div id="divActivityForms"></div>
            <div class="container">
                <div class="col-md-6">
                    <h3>Select Dates</h3>
                    <div class="col-md-6 form-group {!! $errors->has('startDate') ? 'has-error' : '' !!}">
                        <label for="startDate" class="control-label">Start Date</label>
                        @if(isset($voucher))
                            {!! Form::text('startDate', $package->startDate->format('m/d/Y'), [ 'class' => 'form-control', 'readonly'=>'readonly']) !!}
                        @else
                            {!! Form::text('startDate', null, ['id'=>'startDate', 'class' => 'form-control', 'required'=>'required']) !!}
                            @if ($errors->has('startDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('startDate') }}</strong>
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="endDate" class="control-label">End Date</label>
                        @if(isset($voucher))
                            {!! Form::text('endDate', $package->startDate->addDays($package->numberOfDays)->format('m/d/Y'), [ 'class' => 'form-control', 'readonly'=>'readonly']) !!}
                        @else
                            {!! Form::text('endDate', null, ['id'=>'endDate', 'class' => 'form-control', 'required'=>'required', 'readonly'=>'readonly']) !!}
                        @endif
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Package Options</h3>
                </div>
            </div>
            @endif
            <div class="container">
                <div class="col-md-{!! $noinputs ? '12' : '6' !!} form-group">
                    <h3>Hotel</h3>
                     <?php $hotel = $package->packageHotels[0]->hotel; ?>
                    {!! Form::hidden('hotel-id', $hotel->hotelId, ["id"=>"hotel-id"]) !!}
                    <div class="panel panel-default">
                       <div class="panel-heading">{!! $hotel->name !!}</div>
                       <div class="panel-body">
                           <div class="row">
                               <div class="col-md-3">
                                   <img src="{!! $hotel->thumb !!}" />
                               </div>
                               <div class="col-md-9">
                                   <p >{!! $hotel->description !!}</p>
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-12">
                                   <p >{!! $hotel->address !!}</p>
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
                @if(!$noinputs)
                <div class="col-md-6 form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Room Type</h3>
                            <select id="roomTypeId" name="roomTypeId" class="form-control" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="divAdditionalFees" style="display: none">
                            <h3>Additional</h3>
                            <table id="tbladditionalFees" class="table table-striped table-responsive">
                                <thead>
                                    <th>Name</th><th>Price</th><th>Pay Type</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div id="divSupplements"></div>
                            <ul id="ulBoardBases"></ul>
                            <div id="divBoardBases"></div>
                        </div>
                    </div>
                        
                    
                    
                </div>
                @endif
            </div>
            
            <div class="container">
                <div class="col-md-6 form-group">
                    <h3>Activities</h3>
                     @if(!$nonav)
                    <select class="form-control" class="activityId" id="activityId" multiple="multiple">
                        @foreach($package->packageActivities as $packageActivity)
                        <option value="{!! $packageActivity->activity->id !!}"> {!! $packageActivity->activity->name !!}</option>
                        @endforeach
                    </select>
                     @endif
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-12 activities">
                    @foreach($package->packageActivities as $key => $packageActivity)
                        <div class="col-md-4 {!! !($nonav && $key <= 2) ? 'activity-item' : ''!!}" id="activity-{!! $packageActivity->activity->id !!}">
                            <div class="panel panel-default">
                                <div class="panel-heading">{!! $packageActivity->activity->name !!}</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{!! $packageActivity->activity->thumbURL !!}"/>
                                        </div>
                                        <div class="col-md-8">
                                            <p>{!! $packageActivity->activity->description !!}</p>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <label>Options</label>
                                            @if($nonav)
                                            <p>{!! $packageActivity->activity->activityOptions[0]->name !!}</p> 
                                            @else
                                            <select class="form-control activity-options" multiple="multiple" activityId="{!! $packageActivity->activity->id !!}"  id="activityOptions_{!! $packageActivity->activity->id !!}">
                                                    <?php $single = count($packageActivity->activity->activityOptions) === 1; ?>
                                                    @foreach($packageActivity->activity->activityOptions as $option)
                                                        <option value="{!! $option->id !!}" {!! $single ? 'selected' : '' !!} > {!! $option->name !!}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 activity-item" id="activity-details-{!! $packageActivity->activity->id !!}">
                            <h3>{!! $packageActivity->activity->name !!} Details</h3>
                            @foreach($packageActivity
                                ->activity
                                ->details
                                ->ActivityDetails
                                ->Description
                                ->LongDescription
                                ->Fragments
                                ->DescriptionFragment as $activityDesc)
                                <p>{!! $activityDesc->value !!}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                        
            </div>
            
            @if(!$nonav)
            <div class="container">
                <div class="col-md-12"><h3>Package Pricing Options</h3></div>
                <div class="col-md-6">
                    <div class="package-pricing2">
                        <div class="package-pricing2-description">
                            <div class="col-md-6">
                                <h4>Jet Set Go®</h4>
                                <h4 id="jetSetGoPrice2" class="text-muted">Select Travel Dates</h4>
                                <p>Discount: 61% </p>
                            </div>
                            <div class="col-md-6">
                                <p style="font-size: 14px;">Jet Set Go® offers you a whole new way pay for travel: by playing games! Download Jet Set Go® right now to stop paying for travel and start playing for travel!</p>
                            </div>
                        </div>
                        <input type="button" id="start-playing" class="button package-buttons"   value="Start Playing!" onclick="checkCancellationPolicy('jetSetGo');"/>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="package-pricing2">
                        <div class="package-pricing2-description">
                            <div class="col-md-6">
                                <h4>Trpz™</h4>
                                <h4 id="trpzPrice2" class="text-muted">Select Travel Dates</h4>
                                <p>Discount: 39%</p>
                            </div>
                            <div class="col-md-6">
                                <p style="font-size: 14px;">By booking your vacation package with Trpz™, you receive unmatched discounts on one of a kind vacation experiences.</p>
                            </div>
                        </div>
                        <input type="button" id="book-now" class="button package-buttons"  value="Book Now" onclick="checkCancellationPolicy('trpz');" />
                    </div>
                </div>
            </div>
            @endif
            {!! Form::close() !!}
        </div>
        @if(!$nonav)
            @include('frontend.additional')    
        @endif
    </div>
<div class="modal fade" id="policy-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Hotel Cancellation Policy</h4>
      </div>
      <div class="modal-body">
          <div id="divCancellationPolicy"></div>
      </div>
      <div class="modal-footer">
          <button type="button" id="btnAcceptPolicy" class="btn btn-primary">I Accept</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">I do not Accept</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="activity-modal" tabindex="-1" role="dialog">
<form id="activities-form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" >Activity</h4>
      </div>
      <div class="modal-body">
          <div>
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#activity" aria-controls="activity" role="tab" data-toggle="tab">Activity Additions</a></li>
              <li role="presentation"><a href="#passengers" aria-controls="passengers" role="tab" data-toggle="tab">Passengers</a></li>
              <li role="presentation"><a href="#cancellation" aria-controls="cancellation" role="tab" data-toggle="tab">Cancellation Policy</a></li>
            </ul>
            <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activity">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="hidden" id="selectedActivityId" name="selectedActivityId"/>
                                <label for="activityDate">Select Activity Date</label>
                                <input type="text" id="activityDate" name="activityDate" class="form-control"/>
                            </div>
                        </div>
                        <div class="row" id="divActivityForm"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="passengers">
                        <div class="row" id="divPassengersForm"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="cancellation">
                        <div class="row">
                            <div class="col-md-12">
                                <ul id="ulCancellation"></ul>
                            </div>
                        </div>
                    </div>
                
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSaveActivity" class="btn btn-primary">Accept</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</form>
</div>
 <script>
var roomTypes;
var roomType;
var activitySaved = false;
var currentActivity = null;
$(function(){
    var now = moment(new Date());
    var end = moment("{!! $package->dealEndDate !!}");
    var duration = moment.duration(end.diff(now));
    if(duration.minutes() >= 0){
        var dealEnds = duration.days() + " days, " + duration.hours() + " hours and " + duration.minutes() + " minutes";
    }else{
        var dealEnds = "expired";
    }
    $("#dealEnds").html(dealEnds);
    $("#startDate").datetimepicker({
        format: 'MM/DD/YYYY',
        minDate: moment("{!! $package->startDate !!}"),
        maxDate: moment("{!! $package->endDate !!}")
    }).on("dp.hide", function (e) {
        var endDate = new Date(e.date);
        var numberOfDays = parseInt($("#numberOfDays").val());
        endDate.setDate(endDate.getDate() + numberOfDays);
        $("#activityDate").datetimepicker("maxDate", moment(endDate));
        $("#activityDate").datetimepicker("minDate", moment(e.date));
        $('#endDate').val(moment(endDate).format('MM/DD/YYYY'));
        loadHotelInfo();
    }).val("");
    $("#activityDate").datetimepicker({
        format: 'MM/DD/YYYY'
    }).on("dp.hide", function (e) {
        ActivityPreBook();
    });
    
    $("#activityId").multiselect({
        buttonWidth: '100%',
        onChange: function(option, checked, select) {
            var activityId = $(option).val();
            $("#selectedActivityId").val(activityId);
            if(checked){
                $("#activity-"+activityId).show();
                $("#activity-details-"+activityId).show();
                if($('#activityOptions_'+activityId).children().length === 1){
                    $("#activity-modal").modal();
                }else{
                    alert("Please select an Activity Option");
                }
            }else{
                $("#divActivityForms input").each(function(){
                    if($(this).attr("activityid") == activityId){
                        $(this).remove();
                        return false;
                    }
                });
                if($('#activityOptions_'+activityId).children().length > 1){
                    $('#activityOptions_'+activityId).multiselect('deselectAll', false);
                }
                $('#activityOptions_'+activityId).multiselect('updateButtonText');
                $("#activity-"+activityId).hide();
                $("#activity-details-"+activityId).hide();
                loadPrices();
            }
        },
        onDropdownShow: function(event) {
            if($("#startDate").val()==""){
                alert("Please select Start Date first.");
                event.preventDefault();
                return false;
            }
        }
    });
    $(".activity-options").multiselect({
        buttonWidth: '100%',
        onChange: function(option, checked) {
             var control = $(this);
             var selector = "#"+control[0].$select[0].id;
             var activityId = $(selector).attr("activityid");
             if(checked){
                var values = [];
                $(selector+" option").each(function() {
                    if (option.val() != $(this).val()) {
                        values.push($(this).val());
                    }
                });
                $(selector).multiselect('deselect', values);

                $("#selectedActivityId").val(activityId);
                $("#divActivityForms input").each(function(){
                    if($(this).attr("activityid") == activityId){
                        $(this).remove();
                        return false;
                    }
                });
               $("#activity-modal").modal();
             }else{
                $('#activityOptions_'+activityId).multiselect('select', option.val());
             }
        }
    });
    $("#roomTypeId").on("change", function(){
        loadPrices();
    });
    $("#btnAcceptPolicy").click(function(){
        $("#package-form").submit();
    });
    $('#activity-modal').on('hidden.bs.modal', function () {
        if(!activitySaved){
            var activityId = $("#selectedActivityId").val();
            $("#activityId").multiselect('deselect', activityId);
            if($('#activityOptions_'+activityId).children().length > 1){
                $('#activityOptions_'+activityId).multiselect('deselectAll', false);
            }
            $('#activityOptions_'+activityId).multiselect('updateButtonText');
            $("#activity-"+activityId).hide();
            $("#activity-details-"+activityId).hide();
        }
        currentActivity = null;
    });
    $('#activity-modal').on('shown.bs.modal', function() {
        activitySaved = false;
        $("#divActivityForm").empty();
        $("#divPassengersForm").empty();
        $("#ulCancellation").empty();
        $("#activityDate").val("");
        $('.nav-tabs a:first').tab('show'); 
    })
    $("#btnSaveActivity").click(function(){
        var valid = true;
        if($("#activityDate").val() == ""){
            alert("Please select an Activity Date.");
            return;
        }
        $("#divActivityForm textarea, #divActivityForm input, #divActivityForm select").each(function(){
            if($(this).val() == ""){
                 valid = false;
                 return false;
            }
        });
        if(!valid){
            alert("Please complete all Activity Additions fields");
            return;
        }
        $("#divPassengersForm input").each(function(){
            if($(this).val() == ""){
                 valid = false;
                 return false;
            }
        });
        if(!valid){
            alert("Please complete all Passengers fields");
            return;
        }
        if(currentActivity.ActivityAdditions.TextAdditions){
            if(Array.isArray(currentActivity.ActivityAdditions.TextAdditions.TextAddition)){
                currentActivity.ActivityAdditions.TextAdditions.TextAddition.forEach(function(TextAddition){
                    var additionTypeID = TextAddition.additionTypeID;
                    TextAddition.value = $("#TextAdditionID"+additionTypeID).val();
                });
            }else{
                var additionTypeID = currentActivity.ActivityAdditions.TextAdditions.TextAddition.additionTypeID;
                currentActivity.ActivityAdditions.TextAdditions.TextAddition.value = $("#TextAdditionID"+additionTypeID).val();
            }
        }
        if(currentActivity.ActivityAdditions.TrueFalseAdditions){
            if(Array.isArray(currentActivity.ActivityAdditions.TrueFalseAdditions.TrueFalseAddition)){
                currentActivity.ActivityAdditions.TrueFalseAdditions.TrueFalseAddition.forEach(function(TrueFalseAddition){
                    var additionTypeID = TrueFalseAddition.additionTypeID;
                    TrueFalseAddition.value = $("#TrueFalseAdditionID"+additionTypeID).val();
                });
            }else{
                var additionTypeID = currentActivity.ActivityAdditions.TrueFalseAdditions.TrueFalseAddition.additionTypeID;
                currentActivity.ActivityAdditions.TrueFalseAdditions.TrueFalseAddition.value = $("#TrueFalseAdditionID"+additionTypeID).val();
            }
        }
        if(currentActivity.ActivityAdditions.NumericAdditions){
            if(Array.isArray(currentActivity.ActivityAdditions.NumericAdditions.NumericAddition)){
                currentActivity.ActivityAdditions.NumericAdditions.NumericAddition.forEach(function(NumericAddition){
                    var additionTypeID = NumericAddition.additionTypeID;
                    NumericAddition.value = $("#NumericAdditionID"+additionTypeID).val();
                });
            }else{
                var additionTypeID = currentActivity.ActivityAdditions.NumericAdditions.NumericAddition.additionTypeID;
                currentActivity.ActivityAdditions.NumericAdditions.NumericAddition.value = $("#NumericAdditionID"+additionTypeID).val();
            }
        }
        if(currentActivity.ActivityAdditions.NumericRangeAdditions){
            if(Array.isArray(currentActivity.ActivityAdditions.NumericRangeAdditions.NumericRangeAddition)){
                currentActivity.ActivityAdditions.NumericRangeAdditions.NumericRangeAdditionforEach(function(NumericRangeAddition){
                    var additionTypeID = NumericRangeAddition.additionTypeID;
                    NumericRangeAddition.value = $("#NumericRangeAdditionID"+additionTypeID).val();
                });
            }else{
                var additionTypeID = currentActivity.ActivityAdditions.NumericRangeAdditions.NumericRangeAddition.additionTypeID;
                currentActivity.ActivityAdditions.NumericRangeAdditions.NumericRangeAddition.value = $("#NumericRangeAdditionID"+additionTypeID).val();
            }
        }
        if(Array.isArray(currentActivity.Passengers.PassengerInfo)){
            currentActivity.Passengers.PassengerInfo.forEach(function(passenger){
                var seqNumber = passenger.seqNumber;
                passenger.firstName = $("#FirstNamePassenger"+seqNumber).val();
                passenger.lastName = $("#LastNamePassenger"+seqNumber).val();
                if(seqNumber == 1){
                    passenger.homePhone = $("#HomePhonePassenger"+seqNumber).val();
                    passenger.mobilePhone = $("#MobilePhonePassenger"+seqNumber).val();
                }
                if(passenger.PassengerAdditions){
                    if(passenger.PassengerAdditions.TextAdditions){
                        if(Array.isArray(passenger.PassengerAdditions.TextAdditions.TextAddition)){
                            passenger.PassengerAdditions.TextAdditions.TextAddition.forEach(function(TextAddition){
                                var additionTypeID = TextAddition.additionTypeID;
                                TextAddition.value = $("#P"+seqNumber+"TextAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = passenger.PassengerAdditions.TextAdditions.TextAddition.additionTypeID;
                            passenger.PassengerAdditions.TextAdditions.TextAddition.value = $("#P"+seqNumber+"TextAdditionID"+additionTypeID).val();
                        }
                    }
                    if(passenger.PassengerAdditions.TrueFalseAdditions){
                        if(Array.isArray(passenger.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition)){
                            passenger.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.forEach(function(TrueFalseAddition){
                                var additionTypeID = TrueFalseAddition.additionTypeID;
                                TrueFalseAddition.value = $("#P"+seqNumber+"TrueFalseAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = passenger.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.additionTypeID;
                            passenger.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.value = $("#P"+seqNumber+"TrueFalseAdditionID"+additionTypeID).val();
                        }
                    }
                    if(passenger.PassengerAdditions.NumericAdditions){
                        if(Array.isArray(passenger.PassengerAdditions.NumericAdditions.NumericAddition)){
                            passenger.PassengerAdditions.NumericAdditions.NumericAddition.forEach(function(NumericAddition){
                                var additionTypeID = NumericAddition.additionTypeID;
                                NumericAddition.value = $("#P"+seqNumber+"NumericAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = passenger.PassengerAdditions.NumericAdditions.NumericAddition.additionTypeID;
                            passenger.PassengerAdditions.NumericAdditions.NumericAddition.value = $("#P"+seqNumber+"NumericAdditionID"+additionTypeID).val();
                        }
                    }
                    if(passenger.PassengerAdditions.NumericRangeAdditions){
                        if(Array.isArray(passenger.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition)){
                            passenger.PassengerAdditions.NumericRangeAdditions.NumericRangeAdditionforEach(function(NumericRangeAddition){
                                var additionTypeID = NumericRangeAddition.additionTypeID;
                                NumericRangeAddition.value = $("#P"+seqNumber+"NumericRangeAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = passenger.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition.additionTypeID;
                            passenger.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition.value = $("#P"+seqNumber+"NumericRangeAdditionID"+additionTypeID).val();
                        }
                    }
                }
            });
        }else{
            var seqNumber = currentActivity.Passengers.PassengerInfo.seqNumber;
            currentActivity.Passengers.PassengerInfo.firstName = $("#FirstNamePassenger"+seqNumber).val();
            currentActivity.Passengers.PassengerInfo.lastName = $("#LastNamePassenger"+seqNumber).val();
            if(seqNumber == 1){
                currentActivity.Passengers.PassengerInfo.homePhone = $("#HomePhonePassenger"+seqNumber).val();
                currentActivity.Passengers.PassengerInfo.mobilePhone = $("#MobilePhonePassenger"+seqNumber).val();
            }
            if(currentActivity.Passengers.PassengerInfo.PassengerAdditions){
                    if(currentActivity.Passengers.PassengerInfo.PassengerAdditions.TextAdditions){
                        if(Array.isArray(currentActivity.Passengers.PassengerInfo.PassengerAdditions.TextAdditions.TextAddition)){
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.TextAdditions.TextAddition.forEach(function(TextAddition){
                                var additionTypeID = TextAddition.additionTypeID;
                                TextAddition.value = $("#P"+seqNumber+"TextAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = currentActivity.Passengers.PassengerInfo.PassengerAdditions.TextAdditions.TextAddition.additionTypeID;
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.TextAdditions.TextAddition.value = $("#P"+seqNumber+"TextAdditionID"+additionTypeID).val();
                        }
                    }
                    if(currentActivity.Passengers.PassengerInfo.PassengerAdditions.TrueFalseAdditions){
                        if(Array.isArray(currentActivity.Passengers.PassengerInfo.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition)){
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.forEach(function(TrueFalseAddition){
                                var additionTypeID = TrueFalseAddition.additionTypeID;
                                TrueFalseAddition.value = $("#P"+seqNumber+"TrueFalseAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = currentActivity.Passengers.PassengerInfo.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.additionTypeID;
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.TrueFalseAdditions.TrueFalseAddition.value = $("#P"+seqNumber+"TrueFalseAdditionID"+additionTypeID).val();
                        }
                    }
                    if(currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericAdditions){
                        if(Array.isArray(passenger.PassengerAdditions.NumericAdditions.NumericAddition)){
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericAdditions.NumericAddition.forEach(function(NumericAddition){
                                var additionTypeID = NumericAddition.additionTypeID;
                                NumericAddition.value = $("#P"+seqNumber+"NumericAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericAdditions.NumericAddition.additionTypeID;
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericAdditions.NumericAddition.value = $("#P"+seqNumber+"NumericAdditionID"+additionTypeID).val();
                        }
                    }
                    if(currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericRangeAdditions){
                        if(Array.isArray(currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition)){
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericRangeAdditions.NumericRangeAdditionforEach(function(NumericRangeAddition){
                                var additionTypeID = NumericRangeAddition.additionTypeID;
                                NumericRangeAddition.value = $("#P"+seqNumber+"NumericRangeAdditionID"+additionTypeID).val();
                            });
                        }else{
                            var additionTypeID = currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition.additionTypeID;
                            currentActivity.Passengers.PassengerInfo.PassengerAdditions.NumericRangeAdditions.NumericRangeAddition.value = $("#P"+seqNumber+"NumericRangeAdditionID"+additionTypeID).val();
                        }
                    }
                }
        }
        activitySaved = true;
        var activityId = parseInt($("#selectedActivityId").val());
        var activityOptionId = parseInt($('#activityOptions_'+activityId).val()[0]);
        currentActivity.activityDbId = activityId;
        currentActivity.activityOptionDbId = activityOptionId;
        console.log(currentActivity);
        var serializedActivity = JSON.stringify(currentActivity).replace(/'/g,"&apos;");
        $("#divActivityForms").append("<input type='hidden' activityid='"+activityId+"' name='activities[]' value='"+serializedActivity+"' />");
        $('#activity-modal').modal('toggle');
        loadPrices();
    });
});
function initMap() {
    var map = new google.maps.Map(document.getElementById('package-map'), {
      scrollwheel: false,
      zoom: 15
    });
@foreach($package->packageHotels as $key => $packageHotel)
<?php $hotel = App\Hotel::find($packageHotel->hotelId); ?>
    var hotelLocation{!!$key!!} = {lat: {!! $hotel->latitude !!}, lng: {!! $hotel->longitude !!}};
    var marker{!!$key!!} = new google.maps.Marker({
       map: map,
       position: hotelLocation{!!$key!!},
       title: '{!! $hotel->name !!}'
     });
@endforeach
  map.setCenter(hotelLocation0);
}
function loadHotelInfo(){
    var data = {
        'hotel-id' : $('#hotel-id').val(),
        'start-date' : $('#startDate').val(),
        'end-date' : $('#endDate').val(),
        'number-of-people' : $("#numberOfPeople").val()
    };
    showLoading(true);
    $.get("/search-hotel-by-id", data, function(data){
        showLoading(false);
        if(!data.success){
            alert(data.message);
            return;
        }
        var hotel = data.hotel;
        $("#hotel").val(JSON.stringify(hotel));
        roomTypes = hotel.RoomTypes.RoomType;
        if(Array.isArray(roomTypes)){
            $("#roomTypeId").empty().append("<option value></option>");
            roomTypes.forEach(function(roomType, i){
                $("#roomTypeId").append("<option value='"+roomType.hotelRoomTypeId+"'>"+roomType.name+"</option>");
            });
        }else{
            $("#roomTypeId").empty().append("<option value></option>");
            $("#roomTypeId").append("<option value='"+roomTypes.hotelRoomTypeId+"'>"+roomTypes.name+"</option>");
        }
    });
}
function loadPrices(){
    if($("#roomTypeId").val() == "") return;
    var activities = [];
    $("input[name='activities[]']").each(function(i){
        var activity = JSON.parse($(this).val());
        activity.dbId = $(this).attr("activityid");
        activities.push(activity);
    });    
    var data = {
        'hotel-id' : $('#hotel-id').val(),
        'start-date' : $('#startDate').val(),
        'end-date' : $('#endDate').val(),
        'roomType-id' : $('#roomTypeId').val(),
        'package-id' : $("#packageId").val(),
        'activities': JSON.stringify(activities)
    };
    showLoading(true);
    $.get("/get-hotel-price", data, function(data){
        showLoading(false);
        if(!data.success){
            alert(data.message);
            return;
        }
        $("#retailPrice").html("$ "+data.prices.retail).removeClass("text-muted");
        $("#retail").val(data.prices.retail);
        $("#trpzPrice").html("$ "+data.prices.trpz).removeClass("text-muted");
        $("#trpzPrice2").html("$ "+data.prices.trpz).removeClass("text-muted");
        $("#trpz").val(data.prices.trpz);
        $("#jetSetGoPrice").html("$ "+data.prices.jetSetGo).removeClass("text-muted");
        $("#jetSetGoPrice2").html("$ "+data.prices.jetSetGo).removeClass("text-muted");
        $("#jetSetGo").val(data.prices.jetSetGo);
        if(data.supplements.AtProperty.length > 0 || data.supplements.Addition.length > 0 || data.supplements.Included.length > 0 || data.boardBases.length > 0 ){ 
            $("#divAdditionalFees").show();
            if(data.supplements.AtProperty.length > 0 || data.supplements.Addition.length > 0 || data.supplements.Included.length > 0){
                $("#tbladditionalFees").show();
            }else{
                $("#tbladditionalFees").hide();
            }
        }else{
            $("#divAdditionalFees").hide();
        }
        
        $("#tbladditionalFees tbody").empty();
        $("#divSupplements").empty();
        data.supplements.AtProperty.forEach(function(sup, i){
            $("#tbladditionalFees tbody").append("<tr><td>"+sup.suppName+"</td><td>"+sup.publishPrice+"</td><td>At Property</td></tr>");
            $("#divSupplements").append("<input type='hidden' name='supplements[]' value='"+JSON.stringify(sup)+"'>");
        });
        data.supplements.Addition.forEach(function(sup, i){
            $("#tbladditionalFees tbody").append("<tr><td>"+sup.suppName+"</td><td>"+sup.publishPrice+"</td><td>Included in price</td></tr>");
            $("#divSupplements").append("<input type='hidden' name='supplements[]' value='"+JSON.stringify(sup)+"'>");
        });
        data.supplements.Included.forEach(function(sup, i){
            $("#tbladditionalFees tbody").append("<tr><td>"+sup.suppName+"</td><td>"+(parseFloat(sup.publishPrice) == 0 ? "-" : sup.publishPrice) +"</td><td>Included in price</td></tr>");
            $("#divSupplements").append("<input type='hidden' name='supplements[]' value='"+JSON.stringify(sup)+"'>");
        });
         $("#ulBoardBases").empty();
         $("#divBoardBases").empty();
        data.boardBases.forEach(function(bb, i){
            $("#ulBoardBases").append("<li id='"+bb.bbId+"'>"+bb.bbName+" is included</li>");
            $("#divBoardBases").append("<input type='hidden' name='boardBases[]' value='"+JSON.stringify(bb)+"'>");
        });
    });
}
function checkCancellationPolicy(button){
    var formValid = $("#package-form")[0].checkValidity();
    if(!formValid){
        alert("Please select Travel Dates and Room Type");
        return;
    }
    $("#priceType").val(button);
    var data = {
        'hotel-id' : $('#hotel-id').val(),
        'start-date' : $('#startDate').val(),
        'end-date' : $('#endDate').val(),
        'roomType-id' : $('#roomTypeId').val()
    };
    showLoading(true);
    $.get("/get-hotel-cancellation-policy", data, function(data){
        showLoading(false);
        $("#divCancellationPolicy").html(data.HotelPolicy);
        $("#policy-modal").modal();
    });
}
function ActivityPreBook(){
    var activityId =  $("#selectedActivityId").val();
    var date = $("#activityDate").val();
    var optionId = $('#activityOptions_'+activityId).val();
    var data = {
        activityId : activityId,
        date : date,
        optionId : optionId,
        numberOfPeople : $("#numberOfPeople").val()
    };
    $("#divActivityForm").empty();
    $("#divPassengersForm").empty();
    $("#ulCancellation").empty();
    showLoading(true);
    $.get("/activity-prebook", data, function(data){
        showLoading(false);
        if(!data.success){
            alert(data.message);
            $("#activity-modal").modal('toggle');
            return;
        }
        var activityInfo = data.response.ActivitiesInfo.ActivityInfo;
        currentActivity = activityInfo;
        var textAdditions = activityInfo.ActivityAdditions.TextAdditions ? activityInfo.ActivityAdditions.TextAdditions.TextAddition : [];
        var trueFalseAdditions = activityInfo.ActivityAdditions.TrueFalseAdditions ? activityInfo.ActivityAdditions.TrueFalseAdditions.TrueFalseAddition : [];
        var numericAdditions = activityInfo.ActivityAdditions.NumericAdditions ? activityInfo.ActivityAdditions.NumericAdditions.NumericAddition : [];
        var numericRangeAdditions = activityInfo.ActivityAdditions.NumericRangeAdditions ? activityInfo.ActivityAdditions.NumericRangeAdditions.NumericRangeAddition : [];
        if(!Array.isArray(textAdditions)){
            textAdditions = [textAdditions];
        }
        if(!Array.isArray(trueFalseAdditions)){
            trueFalseAdditions = [trueFalseAdditions];
        }
        if(!Array.isArray(numericAdditions)){
            numericAdditions = [numericAdditions];
        }
        if(!Array.isArray(numericRangeAdditions)){
            numericRangeAdditions = [numericRangeAdditions];
        }
        textAdditions.forEach(function(addition, i){
            $("#divActivityForm").append("<div class='col-md-6'><label>"+addition.additionType+"</label><textarea class='form-control' id='TextAdditionID"+addition.additionTypeID+"'></textarea></div>");
        });
        trueFalseAdditions.forEach(function(addition, i){
            $("#divActivityForm").append("<div class='col-md-6'><label>"+addition.additionType+"</label><select class='form-control' id='TrueFalseAdditionID"+addition.additionTypeID+"'><option value='True'>Yes</option><option value='False'>No</option></select></div>");
        });
        numericAdditions.forEach(function(addition, i){
            $("#divActivityForm").append("<div class='col-md-6'><label>"+addition.additionType+"</label><input type='number' class='form-control' id='NumericAdditionID"+addition.additionTypeID+"'/></div>");
        });
        numericRangeAdditions.forEach(function(addition, i){
            $("#divActivityForm").append("<div class='col-md-6'><label>"+addition.additionType+"</label><input type='number' min='"+addition.minValue+"' max='"+addition.maxValue+"' class='form-control' id='NumericRangeAdditionID"+addition.additionTypeID+"'/></div>");
        });
        var passengers = activityInfo.Passengers.PassengerInfo;
        if(!Array.isArray(passengers)){
            passengers = [passengers];
        }
        passengers.forEach(function(p, i){
            var additions = "";
            if(p.PassengerAdditions){
                var passengerAdditions = p.PassengerAdditions;
                var textAdditions = passengerAdditions.TextAdditions ? passengerAdditions.TextAdditions.TextAddition : [];
                var trueFalseAdditions = passengerAdditions.TrueFalseAdditions ? passengerAdditions.TrueFalseAdditions.TrueFalseAddition : [];
                var numericAdditions = passengerAdditions.NumericAdditions ? passengerAdditions.NumericAdditions.NumericAddition : [];
                var numericRangeAdditions = passengerAdditions.NumericRangeAdditions ? passengerAdditions.NumericRangeAdditions.NumericRangeAddition : [];
                if(!Array.isArray(textAdditions)){
                    textAdditions = [textAdditions];
                }
                if(!Array.isArray(trueFalseAdditions)){
                    trueFalseAdditions = [trueFalseAdditions];
                }
                if(!Array.isArray(numericAdditions)){
                    numericAdditions = [numericAdditions];
                }
                if(!Array.isArray(numericRangeAdditions)){
                    numericRangeAdditions = [numericRangeAdditions];
                }
                textAdditions.forEach(function(addition, i){
                    additions += "<div class='col-md-6'><label>"+addition.additionType+"</label><textarea class='form-control' id='P"+p.seqNumber+"TextAdditionID"+addition.additionTypeID+"'></textarea></div>";
                });
                trueFalseAdditions.forEach(function(addition, i){
                    additions += "<div class='col-md-6'><label>"+addition.additionType+"</label><select class='form-control' id='P"+p.seqNumber+"TrueFalseAdditionID"+addition.additionTypeID+"'><option value='True'>Yes</option><option value='False'>No</option></select></div>";
                });
                numericAdditions.forEach(function(addition, i){
                    additions += "<div class='col-md-6'><label>"+addition.additionType+"</label><input type='number' class='form-control' id='P"+p.seqNumber+"NumericAdditionID"+addition.additionTypeID+"'/></div>";
                });
                numericRangeAdditions.forEach(function(addition, i){
                    additions += "<div class='col-md-6'><label>"+addition.additionType+"</label><input type='number' min='"+addition.minValue+"' max='"+addition.maxValue+"' class='form-control' id='P"+p.seqNumber+"NumericRangeAdditionID"+addition.additionTypeID+"'/></div>";
                });
            }
            $("#divPassengersForm").append(
            "<div class='col-md-12'><h4>Passenger "+p.seqNumber+"</h4>"+
                "<div class='col-md-6'><label>First Name</label><input type='text' class='form-control' id='FirstNamePassenger"+p.seqNumber+"'/></div>"+
                "<div class='col-md-6'><label>Last Name</label><input type='text' class='form-control' id='LastNamePassenger"+p.seqNumber+"'/></div>" + 
                (p.seqNumber == 1 ?
                "<div class='col-md-6'><label>Home Phone</label><input type='text' class='form-control' id='HomePhonePassenger"+p.seqNumber+"''/></div>" +
                "<div class='col-md-6'><label>Mobile Phone</label><input type='text' class='form-control' id='MobilePhonePassenger"+p.seqNumber+"'/></div>" 
                : "" ) + additions +
            "</div>");
        });
        var cancellation = activityInfo.CancellationPolicy.CancellationPenalties.CancellationPenalty;
        if(!Array.isArray(cancellation)){
            cancellation = [cancellation];
        }
        cancellation.forEach(function(c, i){
            if(c.Deadline.unitsFromCheckIn == 0){
                $("#ulCancellation").append("<li>After the cancellation deadline the penalty is "+c.Penalty.value+" "+c.Penalty.basisType+".</li>");
            }else{
                $("#ulCancellation").append("<li>Cancellation deadline is "+c.Deadline.unitsFromCheckIn+" "+c.Deadline.offsetUnit+" from check-in. Cancellation penalty is "+c.Penalty.value+" "+c.Penalty.basisType+".</li>");
            }
        });
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB05Vggn0A3-DwI7AwGWEe2ea5E5K1ZYs&callback=initMap" async defer></script>
@endsection