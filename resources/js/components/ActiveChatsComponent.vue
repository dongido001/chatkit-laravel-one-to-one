<template>
    <div class="active-chats row">
         <message-component 
            v-for="chat in activeChats" 
            :key="chat.id"
            :chat="chat"
            :current_user="current_user"
            :autheduser="autheduser">
         </message-component>
    </div>
</template>

<script>
import { ChatManager, TokenProvider } from '@pusher/chatkit-client';
export default {
    props: ['autheduser'],
    data () {
        return {
            activeChats: [],
            current_user: null
        }
    },
    created() {
        this.EventBus.$on('newActiveChat', user => {
            const chat = this.activeChats.find(chat => chat.email == user.email)

            if (!chat) {
                this.activeChats.push(user)
            } 
        })

        this.initializeClient()
    },
    methods: {
        async initializeClient () {
            // Initialize the Chatkit SDK
            const chatManager = new ChatManager({
                instanceLocator: process.env.MIX_CHATKIT_INSTANCE_LOCATOR,
                userId: this.autheduser.email.replace(/[@\.]/g, '_'),
                tokenProvider: new TokenProvider({ url: '/api/generate-token' })
            });

            chatManager
                .connect()
                .then( currentUser => {
                    this.current_user = currentUser 
                    this.EventBus.$emit('chatkitReady')
                })
                .catch( error => this.status = 'error');
        },
    }
}
</script>