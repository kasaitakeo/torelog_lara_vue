<template>
  <div>
    <form @submit.prevent="postComment">
      <textarea v-model="commentContent"></textarea>
      <input type="hidden" :value="logId">
      <button type="submit">addcomment</button>
    </form>
    <p>{{ commentContent }}</p>
    <p>{{ msg }}</p>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  props: {
    logId: Number
  },
  data () {
    return {
      commentContent: '',
      msg: '',
      errors: null
    }
  },
  methods: {
    async postComment () {

      const response = await axios.post('/api/comments', {
        text: this.commentContent,
        log_id: this.logId
      })

      console.log(response)

      if (response.status !== UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.msg = 'commentが投稿されました'

      this.$router.push('/')
    }
  },
  mounted () {
    if (!this.$store.getters['auth/check']) {
      this.$router.push('/')
    }
  },
}
</script>