@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('tools.status')

                <div class="panel panel-default">
                    <div class="panel-heading">@lang('rollcall.title') - {{ $date }}</div>

                    <div class="panel-body">
                        @can('createAt', [\App\Models\Rollcall::class, $date])
                            <div class="row">
                                <form action="{{ route('rollcall.storeAt') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <div class="form-group-sm col-md-3">
                                        <input class="form-control" name="time" type="time">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button name="arrive" value="arrive" class="btn btn-sm btn-success">
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                            @lang('rollcall.arrive')
                                        </button>
                                        <button name="depart" value="depart" class="btn btn-sm btn-danger">
                                            <span class="glyphicon glyphicon-arrow-left"></span>
                                            @lang('rollcall.depart')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endcan
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
                                        @can('update', $rollcall)
                                            <a href="{{ route('rollcall.edit', ['rollcall' => $rollcall]) }}"
                                               class="btn btn-primary">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                        @endcan
                                        @can('delete', $rollcall)
                                            <form method="post"
                                                  style="display: inline;"
                                                  action="{{ route('rollcall.destroy', ['rollcall' => $rollcall]) }}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </form>
                                        @endcan
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


