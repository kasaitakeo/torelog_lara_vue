<template>
  <v-row>
    <v-col cols="12">
      <v-card>
        <div class="errors" v-if="errors">
          <ul v-if="errors.eventPart">
            <li v-for="msg in errors.eventPart" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="errors.eventName">
            <li v-for="msg in errors.eventName" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="errors.eventExplanation">
            <li v-for="msg in errors.eventExplanation" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <!-- イベント編集コンポーネント -->
        <EventEdit
          @event-edit="eventUpdate" 
          :event="event"
        />
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import EventEdit from '../components/EventEdit.vue'

export default {
  components: {
    EventEdit
  },
  data() {
    return {
      event: {},
      errors: null
    }
  },
  methods: {
    // 編集する種目の取得
    async getEvent () {
      const response = await axios.get(`/api/events/${this.$route.params.eventId}`)
  
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
  
      console.log(response.data)
      this.event = response.data
    },
    // 子コンポーネントのevent-editイベントから渡される
    async eventUpdate (e) {
      const response = await axios.put(`/api/events/${this.$route.params.eventId}`, {
        eventPart: e.eventPart,
        eventName: e.eventName,
        eventExplanation: e.eventExplanation
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

      this.$store.commit('message/setContent', {
        content: '種目が更新されました！',
        timeout: 6000
      })

      this.$router.go(-1)
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.getEvent()
      },
      immediate: true
    }
  }
}
</script>
