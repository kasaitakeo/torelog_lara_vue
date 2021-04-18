<template>
  <v-row>
    <div class="errors" v-if="errors">
      <ul v-if="errors.text">
        <li v-for="msg in errors.text" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.weight">
        <li v-for="msg in errors.weight" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.rep">
        <li v-for="msg in errors.rep" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.set">
        <li v-for="msg in errors.set" :key="msg">{{ msg }}</li>
      </ul>
    </div>
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
              @eventPost="eventPost"
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
              @deleteEventLog="deleteEventLog"
            />
          </v-col>
        </v-row>
        <v-card
          class="mx-auto"
          max-width="800"
        >
          <form @submit.prevent="updateLog">
            <v-textarea
              v-model="logTitle"
              :counter="30"
              name="input-7-1"
              filled
              label="トレログにタイトルをつけよう"
              auto-grow
            ></v-textarea>
            <v-textarea 
              v-model="logContent"
              :counter="140"
              name="input-7-1"
              filled
              label="今回のトレログに一言"
              auto-grow
            ></v-textarea>
            <div class="d-flex justify-center mb-6">
              <v-btn @submit.prevent="deleteLog">このトレログを削除する</v-btn>
              <v-btn type="submit">編集終了</v-btn>
            </div>
          </form>  
        </v-card>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import UserEvent from '../components/UserEvent.vue'
import EventLog from '../components/EventLog.vue'

export default {
  components: {
    UserEvent,
    EventLog,
  },
  data () {
    return {
      events: [],
      eventLogs: [],
      logTitle: '',
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
      const response = await axios.get(`/api/logs/${this.$route.params.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response.data)

      this.logTitle = response.data.title
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

      this.$store.commit('message/setContent', {
        content: '実施種目を削除しました！',
        timeout: 3000
      })

      this.getEventLogs()
    },
    // 種目ログを全て削除
    async deleteAllEventLog () {
      
      const response = await axios.delete(`/api/${this.$route.params.logId}/event_logs`)
      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$store.commit('message/setContent', {
        content: '実施種目を全て削除しました！',
        timeout: 3000
      })

      // 再度、空の状態（全て削除した為）の種目ログ取得
      this.getEventLogs()

      this.setEventLogs = false
    },
    // ログのテキスト入力後のログ更新処理
    async updateLog () {
      if (!this.eventLogs.length) {
        alert('トレーニングが登録されていません')

        return
      }
      
      const response = await axios.put(`/api/logs/${this.$route.params.logId}`, {
        user_id: this.userId,
        title: this.logTitle,
        text: this.logContent
      })

      console.log(response.data)

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // メッセージ登録
      this.$store.commit('message/setContent', {
        content: 'トレログが保存されました！',
        timeout: 3000
      })

      this.$router.push('/')
    },
    // ログ削除
    async deleteLog () {
      const response = await axios.delete(`/api/logs/${this.$route.params.logId}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }
      this.$store.commit('message/setContent', {
        content: 'トレログを削除しました！',
        timeout: 3000
      })
      
      this.$router.push('/')
    },
    // 指定したIDのユーザーの種目取得
    async getUserEvents () {
      const response = await axios.get(`/api/users/${this.userId}/events`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    // 現在作成しているログに登録している全ての種目ログの取得
    async getEventLogs () {
      const response = await axios.get(`/api/${this.$route.params.logId}/event_logs`)

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
    // 孫から子へ子から親へemitで投げる（eventBusだと予期せぬ挙動が出現した）
    async eventPost (e) {
      const response = await axios.post('/api/event_logs', {
        log_id: this.$route.params.logId,
        event_id: e.id,
        weight: e.weight,
        rep: e.rep,
        set: e.set
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
    }
  },
  // ページ遷移時の挙動
  beforeRouteLeave (to, from, next) {
    // 種目追加画面へ遷移の場合
    if (to.name === 'event.create') {
      next()
    } else {
      // 種目ログ未設定の場合
      if (!this.eventLogs.length) {
        let answer = window.confirm("種目が未入力のまま編集を終了してもよろしいでしょうか")
        if (answer) {
          this.$store.commit('message/setContent', {
            content: 'トレログ保存しました。',
            timeout: 3000
          })

          next()
        } else {
          next(false)
        }
      // 種目ログ設定済かつログのテキスト未入力の場合
      } else if (this.logContent === '') {
        let answer = window.confirm("トレログの一言が未入力のままトレログを保存してもよろしいでしょうか（種目ログは保存されたままです）")

        if (answer) {
          this.$store.commit('message/setContent', {
            content: 'トレログ保存されました。',
            timeout: 3000
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
  watch: {
    $route: {
      async handler () {
        await this.getLog()
    
        await this.getUserEvents()
      },
      immediate: true
    }
  }
}
</script>