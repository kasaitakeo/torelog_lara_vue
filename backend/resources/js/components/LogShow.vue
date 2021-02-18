<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form>
                    <div class="form-group row border-bottom">
                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id"
                               v-model="log.id">
                    </div>
                    <div class="form-group row border-bottom">
                        <label for="content" class="col-sm-3 col-form-label">Content</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="content"
                               v-model="log.text">
                    </div>
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
    async getLog () {
      console.log(this.logId)
      // await axios.get(`api/logs/${this.logId}`)
      // .then((res) => {
      //   this.log = res.data
      // })
      // const response = await axios.get(`api/logs/${this.logId}`)
      const response = await axios.get('/api/logs/' + this.logId)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.log = response.data
    }
  },
  async mounted () {
    await this.getLog()
  }

}
</script>

<style>

</style>