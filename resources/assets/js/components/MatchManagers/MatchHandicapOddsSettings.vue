<template>
	<div>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#handicap-threshold-settings-modal">
			<i class="fa fa-sort" aria-hidden="true"></i> Handicap Settings
		</button>

		<div
			class="modal fade"
			id="handicap-threshold-settings-modal"
			tabindex="-1"
			role="dialog"
			aria-hidden="true"
			data-backdrop="static"
			data-keyboard="false"
		>
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Set BND auto-bet threshold settings</h4>
					</div>
					<!-- Modal Body -->
					<div class="modal-body">
						<table class="table">
							<tbody>
								<tr
									v-for="match in matches"
									:key="`match-${match.id}`"
									class="row"
									style="
                    border-bottom: 2px solid #1f1f1f;
                    background-color: white !important;
                  "
								>
									<div class="col-md-12">
										<h5>
											<strong>{{ match.name }} #{{ match.id }}</strong>
										</h5>
										<div class="col-md-12">
											<small>
												<em>
													*Note: Higher percentage = if that team goes beyond that percentage, BND will bet to opposing
													team.
												</em>
											</small>
											<small>
												<br />
												<em>
													*Note: Lower percentage = if that team goes below that percentage, BND will bet to opposing
													team.
												</em>
											</small>
											<small>
												<em></em>
											</small>
											<form class="form row">
												<div
													class="form-group col-md-6"
													:class="checkPercentageThreshold(match) == false ? `has-error` : ``"
												>
													<label for="exampleInputName2">{{ teamA.name }} Min. Percentage:</label>
													<input
														type="number"
														min="6"
														max="94"
														class="form-control"
														id="admin-bet-input-percentage-a-bnd"
														placeholder="Input percentage"
														v-model="match.team_a_threshold_percent"
														@change="calculateOpposingTeamPercentage(match, 'max_b')"
													/>
													<label class="control-label small" for="inputError1">
														Total percentage must be 100% and must be between 4% to 96% only.
													</label>
													<label for="exampleInputName2" v-show="showMax">{{ teamA.name }} Max. Percentage:</label>
													<input
														type="number"
														min="6"
														max="94"
														class="form-control"
														id="admin-bet-input-percentage-a-bnd"
														placeholder="Input percentage"
														v-model="match.team_a_max_threshold_percent"
														readonly
														v-show="showMax"
														@change="calculateOpposingTeamPercentage(match, 'min_b')"
													/>
													<label class="control-label small" for="inputError1" v-show="showMax">
														Total percentage must be 100% and must be between 4% to 96% only.
													</label>
												</div>
												<div
													class="form-group col-md-6"
													:class="checkPercentageThreshold(match) == false ? `has-error` : ``"
												>
													<label for="exampleInputEmail2" v-show="showMax">{{ teamB.name }} Max. Percentage:</label>
													<input
														type="number"
														min="6"
														max="94"
														class="form-control"
														id="admin-bet-input-percentage-b-bnd"
														placeholder="Input percentage"
														v-model="match.team_b_max_threshold_percent"
														readonly
														v-show="showMax"
														@change="calculateOpposingTeamPercentage(match, 'min_a')"
													/>
													<label class="control-label small" for="inputError1" v-show="showMax">
														Total percentage must be 100% and must be between 4% to 96% only.
													</label>

													<label for="exampleInputEmail2">{{ teamB.name }} Min. Percentage:</label>
													<input
														type="number"
														min="6"
														max="94"
														class="form-control"
														id="admin-bet-input-percentage-b-bnd"
														placeholder="Input percentage"
														v-model="match.team_b_threshold_percent"
														@change="calculateOpposingTeamPercentage(match, 'max_a')"
													/>
													<label class="control-label small" for="inputError1">
														Total percentage must be 100% and must be between 4% to 96% only.
													</label>
												</div>
											</form>
										</div>
									</div>
									<!-- <div class="col-md-6">{{ teamA.name }}</div>
                  <div class="col-md-6">{{ teamB.name }}</div>-->
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Modal Footer -->
					<div class="modal-footer">
						<button
							type="button"
							class="btn btn-success"
							:disabled="loading"
							@click="saveThresholdPercentages"
							v-if="matchStatus == 'open'"
						>
							Save
						</button>
						<button type="button" class="btn btn-danger" id="close-modal-btn" data-dismiss="modal" :disabled="loading">
							Close
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- End modal for adding item -->
	</div>
</template>

