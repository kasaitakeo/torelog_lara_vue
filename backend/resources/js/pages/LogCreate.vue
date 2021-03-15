<template>
  <div>
    <div class="errors" v-if="errors">
      <ul v-if="errors.photo">
        <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <UserEvent
    :events="events"
    />
    <EventLog
      v-for="event_log in event_logs"
      :key="event_log.id"
      :item="event_log"
      :ableDelete="true"
      @deleteEventLog="deleteEventLog"
    />
    <v-card
      class="mx-auto"
      max-width="800"
    >
    <form @submit.prevent="updateLog">
      <v-textarea v-model="logContent"></v-textarea>
      <v-btn type="submit">ログ作成</v-btn>
    </form>
    </v-card>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { OK } from '../util'
import UserEvent from '../components/UserEvent.vue'
import EventLog from '../components/EventLog.vue'
import eventBus from '../eventBus.js'

export default {
  components: {
    UserEvent,
    EventLog,
  },
  data () {
    return {
      logId: '',
      events: {},
      event_logs: {},
      logContent: '',
      msg: '',
      errors: null,
    }
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    },
  },  
  methods: {
    activate (id) {
      this.active = id
    },
    async postEventLog ({ id, weight, rep, set }) {

      const response = await axios.post('/api/event_logs', {
        log_id: this.logId,
        event_id: id,
        weight: weight,
        rep: rep,
        set: set
      })
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
      this.$store.commit('message/setContent', {
        content: '実施種目が追加されました！',
        timeout: 3000
      })

      console.log(response)

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
    async deleteAllEventLog () {
      
      const response = await axios.delete(`/api/${this.logId}/event_logs`)
      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.getEventLogs()
    },
    async postLog () {
      const response = await axios.post('/api/logs')

      console.log(response)

      this.logId = response.data
    },
    async updateLog () {
      
      const response = await axios.put(`/api/logs/${this.logId}`, {
        text: this.logContent
      })

      console.log(response.data)

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // メッセージ登録
      this.$store.commit('message/setContent', {
        content: 'トレログが登録されました！',
        timeout: 6000
      })

      this.$router.push('/')
    },
    async deleteLog () {
      const response = await axios.delete(`/api/logs/${this.logId}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }
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
    },
    confirmSave (event) {
      event.returnValue = "編集中のものは保存されませんが、よろしいですか？"

      if (event.returnValue) {
        this.deleteLog()

        this.deleteAllEventLog()
      } 
    }
  },
  mounted () {
    if (this.$store.getters['auth/check']) {
      this.postLog()
  
      this.getEvents()

    } else {
      this.$router.push('/')
    }

      eventBus.$on('eventPost', async({ id, weight, rep, set }) => {
        const response = await axios.post('/api/event_logs', {
          log_id: this.logId,
          event_id: id,
          weight: weight,
          rep: rep,
          set: set
        })
        console.log(response)
  
        if (response.status !== CREATED) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
  
        this.$store.commit('message/setContent', {
          content: '実施種目が追加されました！',
          timeout: 3000
        })
   
        this.getEventLogs()
      })
  },
  destroyed () {
    window.removeEventListener("beforeunload", this.confirmSave);
  },
  // beforeRouteLeave (to, from, next) {
  //   // if (typeof this.logContent !== 'undefined') {
  //   if (typeof this.event_logs !== 'undefined') {
  //     this.deleteLog()

  //     next()
  //   // } else if (this.logContent === null) {
  //   //   this.deleteLog()

  //   //   this.deleteAllEventLog()

  //   //   next()
  //   } else {
  //     next()
  //   }
  // },
}
</script>