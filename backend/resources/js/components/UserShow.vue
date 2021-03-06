<template>
  <div>
    <p>{{ user.id }}</p>
    <p>{{ user.name }}</p>
    <RouterLink :to="{name: 'event', params: {userId: user.id}}">
      <button>種目リスト</button>
    </RouterLink>
    <p v-if="user.id === loginUserId">
      <RouterLink :to="{name: 'user.edit', params: {userId: user.id}}">
        <button>edit</button>
      </RouterLink>
    </p>
    <div v-for="log in user.logs" :key="log.id">
      <p>{{ log.id }}</p>
      <RouterLink :to="{name: 'log.show', params: {logId: log.id}}">
        <EventLog
            v-for="event_log in log.event_logs" 
            :key="event_log.id"
            :item="event_log"
            :ableDelete="false"
          />
        <p>{{ log.text }}</p>
      </RouterLink>
    </div>
    <!-- <div v-for="event in user.events" :key="event.id">
      <RouterLink :to="{name: 'event.show', params: {eventId: event.id}}">
        <p>{{ event.event_name }}</p>
      </RouterLink>
    </div> -->
  </div>

</template>

<script>
import { OK } from '../util'
import EventLog from '../components/EventLog.vue'

export default {
  components: {
    EventLog,
  },
  props: {
    userId: Number
  },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
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