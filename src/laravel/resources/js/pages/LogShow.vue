<template>
  <div>
    <Log
      :log="log"
      @favoriteLog="favoriteLog"
      @unFavoriteLog="unFavoriteLog"
    />
  </div>
</template>
<script>
import { OK, CREATED } from '../util'
import Log from '../components/Log.vue'

export default {
  components: {
    Log,
  },
  data () {
    return {
      log: {},
    }
  },
  computed: {
    // いいねする時のログインチェックに使用する
    isLogin () {
      return this.$store.getters['auth/check']
    },
  },
  methods: {
    // propsで受け取ったidのログを取得する
    async getLog () {
      const response = await axios.get(`/api/logs/${this.$route.params.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response)

      this.log = response.data
    },
    // ログのいいねを登録する（いいね登録後ページ更新する時にこのコンポーネントのthis.getLogメソッドを使用する為、子からemitで投げてもらう）
    async favoriteLog (e) {
      if (!this.isLogin) {
        alert('いいね機能を使うにはログインしてください。')

        return false
      }

      const response = await axios.post('/api/favorites', {
        log_id: e.id
      })

      console.log(response)

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
    // ログのいいねを解除する（いいね解除後ページ更新する時にこのコンポーネントのthis.getLogメソッドを使用する為、子からemitで投げてもらう）
    async unFavoriteLog (e) {
      if (!this.isLogin) {
        alert('いいね機能を使うにはログインしてください。')

        return false
      }

      const response = await axios.post('/api/favorites/' + e.id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
  },
  watch: {
    $route: {
      async handler () {
        await this.getLog()
      },
      immediate: true
    }
  }
}
</script>

<style>

</style>