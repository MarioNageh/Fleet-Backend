<?php

namespace Tests\Unit;

use App\Models\Bus;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\TripPath;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Utils\ConstantsCity;
use Tests\Utils\Printer;


class ReservationTest extends TestCase
{
    private $token;
    private $bus;
    private $trip;
    private $users = [];
    private $tested_date='2022-05-25';

    protected function setUp(): void
    {
        parent::setUp();
        Printer::printToConsole("-------------------------------------------------------", "Start");
        $response = $this->postJson('/api/signUp', ['mail' => 'mm@mm.com', 'password' => '123456', 'name' => 'test_user']);
        Printer::printToConsole($response->getContent(), "setUp");
        // Login With User
        $response = $this->postJson('/api/login', ['password' => '123456', 'email' => 'mm@mm.com']);
        $this->token = $response->json("Token");


        /*
         * Make Dummy Trip From Cairo to Asyut [ Cairo - Giza - AlFayyum - Alminya - Asyut]
         * Bus Have 3 Places Only In It's
         */

        #region Create New Bus With 3 Places

        //Create Bus With 3 Places Only
        $this->bus = Bus::create([
            'DriverName' => 'Mario',
            'VehicleRegistrationPlate' => '199-AQW',
            'AvailableSeats' => '3',
        ]);
        Printer::printToConsole("Created Bus {$this->bus}", "Bus");
        #endregion

        #region Create New Trip [Cairo-Asyut]

        //Create Bus With 3 Places Only
        $this->trip = Trip::create([
            'TripName' => 'Cairo-Asyut',
            'IdBus' => $this->bus->IdBus,
        ]);
        Printer::printToConsole("Created Trip {$this->trip}", "Trip");
        #endregion

        #region Path For This Trip
        // 1 -> 2 -> 4 -> 5 -> 6
        $cities_ids = [1, 2, 4, 5, 6];
        //  'IdCity', 'IdTrip', 'NextIdTripPath', 'Order'
        $lastTripPath = null;
        $newTripPath = null;
        $order = 1;
        foreach ($cities_ids as $id) {
            //$cities_ids
            $lastTripPath = $newTripPath;
            $newTripPath = TripPath::create([
                'IdTrip' => $this->trip->IdTrip,
                'IdCity' => $id,
                'NextIdTripPath' => null,
                'Order' => $order,
            ]);
            if ($lastTripPath != null) {
                $lastTripPath->NextIdTripPath = $newTripPath->IdTripPath;
                $lastTripPath->save();
            }
            $order += 1;
        }


        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->getJson("/api/tripPath/{$this->trip->IdTrip}", $headers);
        Printer::printToConsole("Created Trip Path {$response->json()["Format"]}", "TripPath");
        #endregion


        #region Create Working Hour For This Trip As Every Day At 8 Pm To 10 PM
        // 0 SunDay  ..... 6 saturday
        $workingHours=[];
        for ($i = 0; $i <= 6; $i++) {
          array_push($workingHours,WorkingHour::create([
              'IdTrip' => $this->trip->IdTrip, 'DayOfWeek' => $i, 'TripStatringTime' => '20:00:00', 'TripEndingTime' => '22:00:00'
          ]));
        }

        Printer::printToConsole("Working Hour For This Trip Every Day Created", "Working Hour");
        #endregion


        #region Create 10 User

        $user_count = 10;
        for ($i = 0; $i < $user_count; $i++) {
            array_push($this->users, User::create([
                'name' => "test_user$i",
                'password' => Hash::make('123456'),
                'email' => "test_mail$i@mario.com",
            ]));
        }

        Printer::printToConsole("$user_count User Created", "Create Users");
        #endregion


        #region Query The Trip From Cairo To Asyut We Should Found  one Trip Available with 3 Places Available
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::Asyut.'/'.$this->tested_date, $headers);
        Printer::printToConsole($response->getContent(), "Reservation");
        // SeatsData":{"1":true,"2":true,"3":true}
        #endregion


        //[ Cairo - Giza - AlFayyum - Alminya - Asyut]
        #region Add 3 Reservation
        // 1- From Cairo  to Giza
        // 2- From Cairo to AlFayyum
        // 3- from Cairo To Alminya


        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $counter=0;
        {
            $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::Giza.'/'.$this->tested_date, $headers);
            $response=$response->decodeResponseJson()["Data"][0];

