<template>
  <div>
    <v-card
      class="mx-auto"
      max-width="800">
      <v-tabs
        background-color="deep-purple accent-4"
        center-active
        dark
      >
        <v-tab v-for="eventPart in eventParts" :key="eventPart.id">
          <div @click="activate(eventPart.id)">{{ eventPart.name }}</div>
        </v-tab>
      </v-tabs>
    </v-card>
    <div v-for="event in events" :key="event.id">
      <div v-for="part in eventParts" :key="part.id">
        <div v-if="event.part === part.name">
          <div class="tab__content" v-show="active === part.id">
            <Event
              :event="event"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Event from '../components/Event.vue'

export default {
  components: {
    Event,
  },
  props: {
    events: {
      type: Object,
      required: true
    },
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
