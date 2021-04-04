<template>
  <v-row>
    <v-col cols="12">
      <v-card>
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
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { OK } from '../util'
import EventEdit from '../components/EventEdit.vue'

export default {
  components: {
    EventEdit
  },
  data() {
    return {
      event: {},
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
      const response = await axios.put(`/api/events/${this.event.id}`, {
        eventPart: e.eventPart,
        eventName: e.eventName,
        eventExplanation: e.eventExplanation
      })

      console.log(response.data)
      // if (response.status === UNPROCESSABLE_ENTITY) {
      //   this.errors = response.data.errors
      //   return false
      // }

      // if (response.status !== CREATED) {
      //   this.$store.commit('error/setCode', response.status)
      //   return false
      // }

      this.$store.commit('message/setContent', {
        content: '種目が更新されました！',
        timeout: 6000
      })

      this.$router.go(-1)
    }
  },
  created () {
    this.getEvent()

  },
}
</script>
