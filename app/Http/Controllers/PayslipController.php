<?php

namespace App\Http\Controllers;

use App\Repositories\PayslipRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PayslipController extends Controller
{
    protected $payslips;

    public function __construct(PayslipRepository $payslips)
    {
        $this->middleware('auth');

        $this->payslips = $payslips;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    // this is temporary function
    public function calculateMyPayslipHoursOfCurrentMonth(){
        $date = (\DateTime::createFromFormat('Y-m-d', Input::get("date")) !== false)
            ? new Carbon(Input::get("date"))
            : Carbon::now();

        $this->payslips->calculatePayslipTimeForUser(request()->user(), $date);

        return back();
    }
}
