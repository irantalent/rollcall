@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('tools.status')

                <div class="panel panel-default">
                    <div class="panel-heading">@lang('rollcall.title')</div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>@lang('rollcall.time')</th>
                                <th>Type</th>
                                <th>@lang('rollcall.operations')</th>
                            </tr>
                            @foreach($rollcalls as $rollcall)
                                <tr>
                                    <td>{{ $rollcall->rollcall_time }}</td>
                                    <td>
                                        {{ $rollcall->getTypeString() }}
                                        @if($rollcall->type == \App\Models\Rollcall::ARRIVE)
                                            <span class="glyphicon glyphicon-arrow-right text-success"></span>
                                        @else
                                            <span class="glyphicon glyphicon-arrow-left text-danger"></span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rollcall.edit', ['rollcall' => $rollcall]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a href="#">Details and edit</a>
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


