<template> 
    <div class="card users-box">
        <div class="card-header" @click="collapsed = !collapsed"> 
            Users
        </div>
        <div class="card-body users" v-show="!collapsed">
            <div class="user" 
                v-for="user in users" 
                :key="user.id" 
                @click="addToActiveChat(user)"
                v-if="chatReady">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQSXmfj4kUOZR1oT7ood5_AqnC_TgkuyVojx73oE2eYdp4Mvl29" width="30" height="30">
                {{ user.name }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['messages', 'autheduser'],
    data() {
        return {
            collapsed: false,
            users: [],
            chatReady: false
        }
    },
    async created() {
        const {data} = await axios.get('/api/get-users')
        this.users = data.users.filter(user => user.id != this.autheduser.id)
        // Check when chatkit is ready
        this.EventBus.$on('chatkitReady', () => {
            this.chatReady = true
        })
    },
    methods: {
        addToActiveChat(user) {
            this.EventBus.$emit('newActiveChat', user)
        }
    },
}
</script>