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
        <div class="modal-content" id="christmas-event-container">
          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>

          <!-- Modal Body -->
            <div class="modal-body">
                <h5>
                  Happy Holidays, dear bettors!<br/> 
                  <br/>
                  As the holidays draw nearer,  we present our next gift to you!<br/>
                  <br/>
                  Get a chance to win 2ez credits and our limited edition VIP 2ez jacket through a roulette game!<br/>
                  <br/>
                  Event runs from December 16,2020 to January 03, 2021.<br/>
                  <br/> 
                  * Mechanics: <br/>
                  • Bettors must have atleast 100 credits in order to spin the roulette. <br/>
                  • Bettors have a chance to spin once a day during the event duration. <br/>
                  <br/>
                  <br/>
                  Good luck and best wishes! <br/>
                  <br/>
                  The 2ez.bet family wishes you and all those close to you a joyous holiday season and a new year filled with happiness and hope for a world at peace.
                </h5>

                <div>
                    <div style="color: red;" v-if="hasError" class="alert alert-danger">{{ errorMessage}}</div>
                    <VueWinwheel :segments="options" :winner-segment="winnerSegment" :can-spin="canSpin" @doneSpin="doneSpin($event)"/>
                </div>         
                <div v-if="!canSpin && !!canSpinMessage"  class="text-center">
                          <h3>{{ canSpinMessage }}</h3>
                </div>
                <div v-if="!!gcWon" class="congrats-winner-container text-center">
                  <h3 class="trick-or-treat-element" v-if="gcCreditsWon == '2ez Jacket'">
                    You won one (1) of our limited edition VIP 2ez Jacket. <br/>
                    <small>
                      * How to claim your VIP 2ez Jacket: <br/>
                      • Copy the GIFT code below. <br/>
                      • Send the GIFT CODE to 2ez.bet facebook page: <a href="https://www.facebook.com/2ez.bet/" target="_blank">https://www.facebook.com/2ez.bet/</a> <br/>
                      • then our team will make sure that the jacket gets delivered to you! <br/>
                      
                    </small>
                  </h3>
                  <h3 class="trick-or-treat-element" v-else>
                    <small>
                      * How to claim GIFT CODE: <br/>
                      • Go to "My Profile" page. <br/>
                      • Click "Claim Gift Code" button <br/>
                      • Copy & Paste the code below <br/>
                      • then click "Continue" button <br/>
                    </small>
                  </h3>                  
                  <span class="trick-or-treat-element" style="font-size: 20px;">{{ gcWon }}</span><br/><br/>
                
                </div>

            </div>
          <!-- Modal Footer -->
          <div class="modal-footer" style="text-align:left;">
            <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- End modal for adding item -->
  </div>
</template>
<script>
import VueWinwheel from './Winwheel/VueWinWheel'
import loadingGif from '../../../images/loading.gif'
import GetFreeCredits from './GetFreeCredits'
export default {
  components:{
    VueWinwheel
  },
  props: ['loggedIn'],
  data(){
    return{
        validateRoute: '/roulette-spins/validate',
        saveSpinRoute: '/roulette-spins/save',
        preSaveSpinRoute: '//roulette-spins/pre-save',
        giftCode: '',
        createUrl: '/gift-codes',
        loading: false,
        loadingGif: loadingGif,
        newCodes: [],
        successCreate: false,
        spinValue: '',
        checkingSpin: false,
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
        //red: #ff0000
				//black: #000000
        //white: #ffffff
        options:[
            { //win 0
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'100 Credits', 
              strokeStyle: '#fadb1e',
              image: '/images/xmas/100-credits.png'
            }, 
            { //1
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //2
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },
            { //win 3
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'200 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/200-credits.png'
            }, 
            { //4
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //5
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },
            { //win 6
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'500 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/500-credits.png'
            }, 
            { //7
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            {//8
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },
            { //win 9
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'2ez Jacket',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/2ez-jacket-red.png'
            }, 
            { //10
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //11
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },
            { //win 12
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'1000 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/1000-credits.png'
            }, 
            { //13
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //14
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },   
            {//15
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'100 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/100-credits.png'
            }, 
            { //16
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //17
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },     
            { //18
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'200 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/200-credits.png'
            }, 
            { //19
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //20
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },     
            { //win 21
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'100 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/100-credits.png'
            }, 
            { //22
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //23
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },   
            { //win 24
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'100 Credits',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/100-credits.png'
            }, 
            { //25
              textFillStyle: '#ff0000',
              fillStyle: '#ffffff',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-red.png'
            },
            { //26
              textFillStyle: '#ffffff',
              fillStyle: '#ff0000',
              text:'BOKYA',
              strokeStyle: '#fadb1e',
              image: '/images/xmas/bokya-yellow.png'
            },                                                                                
          ]
    }
  },

    methods: {
      checkIfCanSpin: function(){
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
            that.canSpinMessage = message;
            if(success){
              that.winnerSegment = data.segment;
              that.validData = data
            }else{
              that.canSpin = false;
              that.canSpinMessage = message;
            }
          }
        });  

      },

      doneSpin: function(value){
        this.spinValue = value;
     
        const that = this;
        //const creditsWon = !!this.prizes[this.spinValue.index] ? this.prizes[this.spinValue.index] : 0; //hllwn

        const creditsWon = this.spinValue.text;

        const saveData = {
          credits_won : creditsWon,
          endAngle:  this.spinValue.options.endAngle,
          validData: that.validData,
          spin_data: {
            endAngle: this.spinValue.options.endAngle,
            image:  this.spinValue.options.image,
            text:  this.spinValue.options.text,
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
            if(success){
              if(won){
                that.gcWon = response.gc;
                that.gcCreditsWon = response.credits_won;
              }

            }else{
              that.hasError = true;
              that.errorMessage = message;
            }
          }
        });          
      }
    },
    mounted(){
      const that = this;
      if(this.loggedIn == 1){
        $(this.$refs.trickOrTreatModalRef).on('show.bs.modal', function(){
          that.checkIfCanSpin();
        })
      }

    }
}
</script>
<style scoped>
  #christmas-event-container{
    background-image: url('/images/xmas/x-mas-bg.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position-x: center;
    width: 600px !important;
  }
</style>