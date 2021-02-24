<template>
  <div>
    <p>{{ user.id }}</p>
    <p>{{ user.name }}</p>
    <td v-for="event in user.events" :key="event.id">
      <RouterLink :to="{name: 'event.show', params: {eventId: event.id}}">
        <button>{{ event.event_name }}</button>
      </RouterLink>
    </td>
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