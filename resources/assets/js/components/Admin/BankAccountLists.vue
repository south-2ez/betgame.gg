<template>
  <div class="tab-content" style="margin-top: 20px">
      <br />
      <table id="" class="table table-striped" width="100%">
        <thead>
          <tr>
            <th>Account Type</th>
            <th>Account Name</th>
            <th>Account Number</th>
            <th>Account Alias/Nickname</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(account,index) in accounts" :key="`accounts-${index}`">
            <td>{{ getAccountType(account) }}</td>
            <td>{{ getAccountName(account) }}</td>
            <td>{{ getAccountNumber(account) }}</td>
            <td>{{ getAccountAlias(account) }}</td>
            <td class="text-center">
                <span class="text-success" v-if="account[0].status == 1">Enabled</span>
                <span v-else>Disabled</span>
            </td>
            <td class="text-center">
                <button class="btn btn-danger btn-xs" @click="deleteAccount(account)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- <div class="text-center" v-if="loading">
        <img :src="loadingGif" style="width: 50px;" /> <br />
        Loading more gift codes...
      </div>       -->
  
  </div>
</template>

<script>
  import loadingGif from '../../../images/loading.gif'
  export default {
    data() {
      return {
        getUrl: '/admin/get-site-accounts',
        accounts: [],
        loading: false,
        loadingGif: loadingGif,
        updateUrl: '/admin/update-site-accounts',
        deleteUrl: '/admin/delete-site-accounts'

      };
    },
    computed: {

      
    },
    methods: {
      getAccounts() {
     
          this.loading = true;        
          const that = this;
          $.ajax({
            type: 'GET',
            url: this.getUrl,
            success: function(response) {
                console.log('resposne accoutns: ', response)
                that.accounts = response.accounts;
            //   const responseParsed = JSON.parse(response)
            //   const { codes, offset, maxTotal } = responseParsed;
            //   // this.codes = [...this.codes, ...codes];
            //   that.codes = that.codes.concat(codes);
            //   that.offset = offset;
            //   that.maxTotal =  maxTotal;       
            //   that.busy = false;   
            }
          });
        
      },

      getAccountName(account){
          const data = account.filter(acc => acc.name.includes('account-name') );
          return !!data ? data[0].value : '';
      },

      getAccountType(account){
          const data = account.filter(acc => acc.name.includes('account-name') );
          const accType = !!data ? data[0].name.replace('-account-name','') : '';
          switch(accType){
            case 'bdo': 
                return 'BDO';
            case 'bpi': 
                return 'BPI';
            case 'security':
                return 'Security Bank';
            case 'metro':
                return 'Metro Bank';
            default:
                return accType;
          }
      },


      getAccountNumber(account){
          const data = account.filter(acc => acc.name.includes('account-number') );
          return !!data ? data[0].value : '';
      },


      getAccountAlias(account){
          const data = account.filter(acc => acc.name.includes('account-alias') );
          return !!data ? data[0].value : '';
      },

    setAccountAsEnabled: function(account){
        const that = this;
        swal({
            title: `Enable Account`,
            text: `You are about to  <strong style="color:green;">enable</strong> this account for deposits:<br/>
                <div style="font-size: 16px;">
                <span style='font-weight:bold;color: #820804'>Account Type:</span> ${that.getAccountType(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Name:</span> ${that.getAccountName(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Number:</span> ${that.getAccountNumber(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Alias:</span> ${that.getAccountAlias(account)} <br/>
                </div>
                Do you want to proceed?`,
            type: "warning", 
            showCancelButton: true,
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, please proceed.",
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            html:true
        },function(confirmed){
            if(confirmed){
                that.setAccountStatus({
                    account_key: account[0].account_key,
                    status: 1
                });
            }
        });

    },

    setAccountAsDisabled: function(account){
        const that = this;
        swal({
            title: `Disble Account`,
            text: `You are about to <strong style="color:red;">disable</strong> this account for deposits:<br/>
                <div style="font-size: 16px;">
                <span style='font-weight:bold;color: #820804'>Account Type:</span> ${that.getAccountType(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Name:</span> ${that.getAccountName(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Number:</span> ${that.getAccountNumber(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Alias:</span> ${that.getAccountAlias(account)} <br/>
                </div>
                Do you want to proceed?`,
            type: "warning", 
            showCancelButton: true,
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, please proceed.",
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            html:true
        },function(confirmed){
            if(confirmed){
                that.setAccountStatus({
                    account_key: account[0].account_key,
                    status: 0
                });
            }
        });
    },

    setAccountStatus: function(data){
        const that = this;
        $.ajax({
            type: 'PUT',
            url: that.updateUrl,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: data,
            success: function(response) {
                const { success, message } = response;
                if(success){
                    swal({
                        title: `Account status updated.`, 
                        text: message,
                        type: "success",
                        html: true
                    });  
                    
                    that.getAccounts();
                }else{
                    swal({
                        title: message, 
                        type: "error",
                        html: true
                    });    
                }

            }
        });      
    },


      deleteAccount: function(account){
        const that = this;
          swal({
                title: `Deleting Account`,
                text: `You are about to  <strong style="color:red;">DELETE</strong> this account:<br/>
                <div style="font-size: 16px;">
                <span style='font-weight:bold;color: #820804'>Account Type:</span> ${that.getAccountType(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Name:</span> ${that.getAccountName(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Number:</span> ${that.getAccountNumber(account)} <br/>
                <span style='font-weight:bold;color: #820804'>Account Alias:</span> ${that.getAccountAlias(account)} <br/>
                </div>
                Do you want to proceed?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, please continue.",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                html:true
          },
          function(confirmed){

            if(confirmed){
              $.ajax({
                type: 'DELETE',
                url: that.deleteUrl,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                  account_key : account[0].account_key
                },
                success: function(response) {
                  swal({
                      title: `Account deleted.`, 
                      text: `<span style='font-weight:bold;color: #820804'>${that.getAccountAlias(account)}</span> deleted.`,
                      type: "success",
                      html: true
                  });     
                   that.getAccounts(); 
                }
               
              });   
            }

            
          });    
      },

      multiplDelete: function(){
        const that = this;
          swal({
              title: `Deleting ${that.forDeleteCodes.length} codes`,
              text: `You are about to delete <span style='font-weight:bold;color: #820804'>${that.forDeleteCodes.length} gift code(s)</span>, do you want to proceed?.`,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "Yes, please continue.",
              showLoaderOnConfirm: true,
              closeOnConfirm: false,
              html:true
          },
          function(confirmed){

            if(confirmed){
              $.ajax({
                type: 'DELETE',
                url: that.updateUrl,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                  id: that.forDeleteCodes
                },
                success: function(response) {
                  console.log('response');
                  // swal.close();
                  swal({
                      title: `Gift Code deleted.`, 
                      text: `<span style='font-weight:bold;color: #820804'>${that.forDeleteCodes.length} gift codes</span> deleted.`,
                      type: "success",
                      html: true
                  });      
                }
              });   
            }

            
          }); 
      },

      statusText(status) {
        switch (status) {
          case 1:
            return 'Used';
          case 2:
          case 0:
            return 'Gifted';
        }
      }
    },
    mounted() {
      this.getAccounts();
    }
  };
</script>

<style scoped>
  .status-Available {
    color: green;
    font-weight: 600;
  }

  .status-Gifted {
    color: #337ab7;
    font-weight: 600;
  }

  .gift-code-types-filter{
    margin-top: 10px;
    margin-bottom: 10px;
  }

  .search-container{
    display: flex;
  }

  .search-input{
    width: 90%;
    margin-right: 5px;
  }

  .clear-search-input{
    width: 8%;
  }
</style>
