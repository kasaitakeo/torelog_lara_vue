<template>
  <div>
    <div>
      <tr v-for="log in logs" :key="log.id">
        <th scope="row">{{ log.id }}</th>
        <td>{{ log.text }}</td>
        <td>{{ logUser(log.user_id) }}</td>
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
      users: []
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

      this.logs = response.data
    },
    async getUsers () {
      const response = await axios.get('/api/users')

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.users = response.data
    },
    logUser (user_id) {
      for (let user in this.users) {
        if (user.id === user_id) {
          console.log(user.name)
          return user.name
        }
      }
    },
    async deleteLog (id) {
      const response = await axios.delete('/api/logs/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    }
  },
  mounted () {
    this.getLogs()
    this.getUsers()
  }
}
</script>