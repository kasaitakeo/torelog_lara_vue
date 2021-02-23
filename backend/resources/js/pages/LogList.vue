<template>
  <div>
    <div>
      <tr v-for="log in logs" :key="log.id">
        <th scope="row">{{ log.id }}</th>
        <td>{{ log.text }}</td>
        <td>{{ userId }}</td>
        <td>
          <RouterLink v-bind:to="{name: 'user.show', params: {userId: log.user.id}}">
            <button>{{ log.user.name }}</button> 
          </RouterLink>
        </td>
        <td>{{ log.user.name }}</td>
        <td>{{ log.favorites.length }}favorites</td>
        <td>{{ log.comments.length }}comments</td>
        <td>
          <RouterLink v-bind:to="{name: 'log.show', params: {logId: log.id}}">
            <button>show</button> 
          </RouterLink>
        </td>
        <td>
          <RouterLink v-bind:to="{name: 'log.edit', params: {logId: log.id}}">
            <button>edit</button> 
          </RouterLink>
        </td>
        <td>
          <button class="btn btn-danger" @click="deleteLog(log.id)">delete</button>
        </td>
        <td>
        </td>
        <td>
          <RouterLink v-bind:to="{name: 'comment.create', params: {logId: log.id}}">
            <button>addcomment</button> 
          </RouterLink>
        </td>
        <td v-if="favoriteStatus(log.favorites)">
          <button class="btn btn-primary" @click="favoriteLog(log.id)">favorite</button>
        </td>
        <td v-else>
          <button class="btn btn-secondary" @click="unFavoriteLog(log.id)">unfavorite</button>
        </td>
      </tr>
    </div>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  components: {
    },
  data () {
    return {
      logs: [],
    }
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    },
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
    async deleteLog (id) {
      const response = await axios.delete('/api/logs/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
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

      this.getLogs()
    },
    async unFavoriteLog (id) {
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
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
  mounted () {
    this.getLogs()
  }
}
</script>