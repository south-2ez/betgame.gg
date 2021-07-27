    <div id="auditUserModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Audit User: <strong class="username text-danger"></strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" width="100%">
                                <tr>
                                    <th colspan="2">Formula A</th>
                                </tr>
                                <tr>
                                    <th>Total Cashout</th>
                                    <td class="total_cashout">N/A</td>
                                </tr>
                                <tr>
                                    <th>Betted Credits</th>
                                    <td class="curr_bets">N/A</td>
                                </tr>
                                <tr>
                                    <th>Remaining Credits</th>
                                    <td class="curr_credits">N/A</td>
                                </tr>
                                <tr>
                                    <th style="text-align: right">Total = </th>
                                    <td class="total_fa">N/A</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table" width="100%">
                                <tr>
                                    <th colspan="2">Formula B</th>
                                </tr>
                                <tr>
                                    <th>Total Deposit</th>
                                    <td class="total_deposit">N/A</td>
                                </tr>
                                <tr>
                                    <th>Transfered Rebates</th>
                                    <td class="total_rebate">N/A</td>
                                </tr>
                                <tr>
                                    <th>Profit/Loss</th>
                                    <td class="profit_loss">N/A</td>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <th style="text-align: right">Total = </th>
                                    <td class="total_fb">N/A</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading">
                            <h3 class="panel-title">Bets</h3>
                            <span class="pull-right clickable">(Total Profit/Loss: <strong class="profit_loss"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-bets" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Match</th>
                                    <th>Team Selected</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Profit/Loss</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading">
                            <h3 class="panel-title">Deposits</h3>
                            <span class="pull-right clickable">(Total Deposits: <strong class="total_deposit"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-deposits" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Amount</th>
                                    <th>MOP</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                </thead>
                            </table>
                            <h3>Partner Deposits</h3>
                            <table id="audituser-table-partner-deposits" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Partner</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading">
                            <h3 class="panel-title">Rebates</h3>
                            <span class="pull-right clickable">(Total Rebates: <strong class="total_rebate"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-rebates" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Transaction Code</th>
                                    <th>Amount Deposited</th>
                                    <th>Amount Earned</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cashouts</h3>
                            <span class="pull-right clickable">(Total Cashouts: <strong class="total_cashout"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-cashouts" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Amount</th>
                                    <th>MOP</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                </thead>
                            </table>
                            <h3>Partner Cashouts</h3>
                            <table id="audituser-table-partner-cashouts" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>O.R. #</th>
                                    <th>Partner</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                     <div class="panel panel-primary" style="margin-bottom: 10px">
                        <div class="panel-heading">
                            <h3 class="panel-title">Rewards</h3>
                            <span class="pull-right clickable">(Total Rewards Claimed: <strong class="total_rewards"></strong>)&nbsp;&nbsp; <i class="glyphicon glyphicon-chevron-up"></i></span>
                        </div>
                        <div class="panel-body" style="padding: 0 0 15px 0">
                            <table id="audituser-table-rewards" class="table table-striped" width="100%" style="font-size: 90%">
                                <thead>
                                    <th>Date/Time</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Added By</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<script>
  var audit_user_bets
  var audit_user_cashouts
  var audit_user_deposits
  var audit_user_partner_deposits
  var audit_user_partner_cashouts
  var audit_user_rebates
  var audit_user_rewards

  $(document).ready(function() {

    audit_user_bets = $('#auditUserModal #audituser-table-bets').DataTable({
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'bets']) !!}",
        columns: [
            {
                data:'updated_at',
                name:'updated_at'
            },
            {
                data:'match',
                name:'match.name',
                render: function(data,type,row){
                    if(data) {
                        var url = "{{url('/')}}/match/" + data.id;
                        return '<a href="'+url+'" target="_new">' + data.name + '</a>';
                    } else {
                        var url = "{{url('/')}}/tournament/" + row['league'].id;
                        return '<a href="'+url+'" target="_new">' + row['league'].name + ' Winner</a>';
                    }
                }
            },
            {
                data: 'team.name',
                name: 'team.name'
            },
            {
                data: 'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data: 'match',
                name: 'match.status',
                render: function(data,type,row){
                    if(data) {
                        switch(data.status) {
                            case 'open':
                                return '<strong style="color: green">Open</strong>';
                            case 'ongoing':
                                return '<strong style="color: green">Ongoing</strong>';
                            case 'closed':
                            case 'settled':
                                if(row['team_id'] == data.team_winner)
                                    return 'Settled (<strong style="color: blue">Win</strong>)';
                                else
                                    return 'Settled (<strong style="color: red">Loss</strong>)';
                            default:
                                return data.status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                });
                        }
                    } else {
                        if(row['type'] == 'tournament') {
                            switch(row['league'].betting_status) {
                                case 0:
                                    if (row['league'].league_winner)
                                        return 'Closed';
                                    else
                                        return '<strong style="color: green">Ongoing</strong>';
                                    break;
                                case 1:
                                    return '<strong style="color: green">Open</strong>';
                                    break;
                                case -1:
                                    if(row['league'].league_winner == row['team_id'])
                                        return 'Settled (<strong style="color: blue">Win</strong>)';
                                    else
                                        return 'Settled (<strong style="color: red">Loss</strong>)';
                                    break;
                                default:
                                    return 'Closed';
                                    break;
                            }
                        }
                    }
                }
            },
            {
                data: 'amount',
                name: 'amount',
                render: function(data,type,row){
                    if(row['match']) {
                        switch(row['match'].status) {
                            case 'cancelled':
                            case 'forfeit':
                            case 'draw':
                                return 0;
                            case 'settled':
                            case 'closed':
                                // var profit = row['potential_winnings'] - row['amount'];
                                if(row['team_id'] == row['match'].team_winner)
                                    return '<span style="color:green">+'+numberWithCommas(parseFloat(row.gains).toFixed(2))+'</span>';
                                else
                                    return '<span style="color:red">-'+numberWithCommas(row['amount'])+'</span>';
                                break;
                            default:
                                return 'N/A';
                        }
                    } else {
                        if(row['type'] == 'tournament') {
                            if(row['league'].betting_status == -1) {
                                if(row['league'].league_winner == row['team_id']) {
                                    var _gains = row['potential_winnings'] - row.amount;
                                    return '<span style="color:green">+'+numberWithCommas(parseFloat(_gains).toFixed(2))+'</span>';
                                } else
                                    return '<span style="color:red">-'+numberWithCommas(row['amount'])+'</span>';
                            } else
                                return 'N/A';
                        } else
                            return 'N/A';
                    }
                }
            }
        ]
    });
    audit_user_deposits = $('#auditUserModal #audituser-table-deposits').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'deposits']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    if(row.discrepancy.length) {
                        var disc_amount = row.discrepancy.reduce(function(total, currVal) {
                            return total + currVal.amount;
                        }, 0);
                        return '&#8369; '+numberWithCommas(data)+
                            ' (<span style="font-weight:bold; color: blue">'+
                            numberWithCommas(disc_amount)+
                            '</span>)';
                    } else
                        return '&#8369; '+numberWithCommas(data);
                }
            },
            {data:'data.mop',name:'data'},
            {
                data:'status',
                name:'status',
                render: function(data,type,row){
                    // return row['picture'] == null  && data != 'completed' ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                    if(data == 'rejected'){
                        return 'rejected'
                    }else{
                        if(row['picture'] == null  && data != 'completed'){
                            return 'incomplete'
                        }else{
                            switch (data) {
                                case 'completed':
                                    return 'Approved and Completed'
                                default:
                                    return 'Needs Approval'
                }
                        }
                    }
                }
            },
            {
                data:'process_by',
                searchable:false,
                render: function(data,type,row){
                    return data == null ? 'n/a' : data['name']
                }
            }
        ]
    });
    audit_user_cashouts = $('#auditUserModal #audituser-table-cashouts').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'cashouts']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {data:'data.mop',name:'data'},
            {
                data:'status',
                name:'status',
                render: function(data,type,row){
                    // return row['picture'] == null  && data != 'completed' ? 'incomplete' : data == 'completed' ? 'Approved and Completed' : 'Needs Approval' 
                    if(data == 'rejected'){
                        return 'rejected'
                    }else{
                        if(row['picture'] == null  && data != 'completed'){
                            return 'incomplete'
                        }else{
                            switch (data) {
                                case 'completed':
                                    return 'Approved and Completed'
                                default:
                                    return 'Needs Approval'
                }
                        }
                    }
                }
            },
            {
                data:'process_by',
                searchable:false,
                render: function(data,type,row){
                    return data == null ? 'n/a' : data['name']
                }
            }
        ]
    });
    
    audit_user_partner_deposits = $('#auditUserModal #audituser-table-partner-deposits').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'partner_deposits']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {   data:'partner.partner_name',name:'partner.partner_name' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'status',
                name:'status',
                render: function(data,type,row) {
                    if(row['picture'] == null && data < 1 ) {
                        return "Incomplete";
                    }
                    else{
                        switch(data){
        case "1":
                            case 1:
                                return "Approved"; break;
        case "2":
                            case 2:
                                return "Rejected"; break;
                            default: 
                                return "Needs Approval";
                        }
                    }
                }
            }
        ]
    });
    
    audit_user_partner_cashouts = $('#auditUserModal #audituser-table-partner-cashouts').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'partner_cashouts']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'code',name:'code' },
            {   data:'partner.partner_name',name:'partner.partner_name' },
            {
                data:'amount',
                name:'amount',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'status',
                name:'status',
                render: function(data,type,row) {
                    if(row['picture'] == null && data < 1 ) {
                        return "Incomplete";
                    }
                    else{
                        switch(data){
                            case 1:
                            case '1':
                                return "Approved"; break;
                            case 2:
                            case '2':
                                return "Rejected"; break;
                            case -1:
                            case '-1': 
                                return "Pending Admin Verification"; break;
                            default: 
                                return "Needs Approval";
                        }
                    }
                }
            }
        ]
    });

    audit_user_rebates = $('#auditUserModal #audituser-table-rebates').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'rebate']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'transaction.code',name:'transaction.code' },
            {
                data:'transaction.discrepancy',
                name:'transaction.discrepancy',
                render: function (data,type,row) {
                    if(data.length > 0){
                        var _dis = $.grep(data,function(discrepancy){
                            if(discrepancy.amount){
                                return discrepancy;
                            }
                        });
                        return _dis.length > 0 ? '&#8369; '+numberWithCommas(_dis[_dis.length - 1].amount) : 
                                '&#8369; '+numberWithCommas(row.transaction.amount);
                    }else{
                        return '&#8369; '+numberWithCommas(row.transaction.amount)
                    }
                }
            },
            {
                data:'collected',
                name:'collected',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'transfered',
                name:'transfered',
                searchable: false,
                orderable: false,
                render: function(data,type,row){
                    return data ? 'Transfered' : 'Not Transfered';
                }
            },
        ]
    });

    audit_user_rewards = $('#auditUserModal #audituser-table-rewards').DataTable({
        searching: false,
        lengthChange: false,
        processing: true,
        serverSide:true,
        responsive: true,
        ajax: "{!! route('get-audit-data', ['type' => 'rewards']) !!}",
        columns: [
            {
                data:'created_at',
                name:'created_at',
            },
            {   data:'type',name:'type' },
            {
                data:'credits',
                name:'credits',
                render: function(data,type,row){
                    return '&#8369; '+numberWithCommas(data);
                }
            },
            {
                data:'description',
                name:'description'
            },
            {
                data:'added_by.name',
                name:'added_by.name',
            },
        ]
    });


    $(document).on('click', '.audit-user', function(e) {
      e.preventDefault();
      const user = {
        id : $(this).data('user-id'),
        name: $(this).data('user-name')
      }

      showAuditUser(user);
    });

  });



  function showAuditUser(user) {
      $.each($('#auditUserModal .panel-heading span.clickable'), function() {
          if(!$(this).hasClass('panel-collapsed')) {
              $(this).parents('.panel').find('.panel-body').slideUp();
              $(this).addClass('panel-collapsed');
              $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
          }
      });
      $.ajax({
          url: '{{ route("user-audit-info") }}',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          type: 'POST',
          data: { userid: user.id },
          success: function(data) {
              
              if (data.success) {
                  var total_rewards = parseFloat(data.total_rewards);
                  var total_rebates = parseFloat(data.total_rebate);
                  var total_deposits = parseFloat(data.ez_deposit) + parseFloat(data.partner_deposit);
                  var total_cashouts = parseFloat(data.ez_cashout) + parseFloat(data.partner_cashout);
                  var total_a = total_cashouts + parseFloat(data.curr_bets) + parseFloat(data.user_credit);
                  var total_b = total_deposits + parseFloat(data.profit_loss) + parseFloat(data.total_rebate) + parseFloat(data.total_rewards);
                  $('#auditUserModal .username').html(user.name + ' (Ez credit: ' + numberWithCommas(parseFloat(data.user_credit).toFixed(2)) + ')');
                  $('#auditUserModal .curr_credits').html(numberWithCommas(parseFloat(data.user_credit).toFixed(2)));
                  $('#auditUserModal .curr_bets').html(numberWithCommas(data.curr_bets.toFixed(2)));
                  $('#auditUserModal .profit_loss').html(numberWithCommas(data.profit_loss.toFixed(2)));
                  $('#auditUserModal .total_deposit').html(numberWithCommas(total_deposits.toFixed(2)));
                  $('#auditUserModal .total_cashout').html(numberWithCommas(total_cashouts.toFixed(2)));
                  $('#auditUserModal .total_rebate').html(numberWithCommas(total_rebates.toFixed(2)));
                  $('#auditUserModal .total_rewards').html(numberWithCommas(total_rewards.toFixed(2)));
                  $('#auditUserModal .total_fa').html(numberWithCommas(total_a.toFixed(2)));
                  $('#auditUserModal .total_fb').html(numberWithCommas(total_b.toFixed(2)));
              }
          },
          fail: function(xhr, status, error) {
              console.log(error);
          }
      });
      audit_user_bets.ajax.url("{!! route('get-audit-data', ['type' => 'bets']) !!}&userid="+user.id).load();
      audit_user_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'deposits']) !!}&userid="+user.id).load();
      audit_user_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'cashouts']) !!}&userid="+user.id).load();
      audit_user_partner_deposits.ajax.url("{!! route('get-audit-data', ['type' => 'partner_deposits']) !!}&userid="+user.id).load();
      audit_user_partner_cashouts.ajax.url("{!! route('get-audit-data', ['type' => 'partner_cashouts']) !!}&userid="+user.id).load();
      audit_user_rebates.ajax.url("{!! route('get-audit-data', ['type' => 'rebate']) !!}&userid="+user.id).load();
      audit_user_rewards.ajax.url("{!! route('get-audit-data', ['type' => 'reward']) !!}&userid="+user.id).load();
  }  
</script>