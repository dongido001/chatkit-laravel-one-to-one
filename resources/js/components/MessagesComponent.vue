<template> 
    <div class="card user-box">
        <div class="card-header" @click="collapsed = !collapsed"> 
            {{ chat.name }}
        </div>
        <div class="card-body" v-show="!collapsed">
            <div class="user-messages">
                <!-- {{messages}} -->
                <div
                    class="chat-message" 
                    v-for="message in messages" 
                    v-bind:key="message.id"
                    v-bind:class="[(message.senderId !== current_user.id) ? 'from-other' : 'from-current']"
                >
                    <template v-for="part in message.parts">
                        {{ part.payload.content }}
                    </template>
                </div>
            </div>
            <div class="input-container">
                <input
                    class="chat-input" 
                    type="text" 
                    placeholder="enter message..." 
                    v-model="message"
                    v-on:keyup.enter="addMessage(message)"
                    @enter="addMessage(message)"
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['current_user', 'chat', 'autheduser'],
    data() {
        return {
            message: "",
            messages: [],
            collapsed: false,
            roomId: null
        }
    },
    computed: {
        username() {
            return this.autheduser.email.replace(/[@\.]/g, '_')
        }
    },
    async created() {
        const to_username = this.chat.email.replace(/[@\.]/g, '_')
        
        const {data} = await axios.post('/api/get-or-create-room', {
            from_username: this.username,
            to_username: to_username,
            from: this.autheduser.id,
            to: this.chat.id,
        })

        console.log(data)

        this.current_user
            .subscribeToRoomMultipart({
                roomId: data.room,
                hooks: {
                    onMessage: message => {
                        this.messages.push(message)
                    }
                },
                messageLimit: 50
            })

            this.roomId = data.room;
    },
    methods: {
        addMessage() {
            // Send message to the room
            this.current_user.sendSimpleMessage({
                roomId: this.roomId,
                text: this.message,
            })

            this.message = ""
        }
    }
}
</script>