<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('intro', function () {
    return view('intro');
});

Route::get('careers', function () {
    return view('careers');
});

Route::get('careers', function () {
    return view('careers');
});

Route::get('policy', function () {
    return '2ez.bet policy';
});

Route::get('partner', function () {
    return view('partner');
});

Route::get('/partner/new-captcha-image', function() {
    return captcha_img('flat');
});

Route::get('/listrecentmatches', 'HomeController@showRecentMatches');

Route::post('partner', 'Controller@sendPartnerForm')->name('sendpartnerform');

// Route::get('public_image/{filename}', function($filename) {
//     $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
//     $filePath = storage_path('uploads') . '/' . $filename;
//     if (File::exists($filePath) && File::extension($filePath) != 'php') {
//         $contentType = mime_content_type($filePath);
//         if (in_array($contentType, $allowedMimeTypes)) {
//             $img = \Image::make($filePath);
//             return $img->response();
//         } else
//             return abort(404);
//     } else
//         return abort(404);
// });

Auth::routes();
Route::get('/referral/{referral_code}', function($referral_code){
    // request()->session()->flash('referral_code', $referral_code);
    // request()->session()->put('referral_code', $referral_code);
    return view('auth.register')->with('referral_code',$referral_code);
})->name('register.referral');

Route::get('/imp/{uid}', 'HomeController@impersonate');
Route::get('/logout', 'Auth\LoginController@logout');

// OAuth Routes
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');

Route::get('/2eztos', 'TransactionController@showTos');
Route::post('/2eztos', 'TransactionController@ackTos');
Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/setmatches', function() {
//     \Dota2Api\Api::init('1E679656D7D073718C5521AFF75F4C36', array('localhost', 'root', 'passw0rd123', 'dota2', 'dota2api_')); 
//     $leaguesMapperWeb = new Dota2Api\Mappers\LeaguesMapperWeb();
//     $leagues = $leaguesMapperWeb->load();
// //    echo '<code>';
// //    foreach ($leagues as $league) {
// //        print_r($league);
// //        $leagueMapper = new Dota2Api\Mappers\LeaguesMapperDb();
// //        $leagueMapper->save($league);
// //    }
// //    echo '</code>';
//     foreach($leagues as $league) {
//         $leagueMapper = new Dota2Api\Mappers\LeagueMapper($league->get('league_id'));
//         $liveGames = $leagueMapper->load();
//         if($liveGames) {
//             foreach($liveGames as $game) {
//                 echo 'Saving game from league: ' . $league->get('league_id');
//                 $liveMatch = new Dota2Api\Mappers\LiveMatchMapperDb();
//                 $liveMatch->save($game);
//             }
//         }
//     }
// });

// Categories routes
Route::get('/match/dota2/{ptr}', 'HomeController@dota2index');
Route::get('/match/csgo/{ptr}', 'HomeController@csgoindex');
Route::get('/match/sports/{ptr}', 'HomeController@sportsindex');
Route::get('/match/loadAll/{ptr}', 'HomeController@allmatches');
Route::get('/match/lol/{ptr}', 'HomeController@lolindex');
Route::get('/match/nbaplayoffs/{ptr}', 'HomeController@nbaplayoffsindex');