<script>
	import loadingGif from '../../../images/loading.gif';
	import draggable from 'vuedraggable';

	export default {
		components: {
			draggable
		},
		props: ['matchId', 'matchStatus'],
		data() {
			return {
				loading: false,
				matches: [],
				matchesPercentages: [],
				teamA: null,
				teamB: null,
				getMatchDetailsUrl: '/mm/match/match-submatches-details',
				updateUrl: '/mm/update-bnd-threshold-percentages',
				updated: false,
				showMax: false
			};
		},
		computed: {},

		methods: {
			getMatchSubDetails() {
				const that = this;
				$.ajax({
					type: 'GET',
					url: this.getMatchDetailsUrl,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						match_id: that.matchId
					},
					dataType: 'json',
					success: function(response) {
						console.log('response: ', response);
						const { matches, success, message } = response;
						if (success) {
							that.matches = matches;
							that.teamA = matches[0].team_a;
							that.teamB = matches[0].team_b;
							that.matches.forEach(match => {
								that.matchesPercentages.push({
									match_id: match.id,
									team_a: match.team_a_threshold_percent,
									team_a_max: match.team_a_max_threshold_percent,
									team_b: match.team_a_threshold_percent,
									team_b_max: match.team_b_max_threshold_percent
								});
							});
						} else {
						}
						// that.leagues = response.leagues;
						// console.log("getActiveLeagues resposne:", response);
					}
				});
			},

			saveThresholdPercentages() {
				const that = this;

				const hasError = that.matches.some(match =>
					match.team_a_threshold_percent > 0 && match.team_b_threshold_percent > 0
						? this.checkPercentageThreshold(match) == false
						: false
				);

				if (!hasError) {
					const matchesData = this.matches.map(match => ({
						id: match.id,
						team_a_threshold_percent: match.team_a_threshold_percent,
						team_a_max_threshold_percent: match.team_a_max_threshold_percent,
						team_b_threshold_percent: match.team_b_threshold_percent,
						team_b_max_threshold_percent: match.team_b_max_threshold_percent
					}));

					this.loading = true;
					this.updated = false;

					$.ajax({
						type: 'PUT',
						url: this.updateUrl,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						dataType: 'json',
						data: { matches: matchesData },
						success: function(response) {
							const { success, message } = response;
							if (success) {
								// $("#bnd-threshold-settings-modal").modal("hide");
								$('#close-modal-btn').trigger('click');
								that.loading = false;
								that.updated = true;
								swal({
									title: `Threshold Settings`,
									text: message,
									type: 'success',
									html: true
								});
							} else {
								swal({
									title: `Threshold Settings`,
									text: message,
									type: 'error',
									html: true
								});
							}
						}
					});
				} else {
					swal({
						title: `Threshold Settings`,
						text: 'Check if you got the correct percentages set',
						type: 'error',
						html: true
					});
				}
			},

			checkPercentageThreshold(match) {
				const sumIs100 =
					parseFloat(match.team_a_threshold_percent) + parseFloat(match.team_b_max_threshold_percent) == 100;

				const maxSumIs100 =
					parseFloat(match.team_a_max_threshold_percent) + parseFloat(match.team_b_threshold_percent) == 100;

				const isTeamAInRange =
					parseFloat(match.team_a_threshold_percent) <= 96 && parseFloat(match.team_a_threshold_percent) >= 4;

				const isTeamAMaxInRange =
					parseFloat(match.team_a_max_threshold_percent) <= 96 && parseFloat(match.team_a_max_threshold_percent) >= 4;

				const isTeamBInRange =
					parseFloat(match.team_b_threshold_percent) <= 96 && parseFloat(match.team_b_threshold_percent) >= 4;

				const isTeamBMaxInRange =
					parseFloat(match.team_b_max_threshold_percent) <= 96 && parseFloat(match.team_b_max_threshold_percent) >= 4;

				return sumIs100 && maxSumIs100 && isTeamAInRange && isTeamBInRange && isTeamAMaxInRange && isTeamBMaxInRange;
			},

			calculateOpposingTeamPercentage(match, change_field = 'min_b') {
				let currentVal,
					oppossingVal = 0;
				switch (change_field) {
					case 'min_a':
						currentVal = parseFloat(match.team_b_max_threshold_percent);
						oppossingVal = parseFloat(100 - currentVal);
						match.team_a_threshold_percent = oppossingVal.toFixed(2);
						break;
					case 'min_b':
						currentVal = parseFloat(match.team_a_max_threshold_percent);
						oppossingVal = parseFloat(100 - currentVal);
						match.team_b_threshold_percent = oppossingVal.toFixed(2);
						break;
					case 'max_a':
						currentVal = parseFloat(match.team_b_threshold_percent);
						oppossingVal = parseFloat(100 - currentVal);
						match.team_a_max_threshold_percent = oppossingVal.toFixed(2);
						break;
					case 'max_b':
						currentVal = parseFloat(match.team_a_threshold_percent);
						oppossingVal = parseFloat(100 - currentVal);
						match.team_b_max_threshold_percent = oppossingVal.toFixed(2);
						break;
				}
			}
		},

		mounted() {
			console.log('testtt : ', 1);
			this.getMatchSubDetails();
		}
	};
</script>

<style>
	.ghost {
		opacity: 0.5;
		background: #c8ebfb;
	}
</style>
