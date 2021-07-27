<template>
  <div>
    <div class="col-md-4" style="margin: 0px 0px 10px -15px;">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-gift-code">
        <i class="fa fa-plus"></i> Create Gift Codes
      </button>
    </div>
    <div
      class="modal fade"
      id="create-gift-code"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
      data-backdrop="static"
      data-keyboard="false"
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
              Create Gift Codes
            </h4>
          </div>

          <!-- Modal Body -->
          <div class="modal-body">
            
          <div class="text-center" v-if="loading">
            <img :src="loadingGif" style="width: 50px;" /> <br />
            Creating Gift Code(s), please wait...
          </div>
          
          <div v-if="successCreate">
          <div class="alert alert-success">
            Created {{gcQuantity}} new gift codes successfully!
            <a href="#" class="alert-link" @click="copyGiftCodes">Copy Gift Codes</a>
          </div>
            <textarea class="form-control" rows="10" :value="giftCodes.join('\n')" id="giftCodesGenerated"></textarea>
          </div>

          <form v-if="!loading" role="form" method="POST" enctype="multipart/form-data" id="market-product-form">
            <div class="form-group">
              <div class="row">
                <div class="col-md-8">
                  <label for="market_item_price">Gift Code Value</label>
                  <input type="number" class="form-control" v-model="gcAmount" />
                </div>
                <div class="col-md-4">
                  <label for="market_item_price"># of GC to Create</label>
                  <input type="number" min="1" max="100" class="form-control" v-model="gcQuantity" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-12">
                  <label for="market_item_price">Gift Code Purpose</label>
                  <select class="form-control" v-model="gcPurpose">
                    <option value="1">Give Away</option>
                    <option value="2">Resell</option>
                    <option value="3">Disbalance Fix</option>
                  </select>
                </div>
              </div>
            </div>
         

            <div class="form-group">
              <div class="row">
                <div class="col-md-12">
                  <label for="market_item_price">Give it to</label>
                  <input type="text" class="form-control" v-model="gcGiveTo" />
                </div>
              </div>
            </div>


            <div class="form-group">
              <label for="market_item_desc">Gift Code Description</label>
              <textarea
                class="form-control rounded-0"
                id="market_item_desc"
                rows="3"
                name="market_item_desc"
                v-model="gcDescription"
              ></textarea>
            </div>
          </form>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-success" @click="createGiftCodes" :disabled="loading">Create</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" :disabled="loading">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- End modal for adding item -->
  </div>
</template>

<script>
  import loadingGif from '../../../images/loading.gif'
  export default {
    data() {
      return {
        gcAmount: 500,
        gcQuantity: 1,
        gcPurpose: 1,
        gcGiveTo: '',
        gcDescription: '',
        createUrl: '/gift-codes',
        loading: false,
        loadingGif: loadingGif,
        newCodes: [],
        successCreate: false
      };
    },
    computed: {
      giftCodes: function(){
        return this.newCodes.map(code => code.code)
      }
    },

    methods: {
      createGiftCodes() {
        const that = this;
        this.loading = true;
        const createData = {
          amount: this.gcAmount,
          quantity: this.gcQuantity,
          purpose: this.gcPurpose,
          give_to: this.gcGiveTo,
          description: this.gcDescription
        };

        $.ajax({
          type: 'POST',
          url: this.createUrl,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType: 'json',
          data: createData,
          success: function(response) {
            that.loading = false;
            that.successCreate = true;
            that.newCodes = response.new;
            console.log('done creating....', response);
          }
        });
        // $.post(this.createUrl, createData, function() {});
      },

      copyGiftCodes: function(event){
        event.preventDefault();
        const copyText = document.getElementById("giftCodesGenerated");

        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/
        document.execCommand("copy");
      }
    }
  };
</script>

<style></style>
