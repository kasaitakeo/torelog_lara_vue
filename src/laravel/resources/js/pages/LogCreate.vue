<template>
  <v-row>
    <v-col cols="12">
      <v-card>
        <div class="errors" v-if="errors">
          <ul v-if="errors.title">
            <li v-for="msg in errors.title" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <form @submit.prevent="postLog">
          <v-textarea
            v-model="logTitle"
            :counter="30"
            name="input-7-1"
            filled
            label="トレログにタイトルをつけよう"
            auto-grow
          ></v-textarea>
          <v-btn type="submit">トレログ編集へ</v-btn>
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
      logTitle: '',
      errors: null,
    }
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    },
  },  
  methods: {
    // ログの新規登録（新規ログのid取得）
    async postLog () {
      const response = await axios.post('/api/logs', {
        user_id: this.userId,
        title: this.logTitle
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


      // response.dataに新規ログのidのみが返ってくる
      const logId = response.data

      this.$router.push(`/logs/${logId}/edit`)
    },
  },  
}
</script>