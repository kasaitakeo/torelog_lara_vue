<template>
  <v-card class="ma-1 pa-2" elevation="10">
    <!-- 種目作成のリンク -->
    <RouterLink class="d-flex justify-center button button--link" :to="{name: 'event.create'}">
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
    <v-row>
      <!--  -->
      <div v-for="event in events" :key="event.id">
        <div v-for="part in eventParts" :key="part.id">
          <div v-if="event.part === part.name">
            <v-col  class="ma-1">
              <Event
                :event="event"
                v-show="active === part.id"
                :userId="userId"
              />
            </v-col>
          </div>
        </div>
      </div>
    </v-row>
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
  // computed: {
  //   userId () {
  //     return this.$store.getters['auth/userId']
  //   },
  // },  
  methods: {
    activate (id) {
      this.active = id
    },
  },
  mounted () {
  }
}
</script>
