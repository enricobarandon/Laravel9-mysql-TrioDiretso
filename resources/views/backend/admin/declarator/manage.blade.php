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

                    <br/>
                    <hr/>

                    <input type="text" class="declare" id="inpDeclareFirst" data-position="first" placeholder="Input first #">
                    <input type="text" class="declare" id="inpDeclareSecond" data-position="second" placeholder="Input second #">
                    <input type="text" class="declare" id="inpDeclareThird" data-position="third" placeholder="Input third #">
                    
                    <button type="button" class="btn btn-primary" id="btnDeclare">Declare Result</button>
                    <button type="button" class="btn btn-danger" id="btnCancel">Cancel Draw</button>

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
            controlBetting(drawNum,action);
        });

        $(document).on("click", "#btnDeclare", function() {
            let first = $('#inpDeclareFirst').val();
            let second = $('#inpDeclareSecond').val();
            let third = $('#inpDeclareThird').val();

            if (first == '' || second == '' || third == '') {
                swal({
                    title:  'Error',
                    text:   '3 numbers are required for the combination',
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

            declareResult(first, second, third , 0);

        });

        $(document).on("click", "#btnCancel", function() {
            declareResult(0, 0, 0 , 1);
        });

    });

    function controlBetting(drawNum,action) {
        $.ajax({
            type:   'POST',
            url:    '/admin/schedule/controlBetting',
            data:   { 
                _token:     _token,
                drawNum:    drawNum,
                action:     action 
            },
            success: function(response) {
                let json = JSON.parse(response);
                swal({
                    title: json.title,
                    text: json.text,
                    icon: json.icon,
                    button: 'Close'
                });
            }
        });
    }

    function declareResult(first,second,third) {
        let drawNum = $('#inpDrawNum').val();
        $.ajax({
            type:   'POST',
            url:    '/admin/schedule/declareResult',
            data:   { 
                _token:     _token,
                first:      first,
                second:     second,
                third:      third,
                drawNum:    drawNum
            },
            success: function(response) {
                let json = JSON.parse(response);
                swal({
                    title: json.title,
                    text: json.text,
                    icon: json.icon,
                    button: 'Close'
                });
            }
        });
    }

</script>
@endsection
