<template>
  <div>
    <div>
      <Log
        :log="log"
        @favoriteLog="favoriteLog"
        @unFavoriteLog="unFavoriteLog"
      />
      <div v-if="log.user_id === loginUserId">
        <RouterLink v-bind:to="{name: 'log.edit', params: {logId: log.id}}">
          <button>edit</button> 
        </RouterLink>
        <button class="btn btn-danger" @click="deleteLog(log.id)">delete</button>
      </div>
    </div>
  </div>
</template>
<script>
import { OK } from '../util'
import Log from '../components/Log.vue'

export default {
  components: {
    Log,
  },
  props: {
    logId: Number
  },
  data () {
    return {
      log: {},
      // user: {},
      // favorites: {},
      // comments: {},
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },
  methods: {
    async getLog () {
      const response = await axios.get(`/api/logs/${this.logId}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // console.log(response)
      console.log(response.data)
      this.log = response.data
      // this.user = response.data.user
      // this.favorites = response.data.favorites
      // this.comments = response.data.comments
    },
    async deleteLog (id) {
      const response = await axios.delete('/api/logs/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
    async favoriteLog (id) {
      const response = await axios.post('/api/favorites', {
        log_id: id
      })

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
    async unFavoriteLog (id) {
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
    favoriteStatus (favorites) {

      console.log(this.userId)

      for (let favorite in favorites) {
        if (favorite.user_id !== this.userId) {
          return false
        } 
      }
      return true
    }
  },
  created () {
    this.getLog()
  },
  // mounted () {
  //   this.getLog()
  // },
  // watch: {
  //   $route: {
  //     async handler () {
  //       await this.getLog()
  //     },
  //     immediate: true
  //   }
  // }
}
</script>

<style>

</style>