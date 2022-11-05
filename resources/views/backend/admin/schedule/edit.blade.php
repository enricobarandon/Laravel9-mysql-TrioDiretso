@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
            <div class="card">
                <div class="card-header">{{ __('Schedules') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>Manage Schedule</h5>
                    
                    <form method="POST" action="/admin/schedule/{{ $schedule->id }}">
                        @CSRF
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Schedule Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $schedule->name }}">
                        </div>
                        <div class="form-group">
                            <label for="date_time">Schedule Date and Time</label>
                            <input type="text" class="form-control" id="datetime" name="date_time" placeholder="Enter date and time" value="{{ date('Y-m-d h:i:s', strtotime($schedule->date_time)) }}">
                        </div>
                        <div class="form-group">
                            <label for="minimum_bet">Minimum Bet</label>
                            <input type="text" class="form-control" id="minimum-bet" name="minimum_bet" placeholder="Enter date and time" value="{{ $schedule->minimum_bet }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <!-- <input type="text" class="form-control" id="status" name="status" placeholder="Enter date and time" value="{{ $schedule->minimum_bet }}"> -->
                            <select class="form-control" id="status" name="status">
                                <option selected disabled>--Select Status--</option>
                                <?php
                                    foreach($statusArr as $stat){
                                        $selected = '';
                                        if ($stat == $schedule->status) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="'. $stat .'" '. $selected .'>'. strtoupper($stat) .'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
