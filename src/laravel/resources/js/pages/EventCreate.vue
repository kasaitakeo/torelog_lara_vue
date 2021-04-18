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
          @event-edit="eventCreate" 
        />
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import EventEdit from '../components/EventEdit'

export default {
  components: {
    EventEdit
  },
  data () {
    return {
      errors: null
    }
  },
  methods: {
    // 子コンポーネントのevent-editイベントから渡される
    async eventCreate (e) {
      const response = await axios.post('/api/events', {
        eventPart: e.eventPart,
        eventName: e.eventName,
        eventExplanation: e.eventExplanation
      })

      console.log(response)

      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log(response)
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$store.commit('message/setContent', {
        content: '種目が追加されました！',
        timeout: 6000
      })

      // 一つ前のページへ戻る（LogCreateからページ遷移している場合はそのページに戻すため）
    },
  },
  watch: {
    $route: {
      async handler () {
        if (!this.$store.getters['auth/check']) {
          this.$router.push('/')
        }
      },
      immediate: true
    }
  }
}
</script>

<style>

</style>