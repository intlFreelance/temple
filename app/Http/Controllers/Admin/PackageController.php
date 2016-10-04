<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;
use App\Package;
use App\PackageHotel;
use App\PackageActivity;
use App\Destination;
use App\TouricoHotel;
use App\TouricoActivity;
use App\TouricoDestination;

class PackageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->currentDate = Carbon::now()->format('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      echo "Index";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package.create');
    }

    private function getAllDestinations()
    {
        $destination_api = new TouricoDestination;
        $data = [
            'Destination'=>[
                'Continent'=>null,
                'StatusDate'=>'2016-09-10'
            ]
        ];
        return $destination_api->SearchDestinations($data);
    }

    public function jsonDestinations($continent=null,$country=null,$state=null)
    {
        if($continent == null){
          $destinations = Destination::where('elementType','8')->get();
        }else{
          if($country == null){
            $destinations = Destination::where('elementType','7')->where('parent_id',$continent)->get();
          }else{
            if($state == null){
              $destinations = Destination::where('elementType','6')->where('parent_id',$country)->get();
            }else{
              $destinations = Destination::where('elementType','3')->where('parent_id',$state)->get();
            }
          }
        }
        return response()->json($destinations);
    }

    public function updateDestinations()
    {
        Destination::truncate();
        echo "Destinations Truncateded...<br>";
        $destination_api = new TouricoDestination;
        $data = [
            'Destination'=>[
                'Continent'=>null,
                'StatusDate'=>date('Y-m-d',strtotime('-2 years'))
            ]
        ];
        $destination_response = $destination_api->SearchDestinations($data);
        $data = $this->parseDestinations($destination_response);
        echo "Destinations Successfully Updated";
    }

    private function parseDestinations($destination_response){
      foreach($destination_response->DestinationResult->Continent as $continent){
        $continent_destination = Destination::firstOrNew([
          'name'          => $continent->name,
          'destinationId' => $continent->destinationId,
          'provider'      => ($continent->provider!="")?$continent->provider:'0',
          'elementType'   => $continent->elementType,
        ]);
        $continent_destination->save();
        if(isset($continent->Country)){

          if(count($continent->Country) > 1){
            $countries = $continent->Country;
          }else{
            $countries = [$continent->Country];
          }

          foreach($countries as $country){
            $country_destination = Destination::firstOrNew([
              'name'                  => $country->name,
              'destinationId'         => $country->destinationId,
              'provider'              => ($country->provider!="")?$country->provider:'0',
              'elementType'           => $country->elementType,
              'parent_destinationId'  => $continent_destination->destinationId,
              'parent_id'             => $continent_destination->id,
            ]);
            $country_destination->save();
            if(isset($country->State)){

              if(count($country->State) > 1){
                $states = $country->State;
              }else{
                $states = [$country->State];
              }

              foreach($states as $state){
                $state_destination = Destination::firstOrNew([
                  'name'                  => ($state->name!="")?$state->name:'N/A',
                  'destinationId'         => $state->destinationId,
                  'provider'              => ($state->provider!="")?$state->provider:'0',
                  'elementType'           => $state->elementType,
                  'parent_destinationId'  => $country_destination->destinationId,
                  'parent_id'             => $country_destination->id,
                ]);
                $state_destination->save();
                if(isset($state->City)){

                  if(count($state->City) > 1){
                    $cities = $state->City;
                  }else{
                    $cities = [$state->City];
                  }

                  foreach($cities as $city){
                    $city_destination = Destination::firstOrNew([
                      'name'                  => $city->name,
                      'destinationId'         => $city->destinationId,
                      'provider'              => ($city->provider!="")?$city->provider:'0',
                      'destinationCode'       => $city->destinationCode,
                      'elementType'           => $city->elementType,
                      'cityLatitude'          => $city->cityLatitude,
                      'cityLongitude'         => $city->cityLongitude,
                      'parent_destinationId'  => $state_destination->destinationId,
                      'parent_id'             => $state_destination->id,
                    ]);
                    $city_destination->save();
                  }
                }
              }
            }
          }
        }
      }
    }

    public function getHotels() {
        $hotel_api = new TouricoHotel;
        $data = [
            'request'=>[
                'Destination'=>$this->request->query('destination'),
                'CheckIn'=>Carbon::parse($this->request->query('start-date'))->format('Y-m-d'),
                'CheckOut'=>Carbon::parse($this->request->query('end-date'))->format('Y-m-d'),
                'RoomsInformation'=>[
                    'RoomInfo'=>[
                    'AdultNum'=>'2',
                    'ChildNum'=>'0'
                    ]
                ],
                'MaxPrice'=>'0',
                'StarLevel'=>'0',
                'AvailableOnly'=>'true',
                'PropertyType'=>'NotSet',
                'ExactDestination'=>'true'
            ]
        ];
        $hotels = $hotel_api->SearchHotels($data);
        if(isset($hotels->Hotel) && !is_array($hotels->Hotel))
        {
            $hotels = ['Hotel'=>[$hotels->Hotel]];
        }
        return response()->json($hotels);
    }

    public function getActivities() {
        $activity_api = new TouricoActivity;
        $data = [
            'SearchRequest'=>[
                'fromDate'=>Carbon::parse($this->request->query('start-date'))->format('Y-m-d'),
                'toDate'=>Carbon::parse($this->request->query('end-date'))->format('Y-m-d'),
                'destinationIds'=>[
                    'int'=>$this->request->query('destination-id')
                ]
            ]
        ];
        $activities = $activity_api->SearchActivityByDestinationIds($data);
        if(isset($activities->Category) && !is_array($activities->Category))
        {
            $activities = ['Category'=>[$activities->Category]];
        }
        return response()->json($activities);
    }

    /**
     * Save a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxSavePackage()
    {
        $package = new Package;
        $package->name = $this->request->name;
        $package->description = $this->request->description;
        $package->numberOfDays = $this->request->numberOfDays;
        $package->startDate = $this->request->startDate;
        $package->endDate = $this->request->endDate;
        $package->markup = $this->request->markup;
        $package->save();

        $hotelIds = [];
        forEach($this->request->hotelIds as $hotelId) {
            $hotelIds[] = new PackageHotel(['hotelId'=>$hotelId]);
        }

        $activityIds = [];
        forEach($this->request->activityIds as $activityId) {
            $activityIds[] = new PackageActivity(['activityId'=>$activityId]);
        }

        $package->packageHotels()->saveMany($hotelIds);
        $package->packageActivities()->saveMany($activityIds);
        return response()->json($package->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
