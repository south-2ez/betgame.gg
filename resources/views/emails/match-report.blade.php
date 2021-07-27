<table>
    <thead>
            <tr>
                <th colspan="9">Match Report of {{$match->data->team_a->name.' vs '.$match->data->team_b->name.' '.$match->created_at}}</th>
            </tr>
            <tr>
                <td colspan="3">League :</td>   
                <th colspan="6">{{$match->league->name}}</th>
            </tr>
            <tr>
                <td colspan="3">Settled By :</td>   
                <th colspan="6">{{$match->settledBy->name}}</th>
            </tr>
            <tr>
                <td colspan="3">Label :</td>   
                <th colspan="6">{{$match->label}}</th>
            </tr>
            <tr>
                <td colspan="5">Circulating Credits Before Settled :</td>   
                <th colspan="4">{{number_format($match->circulating_credits_before_settled,2)}}</th>
            </tr>
            <tr>
                <td colspan="5">Circulating Credits After Settled :</td>   
                <th colspan="4">{{number_format($match->circulating_credits_after_settled,2)}}</th>
            </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="9"></th>
        </tr>
        <tr>
            <th colspan="9">{{$match->data->team_a->name.' ('.$match->data->team_a->status.')'}}</th>
        </tr>
        <tr>
            <th>ID</th>
            <th colspan="3">Name</th>
            <th colspan="2">Bet amount</th>
            <th colspan="2">Profit/Loss</th>
        </tr>
        @foreach($match->data->team_a->users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td colspan="3">{{$user->name}}</td>
                <td colspan="2" style="font-weight: bold">{{number_format($user->amount,2)}}</td>
                <td colspan="2" style="font-weight: bold; color:{{$match->data->team_a->status == 'win' ? '#00ff00' : '#ff0000'}}">{{number_format($user->profit,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4"></td>
            <th colspan="2">{{number_format($match->data->team_a->total_bet,2)}}</th>
            <th colspan="2" style="color:{{$match->data->team_a->status == 'win' ? '#00ff00' : '#ff0000'}}">{{number_format($match->data->team_a->total_profit,2)}}</th>
        </tr>
        <tr>
            <th colspan="9">{{$match->data->team_b->name.' ('.$match->data->team_b->status.')'}}</th>
        </tr>
        <tr>
            <th>ID</th>
            <th colspan="3">Name</th>
            <th colspan="2">Bet amount</th>
            <th colspan="2">Profit/Loss</th>
        </tr>
        @foreach($match->data->team_b->users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td colspan="3">{{$user->name}}</td>
                <td colspan="2" style="font-weight: bold">{{number_format($user->amount,2)}}</td>
                <td colspan="2" style="font-weight: bold; color:{{$match->data->team_b->status == 'win' ? '#00ff00' : '#ff0000'}}">{{number_format($user->profit,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4"></td>
            <th colspan="2">{{number_format($match->data->team_b->total_bet,2)}}</th>
            <th colspan="2" style="color:{{$match->data->team_b->status == 'win' ? '#00ff00' : '#ff0000'}}">{{number_format($match->data->team_b->total_profit,2)}}</th>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>      
        <tr>
            <td colspan="9"></td>
        </tr>  
        <tr>
            <td colspan="3">Total Bettors {{$match->data->team_a->name}} :</td>   
            <th style="text-align: left; ">{{$match->data->team_a->total_bettors}}</th>
        </tr>
        <tr>
            <td colspan="3">Total Bets {{$match->data->team_a->name}} :</td>   
            <th>{{number_format($match->data->team_a->total_bet,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Average Bets {{$match->data->team_a->name}} :</td>   
            <th>{{number_format($match->data->team_a->average_bet,2)}}</th>
        </tr>  
        <tr>
            <td colspan="3">Total Bettors {{$match->data->team_b->name}} :</td>   
            <th style="text-align: left; ">{{$match->data->team_b->total_bettors}}</th>
        </tr>
        <tr>
            <td colspan="3">Total Bets {{$match->data->team_b->name}} :</td>   
            <th>{{number_format($match->data->team_b->total_bet,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Average Bets {{$match->data->team_b->name}} :</td>   
            <th>{{number_format($match->data->team_b->average_bet,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Total Bettors :</td>   
            <th style="text-align: left; ">{{$match->total_bettors}}</th>
        </tr>
        <tr>
            <td colspan="3">Total Bets this match :</td>   
            <th>{{number_format($match->total_match_bet,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Average Bets:</td>   
            <th>{{number_format($match->average_match_bet,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Match fee :</td>   
            <th style="text-align: left; ">{{$match->match_fee*100}}</th>
        </tr>
        <tr>
            <td colspan="3">Total fees collected :</td>   
            <th>{{number_format($match->total_fees_collected,2)}}</th>
        </tr>
        <tr>
            <td colspan="3">Total payout :</td>   
            <th>{{number_format($match->total_payout,2)}}</th>
        </tr>
    </tbody>
</table>