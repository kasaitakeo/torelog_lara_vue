<template>
  <div>
    <form @submit.prevent="eventCreate">
      <span>SelectedPart: {{ eventPart }}</span>
      <select v-model="eventPart">
        <option disabled value="">Please select one eventpart</option>
        <option value="胸">chest</option>
        <option value="背中">back</option>
        <option value="肩">sholder</option>
        <option value="脚">leg</option>
        <option value="腕">arm</option>
        <option value="腹筋">abdominal</option>
      </select>
      <br>
      <span>event name:{{ eventName }}</span>
      <br>
      <input v-model="eventName" placeholder="add event name">
      <br>
      <span>event explanation:{{ eventExplanation }}</span>
      <br>
      <textarea v-model="eventExplanation" placeholder="add event explanation"></textarea>
      <button type="submit">event create</button>
    </form>
    <p>{{ msg }}</p>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data() {
    return {
      eventPart: '',
      eventName: '',
      eventExplanation: '',
      msg: '',
      errora: null
    }
  },
  methods: {
    async eventCreate () {
      console.log(this.eventPart)
      const response = await axios.post('/api/events', {
        eventPart: this.eventPart,
        eventName: this.eventName,
        eventExplanation: this.eventExplanation
      })

      console.log(response)

      if (response.status !== UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.msg = 'event created'

      this.$router.push('/')
    }
  }

}
</script>

<style>

</style>