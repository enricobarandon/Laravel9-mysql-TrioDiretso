@extends('layouts.player')

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
                <div class="card-header">{{ __('Trio Diretso Player') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>No Ongoing Game</h1>

                    <h5>Upcoming Games</h5>
                    @foreach($upcomingGames as $game) 
                        <p>{{ $game->name }} - {{ date('M d, Y h:i A', strtotime($game->date_time)) }}</p>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
