<template>
  <div>
    <PostEventLog
      class="grid__item"
      v-for="event in events"
      :key="event.id"
      :event="event"
      @post="postEventLog"
    />
    <EventLog
      v-for="event_log in event_logs"
      :key="event_log.id"
      :item="event_log"
      @deleteEventLog="deleteEventLog"
    />
    <form @submit.prevent="postLog">
      <textarea v-model="logContent"></textarea>
      <button type="submit">ログ作成</button>
    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { OK } from '../util'
import PostEventLog from '../components/PostEventLog.vue'
import EventLog from '../components/EventLog.vue'

export default {
  components: {
    PostEventLog,
    EventLog,
  },
  data () {
    return {
      logId: '',
      events: {},
      event_logs: {},
      logContent: '',
      msg: '',
      errors: null
    }
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    },
  },  
  methods: {
    async postEventLog ({ id, weight, rep, set }) {

      const response = await axios.post('/api/event_logs', {
        log_id: this.logId,
        event_id: id,
        weight: weight,
        rep: rep,
        set: set
      })

      console.log(response)

      // if (response.status !== UNPROCESSABLE_ENTITY) {
      //   this.errors = response.data.errors
      //   return false
      // }

      // if (response.status !== CREATED) {
      //   this.$store.commit('error/setCode', response.status)
      //   return false
      // }

      this.getEventLogs()
      this.msg = 'eventlogが追加されました'
    },
    async deleteEventLog ({ id }) {
      
      const response = await axios.delete(`/api/event_logs/${id}`)
      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.getEventLogs()
    },
    async postLog () {

      const response = await axios.post('/api/logs')

      // console.log(response)
      console.log(response.data)

      this.logId = response.data
    },
    async updateLog () {

      const response = await axios.post('/api/logs', {
        text: this.logContent
      })

      if (response.status !== UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.msg = 'logが投稿されました'

      this.$router.push('/')
    },
    async getEvents () {
      const response = await axios.get('/api/events')

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    async getEventLogs () {
      const response = await axios.get(`/api/${this.logId}/event_logs`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.event_logs = response.data
    }
  },
  mounted () {
    this.postLog()
    this.getEvents()
    // this.getEventLogs()

  }
}
</script>