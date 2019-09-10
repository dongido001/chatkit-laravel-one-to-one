<template>
    <div class="chatkit row">
        <active-chats-component :autheduser="autheduser"></active-chats-component>
        <users-component :autheduser="autheduser"></users-component>
    </div>
</template>

<script>
export default {
    props: ['autheduser'],
}
</script>