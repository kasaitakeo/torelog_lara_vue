<template>
  <div>
    <form @submit.prevent="postEventLog">
      <div v-for="event in events">
        <form>
          <input type="hidden" :value="logId">
          <input type="hidden" value="event.Id">
          <ul>
            <li>重量：
            <select name="weight">
            <div v-for="n in 200">
              <div v-if="n === 50">
                <option value="{{ n }}" selected>{{ $i }}KG</option>

              </div>
            </div>
                    @continue;
                @endif
            <option value="{{ $i }}">{{ $i }}KG</option>
            @endfor
            </select>
            </li>
            <li>回数：
            <select name="rep">
            @for ($i = 0; $i < 30; $i++)
                @if ($i === 10) {
                    <option value="{{ $i }}" selected>{{ $i }}REP</option>
                    @continue;
                @endif
            <option value="{{ $i }}">{{ $i }}REP</option>
            @endfor
            </select>
            </li>
            <li>セット数：
            <select name="set">
            @for ($i = 0; $i < 30; $i++)
                @if ($i === 10) {
                    <option value="{{ $i }}" selected>{{ $i }}SET</option>
                    @continue;
                @endif
            <option value="{{ $i }}">{{ $i }}SET</option>
            @endfor
            </select>
            </li>
        </ul>
        <button type="submit" class="btn btn-primary">追加する</button>

        </form>
      </div>
    </form>
    <form @submit.prevent="postLog">
      <textarea v-model="logContent"></textarea>
      <button type="submit">ログ作成</button>
    </form>
    <p>{{ logContent }}</p>
    <p>{{ msg }}</p>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data () {
    return {
      logContent: '',
      msg: '',
      errors: null
    }
  },
  methods: {
    async postLog () {

      const response = await axios.post('/api/logs', {
        text: this.logContent
      })

      if (response.status !== UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.msg = 'logが投稿されました'

      this.$router.push('/')
    }
  }
}
</script>