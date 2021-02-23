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
        <td v-for="event in events" :key="event.id">
          <RouterLink :to="{name: 'event.show', params: {eventId: event.id}}">
            <button>{{ event.name }}</button>
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
      users: [],
      event: []
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
    async getEvents () {
      const response = await axios.get('/api/events')

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },

  },
  mounted () {
    this.getUsers()

    this.getEvents()
  }
}
</script>

<style>

</style>