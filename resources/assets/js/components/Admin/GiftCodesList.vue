<template>
  <div class="tab-content" style="margin-top: 20px">

    <div
      infinite-scroll-disabled="scrollDisabled"
      v-infinite-scroll="getGiftCodes"
      infinite-scroll-throttle-delay="1000"
    >

      <br />
      <div class="search-container">
        <div class="search-input">
          <div class="input-group">
            <input type="text" class="form-control"  v-model="searchCodeText" >
            <span class="input-group-btn">
              <button class="btn btn-primary" type="button" @click="searchGiftCodes">Search</button>
            </span>
          </div><!-- /input-group -->
        </div>
        <div class="clear-search-input">
          <button class="btn btn-default" type="button" @click="clearSearch">Clear / Refresh</button>
        </div>
      </div>
      <div class="gift-code-types-filter">
        <label class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox1" v-model="filterAll"> All
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox2" v-model="filterAvailable"> Available
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox3" v-model="fitlerGifted"> Gifted
        </label>
        <label class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox3" v-model="filterUsed"> Used
        </label>

         <button class="btn btn-sm btn-danger float-right" style="margin-right: 60px;" @click="multiplDelete">Delete Codes</button>
      </div>
      <br />
      <table id="" class="table table-striped" width="100%">
        <thead>
          <tr>
            <th>Code</th>
            <th>Date Created</th>
            <th>Date Redeemed</th>
            <th>Status</th>
            <th>Given To</th>
            <th>Used By</th>
            <th>Amount</th>
            <th>Purpose</th>
            <th>Description</th>
            <th>Created By</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="code in filteredCodes" :key="`code-${code.id}`">
            <td>
              <div class="checkbox">
                <label>
                  <input type="checkbox" :value="code.id" v-model="forDeleteCodes" :disabled="code.status == 1">
                  {{ code.code }}
                </label>
              </div>
            </td>
            <td>{{ code.created_at }}</td>
            <td>{{ code.date_redeemed }}</td>
            <td :class="`status-${statusText(code.status)}`">{{ statusText(code.status) }}</td>
            <td>{{ code.give_to }}</td>
            <td>{{ !!code.used_by ? code.used_by.name : '' }}</td>
            <td>&#8369; {{ code.amount | formatMoney }}</td>
            <td>{{ code.purpose == 1 ? 'Give Away' : code.purpose == 2 ? 'Resell' : 'Disbalance Fix' }}</td>
            <td>{{ code.description }}</td>
            <td>{{ !!code.generated_by ? code.generated_by.name : '' }}</td>
            <td class="text-center">
              <!-- <button class="btn btn-sm btn-primary" :disabled="code.status != 0" @click="markAsGifted(code)">Mark as Gifted</button> -->
              <button class="btn btn-sm btn-danger" :disabled="code.status != 0" @click="deleteGiftCode(code)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="text-center" v-if="busy">
        <img :src="loadingGif" style="width: 50px;" /> <br />
        Loading more gift codes...
      </div>      
    </div>
  </div>
</template>

<script>
  import loadingGif from '../../../images/loading.gif'
  export default {
    data() {
      return {
        getUrl: '/gift-codes/list',
        codes: [],
        filterAll: true,
        filterAvailable: true,
        fitlerGifted: true,
        filterUsed: true,
        searchCodeText: '',
        isSearching: false,
        busy: false,
        offset: 0,
        maxTotal: 999999999,
        loadingGif: loadingGif,
        updateUrl: '/gift-codes',
        forDeleteCodes: []
      };
    },
    watch: {
      filterAll: function(){
        if(this.filterAll){
          this.filterAvailable = true;
          this.fitlerGifted = true;
          this.filterUsed = true;
        }
      }
    },
    computed: {
      filteredCodes: function(){
        return this.codes.filter(code => {
          let show = true;
          if(!this.filterAll){
            switch(code.status){
              case 1:
                show = this.filterUsed;
                break;
              case 2:
                show = this.fitlerGifted;
                break;
              case 0:
                show = this.filterAvailable;
                break;
            }
          }

          return show;
        })
      },
      scrollDisabled: function(){
        return this.busy || this.offset > this.maxTotal || this.isSearching;
      }
    },
    methods: {
      getGiftCodes() {
        if (this.offset < this.maxTotal && !this.busy) {
          this.busy = true;        
          const that = this;
          $.ajax({
            type: 'GET',
            url: this.getUrl,
            data: {
              search: this.searchCodeText,
              offset: this.offset
            },
            success: function(response) {
              const responseParsed = JSON.parse(response)
              const { codes, offset, maxTotal } = responseParsed;
              // this.codes = [...this.codes, ...codes];
              that.codes = that.codes.concat(codes);
              that.offset = offset;
              that.maxTotal =  maxTotal;       
              that.busy = false;   
            }
          });
        }
      },
      searchGiftCodes: function(){
        this.isSearching = true;
        this.offset = 0;
        this.codes = [];
        this.getGiftCodes();
      },

      clearSearch: function(){
        this.searchCodeText = '';
        this.getGiftCodes;
        this.filterAll = true;
        this.filterAvailable = true;
        this.fitlerGifted = true;
        this.filterUsed = true;
        this.offset = 0;
        this.isSearching = false;
        this.codes = [];
        this.getGiftCodes();
      },

      markAsGifted: function(code){
        const that = this;
          swal({
              title: `Marking ${code.code} as gifted`,
              text: `Gift Code: <span style='font-weight:bold;color: #820804'>${code.code}</span> with the amount of <span style='font-weight:bold;color: #820804'>&#8369;${code.amount}</span> will be available for redeeming.`,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-primary",
              confirmButtonText: "Yes, please continue.",
              showLoaderOnConfirm: true,
              closeOnConfirm: false,
              html:true
          },
          function(confirmed){
            if(confirmed){
              $.ajax({
                type: 'PUT',
                url: that.updateUrl,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                  id: code.id,
                  status: 2
                },
                success: function(response) {
                  const { success, message } = response;
                  if(success){
                    swal({
                        title: `Gift Code updated.`, 
                        text: `<span style='font-weight:bold;color: #820804'>${code.code}</span> status changed to gifted.`,
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
  
                }
              });   
            }

 
            
      
          });    
      },
      deleteGiftCode: function(code){
        const that = this;
          swal({
              title: `Delete Gift Code: ${code.code}`,
              text: `You are about to delete Gift Code: <span style='font-weight:bold;color: #820804'>${code.code}</span> with the amount of <span style='font-weight:bold;color: #820804'>&#8369;${code.amount}</span>, do you want to proceed?.`,
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
                  id: code.id,
                },
                success: function(response) {
                  console.log('response');
                  // swal.close();
                  swal({
                      title: `Gift Code deleted.`, 
                      text: `<span style='font-weight:bold;color: #820804'>${code.code}</span> deleted.`,
                      type: "success",
                      html: true
                  });      
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
      this.getGiftCodes();
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
