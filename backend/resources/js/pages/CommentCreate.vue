<template>
  <v-row>
    <v-col cols="12">
      <v-card>
        <form @submit.prevent="postComment">
          <v-textarea
            name="input-7-1"
            filled
            label="コメント"
            auto-grow
            v-model="commentContent"
          ></v-textarea>
          <v-btn type="submit">コメント追加</v-btn>
        </form>
      </v-card>
    </v-col>
  </v-row>
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

      // if (response.status !== UNPROCESSABLE_ENTITY) {
      //   this.errors = response.data.errors
      //   return false
      // }

      // if (response.status !== CREATED) {
      //   this.$store.commit('error/setCode', response.status)
      //   return false
      // }

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