<template>
  <div>
    <div>
      <tr v-for="log in logs" :key="log.id">
        <th scope="row">{{ log.id }}</th>
        <td>{{ log.text }}</td>
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
      logs: []
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
    }
  },
  mounted () {
    this.getLogs()

    console.log(this.logs)
  }
  // watch: {
  //   $route: {
  //     async handler () {
  //       await this.getLogs()
  //     },
  //     immediate: true
  //   }
  // }
}
</script>