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
                <div class="card-header">{{ __('Declarator') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Game Management Page</h1>
                    <h5>Game Name: {{ $gameInfo->name }}</h5>
                    <h5>Minimum Bet Amount: {{ number_format($gameInfo->minimum_bet,2) }}</h5>
                    <h5>Multiplier: {{ number_format($gameInfo->multiplier,2) }}</h5>

                    <br/>

                    <?php
                        if ($drawInfo) {
                            $drawNumber = $drawInfo->draw_number;
                        } else {
                            $drawNumber = 0;
                        }
                    ?>

                    <input type="text" id="inpDrawNum" value="{{ $drawNumber }}" disabled>

                    <button class="btn btn-success ctrl-betting" data-action="open">Open Betting</button>
                    <button class="btn btn-danger ctrl-betting" data-action="close">Close Betting</button>
                    <button class="btn btn-warning ctrl-betting" data-action="next">Next Draw</button>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(() => {
        $(document).on("click", ".ctrl-betting", function() {
            let action = $(this).attr('data-action');
            let drawNum = $('#inpDrawNum').val();
            ajaxControlBetting(drawNum,action);
        });
    });

    function ajaxControlBetting(drawNum,action) {
        $.ajax({
            type:   'POST',
            url:    '/admin/schedule/controlBetting',
            data:   { 
                _token:     _token,
                drawNum:    drawNum,
                action:     action 
            },
            success: function(response) {
                alert(response);
            }
        });
    }

</script>
@endsection
