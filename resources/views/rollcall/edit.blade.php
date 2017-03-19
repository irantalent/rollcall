@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('tools.status')

                <div class="panel panel-default">
                    <div class="panel-heading">@lang('rollcall.title')</div>

                    <div class="panel-body">
                        <form class="form-horizontal"
                              action="{{ route('rollcall.update', ['rollcall' => $rollcall]) }}"
                              method="post">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Date</label>
                                <div class="col-md-9">
                                    <input class="form-control"
                                           type="date"
                                           value="{{ $rollcall->rollcall_time->toDateString() }}"
                                           name="date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Time</label>
                                <div class="col-md-9">
                                    <input class="form-control"
                                           type="time" value="{{ $rollcall->rollcall_time->format('H:i') }}" name="time">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-success">
                                        <span class="glyphicon glyphicon-floppy-disk"></span> Save
                                    </button>
                                    <a href="{{ back()->getTargetUrl() }}" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


