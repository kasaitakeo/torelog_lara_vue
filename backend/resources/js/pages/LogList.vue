<template>
  <v-row>
    <v-col cols="12">
      <!-- ログのタイムライン -->
      <Log
        v-for="log in logs" 
        :key="log.id"
        :log="log"
        @favoriteLog="favoriteLog"
        @unFavoriteLog="unFavoriteLog"
      />
    </v-col>
  </v-row>
</template>

<script>
import { OK } from '../util'
import Log from '../components/Log.vue'

export default {
  components: {
    Log,
  },
  data () {
    return {
      logs: [],
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
  },
  methods: {
    // 全てのログタイムライン取得
    async getLogs () {
      const response = await axios.get('/api/logs')

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.logs = response.data.data
    },
    // ログのいいね登録
    async favoriteLog ({ id }) {
      if (!this.isLogin) {
        alert('いいね機能を使うにはログインしてください。')

        return false
      }

      const response = await axios.post('/api/favorites', {
        log_id: id
      })

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    },
    // ログのいいね解除
    async unFavoriteLog ({ id }) {
      if (!this.isLogin) {
        alert('いいね機能を使うにはログインしてください。')
        return false
      }
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    },
  },
  mounted () {
    this.getLogs()
  }
}
</script>