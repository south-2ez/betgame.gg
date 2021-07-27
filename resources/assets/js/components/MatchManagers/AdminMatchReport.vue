<template>
	<div class="m-container1" style="width: 98% !important">
		<div class="main-ct" style="margin-bottom: 0">
			<div class="title">Admin Report</div>
			<div class="clearfix"></div>
			<div class="matchmain">
				<div class="col-md-12" style="max-height: 500px; overflow: auto">
					<div :class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'">
						<table id="team_a_table" class="table table-striped" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>BET ID</th>
									<th>{{ match.team_a_name }} Bettors</th>
									<th>Odds</th>
									<th>Bet Amount</th>
									<th>Profit/Loss</th>
								</tr>
							</thead>
							<tbody>
								<tr
									v-for="bet in adminTeamAbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>
										<span v-else></span>
										<span v-if="userBetCount[bet.user_id] > 1 && match.match_status == 'open'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Remove D.Bet
											</button>
										</span>
										<span v-else-if="userBetCount[bet.user_id] > 1 && match.match_status == 'ongoing'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Replace with BND
											</button>
										</span>
									</td>
								</tr>

								<tr
									v-for="bet in teamAbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>

										<!-- remove/replace double bet btn -->
										<span v-if="userBetCount[bet.user_id] > 1 && match.match_status == 'open'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Remove D.Bet
											</button>
										</span>
										<span v-else-if="userBetCount[bet.user_id] > 1 && match.match_status == 'ongoing'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Replace with BND
											</button>
										</span>
										<!-- end remove/replace double bet btn -->

										<!-- bnd bet cancel -->
										<span v-if="bet.user_id == bndMainId && match.match_status == 'open'">
											&nbsp;
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="cancelBndBet(bet.id)"
											>
												Remove BND Bet
											</button>
										</span>
										<!--end bnd bet cancel-->
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td>
										<strong>&#8369; {{ match.team_a_bets | formatMoney }}</strong>
									</td>
									<td>
										<span
											v-if="match.match_status == 'settled'"
											:class="match.match_winner == match.team_a_id ? 'text-green' : 'text-red'"
										>
											<strong>&#8369; {{ Math.abs(totalTeamAProfitLoss) | formatMoney }}</strong>
										</span>
										<span v-else></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- for draw -->
					<div v-if="!!match.team_c_id" :class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'">
						<table id="team_c_table" class="table table-striped" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>BET ID</th>
									<th>{{ match.team_c_name }} Bettors</th>
									<th>Odds</th>
									<th>Bet Amount</th>
									<th>Profit/Loss</th>
								</tr>
							</thead>
							<tbody>
								<tr
									v-for="bet in adminTeamCbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>
										<span v-else></span>
										<span v-if="userBetCount[bet.user_id] > 1 && match.match_status == 'open'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Remove D.Bet
											</button>
										</span>
										<span v-else-if="userBetCount[bet.user_id] > 1 && match.match_status == 'ongoing'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Replace with BND
											</button>
										</span>
									</td>
								</tr>

								<tr
									v-for="bet in teamCbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>

										<!-- remove/replace double bet btn -->
										<span v-if="userBetCount[bet.user_id] > 1 && match.match_status == 'open'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Remove D.Bet
											</button>
										</span>
										<span v-else-if="userBetCount[bet.user_id] > 1 && match.match_status == 'ongoing'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Replace with BND
											</button>
										</span>
										<!-- end remove/replace double bet btn -->

										<!-- bnd bet cancel -->
										<span v-if="bet.user_id == bndMainId && match.match_status == 'open'">
											&nbsp;
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="cancelBndBet(bet.id)"
											>
												Remove BND Bet
											</button>
										</span>
										<!--end bnd bet cancel-->
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td>
										<strong>&#8369; {{ match.team_c_bets | formatMoney }}</strong>
									</td>
									<td>
										<span
											v-if="match.match_status == 'settled'"
											:class="match.match_winner == match.team_c_id ? 'text-green' : 'text-red'"
										>
											<strong>&#8369; {{ Math.abs(totalTeamCProfitLoss) | formatMoney }}</strong>
										</span>
										<span v-else></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- end for draw -->

					<div :class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'">
						<table id="team_b_table" class="table table-striped" width="100%">
							<thead>
								<tr>
									<th>ID</th>
									<th>BET ID</th>
									<th>{{ match.team_b_name }} Bettors</th>
									<th>Odds</th>
									<th>Bet Amount</th>
									<th>Profit/Loss</th>
								</tr>
							</thead>
							<tbody>
								<tr
									v-for="bet in adminTeamBbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>
										<span v-else></span>
									</td>
								</tr>

								<tr
									v-for="bet in teamBbets"
									:key="`bet-id-${bet.id}`"
									:class="[
										bet.user.type != 'user' ? 'bg-admin-bet' : '',
										userBetCount[bet.user_id] > 1 ? 'bg-double-bet' : ''
									]"
								>
									<td>{{ bet.user_id }}</td>
									<td>{{ bet.id }}</td>
									<td>{{ bet.user.name }}</td>
									<td>{{ bet.ratio }}</td>
									<td>&#8369; {{ bet.amount | formatMoney }}</td>
									<td>
										<span v-if="match.match_status == 'settled'" :class="bet.gains > 0 ? 'text-green' : 'text-red'"
											>&#8369; {{ Math.abs(bet.gains) | formatMoney }}</span
										>
										<span
											v-else-if="
												(match.match_status == 'open' || match.match_status == 'ongoing') && bet.user.type != 'user'
											"
										>
											<button type="button" class="btn btn-danger btn-xs cancelAdminBet" :data-betid="bet.id">
												Cancel
											</button>
										</span>
										<!-- remove/replace double bet btn -->
										<span v-if="userBetCount[bet.user_id] > 1 && match.match_status == 'open'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Remove D.Bet
											</button>
										</span>
										<span v-else-if="userBetCount[bet.user_id] > 1 && match.match_status == 'ongoing'">
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="removeDoubleBet(bet.id)"
											>
												Replace with BND
											</button>
										</span>
										<!-- end remove/replace double bet btn -->

										<!-- bnd bet cancel -->
										<span v-if="bet.user_id == bndMainId && match.match_status == 'open'">
											&nbsp;
											<button
												type="button"
												class="btn btn-danger btn-xs"
												:data-betid="bet.id"
												@click="cancelBndBet(bet.id)"
											>
												Remove BND Bet
											</button>
										</span>
										<!--end bnd bet cancel-->
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td>
										<strong>&#8369; {{ match.team_b_bets | formatMoney }}</strong>
									</td>
									<td>
										<span
											v-if="match.match_status == 'settled'"
											:class="match.match_winner == match.team_b_id ? 'text-green' : 'text-red'"
										>
											<strong>&#8369; {{ Math.abs(totalTeamBProfitLoss) | formatMoney }}</strong>
										</span>
										<span v-else></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="col-md-12" style="margin-top: 10px">
					<div
						:class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'"
						style="border-bottom: 1px solid #ccc; padding-bottom: 15px"
					>
						<dt class="col-sm-7">Total Bettors on {{ match.team_a_name }}</dt>
						<dd class="col-sm-5">{{ teamAbets.length }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_a_name }} (with Admin Bets)</dt>
						<dd class="col-sm-5">&#8369; {{ match.team_a_bets | formatMoney }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_a_name }} (Real Bets Only no Admin Bets)</dt>
						<dd class="col-sm-5 text-strong" :class="`${teamARealBetsTotal >= 100000 ? 'text-green' : ''}`">
							&#8369; {{ teamARealBetsTotal | formatMoney }} | {{ teamARealBetsPercentage }}% | {{ teamARealBetsRatio }}
						</dd>

						<dt class="col-sm-7">Average Bets on {{ match.team_a_name }}</dt>
						<dd class="col-sm-5">
							&#8369; {{ teamAbets.length > 0 ? teamARealBetsTotal / teamAbets.length : '0' | formatMoney }}
						</dd>
					</div>

					<div
						v-if="!!match.team_c_id"
						:class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'"
						style="border-bottom: 1px solid #ccc; padding-bottom: 15px"
					>
						<dt class="col-sm-7">Total Bettors on {{ match.team_c_name }}</dt>
						<dd class="col-sm-5">{{ teamCbets.length }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_c_name }} (with Admin Bets)</dt>
						<dd class="col-sm-5">&#8369; {{ match.team_c_bets | formatMoney }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_c_name }} (Real Bets Only no Admin Bets)</dt>
						<dd class="col-sm-5 text-strong" :class="`${teamCRealBetsTotal >= 100000 ? 'text-green' : ''}`">
							&#8369; {{ teamCRealBetsTotal | formatMoney }} | {{ teamCRealBetsPercentage }}% | {{ teamCRealBetsRatio }}
						</dd>

						<dt class="col-sm-7">Average Bets on {{ match.team_c_name }}</dt>
						<dd class="col-sm-5">
							&#8369; {{ teamCbets.length > 0 ? teamCRealBetsTotal / teamCbets.length : '0' | formatMoney }}
						</dd>
					</div>

					<div
						:class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'"
						style="border-bottom: 1px solid #ccc; padding-bottom: 15px"
					>
						<dt class="col-sm-7">Total Bettors on {{ match.team_b_name }}</dt>
						<dd class="col-sm-5">{{ teamBbets.length }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_b_name }} (with Admin Bets)</dt>
						<dd class="col-sm-5">&#8369; {{ match.team_b_bets | formatMoney }}</dd>

						<dt class="col-sm-7">Total Bets on {{ match.team_b_name }} (Real Bets Only no Admin Bets)</dt>
						<dd class="col-sm-5 text-strong" :class="`${teamBRealBetsTotal >= 100000 ? 'text-green' : ''}`">
							&#8369; {{ teamBRealBetsTotal | formatMoney }} | {{ teamBRealBetsPercentage }}% | {{ teamBRealBetsRatio }}
						</dd>

						<dt class="col-sm-7">Average Bets on {{ match.team_b_name }}</dt>
						<dd class="col-sm-5">
							&#8369; {{ teamBbets.length > 0 ? teamBRealBetsTotal / teamBbets.length : 0 | formatMoney }}
						</dd>
					</div>
				</div>

				<div class="col-md-12" style="margin-top: 10px">
					<div :class="!!match.team_c_id ? 'col-md-4' : 'col-md-6'">
						<dt class="col-sm-7">Total Bettors on this match</dt>
						<dd class="col-sm-5">{{ bets.length }}</dd>

						<dt class="col-sm-7">Total Bets on this match (with Admin Bets)</dt>
						<dd class="col-sm-5">&#8369; {{ match.total_bets | formatMoney }}</dd>

						<dt class="col-sm-7">
							Total Bets on this match (Real Bets Only no Admin Bets)
						</dt>
						<dd class="col-sm-5 text-strong" :class="`${matchRealBetsTotal >= 100000 ? 'text-green' : ''}`">
							&#8369; {{ matchRealBetsTotal | formatMoney }}
						</dd>

						<dt class="col-sm-7">Average Bets on this match</dt>
						<dd class="col-sm-5">&#8369; {{ bets.length > 0 ? match.total_bets / bets.length : '0' | formatMoney }}</dd>

						<dt class="col-sm-7">Match Fee</dt>
						<dd class="col-sm-5">{{ match.match_fee * 100 }}%</dd>

						<dt class="col-sm-7">Total Fees collected on this match</dt>
						<dd class="col-sm-5">&#8369; {{ totalMatchFee | formatMoney }}</dd>

						<dt class="col-sm-7">Total Payout</dt>
						<dd class="col-sm-5">&#8369; {{ totalPayout | formatMoney }}</dd>

						<dt class="col-sm-7">Circulating Credits Before Settle</dt>
						<dd class="col-sm-5">
							&#8369;
							{{ !!circulatingCreditsBefore ? circulatingCreditsBefore : 'N/A' }}
						</dd>

						<dt class="col-sm-7">Circulating Credits After Settle</dt>
						<dd class="col-sm-5">
							&#8369;
							{{ !!circulatingCreditsAfter ? circulatingCreditsAfter : 'N/A' }}
						</dd>

						<dt class="col-sm-12 text-warning" style="margin-top: 10px">
							[for devs only]
						</dt>
						<!--total A-->
						<dt class="col-sm-7">Total A</dt>
						<dd class="col-sm-5">{{ match.team_a_total_bets }}</dd>
						<!--end total A-->

						<!--total B-->
						<dt class="col-sm-7">Draw</dt>
						<dd class="col-sm-5">{{ match.team_c_total_bets }}</dd>
						<!--end total B-->

						<!--total B-->
						<dt class="col-sm-7">Total B</dt>
						<dd class="col-sm-5">{{ match.team_b_total_bets }}</dd>
						<!--end total B-->
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['matchId', 'teama', 'teamb', 'bets', 'match', 'bndMainId'],
		data: function() {
			return {
				userBetCount: {}
			};
		},
		computed: {
			teamAbets: function() {
				console.log('this.bets: ', this.bets);
				return this.bets.filter(bet => bet.team_id == this.match.team_a_id && bet.user.type == 'user');
			},
			teamARealBetsTotal: function() {
				return parseFloat(this.match.team_a_bets) - parseFloat(this.adminTeamAbetsTotal);
			},
			adminTeamAbets: function() {
				return this.bets.filter(
					bet => bet.team_id == this.match.team_a_id && (bet.user.type == 'admin' || bet.user.type == 'matchmanager')
				);
			},
			adminTeamAbetsTotal: function() {
				return this.adminTeamAbets.reduce(function(sum, bet) {
					return bet.amount + sum;
				}, 0);
			},
			teamBbets: function() {
				return this.bets.filter(bet => bet.team_id == this.match.team_b_id && bet.user.type == 'user');
			},
			teamBRealBetsTotal: function() {
				return parseFloat(this.match.team_b_bets) - parseFloat(this.adminTeamBbetsTotal);
			},
			adminTeamBbets: function() {
				return this.bets.filter(
					bet => bet.team_id == this.match.team_b_id && (bet.user.type == 'admin' || bet.user.type == 'matchmanager')
				);
			},
			adminTeamBbetsTotal: function() {
				return this.adminTeamBbets.reduce(function(sum, bet) {
					return bet.amount + sum;
				}, 0);
			},

			//for draw
			teamCbets: function() {
				return this.bets.filter(bet => bet.team_id == this.match.team_c_id && bet.user.type == 'user');
			},
			teamCRealBetsTotal: function() {
				return parseFloat(this.match.team_c_bets) - parseFloat(this.adminTeamCbetsTotal);
			},
			adminTeamCbets: function() {
				return this.bets.filter(
					bet => bet.team_id == this.match.team_c_id && (bet.user.type == 'admin' || bet.user.type == 'matchmanager')
				);
			},
			adminTeamCbetsTotal: function() {
				return this.adminTeamCbets.reduce(function(sum, bet) {
					return bet.amount + sum;
				}, 0);
			},
			//end for draw

			matchRealBetsTotal: function() {
				return (
					parseFloat(this.teamBRealBetsTotal) +
					parseFloat(this.teamARealBetsTotal) +
					parseFloat(this.teamCRealBetsTotal)
				);
			},

			teamARealBetsPercentage: function() {
				const percentage = this.matchRealBetsTotal > 0 ? (this.teamARealBetsTotal / this.matchRealBetsTotal) * 100 : 0;
				return parseFloat(percentage).toFixed(2);
			},

			teamBRealBetsPercentage: function() {
				const percentage = this.matchRealBetsTotal > 0 ? (this.teamBRealBetsTotal / this.matchRealBetsTotal) * 100 : 0;

				return parseFloat(percentage).toFixed(2);
			},

			teamARealBetsRatio: function() {
				const ratio =
					this.teamARealBetsTotal > 0
						? (this.matchRealBetsTotal / this.teamARealBetsTotal) * (1 - this.match.match_fee)
						: 0;

				return parseFloat(ratio).toFixed(2);
			},

			teamBRealBetsRatio: function() {
				const ratio =
					this.teamBRealBetsTotal > 0
						? (this.matchRealBetsTotal / this.teamBRealBetsTotal) * (1 - this.match.match_fee)
						: 0;

				return parseFloat(ratio).toFixed(2);
			},

			//for draw
			teamCRealBetsPercentage: function() {
				const percentage = this.matchRealBetsTotal > 0 ? (this.teamCRealBetsTotal / this.matchRealBetsTotal) * 100 : 0;

				return parseFloat(percentage).toFixed(2);
			},

			teamCRealBetsRatio: function() {
				const ratio =
					this.teamCRealBetsTotal > 0
						? (this.matchRealBetsTotal / this.teamCRealBetsTotal) * (1 - this.match.match_fee)
						: 0;

				return parseFloat(ratio).toFixed(2);
			},

			teamCRealBetsRatio: function() {
				const ratio =
					this.teamCRealBetsTotal > 0
						? (this.matchRealBetsTotal / this.teamCRealBetsTotal) * (1 - this.match.match_fee)
						: 0;

				return parseFloat(ratio).toFixed(2);
			},
			//end for draw

			totalMatchFee: function() {
				return this.match.total_bets * this.match.match_fee;
			},
			totalPayout: function() {
				return parseFloat(this.match.total_bets) - parseFloat(this.totalMatchFee);
			},

			totalTeamAProfitLoss: function() {
				return this.teamAbets.reduce((sum, { gains }) => parseFloat(sum) + parseFloat(gains), 0);
			},
			totalTeamBProfitLoss: function() {
				return this.teamBbets.reduce((sum, { gains }) => parseFloat(sum) + parseFloat(gains), 0);
			},

			//for draw
			totalTeamCProfitLoss: function() {
				return this.teamCbets.reduce((sum, { gains }) => parseFloat(sum) + parseFloat(gains), 0);
			},
			//end for draw

			circulatingCreditsBefore: function() {
				return !!this.match.matchReport ? parseFloat(this.match.matchReport.circulating_credits_before_settled) : '';
			},

			circulatingCreditsAfter: function() {
				return !!this.match.matchReport ? parseFloat(this.match.matchReport.circulating_credits_after_settled) : '';
			}
		},
		methods: {
			hasDuplicateBet: function(user_id) {
				return parseInt(this.userBetCount[user_id]) > 1;
			},

			removeDoubleBet: function(betId) {
				const that = this;
				swal(
					{
						title: `Remove/Replace Bet# ${betId}`,
						text: `You are about to remove/replace this double bet.
                Do you want to proceed?`,
						type: 'warning',
						showCancelButton: true,
						confirmButtonClass: 'btn-primary',
						confirmButtonText: 'Yes, please proceed.',
						showLoaderOnConfirm: true,
						closeOnConfirm: false,
						html: true
					},
					function(confirmed) {
						if (confirmed) {
							//remove-duplicate-bet
							$.ajax({
								type: 'DELETE',
								url: '/admin/remove-duplicate-bet',
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								dataType: 'json',
								data: {
									bet_id: betId
								},
								success: function(response) {
									const { success, message } = response;
									if (success) {
										swal({
											title: `Duplicate bet removed/replaced.`,
											text: message,
											type: 'success',
											html: true
										});

										window.setTimeout(function() {
											location.reload();
										}, 2000);
									} else {
										swal({
											title: `Removing duplicate bet failed.`,
											text: message,
											type: 'error',
											html: true
										});
									}
								}
							});
						}
					}
				);
			},

			cancelBndBet: function(betId) {
				const that = this;
				swal(
					{
						title: `Cancel Bet# ${betId}`,
						text: `You are about to cancel/remove bet# ${betId}.
                Do you want to proceed?`,
						type: 'warning',
						showCancelButton: true,
						confirmButtonClass: 'btn-primary',
						confirmButtonText: 'Yes, please proceed.',
						showLoaderOnConfirm: true,
						closeOnConfirm: false,
						html: true
					},
					function(confirmed) {
						if (confirmed) {
							//cancel-bnd-bet'
							$.ajax({
								type: 'DELETE',
								url: '/admin/cancel-bnd-bet',
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								dataType: 'json',
								data: {
									bet_id: betId
								},
								success: function(response) {
									const { success, message } = response;
									if (success) {
										swal({
											title: `Bet cancelled/removed.`,
											text: message,
											type: 'success',
											html: true
										});

										window.setTimeout(function() {
											location.reload();
										}, 2000);
									} else {
										swal({
											title: `Cancel bet failed.`,
											text: message,
											type: 'error',
											html: true
										});
									}
								}
							});
						}
					}
				);
			}
		},
		created() {
			console.log('asdf', this.match);
			if (!!this.bets) {
				this.bets.forEach(bet => {
					console.log('user bet type: ', bet.user.type);
					if (bet.user.type != 'admin' && bet.user.type != 'matchmanager') {
						this.userBetCount[bet.user_id] = !!this.userBetCount[bet.user_id] ? this.userBetCount[bet.user_id] + 1 : 1;
					}
				});
			}
		},
		mounted() {
			//console.log('asdf', this.match)
		}
	};
</script>

<style scoped>
	.text-red {
		color: red;
	}

	.text-green {
		color: green;
	}

	.bg-admin-bet {
		background-color: #bae6ff !important;
	}

	.bg-double-bet {
		background-color: #ffbaca !important;
	}

	.text-strong {
		font-weight: bold !important;
		font-size: 18px !important;
	}
</style>
