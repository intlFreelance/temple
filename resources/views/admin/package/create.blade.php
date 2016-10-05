@extends('layouts.app')

@section('content')
<link href="/css/create-package.css" rel="stylesheet">
<link href="/css/textAngular.css" rel="stylesheet">
<div class="container" ng-controller="PackageController">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a Package</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Choose Category</label>
                                        <select ng-model="categoryId" class="form-control">   
                                            <option value="">Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Package Name</label>
                                        <input ng-model="name" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Number of Days</label>
                                        <input ng-model="numberOfDays" type="number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input 
                                            type="text" 
                                            uib-datepicker-popup="MM/dd/yyyy"
                                            is-open="dateStart.open" 
                                            ng-focus="dateStart.open=true" 
                                            ng-model="startDate" 
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input 
                                            type="text" 
                                            uib-datepicker-popup="MM/dd/yyyy"
                                            is-open="dateEnd.open" 
                                            ng-focus="dateEnd.open=true" 
                                            ng-model="endDate" 
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Number of People</label>
                                        <input ng-model="numberOfPeople" type="number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Deal Ends</label>
                                        <input 
                                            type="text" 
                                            uib-datepicker-popup="MM/dd/yyyy hh:mm a"
                                            is-open="dealEnd.open" 
                                            ng-focus="dealEnd.open=true" 
                                            ng-model="dealEnd" 
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Image</label>
                             <input type="file" ngf-select ng-model="imgUpload" name="file"    
                            accept="image/*" ngf-max-size="2MB">
                             <img ngf-thumbnail="imgUpload" class="thumb"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description</label>
                                <div text-angular ng-model="description"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Markup %</label>
                                <input ng-model="markup" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-10"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 id="destination-text" class="pull-left">Select a Destination</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div id="destination-menu" class="col-sm-12">
                            <a
                                ng-click="getDestinationByBreadcrumb(null, 0)">
                                Continent 
                            </a>
                            &nbsp;>&nbsp;
                            <span ng-repeat="segment in destinationSegments">
                                <a ng-click="getDestinationByBreadcrumb(segment, $index)">
                                    <%segment.name%>
                                </a>
                                &nbsp;>&nbsp;
                            </span>
                            <%city%>
                        </div>
                    </div>
                    <div class="row">
                        <div id="destination-menu" class="col-sm-2">
                            <div 
                                class="btn-group"
                                uib-dropdown>
                                <button
                                    class="btn btn-default"
                                    type="button"
                                    uib-dropdown-toggle
                                    ng-disabled="disabled">
                                    <%destinationSegments.length ? 
                                        destinationSegments[destinationSegments.length - 1].name : 
                                        "Select Continent"%> 
                                    <span class="caret"></span>
                                </button>
                                <ul
                                    class="dropdown-menu"
                                    id="destination-menu-list">
                                    <li ng-repeat="destination in destinations">
                                        <a ng-click="getDestinations(destination)">
                                            <%destination.name%>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-10"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p ng-show="missingDates" class="alert alert-danger">Please Choose valid start and end dates</p>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Add Hotels</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div id="added-hotels" class="col-sm-12">
                                <p ng-repeat="hotel in addedHotels"><%hotel.name%>&nbsp;<span ng-click="removeHotel(hotel)" class="glyphicon glyphicon-remove"</p>
                            </div>
                        </div>
                        <div class="row">
                            <div id="hotels" class="col-sm-12">
                                <!--<p ng-show="!hotels">Select a destination first.</p>
                                <p ng-show="hotels && hotels.length === 0">No hotels available at this destination.</p>-->
                                <div
                                    ng-repeat="hotel in hotels"
                                    class="hotel-panel panel panel-default pull-left">
                                    <div class="panel-heading truncate"><%hotel.name%></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <img width="100" height:"100" src="<%hotel.thumb%>"/>
                                            </div>
                                            <div class="col-sm-7">
                                                <%hotel.minAverPrice + ' ' + hotel.currency%><br>
                                                <button ng-click="addHotel(hotel);" class="select-hotel btn btn-default">Add to Package</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Add Activities</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div id="added-activities" class="col-sm-12">
                                <p ng-repeat="activity in addedActivities"><%activity.name%>&nbsp;<span ng-click="removeActivity(activity)" class="glyphicon glyphicon-remove"</p>
                            </div>
                        </div>
                        <div class="row">
                            <div id="activityCategories" class="col-sm-12">
                                <div ng-repeat="category in activityCategories">
                                    <a ng-click="selectActivityCategory(category)"><%category.categoryName%></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="activities" class="col-sm-12">
                                <div
                                    ng-repeat="activity in activities"
                                    class="activity-panel panel panel-default pull-left">
                                     <div class="panel-heading truncate"><%activity.name%></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <img width="100" height:"100" src="<%activity.thumbURL%>"/>
                                            </div>
                                            <div class="col-sm-7">
                                                Adult: <%activity.ActivityOptions.ActivityOption.Availabilities.Availability.adultPrice%>&nbsp;<%activity.currency%><br>
                                                Child: <%activity.ActivityOptions.ActivityOption.Availabilities.Availability.childPrice%>&nbsp;<%activity.currency%><br>
                                                <span ng-if="isArray(activity.ActivityOptions.ActivityOption)">
                                                    Unit: <%activity.ActivityOptions.ActivityOption[0].Availabilities.Availability.unitPrice%>&nbsp;<%activity.currency%><br>
                                                </span>
                                                <button ng-click="addActivity(activity);" class="btn btn-default">Add to Package</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p><%activity.description.substr(0, 120) + '...'%></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button ng-click="submit(imgUpload)" class="btn btn-primary">Create Package</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
