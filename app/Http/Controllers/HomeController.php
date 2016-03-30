<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Repositories\VehicleRepositoryEloquent;
use App\Repositories\EntryRepositoryEloquent;
use App\Repositories\TripRepositoryEloquent;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(VehicleRepositoryEloquent $vehicleRepo, EntryRepositoryEloquent $entryRepo, TripRepositoryEloquent $tripRepo)
    {
        $vehiclesStatistics = $vehicleRepo->getVehiclesStatistics();
        $lastsServiceCostStatistics = $entryRepo->getLastsServiceCostStatistics();
        $servicesStatistics = $entryRepo->getServicesStatistics();
        $tripsStatistics = $tripRepo->getTripsStatistics();
        $lastsFuelCostStatistics = $tripRepo->getLastsFuelCostStatistics();
        return View::make('welcome', ['vehiclesStatistics' => $vehiclesStatistics,
            'lastsServiceCostStatistics' => $lastsServiceCostStatistics,
            'servicesStatistics' => $servicesStatistics,
            'tripsStatistics' => $tripsStatistics,
            'lastsFuelCostStatistics' => $lastsFuelCostStatistics
        ]);
    }

    public function contact()
    {
        return View::make('home.contact');
    }
}
