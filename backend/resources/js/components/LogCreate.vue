<template>
  <div>
    <section>
      <ul>
        <li v-for="eventPart in eventParts" :key="eventPart.id">
          <a href="#" @click="activate(eventPart.id)"
          :class="{active: active === eventPart.id }">{{ eventPart.name }}</a>
        </li>
      </ul>
    </section>
    <div v-for="event in events" :key="event.id">
      <div v-if="event.part === '胸'">
        <div class="tab__content" v-show="active === 0">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '背中'">
        <div class="tab__content" v-show="active === 1">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '肩'">
        <div class="tab__content" v-show="active === 2">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '脚'">
        <div class="tab__content" v-show="active === 3">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '上腕二頭筋'">
        <div class="tab__content" v-show="active === 4">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '上腕三頭筋'">
        <div class="tab__content" v-show="active === 5">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === '腹筋'">
        <div class="tab__content" v-show="active === 6">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
      <div v-else-if="event.part === 'その他'">
        <div class="tab__content" v-show="active === 7">
          <PostEventLog
            class="grid__item"
            :event="event"
            @post="postEventLog"
          />
        </div>
      </div>
    </div>
    <EventLog
      v-for="event_log in event_logs"
      :key="event_log.id"
      :item="event_log"
      :ableDelete="true"
      @deleteEventLog="deleteEventLog"
    />
    <form @submit.prevent="updateLog">
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
      active: 0,
      eventParts: [
        {id: 0, name:'胸'},
        {id: 1, name:'背中'},
        {id: 2, name:'肩'},
        {id: 3, name:'脚'},
        {id: 4, name:'上腕二頭筋'},
        {id: 5, name:'上腕三頭筋'},
        {id: 6, name:'腹筋'},
        {id: 7, name:'その他'},
      ],
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

      // console.log(response)
      console.log(response.data)

      this.logId = response.data
    },
    async updateLog () {
      
      const response = await axios.put(`/api/logs/${this.logId}`, {
        text: this.logContent
      })

      console.log(response.data)

      this.msg = 'logが投稿されました'

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
    this.postLog()

    this.getEvents()
  },
  // created () {
  //   window.addEventListener("beforeunload", this.confirmSave)
  // },
  destroyed () {
    window.removeEventListener("beforeunload", this.confirmSave);
  },
  beforeRouteLeave (to, from, next) {
    if (typeof this.event_logs !== 'undefined') {
      this.deleteLog()

      next()
    }
    // if (isset(this.event_logs) && !isset(this.logContent)) {
    //   const answer = window.confirm("編集中のトレログの一言が未入力のまま保存されますがよろしいですか？")
    //   if (answer) {
    //     next()
    //   } else {
    //     next(false)
    //   } 
    // } else {
    //   this.deleteLog()

    //   next()
    // }
  },
}
</script>