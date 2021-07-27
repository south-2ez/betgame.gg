<template>
	<div>
		<div
			class="modal fade"
			id="trick-or-treat-event-modal"
			tabindex="-1"
			role="dialog"
			aria-hidden="true"
			data-backdrop="static"
			data-keyboard="false"
			ref="trickOrTreatModalRef"
		>
			<div class="modal-dialog">
				<div class="modal-content" id="chinese-new-year-event-container">
					<!-- Modal Header -->
					<div class="modal-header">
						<h2>
							<strong>新年快乐</strong>, dear bettors!
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span>
							</button>
						</h2>
					</div>

					<!-- Modal Body -->
					<div class="modal-body">
						<h5 class="greetings-text">
							<p>
								Let's start the year with a bang! Good news, we have something special prepared for you! Test how lucky
								you are this year by participating at our event 🙂
							</p>

							<p>Test it by flipping one random angpao a day and get a chance to win 2ez Credits!</p>

							<p>
								All you need to have is atleast 100 2ez credits to participate in our event. So what are you waiting
								for? Flip the angpao and win prizes!
							</p>

							<p>Event runs from February 12,2021 to February 18,2021</p>

							<p><strong>"xiào kǒu cháng kāi"</strong> - from your 2ez.bet family</p>

							<p><strong>#2ez #YearOfTheOx #ChineseNewYear2021</strong></p>
							<p></p>
						</h5>

						<div>
							<div style="color: red;" v-if="hasError" class="alert alert-danger">{{ errorMessage }}</div>
							<div v-if="flipped && !!!gcWon" class="alert alert-danger">Sorry, better luck next time.</div>
							<div class="row chinese-card-container">
								<div
									class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-xs-4 chinese-card"
									v-for="option in options"
									:key="`card-${option.index}`"
									@mouseenter="!loading ? (currentlyHovered = option.index) : ''"
									@mouseleave="currentlyHovered = null"
									@click="!loading ? (currentlyHovered = option.index) : ''"
								>
									<!-- <img
										:src="option.image"
										class="img-responsive"
										:class="`${currentlyHovered == null || currentlyHovered == option.index ? '' : 'grayed-out-img'}`"
									/>
									<button
										class="pick-btn"
										v-show="currentlyHovered == option.index || pickedIndex == option.index"
										@click="flipCard(option.index)"
									>
										<span v-if="!loading">Pick</span>
										<i class="fa fa-circle-o-notch fa-spin" v-else></i>
									</button> -->

									<div class="flip-container">
										<div :class="flipping ? 'flipper flipped' : ''">
											<div class="front">
												<img
													:src="option.image"
													:class="
														`${
															hasError
																? ''
																: !canSpinChecked ||
																  currentlyHovered == null ||
																  currentlyHovered == option.index ||
																  pickedIndex == option.index
																? ''
																: 'grayed-out-img'
														}`
													"
												/>
											</div>
											<div class="back">
												<img :src="backImage" :class="flipped && pickedIndex == option.index ? '' : 'grayed-out-img'" />
												<div class="card-value-container">{{ option.text }}</div>
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<button
										class="pick-btn"
										v-show="
											canSpinChecked && !hasError && (currentlyHovered == option.index || pickedIndex == option.index)
										"
										@click="flipCard(option.index)"
										v-if="!flipping"
									>
										<span v-if="!loading">Pick</span>
										<i class="fa fa-circle-o-notch fa-spin" v-else></i>
									</button>
								</div>
							</div>
						</div>
						<div v-if="!canSpin && !!canSpinMessage" class="text-center">
							<h3>{{ canSpinMessage }}</h3>
						</div>
						<br />
						<div v-if="flipped && !!!gcWon" class="alert alert-danger">Sorry, better luck next time.</div>
						<div v-if="!!gcWon" class="congrats-winner-container text-center">
							<h3 class="trick-or-treat-element">
								<div class="alert alert-success">
									Congratulations!! You won <strong>{{ gcCreditsWon }} 2ez.bet credits! </strong> <br /><br />
									<span class="trick-or-treat-element" style="font-size: 16x; font-weight:bold;">{{ gcWon }}</span
									><br />
									<small>
										* How to claim GIFT CODE * <br />
										• Go to "My Profile" page. <br />
										• Click "Claim Gift Code" button <br />
										• Copy & Paste the code above <br />
										• then click "Continue" button <br />
									</small>
								</div>
							</h3>

							<br /><br />
						</div>
						<!-- <div v-if="!!gcWon" class="congrats-winner-container text-center">
							<h3 class="trick-or-treat-element">
								<div class="alert alert-success">
									Congratulations!! You won <strong>{{ gcCreditsWon }} 2ez.bet credits! </strong> <br /><br />
									<span class="trick-or-treat-element" style="font-size: 16x; font-weight:bold;">{{ gcWon }}</span
									><br />
									<small>
										* How to claim GIFT CODE * <br />
										• Go to "My Profile" page. <br />
										• Click "Claim Gift Code" button <br />
										• Copy & Paste the code above <br />
										• then click "Continue" button <br />
									</small>
								</div>
							</h3>

							<br /><br />
						</div> -->
					</div>
					<!-- Modal Footer -->
					<div class="modal-footer" style="text-align:left;">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<div class="modal-footer-chn-event"></div>
			</div>
		</div>

		<!-- End modal for adding item -->
	</div>
