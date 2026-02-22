<template>
    
    <div>
        <button @click="clockIn">Clock In</button>
        <button @click="clockOut">Clock Out</button>

        <p v-if="message">{{ message }}</p>
    </div>

      <div style="padding:20px; background:#eef">
        <h2>Vue is Working ✅</h2>
    </div>

</template>

<script>
export default {
    data() {
        return {
            message: ''
        }
    },
    methods: {
        clockIn() {
            axios.post('/clock-in')
                .then(res => this.message = res.data.message)
                .catch(err => this.message = err.response.data.message)
        },
        clockOut() {
            axios.post('/clock-out')
                .then(res => this.message = res.data.message)
                .catch(err => this.message = err.response.data.message)
        }
    }
}
</script>
