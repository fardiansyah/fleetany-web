<?php

namespace App\Http\Controllers;

use Request;

use App\Http\Controllers\Controller;

use App\Repositories\ModelSensorRepositoryEloquent;
use App\Entities\ModelSensor;
use Log;
use Input;
use Lang;
use Session;
use Redirect;
use Response;
use Illuminate\Support\Facades\View;
use Prettus\Validator\Exceptions\ValidatorException;

class ModelSensorController extends Controller
{
    
    protected $sensorRepo;
    
    public function __construct(ModelSensorRepositoryEloquent $sensorRepo)
    {
        $this->middleware('auth');
        $this->sensorRepo = $sensorRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $modelsensors = $this->sensorRepo->all();
        if (Request::isJson()) {
            return $modelsensors;
        }

        return View::make("modelsensor.index", compact('modelsensors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $modelsensor = new ModelSensor();
        return view("modelsensor.edit", compact('modelsensor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            $this->sensorRepo->validator();
            $this->sensorRepo->create(Input::all());
            Session::flash(
                'message',
                Lang::get(
                    'general.succefullcreate',
                    ['table'=> Lang::get('general.ModelSensor')]
                )
            );
            return Redirect::to('modelsensor');
        } catch (ValidatorException $e) {
            return Redirect::back()->withInput()
                   ->with('errors', $e->getMessageBag());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $idModelSensor
     * @return Response
     */
    public function show($idModelSensor)
    {
        $modelsensor= $this->sensorRepo->find($idModelSensor);
        return View::make("modelsensor.show", compact('modelsensor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $idModelSensor
     * @return Response
     */
    public function edit($idModelSensor)
    {
        $modelsensor = $this->sensorRepo->find($idModelSensor);
        return View::make("modelsensor.edit", compact('modelsensor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $idModelSensor
     * @return Response
     */
    public function update($idModelSensor)
    {
        try {
            $this->sensorRepo->validator();
            $this->sensorRepo->update(Input::all(), $idModelSensor);
            Session::flash(
                'message',
                Lang::get(
                    'general.succefullupdate',
                    ['table'=> Lang::get('general.ModelSensor')]
                )
            );
            return Redirect::to('modelsensor');
        } catch (ValidatorException $e) {
            return Redirect::back()->withInput()
                    ->with('errors', $e->getMessageBag());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $idModelSensor
     * @return Response
     */
    public function destroy($idModelSensor)
    {
        Log::info('Delete field: '.$idModelSensor);
        if ($this->sensorRepo->find($idModelSensor)) {
            $this->sensorRepo->delete($idModelSensor);
            Session::flash('message', Lang::get("general.deletedregister"));
        }

        return Redirect::to('modelsensor');
    }
}