            Reservation::create([
                'IdBus' => $this->trip->IdBus,
                'IdTrip' => $this->trip->IdTrip,
                'IdPathTripStart' => $response['IdStartPath'],
                'IdPathTripEnd' => $response['IdEndPath'],
                'BusLocation' => "#1",
                'IdWorkingHour' => $response['IdWorkingHour'],
                'ReservationDate' => $this->tested_date,
                'IdUser' => $this->users[$counter%$user_count]->IdUser
            ]);
            $counter+=1;
        }
        {
            $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::Alfayum.'/'.$this->tested_date, $headers);
            $response=$response->decodeResponseJson()["Data"][0];

            Reservation::create([
                'IdBus' => $this->trip->IdBus,
                'IdTrip' => $this->trip->IdTrip,
                'IdPathTripStart' => $response['IdStartPath'],
                'IdPathTripEnd' => $response['IdEndPath'],
                'BusLocation' => "#2",
                'IdWorkingHour' => $response['IdWorkingHour'],
                'ReservationDate' => $this->tested_date,
                'IdUser' => $this->users[$counter%$user_count]->IdUser
            ]);
            $counter+=1;
        }
        {
            $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::AlMinya.'/'.$this->tested_date, $headers);
            $response=$response->decodeResponseJson()["Data"][0];

            Reservation::create([
                'IdBus' => $this->trip->IdBus,
                'IdTrip' => $this->trip->IdTrip,
                'IdPathTripStart' => $response['IdStartPath'],
                'IdPathTripEnd' => $response['IdEndPath'],
                'BusLocation' => "#3",
                'IdWorkingHour' => $response['IdWorkingHour'],
                'ReservationDate' => $this->tested_date,
                'IdUser' => $this->users[$counter%$user_count]->IdUser
            ]);
            $counter+=1;
        }

        // Here We Found "SeatsData":{"1":false,"2":false,"3":false}
        $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::Giza.'/'.$this->tested_date, $headers);
        Printer::printToConsole("{$response->getContent()}", "Test Data");


        #endregion
        Printer::printToConsole("-------------------------------------------------------", "End");

    }

    //This Test Must return All Seats = False
    // "SeatsData":{"1":false,"2":false,"3":false}
    public function test_cairo_alminya()
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::AlMinya.'/'.$this->tested_date, $headers);
        $response=$response->decodeResponseJson()["Data"][0];
        $counter_of_seats_available=3;
        foreach ($response["SeatsData"] as $key=>$val){
            if(!$val)
                $counter_of_seats_available-=1;
        }


        $this->assertEquals(
            $counter_of_seats_available,
            0,
            "The Count Of available seats must be 0"
        );
    }



    // "SeatsData":{"1":true,"2":false,"3":false}
    // because the first user in cairo:giza will leave at giza
    public function test_giza_alfayyum()
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get("/api/availableTrip/".ConstantsCity::Giza.'/'.ConstantsCity::Alfayum.'/'.$this->tested_date, $headers);
        $response=$response->decodeResponseJson()["Data"][0];
        $counter_of_seats_available=3;
        foreach ($response["SeatsData"] as $key=>$val){
            if(!$val)
                $counter_of_seats_available-=1;
        }


        $this->assertEquals(
            $counter_of_seats_available,
            1,
            "The Count Of available seats must be 1"
        );

        $this->assertEquals(
            $response["SeatsData"]["1"], true,
            "The Seat available is number 1"
        );
    }




    //This Test Must return All Seats = False
    // "SeatsData":{"1":false,"2":false,"3":false}
    public function test_cairo_Asyut()
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $this->get("/api/availableTrip/".ConstantsCity::Cairo.'/'.ConstantsCity::Asyut.'/'.$this->tested_date, $headers);
        $response=$response->decodeResponseJson()["Data"][0];
        $counter_of_seats_available=3;
        foreach ($response["SeatsData"] as $key=>$val){
            if(!$val)
                $counter_of_seats_available-=1;
        }


        $this->assertEquals(
            $counter_of_seats_available,
            0,
            "The Count Of available seats must be 0"
        );

    }
}
