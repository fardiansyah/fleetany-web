<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ModelMonitorRepositoryEloquent;
use App\Entities\ModelMonitor;
use Log;
use Input;
use Lang;
use Session;
use Redirect;
use Response;
use Illuminate\Support\Facades\View;
use Prettus\Validator\Exceptions\ValidatorException;

class ModelMonitorController extends Controller
{

    protected $monitorRepo;
    public function __construct(ModelMonitorRepositoryEloquent $monitorRepo)
    {
        $this->middleware('auth');
        //$this->middleware('acl');
        $this->monitorRepo = $monitorRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $modelmonitors = $this->monitorRepo->all();
        if (Request::isJson()) {
            return $modelmonitors;
        }
        return View::make("modelmonitor.index", compact('modelmonitors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $modelmonitor = new ModelMonitor();
        return view("modelmonitor.edit", compact('modelmonitor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            $this->monitorRepo->validator();
            $this->monitorRepo->create(Input::all());
            Session::flash(
                'message',
                Lang::get(
                    'general.succefullcreate',
                    ['table'=> Lang::get('general.ModelMonitor')]
                )
            );
            return Redirect::to('modelmonitor');
        } catch (ValidatorException $e) {
            return Redirect::back()->withInput()
                   ->with('errors', $e->getMessageBag());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $idMonitor
     * @return Response
     */
    public function show($idMonitor)
    {
        $modelmonitor= $this->monitorRepo->find($idMonitor);
        return View::make("modelmonitor.show", compact('modelmonitor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $idMonitor
     * @return Response
     */
    public function edit($idMonitor)
    {
        $modelmonitor = $this->monitorRepo->find($idMonitor);
        return View::make("modelmonitor.edit", compact('modelmonitor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $idMonitor
     * @return Response
     */
    public function update($idMonitor)
    {
        try {
            $this->monitorRepo->validator();
            $this->monitorRepo->update(Input::all(), $idMonitor);
            Session::flash(
                'message',
                Lang::get(
                    'general.succefullupdate',
                    ['table'=> Lang::get('general.ModelMonitor')]
                )
            );
            return Redirect::to('modelmonitor');
        } catch (ValidatorException $e) {
            return Redirect::back()->withInput()
                    ->with('errors', $e->getMessageBag());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $idMonitor
     * @return Response
     */
    public function destroy($idMonitor)
    {
        Log::info('Delete field: '.$idMonitor);
        if ($this->monitorRepo->find($idMonitor)) {
            $this->monitorRepo->delete($idMonitor);
            Session::flash('message', Lang::get("general.deletedregister"));
        }
        return Redirect::to('modelmonitor');
    }
}