<template>
  <v-row>
    <v-col cols="12">
      <v-card>
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
  methods: {
    async eventCreate (e) {
      const response = await axios.post('/api/events', {
        eventPart: e.eventPart,
        eventName: e.eventName,
        eventExplanation: e.eventExplanation
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

      this.$store.commit('message/setContent', {
        content: '種目が追加されました！',
        timeout: 6000
      })

      this.$router.push('/')
    }
  },
  mounted () {

  },
}
</script>

<style>

</style>