<template>
  <div>
    <form @submit.prevent="postLog">
      <textarea v-model="logContent"></textarea>
      <button type="submit">ログ作成</button>
    </form>
    <p>{{ logContent }}</p>
    <p>{{ msg }}</p>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data () {
    return {
      logContent: '',
      msg: '',
      errors: null
    }
  },
  methods: {
    async postLog () {

      const response = await axios.post('/api/logs', {
        text: this.logContent
      })

      if (response.status !== UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.msg = 'logが投稿されました'

      this.$router.push('/')
    }
  }
}
</script>