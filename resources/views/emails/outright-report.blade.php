<table>
    <thead>
            <tr>
                <th colspan="9">Outright Report of {{ $data['league']->name }}</th>
            </tr>
            <tr>
                <td colspan="3">Total Bettors on this league :</td>   
                <th colspan="6">{{ $data['total_bettors'] }}</th>
            </tr>
            <tr>
                <td colspan="5">Total Bets on this league :</td>   
                <th colspan="4">{{ number_format($data['total_bets'],2) }}</th>
            </tr>
            <tr>
                <td colspan="5">Average Bets on this league :</td>   
                <th colspan="4">{{ number_format($data['average_bets'],2) }}</th>
            </tr>

            <tr>
                <td colspan="5">Match Fee :</td>   
                <th colspan="4">{{ ($data['betting_fee'] * 100) }}%</th>
            </tr>

            <tr>
                <td colspan="5">Total Fees collected on this league :</td>   
                <th colspan="4">{{ number_format($data['total_fees_collected'],2) }}</th>
            </tr>


            <tr>
                <td colspan="5">Total Payouts :</td>   
                <th colspan="4">{{ number_format($data['total_payout'],2) }}</th>
            </tr>


            <tr>
                <td colspan="5">Total Circulating Credits (Before):</td>   
                <th colspan="4">{{ $data['circulating_credits_before_settled'] }}</th>
            </tr>

            <tr>
                <td colspan="5">Total Circulating Credits (After):</td>   
                <th colspan="4">{{ $data['circulating_credits_after_settled'] }}</th>
            </tr>            
    </thead>
    <tbody>
        @foreach($data['teams'] as $key => $team)
            <tr>
                <th colspan="9"></th>
            </tr>
            <tr>
                <th colspan="9">{{ $team->name }} ({{ $data['league']->league_winner == $team->id ? 'Win' : 'Lost'}})</th>
            </tr>
            <tr>
                <th>ID</th>
                <th colspan="3">Name</th>
                <th colspan="2">Bet amount</th>
                <th colspan="2">Profit/Loss</th>
            </tr>
            @foreach($data['bets']->where('team_id', $team->id) as $key => $bet)
                <tr>
                    <td>{{$bet->user_id}}</td>
                    <td colspan="3">{{$bet->user->name}}</td>
                    <td colspan="2" style="font-weight: bold">{{number_format($bet->amount,2)}}</td>
                    <td colspan="2" style="font-weight: bold; color:{{ $data['league']->league_winner == $team->id ? '#00ff00' : '#ff0000'}}">{{number_format($bet->gains,2)}}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>