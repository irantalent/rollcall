@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('tools.status')

                <div class="panel panel-default">
                    <div class="panel-heading">@lang('rollcall.title')</div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('rollcall.arrive') }}"
                                   class="btn btn-success">@lang('rollcall.arrive')</a>
                                <a href="{{ route('rollcall.depart') }}"
                                   class="btn btn-danger">@lang('rollcall.depart')</a>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <h2>Summary</h2>
                            <hr>
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Total working hours</label>
                                    <div class="col-sm-4">
                                        <p class="form-control-static">
                                            {{ \App\Tools\DateTimeHelper::fromSecondsToStandard($payslip->total_working_time) }}
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Late in</label>
                                    <div class="col-sm-4">
                                        <p class="form-control-static">
                                            {{ \App\Tools\DateTimeHelper::fromSecondsToStandard($payslip->late_in_time) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Early out</label>
                                    <div class="col-sm-4">
                                        <p class="form-control-static">
                                            {{ \App\Tools\DateTimeHelper::fromSecondsToStandard($payslip->early_out_time) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Over time</label>
                                    <div class="col-sm-4">
                                        <p class="form-control-static">
                                            {{ \App\Tools\DateTimeHelper::fromSecondsToStandard($payslip->overtime_time) }}
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <a href="{{ route('payslip.calculateForMeTemp', ['date'=>$date->toDateString()]) }}"
                                   class="btn btn-default">
                                    Calculate times
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-center">
                                <h1>
                                    <a href="{{ route('rollcall.index', ['date'=>(clone $date)->subYear()->toDateString()]) }}"><span
                                                class="small glyphicon glyphicon-backward"></span></a>
                                    <a href="{{ route('rollcall.index', ['date'=>(clone $date)->subMonth()->toDateString()]) }}"><span
                                                class="small glyphicon glyphicon-triangle-left"></span></a>
                                    {{ $date->format('F Y') }}
                                    <a href="{{ route('rollcall.index', ['date'=>(clone $date)->addMonth()->toDateString()]) }}"><span
                                                class="small glyphicon glyphicon-triangle-right"></span></a>
                                    <a href="{{ route('rollcall.index', ['date'=>(clone $date)->addYear()->toDateString()]) }}"><span
                                                class="small glyphicon glyphicon-forward"></span></a>
                                </h1>
                            </div>
                            <table class="table table-bordered cal-table">
                                <tr>
                                    <th>@lang('datetime.mon')</th>
                                    <th>@lang('datetime.tue')</th>
                                    <th>@lang('datetime.wed')</th>
                                    <th>@lang('datetime.thu')</th>
                                    <th>@lang('datetime.fri')</th>
                                    <th>@lang('datetime.sat')</th>
                                    <th>@lang('datetime.sun')</th>
                                </tr>
                                @foreach($weeks as $week)
                                    <tr>
                                        @foreach($week as $day)
                                            <td class="@if($day->date->isToday()) bg-success @endif">
                                                <a href="{{ route('rollcall.show', ['date' => $day->date->toDateString()]) }}">
                                                    <span class="@if(!$day->is_in_this_month) other-month @else this-month @endif cal-day-number ">{{ $day->date->day }}</span>
                                                </a>
                                                <div class="clearfix"></div>
                                                <div data-toggle="tooltip"
                                                     data-placement="left"
                                                     title="@lang('rollcall.first_arrive')">
                                                    <span class="glyphicon glyphicon-arrow-right text-success"></span>
                                                    @if(!is_null($day->first_arrive))
                                                        {{ $day->first_arrive->rollcall_time->toTimeString() }}
                                                    @endif
                                                </div>
                                                <div class="clearfix"></div>
                                                <div data-toggle="tooltip"
                                                     data-placement="left"
                                                     title="@lang('rollcall.last_depart')">
                                                    <span class="glyphicon glyphicon-arrow-left text-danger"></span>
                                                    @if(!is_null($day->last_depart))
                                                        {{ $day->last_depart->rollcall_time->toTimeString() }}
                                                    @endif
                                                </div>
                                                <div class="clearfix"></div>
                                                <div data-toggle="tooltip"
                                                     data-placement="left"
                                                     title="@lang('rollcall.number_of_passes')">
                                                    <span class="glyphicon glyphicon-refresh"></span>
                                                    {{ $day->number_of_passes }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


