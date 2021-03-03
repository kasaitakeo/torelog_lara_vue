<template>
  <div>
    <form @submit.prevent="post">
        <p>{{ event.event_name }}</p>
        <ul>
          <li>重量：
            <select v-model="weight">
              <option v-for="n in 100" :key="n" :value="n * 2">{{ n * 2 }}kg</option>
            </select>
          </li>
          <li>回数：
            <select v-model="rep">
              <option v-for="n in 30" :key="n" :value="n">{{ n }}rep</option>
            </select>
          </li>
          <li>セット数：
            <select v-model="set">
              <option v-for="n in 10" :key="n" :value="n">{{ n }}set</option>
            </select>
          </li>
        </ul>
        <button type="submit">追加する</button>
      </form>
    </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      required: true
    },
  },
  data () {
    return {
      weight: '',
      rep: '',
      set: '',
    }
  },
  methods: {
    async post () {
      
      this.$emit('post', {
        id: this.event.id,
        weight: this.weight,
        rep: this.rep,
        set: this.set
      })
    }
  }
}
</script>