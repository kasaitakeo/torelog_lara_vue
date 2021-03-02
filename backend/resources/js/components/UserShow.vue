<template>
  <div>
    <p>{{ user.id }}</p>
    <p>{{ user.name }}</p>
    <div v-for="log in user.logs" :key="log.id">
      <RouterLink :to="{name: 'log.show', params: {logId: log.id}}">
        <p>{{ log.text }}</p>
      </RouterLink>
    </div>
    <div v-for="event in user.events" :key="event.id">
      <RouterLink :to="{name: 'event.show', params: {eventId: event.id}}">
        <p>{{ event.event_name }}</p>
      </RouterLink>
    </div>
  </div>

</template>

<script>
import { OK } from '../util'

export default {
  props: {
    userId: Number
  },
  data () {
    return {
      user: {},
    }
  },
  methods: {
    async getUser () {
      const response = await axios.get('/api/users/' + this.userId)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.user = response.data
    },
    // async getEvents () {
    //   const response = await axios.get('/api/events', {
    //     user_id: this.user.id
    //   })

    //   console.log(response)

    //   if (response.status !== OK) {
    //     this.$store.commit('error/setCode', response.status)
    //     return false
    //   }

    //   this.events = response.data
    // }
  },
  mounted () {
    this.getUser()

    // this.getEvents()
  }
}
</script>

<style>

</style>