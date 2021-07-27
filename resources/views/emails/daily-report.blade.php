<table>
    <thead>
            <tr>
                <th colspan="9">Daily Report of {{$data['date']}}</th>
            </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="9"></th>
        </tr>
        <tr>
            <td colspan="4">Total Number of matches today :</td>   
            <th style="text-align: left; ">{{$data['total_matches']}}</th>
        </tr>
        <tr>
            <td colspan="4">Total amount of bets today :</td>   
            <th>{{number_format($data['total_bets'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Average amount of bets today :</td>   
            <th>{{number_format($data['average_bet_amount'],2)}}</th>
        </tr>  
        <tr>
            <td colspan="4">Total number of bettors today :</td>   
            <th style="text-align: left; ">{{$data['total_bettors']}}</th>
        </tr>
        <tr>
            <td colspan="4">Average number of bettors per match :</td>   
            <th style="text-align: left; ">{{$data['average_bettors']}}</th>
        </tr>
        <tr>
            <td colspan="4">Total approved deposits:</td>   
            <th style="text-align: left; ">{{number_format($data['total_approved_deposits'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total approved cashouts :</td>   
            <th style="text-align: left; ">{{number_format($data['total_approved_cashouts'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Diff of deposists and cashouts credits :</td>   
            <th style="text-align: left; ">{{number_format($data['diff_deposists_cashouts'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total buy credits :</td>   
            <th style="text-align: left; ">{{number_format($data['total_buy_credits'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total sell credits :</td>   
            <th style="text-align: left; ">{{number_format($data['total_sell_credits'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Diff of buy and sell credits :</td>   
            <th style="text-align: left; ">{{number_format($data['diff_buy_sell'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total Cashout Fees collected today :</td>   
            <th style="text-align: left; ">{{number_format($data['total_cashout_fees_collected'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total Cashout Fees collected today (via Partner) :</td>   
            <th style="text-align: left; ">{{number_format($data['total_cashout_fees_collected_via_partner'],2)}}</th>
        </tr>        
        <tr>
            <td colspan="4">Total Match Fees collected today :</td>   
            <th>{{number_format($data['total_fee'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Total Earned today :</td>   
            <th>{{number_format($data['total_earned_today'],2)}}</th>
        </tr>
        <tr>
            <td colspan="4">Average Match Fee collected per match :</td>   
            <th style="text-align: left; ">{{number_format($data['average_fee'],2)}}</th>
        </tr>
    </tbody>
</table>