// Show more categories routes
Route::get('/match/loadDota/{ptr}', 'HomeController@showDotaMatches');
Route::get('/match/loadCsgo/{ptr}', 'HomeController@showCsgoMatches');
Route::get('/match/loadSports/{ptr}', 'HomeController@showSportsMatches');
Route::get('/match/loadLol/{ptr}', 'HomeController@showLolMatches');
Route::get('/match/loadNbaPlayoffs/{ptr}', 'HomeController@showNbaPlayoffsMatches');
Route::get('/match/load/{ptr}', 'HomeController@showMoreMatches');
Route::get('/matches/open-live/{type}/{offset?}/{take?}','MatchController@getOpenLiveMatches')->name('matches.open-live');

Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/badges', 'AdminController@getAllBadges')->name('get_all_badges');
Route::post('/admin/badges/setbadges', 'AdminController@addEditBadges')->name('setbadges');
Route::delete('/admin/badges/setbadges', 'AdminController@deleteBadges')->name('json_badge_delete');
Route::post('/admin/badges/assignbadges', 'AdminController@assignBadges')->name('assignbadges');
Route::post('/match/addbet', 'BetController@addMatchBet')->name('json_match_addbet');
Route::post('/match/winningamount', 'BetController@getPossibleWinningPerUser')->name('json_match_possible_winning');
Route::get('/match/{match}', 'MatchController@viewMatch')->name('match.view');
Route::post('/tournament/winningamount', 'BetController@getTournamentPossibleWinningPerUser')->name('json_tournament_possible_winning');
Route::post('/match/updatebet', 'BetController@updateMatchBet')->name('json_match_updatebet');
// Route::post('/match/switchbet', 'BetController@switchTeamBet')->name('json_match_switchteam'); //disabled for security issues
Route::delete('/match/cancel', 'BetController@cancelMatchBet')->name('json_matches_cancelbet');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/report/match', 'MatchManagerController@returnMatchReport')->name('report_match');
Route::get('/report/league', 'MatchManagerController@returnLeagueReport')->name('report_league');
Route::get('/report/league/mail', 'MatchManagerController@returnLeagueReportMail')->name('report_league.mail');
Route::get('/admin/rewards', 'AdminController@getAllRewards')->name('get_all_rewards');
Route::post('/admin/addrewards', 'AdminController@addManualRewards')->name('set_user_rewards');
Route::get('/admin/reset-credits', 'AdminController@resetCredits')->name('reset-credits');
Route::post('/admin/generic-update-status', 'AdminController@updateStatus')->name('generic-update-status');
Route::post('/admin/transaction/{transaction}/reject', 'AdminController@rejectTransaction')->name('rejectTransaction');
Route::get('/admin/audit/get-user-auditdata', 'AdminController@getAuditData')->name('get-audit-data');
Route::post('/admin/audit/auditinfo', 'AdminController@getUserAuditInfo')->name('user-audit-info');
Route::delete('/admin/canceladminbet', 'MatchManagerController@cancelAdminBet')->name('cancel-admin-bet');
Route::delete('/admin/remove-admin-bets', 'MatchManagerController@removeAdminBets')->name('remove-admin-bets');
//remove double bet
Route::delete('/admin/remove-duplicate-bet', 'MatchManagerController@removeDuplicateBet')->name('remove-duplicate-bet');
Route::delete('/admin/cancel-bnd-bet', 'MatchManagerController@removeBndBet')->name('cancel-bnd-bet');
//
Route::put('/admin/update-match-more-info-link', 'MatchManagerController@updateMoreInfoLink')->name('admin.update-more-info-link');
Route::put('/admin/update-both-admin-bets', 'MatchManagerController@updateBothAdminBets')->name('update-both-admin-bets'); //changing admin bets at the same time route
Route::post('/admin/update-tournament-admin-bets', 'MatchManagerController@updateTournamentAdminBets')->name('update-tournament-admin-bets'); //changing admin bets at the same time route
Route::put('/mm/update-bnd-threshold-percentages', 'MatchManagerController@updateMatchesBndThresholds')->name('update-bnd-threshold-percentages'); //changing admin bets at the same time route
Route::get('/mm/match/match-submatches-details','MatchManagerController@getMatchSubDetails')->name('admin.get.match.subs.details');
Route::put('/admin/update-match-stream-links', 'MatchManagerController@updateStreamLinks')->name('admin.update-stream-links');
Route::post('/admin/setsitesettings', 'AdminController@setSiteSettings')->name('set-site-settings');
Route::post('/admin/payout', 'AdminController@payoutUser')->name('payout.user');
Route::get('/admin/get-all-payout', 'AdminController@getAllPayout')->name('get.all.payout');
Route::get('/rebates', 'AdminController@getRebates')->name('get-rebates');
Route::get('/earnings', 'AdminController@getEarnings')->name('get-earnings');
Route::get('/bet-log/{user_id?}', 'AdminController@getBetLogs')->name('bet-log');
Route::post('/admin/add-site-account', 'AdminController@addSiteAccount')->name('admin.site.account');
Route::get('/admin/get-site-accounts','AdminController@getSiteAccountList')->name('admin.site.account.get');
Route::put('/admin/update-site-accounts','AdminController@updateSiteAccount')->name('admin.site.account.update');
Route::delete('/admin/delete-site-accounts','AdminController@deleteSiteAccount')->name('admin.site.account.delete');


