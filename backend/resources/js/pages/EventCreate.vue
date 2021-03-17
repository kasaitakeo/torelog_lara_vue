<template>
    <v-card class="pt-15 mt-15">
      <v-col cols="12">
        <form @submit="eventCreate">
          <v-select
            v-model="eventPart"
            :items="itemParts" 
            label="部位" 
            data-vv-name="select" 
            required 
          >
          </v-select>
          <v-text-field
            v-model="eventName"
            :counter="10"
            :rules="nameRules"
            label="種目名"
            placeholder="インクラインベンチプレス"
            required
          ></v-text-field>
          <v-textarea
            v-model="eventExplanation"
            clearable
            clear-icon="mdi-close-circle"
            label="種目解説"
            placeholder="大胸筋上部、コンパウンド種目、高重量狙い"
          ></v-textarea>
          <v-btn type="submit">種目追加</v-btn>
        </form>
      </v-col>
    </v-card>
      <!-- <span>種目部位選択: {{ eventPart }}</span>
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
    <p>{{ msg }}</p> -->
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data() {
    return {
      itemParts: ["胸", "背中", "肩", "脚", "上腕二頭筋", "上腕三頭筋", "腹筋", "その他"],
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

      this.$store.commit('message/setContent', {
        content: '種目が追加されました！',
        timeout: 6000
      })

      this.$router.push('/')
    }
  },
  mounted () {
    if (!this.$store.getters['auth/check']) {
      this.$router.push('/')
    }
  },
}
</script>

<style>

</style>