<template>
  <div>
    <form @submit.prevent="eventCreate">
      <span>SelectedPart: {{ selected }}</span>
      <select v-model="part">
        <option disabled value="">Please select one part</option>
        <option>chest</option>
        <option>back</option>
        <option>sholder</option>
        <option>leg</option>
        <option>arm</option>
        <option>abdominal</option>
      </select>
      <span>event name:{{ eventName }}</span>
      <br>
      <input v-model="eventName" placeholder="edit me">
      <span>event explanation:{{ eventExplanation }}</span>
      <br>
      <textarea v-model="eventExplanation" placeholder="add multiple lines"></textarea>
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
      part: '',
      eventName: '',
      eventExplanation: '',
    }
  },
  method: {
    async evenetCreate () {
      const response = await axios.post('/api/events', {
        part: this.part,
        eventName: this.eventName,
        eventExplanation: this.eventExplanation
      })

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