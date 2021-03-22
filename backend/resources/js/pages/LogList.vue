<template>
  <div>
    <Log
      v-for="log in logs" 
      :key="log.id"
      :log="log"
      @favoriteLog="favoriteLog"
      @unFavoriteLog="unFavoriteLog"
    />
  </div>
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
  methods: {
    async getLogs () {
      const response = await axios.get('/api/logs')

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.logs = response.data.data
    },
    async favoriteLog ({ id }) {
      if (!this.$store.getters['auth/check']) {
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
    async unFavoriteLog ({ id }) {
      if (!this.$store.getters['auth/check']) {
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