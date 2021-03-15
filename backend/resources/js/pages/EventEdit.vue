<template>
  <div>
    <form @submit.prevent="eventCreate">
      <span>SelectedPart: {{ event.part }}</span>
      <br>
      <span>event name:{{ event.event_name }}</span>
      <br>
      <input v-model="event.name" placeholder="add event name">
      <br>
      <span>event explanation:{{ event.event_explanation }}</span>
      <br>
      <textarea v-model="event_explanation" placeholder="add event explanation"></textarea>
      <button type="submit">event create</button>
    </form>
    <p>{{ msg }}</p>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    eventId: Number
  },
  data() {
    return {
      event: {},
      msg: '',
      errora: null
    }
  },
  methods: {
    async getEvent () {
      const response = await axios.get('/api/events/' + this.eventId)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.event = response.data
    },
    async submit () {
      const response = await axios.put('/api/events/' + this.event.id, this.event)

       if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push({name: 'event'})
    }
  },
  mounted () {
    if (!this.$store.getters['auth/check']) {
      this.$router.push('/')
    }
    this.getEvent()
  }
}
</script>

<style>

</style>