<template>
  <v-card class="ma-1 pa-2" elevation="10">
    <!-- 種目作成のリンク -->
    <RouterLink v-if="this.$route.name === 'log.show' || 'log.edit'" class="d-flex justify-center button button--link" :to="{name: 'event.create'}">
      種目追加
    </RouterLink>
    <v-tabs
      background-color="#039BE5"
      dark
      show-arrows
    >
    <!-- タブで各部位毎に種目を分ける -->
    <v-tabs-slider color="teal lighten-3"></v-tabs-slider>
      <!-- クリックしたタブがアクティブになるようイベント作動 -->
      <v-tab v-for="eventPart in eventParts" :key="eventPart.id" @click="activate(eventPart.id)">
        {{ eventPart.name }}
      </v-tab>
    </v-tabs>
    <v-col cols="12">
      <!-- 種目部位を全てループ -->
      <div v-for="part in eventParts" :key="part.id">
        <!-- 取得したユーザーの種目をループ -->
        <div v-for="event in events" :key="event.id">
          <!-- 各種目部位と等しい種目から並べる（縦列で）※ -->
          <div v-if="event.event_part === part.name">
              <!-- アクティブなタブと同じ種目を表示 -->
              <Event
                :event="event"
                v-show="active === part.id"
                :userId="userId"
                @eventPost="eventPost"
              />
          </div>
        </div>
      </div>
    </v-col>
  </v-card>
</template>

<script>
// LogCreate UserShowが親コンポーネント
import Event from '../components/Event.vue'

export default {
  components: {
    Event,
  },
  props: {
    events: {
      type: Array,
      required: true
    },
    userId: Number
  },
  data () {
    return {
      active: 0,
      eventParts: [
        {id: 0, name:'胸'},
        {id: 1, name:'背中'},
        {id: 2, name:'肩'},
        {id: 3, name:'脚'},
        {id: 4, name:'上腕二頭筋'},
        {id: 5, name:'上腕三頭筋'},
        {id: 6, name:'腹筋'},
        {id: 7, name:'その他'},
      ],
      msg: '',
      errors: null,
    }
  },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    }
  },
  methods: {
    activate (id) {
      this.active = id
    },
    async eventPost (e) {
      this.$emit('eventPost', {
        id: e.id,
        weight: e.weight,
        rep: e.rep,
        set: e.set
      })
    }
  },
}
</script>
