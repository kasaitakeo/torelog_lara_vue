<template>
  <div>
    <p>{{ user.id }}</p>
    <p>{{ user.name }}</p>
    <!-- <RouterLink :to="{ name: 'user.event', params: { userId: user.id }}">
      <button>種目リスト</button>
    </RouterLink> -->
    <!-- <UserEvent
    :userId="user.id"
    /> -->
    <UserEvent
      :events="events"
      />
    <p v-if="user.id === loginUserId">
      <RouterLink :to="{ name: 'user.edit', params: { userId: user.id }}">
        <button>edit</button>
      </RouterLink>
    </p>
    <div >
      <Log
        v-for="log in user.logs"
        :key="log.id"
        :log="log"
        @favoriteLog="favoriteLog"
        @unFavoriteLog="unFavoriteLog"
      />
    </div>
  </div>

</template>

<script>
import { OK } from '../util'
import Log from '../components/Log.vue'
import UserEvent from '../components/UserEvent.vue'

export default {
  components: {
    Log,
    UserEvent,
  },
  // props: {
  //   userId: Number
  // },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },  
  data () {
    return {
      user: {},
      events: {},
    }
  },
  methods: {
    async getUser () {
      const response = await axios.get(`/api/users/${this.$route.params.userId}`)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response.data)

      this.user = response.data

    },
    async getUserEvents () {
      const response = await axios.get(`/api/${this.$route.params.userId}/events`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    async favoriteLog ({ id }) {
      const response = await axios.post('/api/favorites', {
        log_id: id
      })

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
    async unFavoriteLog ({ id }) {
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
  },
  mounted () {
    this.getUser()
    this.getUserEvents()
  }
}
</script>

<style>

</style>