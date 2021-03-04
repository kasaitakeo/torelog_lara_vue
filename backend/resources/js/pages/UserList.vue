<template>
  <div>
    <div>
      <tr v-for="user in users" :key="user.id">
        <th>{{ user.id }}</th>
        <td>{{ user.name }}</td>
        <td>
          <RouterLink :to="{name: 'user.show', params: {userId: user.id}}">
            <button>show</button>
          </RouterLink>
        </td>
        <td>
          <button class="btn btn-danger" @click="follow(user.id)">follow</button>
        </td>
        <td>
          <button class="btn btn-danger" @click="unfollow(user.id)">unfollow</button>
        </td>
      </tr>
    </div>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  data () {
    return {
      users: [],
    }
  },
  methods: {
    async getUsers () {
      const response = await axios.get('/api/users')

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.users = response.data
    },
    async follow (id) {
      const response = await axios.post('/api/follow', {
        user_id: id
      })

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUsers()
    },
    async unfollow (id) {
      const response = await axios.post('/api/unfollow/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLogs()
    },
  },
  mounted () {
    this.getUsers()
  }
}
</script>

<style>

</style>