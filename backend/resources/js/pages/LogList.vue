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
      <Pagination :current-page="currentPage" :last-page="lastPage" />
    </v-col>
  </v-row>
</template>

<script>
import { OK, CREATED } from '../util'
import Log from '../components/Log'
import Pagination from '../components/Pagination'

export default {
  components: {
    Log,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data () {
    return {
      logs: [],
      currentPage: 0,
      lastPage: 0
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
      const response = await axios.get(`/api/logs/?page=${this.page}`)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.logs = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
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

      if (response.status !== CREATED) {
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
  watch: {
    $route: {
      async handler () {
        await this.getLogs()
      },
      immediate: true
    }
  }
  // mounted () {
  //   this.getLogs()
  // }
}
</script>