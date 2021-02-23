<template>
  <div>
    <p>name: {{ log.user.name }}</p>
    <p>log: {{ log.text }}</p>
    <div v-for="comment in log.comments" :key="comment.id">
      <p>comment: {{ comment.text }}</p>
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
      log: {}
    }
  },
  methods: {
    async getLog () {
      console.log(this.logId)
      const response = await axios.get('/api/logs/' + this.logId)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.log = response.data
    }
  },
  mounted () {
    this.getLog()
  }

}
</script>

<style>

</style>