@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @elseif (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card card-primary">
                <div class="card-header">
                    <h5><i class="fa fa-info-circle"></i> Schedules</h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="/admin/schedule/create" class="btn btn-primary float-right mb-2"><i class="fa fa-plus"></i> Create Schedule</a>
                    <table class="table global-table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Date and Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->id }}</td>
                                    <td>{{ $schedule->name }}</td>
                                    <td>{{ $schedule->status }}</td>
                                    <td>{{ date('M d, Y h:i:s a', strtotime($schedule->date_time)) }}</td>
                                    <td>
                                        <a href="/admin/schedule/{{ $schedule->id }}/manage" class="btn btn-primary">Manage</a>
                                        <a href="/admin/schedule/{{ $schedule->id }}/edit" class="btn btn-secondary">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
