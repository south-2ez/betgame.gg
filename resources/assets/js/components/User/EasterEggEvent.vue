<template>
	<div>
		<div
			class="modal fade"
			id="easter-egg-modal"
			tabindex="-1"
			role="dialog"
			aria-hidden="true"
			data-backdrop="static"
			data-keyboard="false"
			ref="easterEggModalRef"
		>
			<div class="modal-dialog">
				<div class="modal-content" id="easter-egg-event-container">
					<!-- Modal Header -->
					<div class="modal-header">
						<h2>
							<strong>Happy Easter</strong>, dear bettors!
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
								Get a chance to win 2ez credits just by choosing an egg. 16 random eggs will pop out upon logging in and
								you get to choose 1 daily. All you need to have is atleast 100 credits on your account!
							</p>

							<p>Event runs from April 5, 2021 until April 11, 2021</p>

							<p>
								So what are you waiting for? Enjoy Egg hunting
							</p>

							<p>Celebrate this Easter with a heart filled with love and peace. Have a blessed and wonderful Easter!</p>

							<p><strong>#2ez #EasterEggHunt</strong></p>
							<p></p>
						</h5>

						<div>
							<div style="color: red;" v-if="hasError" class="alert alert-danger">{{ errorMessage }}</div>

							<div v-if="pickedAnimateImage != null" class="token" :style="`background:url(${pickedAnimateImage})`">
								<span class="token-text" v-show="showTokenText">{{ gcCreditsWon }} credits</span>
							</div>

							<div class="row easter-egg-container" v-if="!flipping">
								<div
									class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 easter-egg"
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
										<div v-show="!flipping">
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
													@load="option.loaded = true"
												/>
												<img :src="option.animateImg" @load="option.loadedAnimate = true" v-show="false" />
											</div>
											<div class="back">
												<img :src="backImage" :class="flipped && pickedIndex == option.index ? '' : 'grayed-out-img'" />
												<div class="egg-value-container">{{ option.text }}</div>
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<button
										class="pick-btn"
										small
										v-show="
											canSpinChecked && !hasError && (currentlyHovered == option.index || pickedIndex == option.index)
										"
										@click="flipCard(option)"
										v-if="!flipping"
									>
										<span v-if="!loading">Crack</span>
										<i class="fa fa-circle-o-notch fa-spin" v-else></i>
									</button>
								</div>
							</div>
						</div>
						<div v-if="!canSpin && !!canSpinMessage" class="text-center">
							<h3>{{ canSpinMessage }}</h3>
						</div>
						<br />
						<div v-if="showTokenText && !gcWon" class="alert alert-danger">Sorry, better luck next time.</div>
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

	import Egg1SingleFrame from '../../../images/easter-egg/Egg1SingleFrame.png';
	import Egg2SingleFrame from '../../../images/easter-egg/Egg2SingleFrame.png';
	import Egg3SingleFrame from '../../../images/easter-egg/Egg3SingleFrame.png';
	import Egg4SingleFrame from '../../../images/easter-egg/Egg4SingleFrame.png';
	import Egg5SingleFrame from '../../../images/easter-egg/Egg5SingleFrame.png';
	import Egg6SingleFrame from '../../../images/easter-egg/Egg6SingleFrame.png';
	import Egg7SingleFrame from '../../../images/easter-egg/Egg7SingleFrame.png';
	import Egg8SingleFrame from '../../../images/easter-egg/Egg8SingleFrame.png';
	import Egg9SingleFrame from '../../../images/easter-egg/Egg1SingleFrame.png';
	import Egg10SingleFrame from '../../../images/easter-egg/Egg2SingleFrame.png';
	import Egg11SingleFrame from '../../../images/easter-egg/Egg3SingleFrame.png';
	import Egg12SingleFrame from '../../../images/easter-egg/Egg4SingleFrame.png';
	import Egg13SingleFrame from '../../../images/easter-egg/Egg5SingleFrame.png';
	import Egg14SingleFrame from '../../../images/easter-egg/Egg6SingleFrame.png';
	import Egg15SingleFrame from '../../../images/easter-egg/Egg7SingleFrame.png';
	import Egg16SingleFrame from '../../../images/easter-egg/Egg8SingleFrame.png';

	import AnimateEgg1 from '../../../images/easter-egg/Egg1.png';
	import AnimateEgg2 from '../../../images/easter-egg/Egg2.png';
	import AnimateEgg3 from '../../../images/easter-egg/Egg3.png';
	import AnimateEgg4 from '../../../images/easter-egg/Egg4.png';
	import AnimateEgg5 from '../../../images/easter-egg/Egg5.png';
	import AnimateEgg6 from '../../../images/easter-egg/Egg6.png';
	import AnimateEgg7 from '../../../images/easter-egg/Egg7.png';
	import AnimateEgg8 from '../../../images/easter-egg/Egg8.png';

	export default {
		components: {},
		props: ['loggedIn', 'userId'],
		data() {
			return {
				validateRoute: '/easter-egg/validate-flip',
				saveSpinRoute: '/easter-egg/save',
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
						image: Egg1SingleFrame,
						text: '',
						index: 0,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg1
					},
					{
						image: Egg2SingleFrame,
						text: '',
						index: 1,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg2
					},
					{
						image: Egg3SingleFrame,
						text: '',
						index: 2,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg3
					},
					{
						image: Egg4SingleFrame,
						text: '',
						index: 3,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg4
					},
					{
						image: Egg5SingleFrame,
						text: '',
						index: 4,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg5
					},
					{
						image: Egg6SingleFrame,
						text: '',
						index: 5,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg6
					},
					{
						image: Egg7SingleFrame,
						text: '',
						index: 6,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg7
					},
					{
						image: Egg8SingleFrame,
						text: '',
						index: 7,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg8
					},
					{
						image: Egg9SingleFrame,
						text: '',
						index: 8,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg1
					},
					{
						image: Egg10SingleFrame,
						text: '',
						index: 9,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg2
					},
					{
						image: Egg11SingleFrame,
						text: '',
						index: 10,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg3
					},
					{
						image: Egg12SingleFrame,
						text: '',
						index: 11,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg4
					},
					{
						image: Egg13SingleFrame,
						text: '',
						index: 12,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg5
					},
					{
						image: Egg14SingleFrame,
						text: '',
						index: 13,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg6
					},
					{
						image: Egg15SingleFrame,
						text: '',
						index: 14,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg7
					},
					{
						image: Egg16SingleFrame,
						text: '',
						index: 15,
						loaded: false,
						loadedAnimate: false,
						animateImg: AnimateEgg8
					}
				],
				currentlyHovered: null,
				pickedIndex: null,
				flipping: false,
				flipped: false,
				now: new Date().getTime(),
				showEasterEggModal: false,
				pickedAnimateImage: null,
				showTokenText: false
			};
		},

		watch: {
			currentlyHovered: function() {},
			countLoadedImages: function() {
				if (this.countLoadedImages == 16 && this.showEasterEggModal && this.canSpin) {
					const minutes = 30 * 60;
					const nextTime = this.now + 1000 * minutes;
					localStorage.setItem(`nextEasterEggShowModalTime_${this.userId}`, nextTime);
					$('#easter-egg-modal').modal('show');
				}
			}
		},

		computed: {
			countLoadedImages: function() {
				return this.options.filter(option => option.loaded == true && option.loadedAnimate == true).length;
			}
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

			flipCard: function(option) {
				const selectedIndex = option.index;

				this.loading = true;
				this.flipping = true;
				this.pickedIndex = selectedIndex;
				this.pickedAnimateImage = option.animateImg;

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
								that.showTokenText = true;
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
							}, 3000);
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
			},

			checkLoaded: function(option) {
				console.log('image loaded option: ', option);
			}
		},

		mounted() {
			const that = this;

			if (this.loggedIn == 1) {
				const nextEasterEggShowModalTime = localStorage.getItem(`nextEasterEggShowModalTime_${this.userId}`);
				const that = this;
				if (!!nextEasterEggShowModalTime == false || this.now >= nextEasterEggShowModalTime) {
					this.showEasterEggModal = true;
					this.checkIfCanSpin();
				}

				// $(this.$refs.easterEggModalRef).on('show.bs.modal', function() {
				// 	that.checkIfCanSpin();
				// });
			}
		}
	};
