<template>
    <div>
        <div
        class="modal fade"
        id="user-messages-modal"
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
                <h2 class="modal-title" id="myModalLabel">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i> NEW MESSAGE FROM 2EZ.BET
                </h2>
            </div>

            <!-- Modal Body -->
            <div class="modal-body message-content" v-html="message">
               
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <a type="button" class="btn btn-info" @click="markAsRead" :href="messageFbLink" target="_blank">Reply & Mark as Read</a>
                <button type="button" class="btn btn-primary" @click="markAsRead">Mark as Read</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Ignore for now</button>
            </div>
            </div>
        </div>
        </div>

    <!-- End modal for adding item -->        
    </div>
</template>

<script>
  export default {
    props: ['loggedIn'],
    data() {
      return {
          messagesUrl: '/user-messages/list',
          messagesReadUrl: '/user-messages/read',
          message: '',
          now: new Date().getTime(),
          messageFbLink: 'https://m.me/2ez.bet'
      };
    },
    computed: {

    },

    methods: {
        checkMessages: function(){
            const that = this;
            $.ajax({
                type: 'GET',
                url: this.messagesUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const minutes = 5*60;
                    const nextTime = that.now + (1000*minutes);
                    // localStorage.setItem("nextMessageCheckTime", nextTime);
                    const { hasMessage, messages } = JSON.parse(response);
                
                    if(hasMessage){
                        const messagesHtml = `${messages.map(message => {
                            message.message = message.message.replace(new RegExp('\r?\n','g'), '<br>');
                            console.log(`${message.message.replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank">$1</a>')} - <small class="text-muted"> Sent ${ message.date_sent } </small>`);
                            return `${message.message.replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank">$1</a>')} - <small class="text-muted"> Sent ${ message.date_sent } </small>`
                        }).join('<hr/>')}`;
                    
                        $('#user-messages-modal').modal();
                        that.message = messagesHtml;
                    }

                    
                    

                }
            });  
        },

        markAsRead: function(){
            $('#user-messages-modal').modal('hide');
            $.ajax({
                type: 'PUT',
                url: this.messagesReadUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('response:', response)
                }
            });             
        }
    },

    created(){
        if(this.loggedIn == 1){
            const nextMessageCheckTime = localStorage.getItem("nextMessageCheckTime");
            const that =  this;
            if(!!nextMessageCheckTime == false || this.now >= nextMessageCheckTime){
                setTimeout(function () {
                        that.checkMessages();
                }, 5000);
            }
        }

        
    }
  };
</script>

<style scoped>
    .message-content{
        font-weight: 500;
        font-size: 14px;
        word-break: break-word;
    }
</style>