</template>
<script>
	import loadingGif from '../../../images/loading.gif';
	import GetFreeCredits from './GetFreeCredits';
	import frontImage from '../../../images/chinese-new-year/FlipCardFront.png';
	import backImage from '../../../images/chinese-new-year/FlipBackBlank.png';
	import bokyaImage from '../../../images/chinese-new-year/FlipBack_BLNT.png';

	export default {
		components: {},
		props: ['loggedIn'],
		data() {
			return {
				validateRoute: '/chinese-new-year/validate-flip',
				saveSpinRoute: '/chinese-new-year/save',
				preSaveSpinRoute: '/roulette-spins/pre-save',
				giftCode: '',
				createUrl: '/gift-codes',
				loading: false,
				loadingGif: loadingGif,
				newCodes: [],
				successCreate: false,
				spinValue: '',
				checkingSpin: false,
				canSpinChecked: false,
				canSpin: false,
				canSpinMessage: '',
				startAngle: 0,
				spinTime: 0,
				validData: null,
				gcWon: null,
				gcCreditsWon: 0,
				winnerSegment: -1,
				hasError: false,
				errorMessage: '',
				frontImage: frontImage,
				backImage: backImage,
				//red: #ff0000
				//black: #000000
				//white: #ffffff
				options: [
					{
						image: frontImage,
						text: '',
						index: 0
					},
					{
						image: frontImage,
						text: '',
						index: 1
					},
					{
						image: frontImage,
						text: '',
						index: 2
					},
					{
						image: frontImage,
						text: '',
						index: 3
					},
					{
						image: frontImage,
						text: '',
						index: 4
					},
					{
						image: frontImage,
						text: '',
						index: 5
					},
					{
						image: frontImage,
						text: '',
						index: 6
					},
					{
						image: frontImage,
						text: '',
						index: 7
					},
					{
						image: frontImage,
						text: '',
						index: 8
					},
					{
						image: frontImage,
						text: '',
						index: 9
					},
					{
						image: frontImage,
						text: '',
						index: 10
					},
					{
						image: frontImage,
						text: '',
						index: 11
					},
					{
						image: frontImage,
						text: '',
						index: 12
					},
					{
						image: frontImage,
						text: '',
						index: 13
					},
					{
						image: frontImage,
						text: '',
						index: 14
					},
					{
						image: frontImage,
						text: '',
						index: 15
					}
				],
				currentlyHovered: null,
				pickedIndex: null,
				flipping: false,
				flipped: false
			};
		},

		watch: {
			currentlyHovered: function() {}
		},

		methods: {
			setLocalStorageCheckFlip(value) {
				localStorage.setItem('canFlipChnNwYearEventToday', value);
			},

			getLocalStorageCheckFlip() {
				return localStorage.getItem('canFlipChnNwYearEventToday') == 'true';
			},

			checkIfCanSpin: function() {
				const that = this;
				this.checkingSpin = true;
				$.ajax({
					type: 'POST',
					url: this.validateRoute,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: 'json',
					data: {},
					success: function(response) {
						const { data, success, message } = response;
						that.canSpin = success;
						that.setLocalStorageCheckFlip(success);
						that.canSpinChecked = true;
						that.canSpinMessage = message;
						if (success) {
						} else {
							that.canSpin = false;
							that.canSpinMessage = message;
							that.hasError = true;
							that.errorMessage = message;
						}
					}
				});
			},

			doneSpin: function(value) {
				this.spinValue = value;
				const that = this;
				//const creditsWon = !!this.prizes[this.spinValue.index] ? this.prizes[this.spinValue.index] : 0; //hllwn

				const creditsWon = this.spinValue.text;

				const saveData = {
					credits_won: creditsWon,
					endAngle: this.spinValue.options.endAngle,
					validData: that.validData,
					spin_data: {
						endAngle: this.spinValue.options.endAngle,
						image: this.spinValue.options.image,
						text: this.spinValue.options.text
					}
				};

				$.ajax({
					type: 'POST',
					url: this.saveSpinRoute,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: 'json',
					data: saveData,
					success: function(response) {
						const { data, success, message, won } = response;
						if (success) {
							if (won) {
								that.gcWon = response.gc;
								that.gcCreditsWon = response.credits_won;
							}
						} else {
							that.hasError = true;
							that.errorMessage = message;
						}
					}
				});
			},

			flipCard: function(selectedIndex) {
				this.loading = true;
				this.flipping = true;
				this.pickedIndex = selectedIndex;
				const that = this;
				if (this.getLocalStorageCheckFlip()) {
					this.setLocalStorageCheckFlip(false);

					//process flip
					$.ajax({
						type: 'POST',
						url: this.saveSpinRoute,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						dataType: 'json',
						data: { cardIndex: selectedIndex },
						success: function(response) {
							const { success, message, won, cardIndexValues } = response;
							// console.log(' cardIndexValues: ', cardIndexValues);

							if (!!cardIndexValues) {
								// console.log('cardIndexValues.entries : ', cardIndexValues);
								Object.entries(cardIndexValues).forEach((val, cardIndex) => {
									// console.log('val: ', cardIndex, val);
									that.options[cardIndex].text = val[1];
								});
							}

							setTimeout(() => {
								// console.log('selected index: ', selectedIndex);
								that.loading = false;
								// that.flipping = false;
								// console.log(' response save flip: ', response);
								that.flipped = true;

								if (success) {
									if (won) {
										that.gcWon = response.gc;
										that.gcCreditsWon = response.credits_won;
									}
								} else {
									that.hasError = true;
									that.errorMessage = message;
								}

								that.scrollToElement();
							}, 1000);
						}
					});

					// setTimeout(() => {
					// 	console.log("Time's up -- stop!");
					// 	this.flipping = true;
					// 	this.loading = false;
					// }, 3000);
					// console.log('selected index: ', selectedIndex);
				} else {
					this.hasError = true;
					this.errorMessage = `Try again tomorrow. You can only FLIP once a day.`;
					this.loading = false;

					// alert('cant flip!!!');
				}
			},
			scrollToElement() {
				const el = this.$el.getElementsByClassName('modal-footer-chn-event')[0];
				// const el2 =
				if (el) {
					// Use el.scrollIntoView() to instantly scroll to the element
					el.scrollIntoView({ behavior: 'smooth' });
					//el.scrollIntoView({ behavior: 'smooth', block: 'end' });
					// el.scrollIntoView({ behavior: 'smooth', block: 'end', inline: 'nearest' });
				}

				// this.$refs.trickOrTreatModalRef.scrollTop = this.$refs.trickOrTreatModalRef.height;
			}
		},

		mounted() {
			const that = this;
			// console.log('getLocalStorageCheckFlip : ', this.getLocalStorageCheckFlip());
			if (this.loggedIn == 1) {
				$(this.$refs.trickOrTreatModalRef).on('show.bs.modal', function() {
					that.checkIfCanSpin();
				});
			}
		}
	};
