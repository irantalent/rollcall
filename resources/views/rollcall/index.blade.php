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
                                <a href="{{ route('rollcall.arrive') }}" class="btn btn-success">@lang('rollcall.arrive')</a>
                                <a href="{{ route('rollcall.depart') }}" class="btn btn-danger">@lang('rollcall.depart')</a>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>@lang('rollcall.date')</th>
                                <th>@lang('rollcall.first_arrive')</th>
                                <th>@lang('rollcall.last_depart')</th>
                                <th>@lang('rollcall.number_of_passes')</th>
                                <th>@lang('rollcall.operations')</th>
                            </tr>
                            @foreach($rollcalls as $rollcall)
                                <tr>
                                    <td>{{ $rollcall->date }}</td>
                                    <td>
                                        {{ $rollcall->first_arrive_datetime }}
                                    </td>n
                                    <td>
                                        {{ $rollcall->last_depart_datetime }}
                                    </td>
                                    <td>{{ $rollcall->passes }}</td>
                                    <td>
                                        <a href="{{ route('rollcall.show', ['date' => $rollcall->date]) }}">Details and edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="panel-footer">
                        {{ $rollcalls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