Route::get('/matchmanager', 'MatchManagerController@index')->name('matchmaker');
Route::patch('/matchmanager/setleaguestatus', 'MatchManagerController@setLeagueStatus')->name('set_league_status');
Route::patch('/matchmanager/tournament/setfavorite', 'MatchManagerController@setFavoriteTeam')->name('json_set_favorite_team');
Route::post('/matchmanager/setleagues', 'MatchManagerController@addEditLeagues')->name('setleagues');
Route::delete('/matchmanager/setleagues', 'MatchManagerController@deleteLeagues')->name('delete_league');
Route::get('/matchmanager/getallteams', 'MatchManagerController@getAllTeams')->name('get_all_teams');
Route::post('/matchmanager/setteams', 'MatchManagerController@addEditTeams')->name('setTeams');
Route::delete('/matchmanager/setteams', 'MatchManagerController@deleteTeam')->name('delete_team');
Route::get('/matchmanager/showleagues', 'MatchManagerController@listLeagues')->name('showleagues');
Route::get('/matchmanager/showmatches', 'MatchManagerController@listMatches')->name('showmatches');
Route::post('/matchmanager/setmatches', 'MatchManagerController@addEditMatches')->name('setmatches');
Route::delete('/matchmanager/delmatches', 'MatchManagerController@deleteMatches')->name('json_matches_delete');
Route::post('/matchmanager/settlematches', 'MatchManagerController@settleMatches')->name('json_matches_settle');
Route::put('/matchmanager/undo-settled-match','MatchManagerController@revertToOngoing')->name('json_matches_undo_settled');
Route::post('/matchmanager/lock-match-betting','MatchManagerController@lockForMatchBetting')->name('json_lock_match_betting');
Route::post('/matchmanager/unlock-match-betting','MatchManagerController@unlockForMatchBetting')->name('json_unlock_match_betting');
Route::patch('/matchmanager/setmatcheopen', 'MatchManagerController@setMatchOpen')->name('json_matches_setopen');
Route::post('/matchmanager/settletournament', 'MatchManagerController@settleTournament')->name('settle_tournament');
Route::get('/matchmanager/listteams/{type?}', 'MatchManagerController@getAllTeamsRaw')->name('list_all_teams');
Route::get('/matchmanager/listgametypes', 'MatchManagerController@listGameTypes')->name('list_game_types');
Route::post('/matchmanager/setgametypes', 'MatchManagerController@addEditGameTypes')->name('setGameTypes');
Route::delete('/matchmanager/delgametypes', 'MatchManagerController@delGameTypes')->name('del_game_type');
Route::get('/matchmanager/getongoingleagues', 'MatchManagerController@getOngoingLeagues');
Route::get('/matchmanager/showteam/{league}', 'MatchManagerController@showRawTeamsType');
Route::post('/matchmanager/extendmatchtime', 'MatchManagerController@extendMatchTime')->name('extend_match_time');
Route::post('/matchmanager/editmatch', 'MatchManagerController@editMatch')->name('edit_match_page');
//Route::post('/matchmanager/editmatch', 'MatchManagerController@editMatchStanding')->name('edit_match_settled');//edit settled matches score
Route::post('/matchmanager/setscore', 'MatchManagerController@editMatchStanding')->name('setscore');//setscore
Route::get('/matchmanager/getsubmatches/{mainmatch}', 'MatchManagerController@showSubMatches');
Route::post('/matchmanager/editmatchbet', 'MatchManagerController@editMatchManagerBet')->name('json_match_editbet');
Route::get('/matchmanager/leagues/active', 'MatchManagerController@getActiveLeagues')->name('leagues.active');
Route::put('/matchmanager/leagues/update/order', 'MatchManagerController@updateLeagueDisplayOrder')->name('leagues.update.order');

