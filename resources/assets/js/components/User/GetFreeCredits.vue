<template>
  <div>
  
      <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#claim-gift-code">
        <i class="fa fa-gift" aria-hidden="true"></i> Claim Gift Code
      </button>
      
    <div
      class="modal fade"
      id="claim-gift-code"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
      data-backdrop="static"
      data-keyboard="false"
      ref="claimGiftCodeModalRef"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel" style="color: #333;">
              Claim Gift Code
            </h4>
          </div>

          <!-- Modal Body -->
            <div class="modal-body">
                    
                <div class="text-center" v-if="loading">
                    <br/>
                    <img :src="loadingGif" style="width: 50px;" /> <br />
                    Processing your Gift Code, please wait...
                </div>
                
                <div class="search-input">
                    <h3><small>ENTER YOUR GIFT CODE TO ADD CREDITS TO YOUR ACCOUNT</small></h3>
                    <div class="input-group">
                        <input type="text" class="form-control"  v-model="giftCode" placeholder="GC-XXXXXXXX">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" @click="processClaimGiftCode" :disabled="loading || !!giftCode == false ">Continue</button>
                        </span>
                    </div><!-- /input-group -->
                </div>

                <div v-if="showPartners">
                    <h3><small>Below are our partners that regularly gives away free 2ez.bet credits every week.</small></h3>

                    <div>
                        Like & follow these pages to join their weekly giveaways:

                        <div v-for="(partner, index) in partners" :key="`partner-gc-${index}`" class="partners-gc">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img class="img-responsive" :src="partner.logo" />
                                </div>
                                <div class="col-md-3">
                                    <div><h2>{{partner.name}}</h2></div>
                                    <div v-if="!!partner.facebook">Facebook: <a :href="partner.facebook">{{partner.facebook}}</a></div>
                                    <div v-if="!!partner.website">Website: <a :href="partner.facebook">{{partner.website}}</a></div>
                                </div>
                                <div class="col-md-7">
                                    <div v-if="!!partner.description">{{partner.description}}</div>
                                    <div v-if="!!partner.paymentMethods.length > 0">
                                        <h4><small>Payment Methods Available:</small></h4>
                                        <div v-for="(pMethod,pMethodIndex) in partner.paymentMethods" :key="`pmethod-${pMethodIndex}`">
                                            {{pMethod}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div>
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
    import southggLogo from '../../../images/partners/partner-southgg.jpg';
    import pgdnjayarrLogo from '../../../images/partners/partner-pgdnjr.jpg';
    import ogcreditsLogo from '../../../images/partners/partner-ogcredits.png';
  export default {
    props: {
      showPartners: {
        default: true
      },

    },
    data() {
      return {
        giftCode: '',
        createUrl: '/gift-codes',
        loading: false,
        loadingGif: loadingGif,
        newCodes: [],
        successCreate: false,
        partners: [
            {
                name: 'South.gg',
                facebook: 'https://www.facebook.com/southgg/',
                website: 'https://south.gg/',
                description: 'South.gg is a Philippine Organization aiming to introduce and influence people to Esports locally and globally. Your daily source of Esport prediction, Analysis, News and Event Organizing.',
                paymentMethods: [],
                logo: southggLogo
            },
            {
                name: 'PGDN JayArr',
                facebook: 'https://www.facebook.com/pgdnjayarr/',
                website: '',
                description: '',
                paymentMethods: [
                    'GCash', 'PayMaya', 'coins.ph', 'Palawan Padala Pawnshop', 'Kwarta Padala - MLhuillier'
                ],
                logo: pgdnjayarrLogo
            },
            {
                name: 'OGcredits',
                facebook: 'https://www.facebook.com/OGcredits/',
                website: '',
                description: 'Online Gaming Credits is your Ultimate Game Credits Loading Partner with easy modes of payments',
                paymentMethods: [
                    'GCash', 'PayMaya', 'coins.ph', 'Palawan Padala Pawnshop', 'Kwarta Padala - MLhuillier'
                ],
                logo: ogcreditsLogo
            }
        ]
      };
    },
    computed: {

    },

    methods: {
        processClaimGiftCode: function(){
            this.loading = true;
            const that = this;
            $.ajax({
            type: 'POST',
            url: '/gift-codes/claim',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                code: this.giftCode,
            },
            success: function(response) {
                that.loading = false;
                that.giftCode = '';
                const { success, message } = response;

                if(success){
                    swal({
                        title: `Gift Code claimed.`, 
                        text:  message,
                        type: "success",
                        html: true
                    });    
                }else{
                    swal({
                        title: message, 
                        type: "error",
                        html: true
                    });      
                }
                // swal.close();

            }
            });  
        },
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
</style>
