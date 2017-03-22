<?php

namespace App\Http\Controllers;

use App\Http\Requests\RollcallRequest;
use App\Models\Rollcall;
use App\Repositories\RollcallRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RollcallController extends Controller
{
    protected $rollcalls;

    public function __construct(RollcallRepository $rollcalls)
    {
        $this->middleware('auth');

        $this->rollcalls = $rollcalls;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rollcall.calendar.month', $this->rollcalls->calendarMonthView());

        return view('rollcall.index', [
            'rollcalls' => $this->rollcalls->index()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rollcall.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RollcallRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RollcallRequest $request)
    {
        $this->rollcalls->store($request);

        return redirect(route('rollcall.index', ['language' => request()->language]))->with('status', __('rollcall.stored'));
    }

    /**
     * Display the specified resource.
     *
     * @param $rollcall
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        return view('rollcall.index_date', [
            'rollcalls' => $this->rollcalls->show($date),
            'date' => $date
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rollcall $rollcall
     * @return \Illuminate\Http\Response
     */
    public function edit(Rollcall $rollcall)
    {
        return view('rollcall.edit', ['rollcall' => $rollcall]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RollcallRequest|Request $request
     * @param Rollcall $rollcall
     * @return \Illuminate\Http\Response
     */
    public function update(RollcallRequest $request, Rollcall $rollcall)
    {
        dd($request->date . " " . $request->time);

        $this->rollcalls->update($request, $rollcall);

        return redirect(route('rollcall.index'))->with('status', __('rollcall.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rollcall $rollcall
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rollcall $rollcall)
    {
        $this->rollcalls->destroy($rollcall);

        return back()->with('status', __('rollcall.destroyed'));
    }

    public function arriveNow()
    {
        $this->rollcalls->arriveNow();

        return redirect(route('rollcall.index'))->with('status', __('rollcall.stored'));
    }

    public function departNow()
    {
        $this->rollcalls->departNow();

        return redirect(route('rollcall.index'))->with('status', __('rollcall.stored'));
    }

    public function storeAt(Request $request)
    {
        $this->authorize('createAt', [Rollcall::class, $request->date]);

        $this->rollcalls->storeAt($request);

        return back()->with('status', __('rollcall.stored'));
    }
}
