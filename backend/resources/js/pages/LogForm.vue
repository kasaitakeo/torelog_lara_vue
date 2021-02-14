<template>
  <div>
    <form @submit.prevent="postLog">
      <textarea v-model="logContent"></textarea>
      <button type="submit">ログ作成</button>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      logContent: '',
    }
  },
  methods: {
    async postLog () {

      const response = await axios.post('/api/logs/create', {
        text: this.logContent
      })
    }
  }
}
</script>