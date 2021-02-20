<template>
  <div>
    <form @submit.prevent="submit">
      <div class="form-group row">
        <label for="id" class="col-sm-3 col-form-label">ID</label>
        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id" v-model="user.id">
      </div>
      <div class="form-group row">
        <label for="content" class="col-sm-3 col-form-label">Content</label>
        <input type="text" class="col-sm-9 form-control" id="content" v-model="user.name">
      </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
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
    },
    async submit () {
      const response = await axios.put('/api/users/' + this.user.id, this.user)

       if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push({name: 'user'})
    }
  },
  mounted () {
    this.getUser()
  }
}
</script>

<style>

</style>