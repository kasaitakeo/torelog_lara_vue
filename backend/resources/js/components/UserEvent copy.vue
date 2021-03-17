<template>
  <div>
    <v-card>
      <v-tabs
        v-model="tabName"
        background-color="primary"
        dark
      >
        <v-tab
          v-for="eventPart in eventParts"
          :key="eventPart.tabName"
        >
          {{ eventPart.tabName }}
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tabName">
        <v-tab-item
          v-for="eventPart in eventParts"
          :key="eventPart.tab"
        >
          <v-card flat>
            <v-card-text>
              <Event
                v-for="event in eventPart.eventList"
                :key="event.id"
                :event="event"
              />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>


    <!-- <v-card
      class="mx-auto mb-3"
      max-width="800">
      <v-tabs
        background-color="#039BE5"
        center-active
        dark
      >
        <v-tab v-for="eventPart in eventParts" :key="eventPart.id" @click="activate(eventPart.id)">
          {{ eventPart.name }}
        </v-tab>
      </v-tabs>
     </v-card>
          <v-card 
            class="mx-auto"
            max-width="800"
          > -->
            <!-- <v-row>
    <div v-for="event in events" :key="event.id">
      <div v-for="part in eventParts" :key="part.id">
        <div v-if="event.part === part.name">
              <v-col  class="ma-1">
                <Event
                  :event="event"
                  v-show="active === part.id"
                />
              </v-col>
        </div>
      </div>
    </div>
            </v-row>
          </v-card> --> 
  </div>
</template>

<script>
import Event from '../components/Event.vue'
import { OK } from '../util'

export default {
  components: {
    Event,
  },
  props: {
    // events: {
    //   type: Object,
    //   required: true
    // },
    userId: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      tabName: null,
      eventParts: [
        {tabName:'胸', eventList: []},
        {tabName:'背中', eventList: []},
        {tabName:'肩', eventList: []},
        {tabName:'脚', eventList: []},
        {tabName:'上腕二頭筋', eventList: []},
        {tabName:'上腕三頭筋', eventList: []},
        {tabName:'腹筋', eventList: []},
        {tabName:'その他', eventList: []},
      ],
      events: {},
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
    async getEvents () {
      const response = await axios.get(`/api/${this.userId}/events`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
      // console.log(response.data)
      this.events = response.data

      this.getPartEvents()
    },
    getPartEvents () {
      for (let eventPart in this.eventParts) {
        for (let event in this.events) {
          console.log(event)
          if(event.part === eventPart.tabName) {
            eventPart.eventList.push(event.name)
            break
          }
        }
      }
      console.log(this.eventParts)
    }
  },
  created () {
    this.getEvents()
    // console.log(this.events)

  },
  // mounted () {
  //   this.$nextTick(()=>{
  //     this.getPartEvents()
  //     console.log(this.events)

  //   })
  // }
}
</script>
