<template>
  <div>
    <div class="errors" v-if="errors">
      <ul v-if="errors.photo">
        <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <v-row>
      <v-col>
        <v-card
          class="mx-auto mb-3"
          max-width="800"
        >
          <v-row>
            <v-col>
              <UserEvent
              :events="events"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <EventLog
                v-for="eventLog in eventLogs"
                :key="eventLog.id"
                :item="eventLog"
                :ableDelete="true"
                @deleteEventLog="deleteEventLog"
              />
            </v-col>
          </v-row>
          <v-card
            class="mx-auto"
            max-width="800"
          >
          <form @submit.prevent="updateLog">
            <v-textarea v-model="logContent"></v-textarea>
            <v-btn type="submit">ログ作成</v-btn>
          </form>
          </v-card>

        </v-card>
      </v-col>
    </v-row>

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
  props: {
    logId: Number
  },
  data () {
    return {
      events: [],
      eventLogs: {},
      logContent: '',
      errors: null,
      setEventLogs: false
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
    async getLog () {
      const response = await axios.get(`/api/logs/${this.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response.data)

      this.logContent = response.data.text
      this.eventLogs = response.data.event_logs
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
        content: 'トレログが保存されました！',
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

      if (!this.setEventLogs) {
        this.setEventLogs = true
      }

      this.eventLogs = response.data
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
      this.getLog()
  
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
}
</script>