</script>
<style scoped>
	#chinese-new-year-event-container {
	}

	.easter-egg-container {
		max-width: 100%;
	}

	.easter-egg {
		margin: auto;
		margin-bottom: 8px;
	}

	.easter-egg img {
		width: 100%;
		max-width: 120px;
	}

	.easter-egg img:hover,
	.easter-egg img:focus {
		cursor: pointer;
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
		left: 30%;
		background-color: #d4af37;
		border: none; /* Remove borders */
		color: white; /* White text */
		padding: 2px 10px; /* Some padding */
		font-size: 12px; /* Set a font size */
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

	.egg-value-container {
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

	.token {
		position: relative;
		width: 300px;
		height: 300px;
		margin: 2em auto;
		animation: token 3s steps(23) forwards;
	}

	@keyframes token {
		/*  0%{
    background-position: 0;
  } */
		0% {
			background-position: 0 -300px;
			transition-timing-function: linear;
		}
		100% {
			background-position: 0 -7200px;
			transition: opacity 1ms linear 100ms;
		}
	}

	.token-text {
		position: absolute;
		top: 40%;
		font-size: 3em;
		/* z-index: 99999; */
		left: 33%;
		color: #d4af37;
	}

	/** devices screen size wrapper guide **/

	@media (min-width: 320px) {
		.token {
			right: 7%;
		}

		.token-text {
			left: unset;
			margin-left: 75px;
		}
	}

	@media (min-width: 375px) {
		.token {
			right: unset;
		}

		.token-text {
			left: unset;
			margin-left: 65px;
		}
	}

	@media (min-width: 414px) {
		.token-text {
			margin-left: 75px;
			left: unset;
			font-size: 2em;
		}
	}

	@media (min-width: 576px) {
	}

	@media (min-width: 768px) {
		.token {
			right: unset;
		}
		.token-text {
			margin-left: 95px;
			left: unset;
			font-size: 2em;
		}
	}

	@media (min-width: 992px) {
	}

	@media (min-width: 1200px) {
	}

	/** END - devices screen size wrapper guide **/
</style>
