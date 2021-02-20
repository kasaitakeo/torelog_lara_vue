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
          <RouterLink :to="{name: 'user.edit', params: {userId: user.id}}">
            <button>edit</button>
          </RouterLink>
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
      users: []
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
    }
  },
  mounted () {
    this.getUsers()
  }
}
</script>

<style>

</style>