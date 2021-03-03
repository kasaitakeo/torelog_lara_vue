<template>
  <div>
    <form @submit="eventCreate">
      <span>種目部位選択: {{ eventPart }}</span>
      <select v-model="eventPart">
        <option disabled value="">種目部位を選択してください</option>
        <option value="胸">胸</option>
        <option value="背中">背中</option>
        <option value="肩">肩</option>
        <option value="脚">脚</option>
        <option value="上腕二頭筋">上腕二頭筋</option>
        <option value="上腕三頭筋">上腕三頭筋</option>
        <option value="腹筋">腹筋</option>
        <option value="その他">その他</option>
      </select>
      <br>
      <span>種目:{{ eventName }}</span>
      <br>
      <input v-model="eventName" placeholder="種目名を入力してください">
      <br>
      <span>種目解説:{{ eventExplanation }}</span>
      <br>
      <textarea v-model="eventExplanation" placeholder="種目の解説を入力してください"></textarea>
      <button type="submit">種目追加</button>
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

      this.eventPart = ''

      this.eventName = ''

      this.eventExplanation = ''

      this.$router.push('/')
    }
  }

}
</script>

<style>

</style>