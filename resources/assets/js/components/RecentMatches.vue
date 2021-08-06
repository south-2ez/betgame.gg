<template>
	<div id="topbets" class="right-content recent-matches-content" style="margin-top: 0">
		<div class="right-blk">
			<div class="title-right">Recent Results</div>
			<!-- Recent category buttons -->
			<div class="recentcategory title-right recent-text">
				<div style="padding: 0px; background-color: #2D2D2D;">
					<button
						@click="fetchRecentMatches($event, 'all')"
						style="color: white; background-color: #2D2D2D"
						class="all-match-btn button-recent button-recent-hover focus-recent-category selected"
					>
						ALL
					</button>
					<button
						@click="fetchRecentMatches($event, 'dota2')"
						style="color: white; background-color: #2D2D2D"
						class="dota-match-btn button-recent button-recent-hover focus-recent-category"
					>
						<img src="/images/dota2icon.png" />
					</button>
					<button
						@click="fetchRecentMatches($event, 'csgo')"
						style="color: white; background-color: #2D2D2D"
						class="csgo-match-btn button-recent button-recent-hover focus-recent-category"
					>
						<img src="/images/csgoicon.png" />
					</button>
					<button
						@click="fetchRecentMatches($event, 'lol')"
						style="color: white; background-color: #2D2D2D"
						class="lol-match-btn button-recent button-recent-hover focus-recent-category"
					>
						<img src="/images/lol24px.png" />
					</button>
					<button
						@click="fetchRecentMatches($event, 'sports')"
						style="color: white; background-color: #2D2D2D"
						class="sports-match-btn button-recent button-recent-hover focus-recent-category"
					>
						<img src="/images/sportsicon.png" />
					</button>
				</div>
			</div>
			<div class="recent-box" style="display:none">
				<div>
					<ul>
						<li class="li-recent-matches" v-for="match in recent_matches" :key="`match-${match.id}`">
							<a :href="'/match/' + match.id">
								<div class="row">
									<div class="col-xs-9">
										<div class="row">
											<div class="col-xs-2">
												<img :src="`/${match.team_a.image}`" class="results-team-logo" />
											</div>
											<div class="col-xs-8">
												<span :class="match.team_winner == match.team_a.id ? 'text-green' : 'text-gray'">
													{{ match.team_a.shortname ? match.team_a.shortname : match.team_a.name }}
												</span>
											</div>
											<div class="col-xs-2">{{ match.teama_score }}</div>
										</div>
										<div class="row">
											<div class="col-xs-2">
												<img :src="`/${match.team_b.image}`" class="results-team-logo" />
											</div>
											<div class="col-xs-8">
												<span :class="match.team_winner == match.team_b.id ? 'text-green' : 'text-gray'">
													{{ match.team_b.shortname ? match.team_b.shortname : match.team_b.name }}
												</span>
											</div>
											<div class="col-xs-2">{{ match.teamb_score }}</div>
										</div>
									</div>
									<div
										class="col-xs-3"
										:class="
											match.status == 'cancelled' ||
											match.status == 'draw' ||
											match.status == 'forfeit' ||
											(!!match.team_c && match.team_winner == match.team_c.id)
												? 'draw-cancelled-score'
												: ''
										"
									>
										<i class="text-danger cancelled-match" aria-hidden="true" v-if="match.status == 'cancelled'"
											>Cancelled</i
										>
										<i class="draw-match" aria-hidden="true" v-if="match.status == 'draw'">Draw</i>
										<i
											class="draw-match"
											aria-hidden="true"
											v-if="!!match.team_c && match.team_winner == match.team_c.id"
											>Draw</i
										>
										<i class="text-danger cancelled-match" aria-hidden="true" v-if="match.status == 'forfeit'"
											>Forfeit</i
										>
										<div
											class="row trophy-container"
											v-if="match.status == 'settled' && !(!!match.team_c && match.team_winner == match.team_c.id)"
										>
											<div class="col-xs-12">
												<i
													class="fa fa-trophy text-primary"
													aria-hidden="true"
													title="Winner"
													v-if="match.status == 'settled' && match.team_winner == match.team_a.id"
												></i>
											</div>
										</div>
										<div
											class="row trophy-container"
											v-if="match.status == 'settled' && !(!!match.team_c && match.team_winner == match.team_c.id)"
										>
											<div class="col-xs-12">
												<i
													class="fa fa-trophy text-primary"
													aria-hidden="true"
													title="Winner"
													v-if="match.status == 'settled' && match.team_winner == match.team_b.id"
												></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<!-- <a :href="'/match/' + match.id">
								<i
									class="fa fa-trophy text-primary"
									aria-hidden="true"
									title="Winner"
									v-if="match.team_a.id == match.team_winner"
								></i>
								{{ match.team_a.shortname ? match.team_a.shortname : match.team_a.name }}
								<strong style="color: green">vs</strong>
								{{ match.team_b.shortname ? match.team_b.shortname : match.team_b.name }}
								<i
									class="fa fa-trophy text-primary"
									aria-hidden="true"
									title="Winner"
									v-if="
										match.team_b.id == match.team_winner || (!!match.team_c && match.team_c.id == match.team_winner)
									"
								></i>
								<span v-if="match.status == 'draw'">
									<strong>{{ match.teama_score }}-{{ match.teamb_score }}</strong
									>&nbsp;(Draw)
								</span>
								<span v-else-if="match.status == 'cancelled'">&nbsp;(Cancelled)</span>
								<span v-else-if="match.status == 'forfeit'">&nbsp;(Forfeit)</span>
								<span v-else>
									<strong>{{ match.teama_score }}-{{ match.teamb_score }}</strong>
								</span>
							</a> -->
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="divide"></div>
		<ul class="last-link" style="list-style: none">
			<li>
				<a href="/termsandconditions" class="faq-text" data-pjax>Terms & Conditions</a>
			</li>
			<li>
				<a href="/faq" class="faq-text" data-pjax>Frequently Asked Question (FAQ)</a>
			</li>
			<li>
				<a href="https://www.facebook.com/groups/2ez.bet/" target="_blank">Join our Community</a>
			</li>
		</ul>
		<div class="social">
			<ul style="list-style: none">
				<li>
					<a href="https://www.facebook.com/groups/114061875898337" target="_blank">
						<i class="fa fa-facebook"></i>
					</a>
				</li>
				<li>
					<a href="https://twitter.com/" target="_blank">
						<i class="fa fa-twitter"></i>
					</a>
				</li>
				<li>
					<a href="https://www.youtube.com/channel/UCbTPPtTXldnrvAnRQQn32Wg" target="_blank">
						<i class="fa fa-youtube"></i>
					</a>
				</li>
			</ul>
		</div>
		<div class="poweredby" style="text-align: center; color: #888;">
			<span>© Copyright 2016 - 2021. All rights reserved. Betting can be addictive. Please bet responsibly.</span>
			<br />
			<span>
				Powered by
				<a href="https://2ez.bet/">2ez.bet</a>
			</span>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'RecentMatches',
		props: [],
		data: function() {
			return {
				recent_matches: []
			};
		},
		mounted() {
			$('.all-match-btn').trigger('click');
		},
		watch: {
			// category: function() {
			// 	console.log('category', category)
			// }
		},
		methods: {
			fetchRecentMatches(evt, type) {
				var self = this;
				$('.focus-recent-category').removeClass('selected');
				$(evt.target)
					.closest('button')
					.addClass('selected');
				$.ajax({
					url: '/listrecentmatches',
					type: 'GET',
					data: { type: type },
					success: function(data) {
						$('.recent-box').show();
						console.log('ddd', data)
						self.recent_matches = data;
					},
					error: function(error) {
						console.log('errr', error)
					}
				});
			},
			createItem(data) {
				alert(data);
			}
		}
	};
</script>

<style scoped>
	.results-team-logo {
		width: 20px;
	}

	.cancelled-match,
	.draw-match {
		font-size: 11px;
	}

	.draw-cancelled-score {
		margin-top: 15px;
	}

	.trophy-container {
		height: 34px;
	}

	.text-green {
		color: #4bd64b;
	}

	.text-gray {
		color: gray;
	}

	.li-recent-matches {
		background: #1e1d2b;
	}
</style>
