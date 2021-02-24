<template>
  <div>
    <p>ID: {{ event.id }}</p>
    <p>part: {{ event.part }}</p>
    <p>event name: {{ event.event_name }}</p>
    <p>event explanation: {{ event.event_explanation }}</p>
    <p>creater: {{ event.user.name }}</p>
    <RouterLink v-bind:to="{name: 'event.edit', params: {eventId: event.id}}">
      <button>event edit</button> 
    </RouterLink>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    eventId: Number
  },
  data () {
    return {
      event: {}
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
    }
  },
  mounted () {
    this.getEvent()
  }
}
</script>

<style>

</style>