// Route::get('/admin/match/{match}/save-match-report', 'AdminController@saveMatchReport')->name('save-match-report');
Route::get('/admin/match/{match}', 'AdminController@viewReport');
Route::get('/admin/dashboard', 'AdminController@dashboard');
Route::get('/admin/getAllReferalsProfile', 'AdminController@getAllReferalsProfile')->name('get-all-referrals');
Route::get('/admin/fees', 'AdminController@getAllFees')->name('get-all-fees');
Route::get('/admin/get-affliates','AdminController@getAffliates')->name('admin.get.affliates');
Route::put('/admin/update-affliates','AdminController@updateAffliates')->name('admin.update.affliates');
Route::get('/admin/get-sub-agents','AdminController@getSubAgents')->name('admin.get.subagents');
Route::put('/admin/update-subagents','AdminController@updateSubAgents')->name('admin.update.subagents');
Route::get('/admin/get-partner-sub-users','AdminController@getPartnerSubUsers')->name('admin.get.partner.sub-users');
Route::put('/admin/update-partner-sub-users','AdminController@updatePartnerSubUsers')->name('admin.update.partner.sub-users');

// Market Items
Route::post('/add-market-item', 'AdminController@addMarketItem')->name('add-market-item');
Route::get('/market-item-list', 'AdminController@getMarketItemList');
Route::get('/market', 'HomeController@displayItemMarket');
Route::post('/buy-item', 'MarketController@userPurchase');

// Transactions routes
Route::get('/purchase/{via?}/{partner_id?}', 'TransactionController@deposit')->name('deposit.page');
Route::get('/transfer/{via?}/{partner_id?}', 'TransactionController@cashout')->name('cashout.page');
Route::post('/deposit', 'TransactionController@createUpdate')->name('transact');
Route::post('/depoist/check-vc','TransactionController@checkVoucherCodeViaDirect')->name('deposit.check.voucher_code');
Route::post('/upload', 'TransactionController@uploadPicture')->name('upload');
Route::get('/unitTest', 'TransactionController@unitTest');
Route::get('/balance', 'TransactionController@getBalance');
Route::get('/transactions', 'TransactionController@getAllTransaction')->name('get_all_transactions');
Route::get('/transactions/profile', 'TransactionController@profileTransaction')->name('get_profile_transactions');
Route::post('/admin/transaction/setStatus', 'AdminController@setStatus')->name('set_status');
Route::get('/transaction/images/{filename}', 'TransactionController@getTransactionImage');
Route::post('/admin/transaction/adminExtraAction', 'AdminController@adminExtraAction')->name('adminExtraAction');
Route::post('/admin/transaction/approveWDiscrepancy', 'AdminController@approveWDiscrepancy')->name('approveWDiscrepancy');
Route::delete('/admin/transaction/delete', 'AdminController@deleteTransaction')->name('delete_transaction');
Route::get('/transaction/referrals', 'TransactionController@getAllReferalsProfile')->name('get-user-referals');
Route::get('/users/rewards', 'TransactionController@getUserRewards')->name('get-user-rewards');
Route::get('/users/gift-codes', 'TransactionController@getUserGiftCodes')->name('get-user-gift-codes');
Route::post('/transaction/add-report-bug', 'TransactionController@addReportedBug')->name('add-report-bug');
Route::post('/transaction/add-promotion', 'TransactionController@addPromotion')->name('add-promotion');
Route::get('/reported-bugs', 'TransactionController@getAllReportedBugs')->name('get-all-reported-bugs');
Route::get('/reported-bugs/showimage/{bug}', 'TransactionController@showBugImage');
Route::get('/promos', 'TransactionController@getAllPromo')->name('get-all-promo');
Route::get('/profile/commisions', 'TransactionController@profileCommissions')->name('get-commissions-profile');
Route::get('/profile/commisions-partners', 'TransactionController@profileCommissionsPartners')->name('get-commissions-partners-profile');
Route::get('/profile/commisions-bets', 'TransactionController@profileCommissionsBets')->name('get-commissions-bets-profile');
Route::get('/profile/commisions-subs-total', 'TransactionController@profileSubStreamersTotalCommissionsBets')->name('get-commissions-subs-total-profile');
Route::get('/user/depositCommissions', 'TransactionController@depositCommissions')->name('get-deposit-commissions');
Route::get('/profile/rebates', 'TransactionController@getRebates')->name('get-rebates-profile');
Route::post('/profile/wallet/transfer', 'TransactionController@transferWallet')->name('get-wallet-transfer');
Route::get('/my-purchase/{purchase_type}/{purchase_id}', 'TransactionController@myPurchase');
Route::post('/my-purchase/{purchase_type}/{purchase_id}', 'TransactionController@myPurchaseChange');

