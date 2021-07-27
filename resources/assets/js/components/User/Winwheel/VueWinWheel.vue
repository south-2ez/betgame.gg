<template>
		<section class="vue-winwheel">
			<div class="mobile-container">
				<h1></h1>
				<div class="wheel-wrapper">
					<div class="canvas-wrapper">
						<img src="/images/logo-emboss.png" id="roulette-inner-icon" />
						<canvas id="canvas" width="360" height="360">
							<p style="{color: white}" align="center">Sorry, your browser doesn't support canvas. Please try Google Chrome.</p>
						</canvas>
					</div>
					<div class="button-wrapper" v-if="canSpin && !spinned ">
						<a class="btn btn-play" href="#" @click.prevent="startSpin()" v-if="!loadingPrize && !wheelSpinning">SPIN!</a>
					</div>
				</div>
			</div>
			<div class="custom-modal modal-mask" id="modalSpinwheel" v-if="modalPrize">
				<div slot="body">
					<a href="" @click.prevent="hidePrize()" class="modal-dismiss">
						<i class="icon_close"></i>
					</a>
					<h2>
						{{ prizeName != 'BOKYA' ? 'Congratulations you won: ' : 'Sorry, better luck next time!'}}
					</h2>
					<h1> {{prizeName}}</h1>
				</div>
			</div>
		</section>
</template>


<script>
import * as Winwheel from './Winwheel.min'
export default {
  name: 'VueWinWheel',
  props:{
		segments:{
			default(){
				return [								
				]
			}
		},
		winnerSegment: {
			default: -1,
		},
		canSpin: {
			default: false,
		}
  },
  data () {
    return {
			preSaveSpinRoute: '/roulette-spins/pre-save',
      loadingPrize: false,
      theWheel: null,
      modalPrize: false,
      wheelPower: 1,
      wheelSpinning: false,
			prizeName: 'BUY 1 GET 1',
			spinned: false,
			checkingSpin: false,
      WinWheelOptions: {
        textFontSize: 14,
        outterRadius: 410,
        innerRadius: 25,
				lineWidth: 8,
				drawMode: 'segmentImage',
        animation: {
          type: 'spinOngoing',
          duration: 0.5
        }
      }
    }
  },
  methods: {
    showPrize () {
      this.modalPrize = true
    },
    hidePrize () {
      this.modalPrize = false
		},

    startSpin () {
			this.spinned = true;
      if (this.wheelSpinning === false) {
        this.theWheel.startAnimation()
        this.wheelSpinning = true
        this.theWheel = new Winwheel.Winwheel({
          ...this.WinWheelOptions,
          numSegments: this.segments.length,
          segments: this.segments,
          animation: {
            type: 'spinToStop',
            duration: 5,
            spins: 5,
            callbackFinished: this.onFinishSpin
          }
        })
        // example input prize number get from Backend
        // Important thing is to set the stopAngle of the animation before stating the spin.
        var prizeNumber = Math.floor(Math.random() * this.segments.length) // or just get from Backend
				//var stopAt = 360 / this.segments.length * prizeNumber - 360 / this.segments.length / 2 // center pin
					
        //this.theWheel.animation.stopAngle = 354;
				
				this.theWheel.animation.stopAngle = this.winnerSegment
				this.theWheel.startAnimation()
        this.wheelSpinning = false
      }
    },
    resetWheel () {
      this.theWheel = new Winwheel.Winwheel({
        ...this.WinWheelOptions,
        numSegments: this.segments.length,
        segments: this.segments
      })
      if (this.wheelSpinning) {
        this.theWheel.stopAnimation(false) // Stop the animation, false as param so does not call callback function.
      }
      this.theWheel.rotationAngle = 0 // Re-set the wheel angle to 0 degrees.
      this.theWheel.draw() // Call draw to render changes to the wheel.
      this.wheelSpinning = false // Reset to false to power buttons and spin can be clicked again.
    },
    initSpin () {
      this.loadingPrize = true
            this.resetWheel()
            this.loadingPrize = false
    },
    onFinishSpin (indicatedSegment) {
			this.prizeName = indicatedSegment.text
			this.showPrize()
			
      const doneData = {
        text : indicatedSegment.text,
        options: indicatedSegment,
      }

      this.$emit('doneSpin',doneData);			
    }
  },
  computed: {},
  updated () {},
  mounted () {
    this.initSpin()
    // this.resetWheel()
  },
  created () {}
}
</script>

<style scoped>
.vue-winwheel {
	text-align: center;
	background-image: url('/static/img/obstacle-run/bg-spinner-mobile.svg');
	background-size: cover;
	background-position: center bottom;
	background-repeat: no-repeat;
}
.vue-winwheel h1 {
	color: #b32656;
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	font-size: 36px;
	line-height: 90px;
	letter-spacing: 4px;
	margin: 0;
}
.vue-winwheel h2 {
	margin: 0;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content {
	width: calc(100vw - 30px);
	padding-top: 52px;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content h2 {
	text-transform: uppercase;
	color: #b32656;
	margin-bottom: 16px;
	margin-top: 0;
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	font-size: 18px;
	letter-spacing: 1.1px;
	margin: 0;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content p {
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	font-size: 14px;
	color: black;
	text-align: center;
	line-height: 25px;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content p strong {
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content .modal-dismiss {
	top: 12px;
	right: 12px;
}
.vue-winwheel #modalSpinwheel.custom-modal .content-wrapper .content .modal-dismiss i.icon_close {
	font-size: 30px;
	color: #da2a52;
}
.vue-winwheel canvas#canvas {
	position: relative;
}
.vue-winwheel .canvas-wrapper {
	position: relative;
}
.vue-winwheel .canvas-wrapper:after {
	content: '';
	display: block;
	width: 42px;
	background: #ffffff;
	/* background-image: url('/images/logo-emboss.png');
	background-position-x: center;
	background-position-y: center; */
	height: 42px;
	position: absolute;
	left: calc(50% - 25px);
	margin: auto;
	border-radius: 100%;
	top: calc(50% - 29px);
	border: 5px solid white;
	box-sizing: content-box;
}
.vue-winwheel .canvas-wrapper:before {
	content: '';
	display: block;
	width: 360px;
	background: #a52323;
	height: 360px;
	position: absolute;
	left: 0;
	right: 0;
	margin: 0 auto;
	border-radius: 100%;
	top: 0;
}
.vue-winwheel .wheel-wrapper {
	position: relative;
}

.vue-winwheel .wheel-wrapper #roulette-inner-icon{
    position: absolute;
    top: 150px;
    z-index: 9999;
    left: 255px;
    width: 60px;
}

.vue-winwheel .wheel-wrapper:before {
	content: '';
	width: 55px;
	height: 55px;
	position: absolute;
	top: -10px;
	left: calc(50% - 31px);
	right: 0;
	display: block;
	z-index: 99999;
	/* background-image: url('./flower_with_bell_55x55.png'); */
	background-image: url('/images/xmas/point-white-1.png');
	background-repeat: no-repeat;
	background-size: contain;
	background-position: center;
}
.vue-winwheel .wheel-wrapper .button-wrapper {
	margin: 0 auto;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	width: 231px;
	height: 118px;
}
.vue-winwheel .wheel-wrapper .btn.btn-play {
	padding: 0 58px !important;
	background: #d4af37;
	height: 40px;
	line-height: 40px;
	color: white;
	font-weight: bold;
	text-decoration: none;
	border-radius: 2px;
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	letter-spacing: 2px;
}
</style>
