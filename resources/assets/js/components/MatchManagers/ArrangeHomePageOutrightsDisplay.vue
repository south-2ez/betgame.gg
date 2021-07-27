<template>
  <div style="display:inline;">
 
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-gift-code">
        <i class="fa fa-sort" aria-hidden="true"></i> Re-order Active Outrights
      </button>
   
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
              Re-order Active Outrights in Home Page
            </h4>
          </div>
          <br/>
          <div class="alert alert-success" v-if="updated">
            Successfully updated outright order!
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <draggable 
                v-model="leagues"         
                class="list-group"
                ghost-class="ghost"
                @start="dragging = true"
                @end="dragging = false"
            >
                <div v-for="(league, index) in leagues" :key="league.id" class="list-group-item">
                    {{league.name}}
                    <span class="badge">{{ index+1 }}</span>
                </div>
            </draggable>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-success" :disabled="loading" @click="saveLeaguesDisplayOrder">Save</button>
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
  import draggable from 'vuedraggable'

  export default {
    components: {
        draggable
    },
    data() {
        return {
            loading: false,
            leagues: [],
            getActiveLeaguesUrl: '/matchmanager/leagues/active',
            updateUrl: '/matchmanager/leagues/update/order',
            updated: false,
        };
    },
    computed: {
        giftCodes: function(){
            return this.newCodes.map(code => code.code)
        }
    },

    methods: {

        getActiveLeagues(){
            const that = this;
            console.log('getActiveLeagues called.')
            $.ajax({
                type: 'GET',
                url: this.getActiveLeaguesUrl,
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    that.leagues = response.leagues;
                    console.log('getActiveLeagues resposne:', response)
                }
            });
        },

        saveLeaguesDisplayOrder(){
            const that = this;
            this.loading = true;
            this.updated = false;
            console.log(this.leagues);

            $.ajax({
                type: 'PUT',
                url: this.updateUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: { leagues: that.leagues },
                success: function(response) {
                    that.loading = false;
                    that.updated = true;
                    
                }
            });
        }


    },

    mounted(){

        this.getActiveLeagues();
    }
  };
</script>

<style>
    .ghost {
    opacity: 0.5;
    background: #c8ebfb;
    }
</style>
