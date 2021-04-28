<template>
  <v-row>
    <Loading v-show="loading" :loading="loading"></Loading>
    <v-col v-show="!loading" cols="12" sm="8" md="8" class="mx-auto">
      <!-- <RouterLink v-if="!logs.length" class="button button--link" :to="{name: 'user'}"> -->
        <v-btn
          v-if="!logs.length"
          to="/users"
          block
          color="brown lighten-5"
          elevation="4"
        >ユーザーリストから他のユーザーをフォローしてください！</v-btn>
      <!-- </RouterLink> -->
      <!-- ログのタイムライン -->
      <Log
        v-for="log in logs" 
        :key="log.id"
        :log="log"
        @favoriteLog="favoriteLog"
        @unFavoriteLog="unFavoriteLog"
      />
      <Pagination 
        :current-page="currentPage" 
        :last-page="lastPage" 
        @loadingStart="loadingStart"
      />
    </v-col>
  </v-row>
</template>

<script>
import { OK, CREATED } from '../util'
import Log from '../components/Log'
import Pagination from '../components/Pagination'
import Loading from '../components/Loading.vue'

export default {
  components: {
    Loading,
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
      loading: true,
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

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    },
    loadingStart () {
      this.loading = true
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.getLogs()

        setTimeout(() => {
          this.loading = false
        }, 3000)
      },
      immediate: true
    }
  },
}
</script>