Route::post('/tournament/updatebet', 'BetController@updateTournamentBet')->name('json_tournament_updatebet');
Route::post('/tournament/addbet', 'BetController@addTournamentBet')->name('json_tournament_addbet');
Route::post('/tournament/update-tbd-team', 'MatchManagerController@updateLeagueTbdTeam')->name('adminUpdateLeagueTbdTeam');
Route::get('/tournament/userbets', 'BetController@getUserBets')->name('json_tournament_userbets');
Route::get('/tournament/allbets', 'AdminController@getAllBets')->name('json_tournament_allbets'); //moved to AdminController from BetsController so that only admin can see this data
Route::delete('/tournament/cancelbet', 'BetController@cancelTournamentBet')->name('json_tournament_cancelbet');
Route::get('/transactions/donations', 'TransactionController@getAllDonations')->name('get_donations_transactions');
Route::get('/transactions/commissions', 'TransactionController@getAllCommissions')->name('get_commissions_transactions');
Route::get('/transactions/commissions-partners', 'TransactionController@getAllCommissionsPartners')->name('get_commissions_partners_transactions');
Route::get('/transactions/commissions-bets', 'TransactionController@getAllCommissionsBets')->name('get_commissions_bets_transactions');
Route::get('/users', 'AdminController@getAllUsers')->name('users');
Route::post('/users/voucher', 'AdminController@assignVoucherCodeToUser')->name('add_update_voucher');
Route::post('/users/reset/password', 'AdminController@resetPassword')->name('reset_password');
Route::post('/users/changeRole', 'AdminController@changeRole')->name('change_role');
Route::post('/users/addRole', 'AdminController@addRole')->name('add_role');
Route::post('/users/addCredits', 'AdminController@addUserCredits')->name('set_user_credits');
Route::post('/users/requestbetatester', 'HomeController@requestBetaTester')->name('request_betatester');
// Route::get('/leader-board', 'HomeController@leaderBoard')->name('leader-board'); //removed function leaderBoard in HomeController doesnt exists
Route::get('/tournament/{league}', 'BetController@tournamentBettings')->name('tournament');
Route::post('/users/update-ban-status', 'AdminController@updateBanStatus')->name('update_ban_status');

