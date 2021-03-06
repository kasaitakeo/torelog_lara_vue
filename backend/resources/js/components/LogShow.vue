<template>
  <div>
    <p>name: {{ user.name }}</p>
    <!-- {{ log.user.name }}だとnameが未定義のプロパティになる？？？ -->
    <!-- <p>name: {{ log.user.name }}</p> -->
    <div v-for="event_log in log.event_logs" :key="event_log.id">
      <p>部位: {{ event_log.event.part }}</p>
      <p>種目名: {{ event_log.event.event_name }}</p>
      <p>{{ event_log.weight }}KG</p>
      <p>log: {{ event_log.rep }}REP</p>
      <p>log: {{ event_log.set }}SET</p>
    </div>
    <p>log: {{ log.text }}</p>
    <div v-for="comment in log.comments" :key="comment.id">
      <p>comment: {{ comment.text }}</p>
    </div>
    <div v-if="user.id === loginUserId">
      <RouterLink v-bind:to="{name: 'log.edit', params: {logId: log.id}}">
        <button>edit</button> 
      </RouterLink>
      <button class="btn btn-danger" @click="deleteLog(log.id)">delete</button>
    </div>
  </div>
</template>
<script>
import { OK } from '../util'

export default {
  props: {
    logId: Number
  },
  data () {
    return {
      log: {},
      user: ''
    }
  },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },
  methods: {
    async getLog () {
      const response = await axios.get('/api/logs/' + this.logId)

      console.log(response)
      console.log(response.data.user.name)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.log = response.data
      this.user = response.data.user
    },
    async deleteLog (id) {
      const response = await axios.delete('/api/logs/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    },
  },
  mounted () {
    this.getLog()
  }

}
</script>

<style>

</style>