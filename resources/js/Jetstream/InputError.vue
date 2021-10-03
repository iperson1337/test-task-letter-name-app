<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message && typeof message !== 'string' ? message[0] : message }}
        </p>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: ['message']
    })
</script>
