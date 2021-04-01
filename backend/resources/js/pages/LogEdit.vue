<template>
  <div>
    <div class="errors" v-if="errors">
      <ul>
        <li v-for="msg in errors.text" :key="msg">{{ msg }}</li>
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
              <!-- ログインユーザーの種目コンポーネント -->
              <UserEvent
              :events="events"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <!-- 登録した種目ログ -->
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
            <v-textarea v-model="logContent"></v-textarea>
            <div class="d-flex justify-center mb-6">
              <v-btn @submit.prevent="deleteLog">ログ削除</v-btn>
              <v-btn @submit.prevent="updateLog">編集終了</v-btn>
            </div>
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
    // 編集するログの情報を取得
    async getLog () {
      const response = await axios.get(`/api/logs/${this.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response.data)

      this.logContent = response.data.text
      this.eventLogs = response.data.event_logs
      this.setEventLogs = true
    },
    // 種目ログの削除
    async deleteEventLog ({ id }) {
      
      const response = await axios.delete(`/api/event_logs/${id}`)
      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.getEventLogs()
    },
    // 種目ログを全て削除
    async deleteAllEventLog () {
      
      const response = await axios.delete(`/api/${this.logId}/event_logs`)
      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 再度、空の状態（全て削除した為）の種目ログ取得
      this.getEventLogs()

      this.setEventLogs = false
    },
    // ログのテキスト入力後のログ更新処理
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
    // ログ削除
    async deleteLog () {
      const response = await axios.delete(`/api/logs/${this.logId}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }
    },
    // ユーザーが登録している種目を全て取得
    async getEvents () {
      const response = await axios.get('/api/events')

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    // 現在作成しているログに登録している全ての種目ログの取得
    async getEventLogs () {
      const response = await axios.get(`/api/${this.logId}/event_logs`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 種目ログが登録されているかの真偽値がfalseの場合trueにする
      if (!this.setEventLogs) {
        this.setEventLogs = true
      }

      this.eventLogs = response.data
    },
  },
  mounted () {
    if (this.$store.getters['auth/check']) {
      this.getLog()
  
      this.getEvents()

      // 孫コンポーネントのEvent.vueのeventPostが行われた際の新規種目ログ登録処理
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
    } else {
      this.$router.push('/')
    }

  },
}
</script>