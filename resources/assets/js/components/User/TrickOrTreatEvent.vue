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
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
              Happy Halloween 2020 <img src="/images/halloween/ghost1_75x75.png" />
            </h4>
          </div>

          <!-- Modal Body -->
            <div class="modal-body">
   

                <div>
                    <h4>
                      <p>Ghostly Greetings, dear bettors!</p>

                      <p>
                      It's Halloween season and we have a spooky surprise for you. <br/><br/>
                      Trick or Treat! Test your luck by clicking the pumpkin in our site home page (beside our logo) and get a chance to win up to 1000 2ez credits daily.
                      </p>

                      <p>
                      All you need to have is atleast 100 2ez credits in your account in order to have 1 spin daily.
                      </p>

                      <p>
                      The daily spin resets at 12:00MN. <br/>
                      Promo runs from November 1,2020 to November 7, 2020 only!
                      </p>

                      <p>
                      So what are you waiting for? Find the pumpkin and happy spinning!</p>

                      Best of luck! <br/>
                      #HappyHalloWin #2ez
                    </h4>

                    <div class="halloween-event-mechanics-container">
                      Important Information:
                      <ol>
                        <li>Click the PUMPKIN image besides our logo located at the top left of the site.</li>
                        <li>Each user is only allowed to spin the roulette once a day.</li>
                        <li>Only users with atleast 100 credits can spin the roulette.</li>
                        <li>Daily spin resets at 12:00 midnight</li>
                        <li>This event will run for a week (from November 1 to November 7).</li>
                      </ol>
                    </div>

                    <div v-if="!!gcWon" class="congrats-winner-container text-center">
                      <h3 class="trick-or-treat-element">
                        Congratulations!<br/> You won {{ gcCreditsWon}} credits.
                        <br/> Copy the Gift code below, and click Claim Gift Code button to claim credits:
                      </h3>
                      <span class="trick-or-treat-element">{{ gcWon }}</span><br/><br/>
                    
                    </div>

                    <div v-if="!!gcWon" class="text-center">
                      <get-free-credits :show-partners="false"></get-free-credits>
                    </div>
                  

                    
                    <div class="text-center roullete-container" v-if="canSpin">
                        <simple-roulette 
                          :options="prizes" 
                          canvas-width="500" 
                          canvas-height="500"
                          :spin-angle-start="startAngle"
                          :spin-time-total="spinTime"
                          @doneSpin="doneSpin($event)"
                          v-if="canSpin"
                        ></simple-roulette>
                    
                    </div>
                    <div v-else-if="!canSpin && !!canSpinMessage"  class="text-center">
                        <h3>{{ canSpinMessage }}</h3>
                    </div>
                    <div v-else-if="checkingSpin" class="text-center">
                        <small>Checking spin availability... </small>
                    </div>                    
                </div>         

            </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" :disabled="loading">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- End modal for adding item -->
  </div>
</template>

<script>
  import loadingGif from '../../../images/loading.gif'
  import SimpleRoulette from './SimpleRoulette'
  import GetFreeCredits from './GetFreeCredits'
  export default {
    components: {
      SimpleRoulette,
      GetFreeCredits
    },
    props: ['loggedIn'],
    data() {
      return {
        validateRoute: '/roulette-spins/validate',
        saveSpinRoute: '/roulette-spins/save',
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
        prizes: [
            '',
            '1000',
            '',
            '',
            '100', 
            '',
            '',
            '200' ,
            '',
            '',
            '100', 
            '',
            '',
            '500', 
            '',
            '',
            '100', 
            '',
          ]                                                                      
        
      };
    },
    computed: {},
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
              that.startAngle = data.start_angle;
              that.spinTime = data.spin_time;
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
        const creditsWon = !!this.prizes[this.spinValue.index] ? this.prizes[this.spinValue.index] : 0;
        const saveData = {
          credits_won : creditsWon,
          start_angle : this.startAngle,
          spin_time: this.spinTime,
          spin_data: this.spinValue,
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
              alert(message);
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
  };
</script>

<style scoped>
  #claim-gift-code .modal-dialog{
      width: 80% !important;
  }

  #claim-gift-code .modal-body{
      padding-top: 0px;
  }

  .partners-gc{
      margin-top: 10px;
      margin-bottom: 10px;
  }

  #trick-or-treat-event-modal{
    color: aliceblue;
  }

  #trick-or-treat-event-modal .modal-content{
    background-image: url('/images/halloween/tournament-bg.png');
    background-color: #616161;
    min-height: 500px;
    background-size: cover;
  }

  #trick-or-treat-event-modal .modal-title{
    text-transform: uppercase;
    font-weight: 600;
    font-size: 25px;
    text-align: center;
    color: #d4af37;
  }

  .congrats-winner-container{
    text-align: center;
  }

  .congrats-winner-container .trick-or-treat-element{
      color: #04ff00;
      font-size: 16px;
      
  }

  @media only screen and (max-width: 768px) {
      /* For mobile phones: */
      .roullete-container {
        position: relative;
        left: -18%;
      }
  }
</style>
