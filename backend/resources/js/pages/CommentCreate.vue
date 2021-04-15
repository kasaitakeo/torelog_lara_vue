<template>
  <v-row>
    <v-col cols="12">
      <v-card>
        <div class="errors" v-if="errors">
          <ul v-if="errors.text">
            <li v-for="msg in errors.text" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <form @submit.prevent="postComment">
          <v-textarea
            :counter="140"
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
  data () {
    return {
      commentContent: '',
      msg: '',
      errors: null
    }
  },
  methods: {
    // コメント送信
    async postComment () {

      const response = await axios.post('/api/comments', {
        log_id: this.$route.params.logId,
        text: this.commentContent
      })

      console.log(response)

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push('/')
    }
  },
}
</script>