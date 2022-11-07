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

                    <h1>Betting Page</h1>
                    <h6>Balance: <span id="balance">{{ number_format($user->balance,2) }}</span></h6>
                    <h6>Minimum Bet: <span id="minimumBet">{{ number_format($ongoingGame->minimum_bet,2) }}</span></h6>
                    <h6>Draw # <span id="drawNumber">{{ $drawInfo->draw_number }}</span></h6>
                    <h6>Betting: {{ $drawInfo->status }}</h6>

                    <input type="text" class="bet" id="inpFirst" data-position="first" placeholder="Input first #">
                    <input type="text" class="bet" id="inpSecond" data-position="second" placeholder="Input second #">
                    <input type="text" class="bet" id="inpThird" data-position="third" placeholder="Input third #">

                    <br/>
                    <br/>
                    <br/>
                    <label for="inpBetAmount">Bet Amount:</label>
                    <input type="text" id="inpBetAmount" placeholder="input bet amount">
                    <button type="button" class="btn btn-success" id="btnPostBet">Post Bet</button>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(() => {
        // $(document).on("click", ".ctrl-betting", function() {
        //     let action = $(this).attr('data-action');
        //     let drawNum = $('#inpDrawNum').val();
        //     ajaxControlBetting(drawNum,action);
        // });
        
        $(document).on("click", "#btnPostBet", function() {
            postBet();
        });
    });

    function postBet() {

        let drawNumber = $('#drawNumber').text();
        let minimumBet = parseInt($('#minimumBet').text());
        let betAmount = parseInt($('#inpBetAmount').val());
        let first = $('#inpFirst').val();
        let second = $('#inpSecond').val();
        let third = $('#inpThird').val();

        if (first == '' || second == '' || third == '') {
            swal({
                title:  'Error',
                text:   '3 numbers are required for 1 bet',
                icon:   'error',
                button: 'Close'
            });
            return false;
        }

        if (first > 10 || second > 10 || third > 10) {
            swal({
                title:  'Error',
                text:   'Please input numbers not exceeding 10',
                icon:   'error',
                button: 'Close'
            });
            return false;
        }

        if ($('#inpBetAmount').val() == '') {
            swal({
                title:  'Error',
                text:   'Please enter a bet amount',
                icon:   'error',
                button: 'Close'
            });
            return false;
        }

        if (minimumBet > betAmount) {
            swal({
                title:  'Error',
                text:   'The minimum bet amount is ' + minimumBet,
                icon:   'error',
                button: 'Close'
            });
            return false;
        }

        $.ajax({
            type:   'POST',
            url:    '/postBet',
            data:   { 
                _token:     _token,
                first:      first,
                second:     second,
                third:      third,
                betAmount:  betAmount,
                drawNumber: drawNumber
            },
            success: function(response) {
                let json = JSON.parse(response);

                if (json.result == 1) {
                    $('#balance').text(json.new_balance);
                    swal({
                        title: json.title,
                        text: json.text,
                        icon: json.icon,
                        button: 'Close'
                    });
                } else {
                    swal({
                        title: json.title,
                        text: json.text,
                        icon: json.icon,
                        button: 'Close'
                    });
                }
            }
        });
    }

</script>
@endsection