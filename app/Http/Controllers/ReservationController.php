<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\TripPath;
use App\Models\User;
use App\Util\BaseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\Mime\Header\get;

class ReservationController extends Controller
{
    public function getAllReservations()
    {
        return (new BaseMessage("Successful Loaded Data", "تم تحميل البيانات بنجاح", 200, Reservation::where('IdUser', Auth::user()->id)->get()))->toJson();
    }

    public function availableTrip($idStartCity, $idEndCity, $date)
    {
        // First Search For Paths Include Start City
        // Then We Will Check The End City In The Same Path Of Trip
        $tripPaths = TripPath::where(['IdCity' => $idStartCity])->get();

        // 0 -> Sun Day   , 1 -> mon ......
        $day_of_week_number = date('w', strtotime($date));

        // Trips
        $valid_trips = [];

        // Here We Will Check All Possible Paths then Check If The End City In The Same Trip
        /*
         * For Example   Cairo ---> Mina  ----> Aswan
         * here we will check if cairo and aswan in the same Trip
         * Every Trip Has Working Hour , and Bus
         * Every Bus Has available seats
         * this algorithm return all available Trips Can Join and All Seats By Number That can front end Show
         */
        foreach ($tripPaths as $path) {
            // Check For Insure That Start : End In Same Trip
            if ($path->hasEndPointInThisPath($idEndCity)) {
                // Now This Is Valid Trip
                // Check Times
                $trip = $path->trip;
                $bus = $trip->bus;

                // We Use The Idea Of Order To Simply can Get Intersection Points Of Users
                // Cairo ---> Mina  ----> Aswan
                //  1    --->  2    ---->  3

                $startPointPath=TripPath::where(['IdTrip' => $path->IdTrip, 'IdCity' => $idStartCity])->first();
                $endPointPath=TripPath::where(['IdTrip' => $path->IdTrip, 'IdCity' => $idEndCity])->first();
                $orderOfStartPoint = $startPointPath->Order;
                $orderOfEndPoint = $endPointPath->Order;

                foreach ($trip->workingHours as $workingHour) {
                    // get available_sates For Bus
                    $available_sates = $bus->AvailableSeats;

                    // Check For Trip Has Working Time In This Day Of Week Or Not
                    if (!$workingHour->hasWorkingIsThisDay($day_of_week_number)) {
                        continue;
                    }

                    // here we get all reservation of this trip on this selected day and working time
                    $reservation = $path->trip->reservationTrip($workingHour->IdWorkingHour, $date);


                    // Init Bus Seats
                    // Here We Assume That All Bus Seats Are available
                    $bus_seats = [];
                    for ($i = 1; $i <= $available_sates; ++$i) {
                        $bus_seats[$i] = true;
                    }
                    //////////////////////////////////////////////////

                    // Get Intersection Points With User ($idStartCity,$idEndCity) For ALL $reservation
                    // If Found We decrease available_sates by one and mark this seat is not available
                    foreach ($reservation as $rev) {
                        $bus_location_number = str_replace('#', '', $rev->BusLocation);

                        $orderOfStartPointRev = $rev->startPath->Order;

                        $orderOfEndPointRev = $rev->endPath->Order;

                        $intersectionStartOrder = max($orderOfStartPoint, $orderOfStartPointRev);
                        $intersectionEndOrder = min($orderOfEndPoint, $orderOfEndPointRev);

                        if ($intersectionEndOrder > $intersectionStartOrder) {
                            $available_sates -= 1;
                            $bus_seats[$bus_location_number] = false;
                        }

                    }

                    // Finally Update This Working Hour By Bus Seats State
                    $data = array("IdTrip" => $path->trip->IdTrip, "SeatsData" => $bus_seats,
                        "Reservation_Date" => $date, "TimeStartTime" => $workingHour->TripStatringTime, "IdWorkingHour" => $workingHour->IdWorkingHour
                        ,"IdStartPath"=>$startPointPath->IdTripPath,"IdEndPath"=>$endPointPath->IdTripPath,

                        "IdStartCity"=>$idStartCity,"IdEndCity"=>$idEndCity
                    );
                    array_push($valid_trips, $data);

                }


            }

        }

        return (new BaseMessage("Successful Loaded Available Trip", "تم تحميل بيانات الرحلات بنجاح", 200,$valid_trips))->toJson(true);
    }




    public function reservationNewTrip(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'IdTrip' => 'required',
            'IdWorkingHour' => 'required',
            'IdStartPath' => 'required',
            'IdEndPath' => 'required',
            'Date'=>'required',
            'SeatNumber'=>'required',
            'IdStartCity'=>'required',
            'IdEndCity'=>'required',
        ]);
        $idTrip=$request->input('IdTrip');

        if ($validated->fails()) {

            return BaseMessage::someThingWrong();
        }

        $reservation = null;
        try {
            $available_trips=($this->availableTrip($request->input('IdStartCity'),$request->input('IdEndCity'),$request->input('Date')));
            $available_trips= json_decode($available_trips->getContent(),true);

            $seat_number=$request->input('SeatNumber');

            if(count($available_trips['Data'])==0)
                return (new BaseMessage("This Trip Not have Working Hour Now", "هذة الرحلة غير متاحة حاليا", 405))->toJson();

            foreach ($available_trips["Data"] as $tp){

                if($tp["IdTrip"]==$request->input('IdTrip') && $tp["IdWorkingHour"]==$request->input('IdWorkingHour')&& $tp["IdWorkingHour"]==$request->input('IdWorkingHour')){
                    if(!array_key_exists($seat_number,$tp["SeatsData"]))
                        return (new BaseMessage("Wrong Seat Number", "خطا في رقم المقعد", 405))->toJson();

                    if($tp["SeatsData"][$seat_number]){
                        $trip=Trip::where('IdTrip',$idTrip)->first();
                        //'IdBus', 'IdTrip', 'IdPathTripStart', 'IdPathTripEnd', 'BusLocation','IdWorkingHour','ReservationDate'
                        $reservation=  Reservation::create([
                          'IdBus' => $trip->IdBus,
                          'IdTrip' => $idTrip,
                          'IdPathTripStart' => $request->input('IdStartPath'),
                          'IdPathTripEnd' => $request->input('IdEndPath'),
                          'BusLocation' => "#{$request->input('SeatNumber')}",
                          'IdWorkingHour' => $request->input('IdWorkingHour'),
                          'ReservationDate' => $request->input('Date'),
                          'IdUser'=>Auth::id()

                      ]);
                    }else{
                        return (new BaseMessage("Seat Already Booked up", "هذا المقعد محجوز مسبقا", 405))->toJson();
                    }
                    break;
                }
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            return BaseMessage::someThingWrong();
        }


        if ($reservation)
            return (new BaseMessage("Successful Reservation", "تم التسجيل في الرحلة بنجاح", 201))->toJson();
        else
            return BaseMessage::someThingWrong();
    }
}
