<template>
  <div>
    <p>{{ user.id }}</p>
    <p>{{ user.name }}</p>
  </div>
  
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    userId: Number
  },
  data () {
    return {
      user: {}
    }
  },
  methods: {
    async getUser () {
      const response = await axios.get('/api/users/' + this.userId)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.user = response.data
    }
  },
  mounted () {
    this.getUser()
  }
}
</script>

<style>

</style>