</script>
<style scoped>
	#chinese-new-year-event-container {
	}

	.chinese-cardc-container {
		max-width: 100%;
	}

	.chinese-card {
		margin: auto;
		margin-bottom: 8px;
	}

	.chinese-card img {
		width: 100%;
		max-width: 120px;
	}
	.grayed-out-img {
		-webkit-filter: grayscale(100%);
		-moz-filter: grayscale(100%);
		-o-filter: grayscale(100%);
		-ms-filter: grayscale(100%);
		filter: grayscale(100%);
	}

	.pick-btn {
		position: absolute;
		top: 41% !important;
		left: 36%;
		background-color: #d4af37;
		border: none; /* Remove borders */
		color: white; /* White text */
		padding: 5px 10px; /* Some padding */
		font-size: 16px; /* Set a font size */
		z-index: 5;
	}

	.flip-container {
		-webkit-perspective: 1000;
		-moz-perspective: 1000;
		perspective: 1000;
		width: 100;
		height: 170px;
	}

	.flipper {
		transition: 0.6s;
		-webkit-transform-style: preserve-3d;
		-moz-transform-style: preserve-3d;
		transform-style: preserve-3d;
		-webkit-transition: all 1s ease-in-out;
		-moz-transition: all 1s ease-in-out;
		transition: all 1s ease-in-out;
		position: relative;
		height: 170px;
	}

	.front,
	.back {
		width: 100%;
		height: 170px;
		position: absolute;
		left: 0;
		top: 0;
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		backface-visibility: hidden;
		color: #fff;
		text-shadow: 1px 1px #000;
		font-size: 2em;
		line-height: 170px;
		text-align: center;
	}

	.back {
		/* background: #3498db; */
		-webkit-transform: rotateY(180deg);
		-mozo-transform: rotateY(180deg);
		transform: rotateY(180deg);
	}

	.front {
		z-index: 2;
		/* background: #2ecc71; */
		-webkit-transform: rotateY(0deg);
		-moz-transform: rotateY(0deg);
		transform: rotateY(0deg);
	}

	.flip-container p {
		margin: 10px 0;
		text-align: center;
	}

	@keyframes flipY {
		from {
			-webkit-transform: rotateY(-180deg);
			-moz-transform: rotateY(-180deg);
			transform: rotateY(-180deg);
		}

		to {
			-webkit-transform: rotateY(180deg);
			-moz-transform: rotateY(180deg);
			transform: rotateY(180deg);
		}
	}

	.flip-container .flipper.flipped {
		-webkit-transform: rotateY(180deg);
		-moz-transform: rotateY(180deg);
		transform: rotateY(180deg);
	}

	.modal-header h2 {
		margin-top: 5px;
	}

	.card-value-container {
		position: absolute;
		top: 0px;
		font-size: 1em;
		text-align: center;
		width: 100%;
		height: 100%;
	}

	.greetings-text {
		color: #414141 im !important;
	}
</style>
