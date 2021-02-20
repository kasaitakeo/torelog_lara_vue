<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id" v-model="log.id">
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-sm-3 col-form-label">Content</label>
                        <input type="text" class="col-sm-9 form-control" id="content" v-model="log.text">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    logId: Number
  },
  data () {
    return {
      log: {}
    }
  },
  methods: {
    async getLog() {
      // console.log(this.logId)
      const response = await axios.get('/api/logs/' + this.logId)

      // console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.log = response.data
    },
    async submit() {
      const response = await axios.put('/api/logs/' + this.logId, this.log)
      
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push({name: '/'})
    }
  },
  async mounted () {
    await this.getLog()
  }
}
</script>