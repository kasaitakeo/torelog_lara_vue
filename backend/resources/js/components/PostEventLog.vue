<template>
  <div>
    <form @submit.prevent="post">
        <p>{{ event.event_name }}</p>
        <ul>
          <li>重量：
            <select v-model="weight">
              <option v-for="n in 100" :key="n" :value="n * 2">{{ n * 2 }}kg</option>
              <!-- <div v-for="n in 100" :key="n"> -->
                <!-- <div v-if="n === 25">
                  <option selected >{{ n * 2 }}kg</option>
                </div>
                <div v-else>
                  <option>{{ n * 2 }}kg</option>
                </div> -->
              <!-- </div> -->
            </select>
          </li>
          <li>回数：
            <select v-model="rep">
              <option v-for="n in 100" :key="n" :value="n">{{ n }}rep</option>
              <!-- <div v-for="n in 30" :key="n">
                <div v-if="n === 10">
                  <option selected>{{ n }}rep</option>
                </div>
                <div v-else>
                  <option>{{ n }}rep</option>
                </div>
              </div> -->
            </select>
          </li>
          <li>セット数：
            <select v-model="set">
              <option v-for="n in 100" :key="n" :value="n">{{ n }}set</option>
              <!-- <div v-for="n in 10" :key="n">
                <div v-if="n === 4">
                  <option selected>{{ n }}set</option>
                </div>
                <div v-else>
                  <option>{{ n }}set</option>
                </div>
              </div> --> -->
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