//Partner Routes
Route::get('/agent', 'AgentController@agentPage')->name('agent');
Route::get('/agent/credits', 'AgentController@partnerRemainingCredits')->name('get_partner_credits');
Route::get('/agent/currentuser/credits', 'AgentController@remainingCredits')->name('get_user_credits');
Route::get('/agent/users/transactions', 'AgentController@partnerUserTransactions')->name('get_partner_users_transactions');
Route::get('/agent/sub-agents/transactions', 'AgentController@partnerSubAgentTransactions')->name('get_partner_agent_transactions');
Route::get('/agent/admin/transactions', 'AgentController@partnerAdminTransactions')->name('get_partner_admin_transactions');
Route::get('/agent/admin/buy_sell_transactions', 'AgentController@partnerBuySellAdminTransactions')->name('get_partner_buy_sell_admin_transactions');
Route::post('/agent/users/deposits', 'AgentController@acceptDeclineUserDeposits')->name('json_partner_user_deposit');
Route::post('/agent/users/cashouts', 'AgentController@acceptDeclineUserCashouts')->name('json_partner_user_cashout');
Route::post('/agent/initiate/deposits', 'AgentController@sendDepositOnPartner')->name('deposit_to_partner');
Route::post('/agent/initiate/cashouts', 'AgentController@sendCashoutOnPartner')->name('cashout_to_partner');
Route::post('/agent/information/submit', 'AgentController@setPartnerInformation')->name('json_set_partner_info');
Route::post('/agent/information/update', 'AgentController@updatePartnerInformation')->name('json_update_partner_info');
Route::post('/agent/credits/deposits', 'AgentController@sendDepositOnAdmin')->name('deposit_to_admin');
Route::post('/agent/credits/deposits-parent', 'AgentController@sendDepositOnParent')->name('deposit_to_parent');
// Route::post('/agent/credits/cashouts', 'AgentController@sendDepositOnAdmin')->name('cashout_to_admin');
Route::post('/agent/upload', 'AgentController@uploadPicture')->name('upload_partner');
Route::get('/agent/users/transactions/dashboard', 'AgentController@partnerUserTransactionsDashboard')->name('dash_partner_users_transactions');
Route::get('/agent/admin/transactions/dashboard', 'AgentController@partnerAdminTransactionsDashboard')->name('dash_partner_admin_transactions');
Route::get('/agent/lists', 'AgentController@allPartners')->name('partners');
Route::post('/agent/verified', 'AgentController@partnerStatus')->name('partner_status');
Route::post('/agent/show-hide-in-site', 'AgentController@partnerShowHide')->name('partner_show_in_site');
Route::post('/agent/transactions/approved', 'AgentController@approvedTransactions')->name('partner_transactions');
Route::post('/agent/transactions/declined', 'AgentController@declinedTransactions')->name('declined_transactions');
Route::get('/agent/earnings', 'AgentController@partnerPayouts')->name('get_partner_payouts');
Route::post('/agent/logo', 'AgentController@setPartnerLogo')->name('set_partner_logo');
Route::post('/agent/transaction/adminExtraActionOnPartner', 'AgentController@adminExtraActionOnPartner')->name('adminExtraActionOnPartner');
Route::post('/agent/transaction/partnerDiscrepancy', 'AgentController@partnerDiscrepancy')->name('partnerDiscrepancy');
// Route::post('/agent/transaction/agent_admin/receipt', 'AgentController@setReceipt')->name('update_partner_receipt'); //method does not exists in AgentController
Route::get('/agent/list/transaction_labels', 'AgentController@allPartnersForTransact')->name('get_partners_list');
Route::get('/agent/list/transaction_tables', 'AgentController@payoutDatatable')->name('partner_payouts');
Route::post('/agent/download/transactions', 'AgentController@downloadTransactions')->name('partner.download.transactions');
Route::get('/agent/download-view/transactions', 'AgentController@downloadTransactionsView')->name('partner.download.transactions.view');
Route::get('/agent/download-file/transactions','AgentController@downloadTransactionFile')->name('partner.download.transactions.file');

Route::post('/agent/payouts/process', 'AgentController@processPayout')->name('process_payout');
Route::post('/agent/profiles/update', 'AgentController@publicProfile')->name('public_profile');

// Routes for misc pages
Route::get('/faq', 'FaqController@faqPage')->name('faq');
Route::get('/faq/questions/all', 'FaqController@getExistingFaqs')->name('allFaq');
Route::post('/faq/questions/new', 'FaqController@addNewQuestion')->name('addFaq');
Route::post('/faq/questions/update', 'FaqController@updateQuestion')->name('updateFaq');
Route::post('/faq/questions/delete', 'FaqController@deleteQuestions')->name('deleteFaq');
Route::get('/termsandconditions', 'FaqController@viewTOSContent');
Route::post('/termsandconditions/update', 'FaqController@updateTOS')->name('updateTOS');


//Gift codes
Route::get('/gift-codes/list','GiftCodesController@list')->name('gc.list');
Route::post('/gift-codes','GiftCodesController@create')->name('gc.create');
Route::put('/gift-codes','GiftCodesController@update')->name('gc.update');
Route::delete('/gift-codes','GiftCodesController@delete')->name('gc.delete');
Route::post('gift-codes/claim','HomeController@processClaimGiftCode')->name('gc.claim');

