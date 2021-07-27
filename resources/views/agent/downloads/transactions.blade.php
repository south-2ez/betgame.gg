<table>
    <thead>
        <tr>
            <th>CODE</th>
            <th>Transaction With</th>
            <th>Request Date & Time</th>
            <th>Processed Date & Time</th>
            <th>Transcation Type</th>
            <th>Amount</th>
            <th>Earnings</th>
            <th>Voucher Code</th>
            <th>Status</th>
            <th>Processed By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->code }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ $transaction->approved_rejected_date }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->type == 'cashout' ? $transaction->amount - ( $transaction->amount * env('CASHOUT_FEE',0.05) ) : $transaction->amount }}</td>
                <td>{{ $transaction->partner_earnings }}</td>
                <td>{{ $transaction->type == 'deposit' ? $transaction->voucher_code : 'N/A' }}</td>
                <td>{{ $transaction->status == 0 ? 'Pending' :  ( $transaction->status == 1 ? 'Completed' : 'Rejected' ) }}</td>
                <td>{{ !empty($transaction->processBy) ? $transaction->processBy->name : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>