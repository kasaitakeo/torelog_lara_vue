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
                v-for="event_log in event_logs"
                :key="event_log.id"
                :item="event_log"
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
              <div class="d-flex justify-center mb-6">
                <v-btn type="submit">編集終了</v-btn>
              </div>
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
  data () {
    return {
      logId: '',
      events: [],
      event_logs: {},
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
    // 種目ログの削除
    async deleteEventLog ({ id }) {
      const response = await axios.delete(`/api/event_logs/${id}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 再度、削除後の種目ログ取得
      this.getEventLogs()
    },
    // 種目ログを全て削除
    async deleteAllEventLog () {
      const response = await axios.delete(`/api/${this.logId}/event_logs`)
      
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 再度、空の状態（全て削除した為）の種目ログ取得
      this.getEventLogs()

      this.setEventLogs = false
    },
    // ログの新規登録（新規ログのid取得）
    async postLog () {
      const response = await axios.post('/api/logs')
      
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // response.dataに新規ログのidのみが返ってくる
      this.logId = response.data
    },
    // ログのテキスト入力後のログ更新処理
    async updateLog () {
      const response = await axios.put(`/api/logs/${this.logId}`, {
        text: this.logContent
      })
      console.log(response)

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

      // this.logId = ''

      this.$router.push('/')
    },
    // ページ遷移時にログ削除する場合の処理
    async deleteLog () {
      const response = await axios.delete(`/api/logs/${this.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }
    },
    // ユーザーが登録している種目を全て取得
    async getEvents () {
      const response = await axios.get('/api/events')

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    // 現在作成しているログに登録している全ての種目ログの取得
    async getEventLogs () {
      const response = await axios.get(`/api/${this.logId}/event_logs`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 種目ログが登録されているかの真偽値がfalseの場合trueにする
      if (!this.setEventLogs) {
        this.setEventLogs = true
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
  watch: {
    $route: {
      async handler () {
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
    
          // 新規種目ログが登録された状態で現在編集中の種目ログの状態を更新
          this.getEventLogs()
        })
      },
      immediate: true
    }
  },
  mounted () {
    // ログインされている場合のみ編集できる設定
    if (this.$store.getters['auth/check']) {
      this.postLog()
  
      this.getEvents()

      // 孫コンポーネントのEvent.vueのeventPostが行われた際の新規種目ログ登録処理
      
    } else {
      // ログインされていない場合ホーム画面にページ遷移
      this.$router.push('/')
    }

  },
  created () {
    // ページロード前の処理
    window.addEventListener("beforeunload", this.confirmSave)
  },
  destroyed () {
    // ページ削除前の処理
    window.removeEventListener("beforeunload", this.confirmSave);
  },
  // ページ遷移時の挙動
  beforeRouteLeave (to, from, next) {
    // 種目追加画面へ遷移の場合
    if (to.name === 'event.create') {
      next()
    } else {
      // 種目ログ設定済かつログのテキスト未入力の場合
      if (this.setEventLogs && this.logContent === '') {
        let answer = window.confirm("コメント未入力のままトレログを保存してもよろしいでしょうか")
        if (answer) {
          this.$store.commit('message/setContent', {
            content: 'トレログが保存されました。',
            timeout: 6000
          })

          next()
        } else {
          this.deleteLog()

          this.deleteAllEventLog()

          next()
        }
      // 種目ログ未設定の場合
      } else if (!this.setEventLogs) {
        let answer = window.confirm("種目が未入力のままトレログの編集を終了してしまうとログは保存されません。このまま編集を終了してもよろしいでしょうか")
        if (answer) {
          this.deleteLog()

          this.$store.commit('message/setContent', {
            content: 'トレログ保存できませんでした。',
            timeout: 6000
          })

          next()
        } else {
          next(false)
        }
      } else {
        next()
      }
    }
  },  
}
</script>