//Messages
Route::post('/user-messages','UserMessagesController@create')->name('user.message.create');
Route::put('/user-messages','UserMessagesController@update')->name('user.message.update');
Route::delete('/user-messages','UserMessagesController@delete')->name('user.message.delete');
Route::get('/user-messages/list','UserMessagesController@list')->name('user.message.list');
Route::put('/user-messages/read','UserMessagesController@read')->name('user.message.read');

//Verifying user
Route::post('/user-verify-2ez','AdminController@markAsVerified')->name('user.mark.verified');
Route::get('/user-verify-by-partners','AdminController@getUserPartnersVerified')->name('user.verified.by.partners'); //user.verified.by.partners
Route::post('/user-verify-by-partner','AgentController@markAsVerified')->name('user.mark.verified.partner');
Route::post('/set-user-voucher-as-tims','AgentController@setVoucherAsTims')->name('user.set.voucher.tims'); // set tims voucher code for user that doesnt have voucher code yet
Route::get('/user-verify-by-partners-vp','AgentController@getUserPartnersVerified')->name('get.user.verified.by.partners'); //user.verified.by.partners

//Roullete Spins
// Route::post('/roulette-spins/validate','RouletteController@validateSpin')->name('roulette.spins.validate');
// Route::post('/roulette-spins/save','RouletteController@saveSpin')->name('roulette.spins.save');
// Route::post('/roulette-spins/pre-save','RouletteController@preSaveSpinCheck')->name('roulette.spins.pre-save');
// Route::get('/roulette-spins/message-havent-spinned-today','RouletteController@messageUsersWhoHaventSpin')->name('roulette.spins.message');

//flip chinese new year 2021
// Route::post('/chinese-new-year/validate-flip','RouletteController@validateFlip')->name('card.flip.validate');
// Route::post('/chinese-new-year/pre-save','RouletteController@preSaveSpinCheck')->name('card.flip.pre-save');
// Route::post('/chinese-new-year/save','RouletteController@saveFlip')->name('roulette.spins.save');

//Easter egg event 2021
Route::post('/easter-egg/validate-flip','RouletteController@validateFlip')->name('card.flip.validate');
Route::post('/easter-egg/pre-save','RouletteController@preSaveSpinCheck')->name('card.flip.pre-save');
Route::post('/easter-egg/save','RouletteController@saveFlip')->name('roulette.spins.save');


if( !app()->environment('prod') ){
    Route::get('/roulette-spins/test','RouletteController@testSpinTime')->name('roulette.spins.test');
    Route::get('/chinese-new-year/test','RouletteController@validateFlip')->name('card.flip.validate');
}


Route::put('/cache/flush','HomeController@cacheFlush')->name('user.message.read');

//Reset password or change password by user
Route::put('/user/change-password','HomeController@resetPassword')->name('user.change-password');
Route::put('/user/deactivate-account', 'HomeController@deactivateAccount')->name('user.deactivate-account');
Route::put('/user/reactivate-account', 'HomeController@reactivateAccount')->name('user.reactivate-account');

if( !app()->environment('prod') ){
    Route::get('mock-smr','MatchController@mockSendMatchReport')->name('mock.smr');
    Route::get('mock-set-commissions-bets','MatchManagerController@mockSetCommissionsBet')->name('mock.scb');
}


//Push notification routes
Route::post('/push','PushController@store');
Route::get('/push','PushController@push')->name('push');

//affliate redirect with voucher code
Route::get('/affliate/{voucher_code}', 'HomeController@userAffliation');
Route::get('/affiliate/{voucher_code}', 'HomeController@userAffliation');
Route::post('/affiliate/download-voucher-users','MatchController@downloadAffliateVoucherCodeUsers')->name('affiliate.download.voucher.users');
Route::post('/affiliate/get-new-voucher-users','MatchController@getAffliateNewVoucherCodeUsers')->name('affiliate.get.voucher.users');


//bnd controller stuff
Route::get('/bnd/matches/active','BndController@showMatches')->name('bnd.matches.active');
Route::get('/bnd/{match}', 'BndController@viewMatchSettings')->name('bnd.match.view');
Route::get('/bnd-test/{match}', 'BndController@viewMatchSettingsTest')->name('bnd.match.view-test');