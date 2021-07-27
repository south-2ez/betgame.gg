<template>
  <div>
    <div class="col-md-4" style="margin: 10px 0px 10px -15px;">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-gift-code">
        <i class="fa fa-plus"></i> Add New Account
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
                Add New Account
            </h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            
            <div class="text-center" v-if="loading">
                <img :src="loadingGif" style="width: 50px;" /> <br />
                Adding account, please wait...
            </div>
            
            <div v-if="successCreate">
                <div class="alert alert-success">
                    Created account with succesfully! 
                </div>

            </div>

            <div v-if="!!createErrors">
                <div class="alert alert-danger">
                    <ul>
                        <li  v-for="(createError,index) in createErrors" :key="`create-${index}`">
                            {{ createError[0] }}
                        </li>
                    </ul>
                </div>

            </div>



            <form v-if="!loading" role="form" method="POST" enctype="multipart/form-data" id="site_add_account_form">

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="market_item_price">Account type</label>
                        <select class="form-control" v-model="accountType">
                            <option value="bdo">BDO</option>
                            <option value="bpi">BPI</option>
                            <option value="metro">Metro Bank</option>
                            <option value="security">Security Bank</option>
                        </select>
                    </div>
                </div>
            </div>

        <div class="form-group">
            <div class="row">
            <div class="col-md-12">
                <label for="market_item_price">Account Name:</label>
                <input type="text" class="form-control" :name="`${accountType}-account-name`" v-model="accountName" placeholder="Account Name"/>
            </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
            <div class="col-md-12">
                <label for="market_item_price">Account Number:</label>
                <input type="text" class="form-control" :name="`${accountType}-account-number`" v-model="accountNumber" placeholder="Account Number"/>
            </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
            <div class="col-md-12">
                <label for="market_item_price">Alias/Nickname:</label>
                <input type="text" class="form-control" :name="`${accountType}-account-alias`" v-model="accountAlias" placeholder="Account Alias/Nickname"/>
            </div>
            </div>
        </div>

        </form>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-success" @click="addAcount" :disabled="loading">Create</button>
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
                loadingGif: loadingGif,
                loading: false,
                successCreate: false,
                accountName: '',
                accountNumber: '',
                accountAlias: '',
                accountType: '',
                addAccountUrl: '/admin/add-site-account',
                createErrors: null,
            }
        },
        computed: {
        //   giftCodes: function(){
        //     return this.newCodes.map(code => code.code)
        //   }
        },

        methods: {
            addAcount() {
                const that = this;
                console.log('that: ', that)
                this.loading = true;
                this.createErrors = null;
                const createData = {
                    accountType: this.accountType,
                    accountName: this.accountName,
                    accountNumber: this.accountNumber,
                    accountAlias: this.accountAlias
                };

                
                console.log('createData : ', createData)
                $.ajax({
                  type: 'POST',
                  url: this.addAccountUrl,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  dataType: 'json',
                  data: createData,
                  success: function(response) {
                    that.loading = false;
                    that.successCreate = response.success;
                    that.createErrors = !response.success ? response.errors : null

                    if(response.success){
                        that.accountType = '';
                        that.accountName = '';
                        that.accountNumber = '';
                        that.accountAlias = '';
                        
                        that.$parent.$refs.bankAccountListsRef.getAccounts();
                    }



                    console.log(that.createErrors)
           
                    console.log('done creating....', response);
                  }
                });

            },

        },
    };
</script>

<style></style>
