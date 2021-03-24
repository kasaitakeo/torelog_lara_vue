<template>
  <v-row>
    <v-col cols="12">
      <v-card>
        <form @submit.prevent="eventEdit">
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
            :counter="30"
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
          <v-btn v-if="this.$route.path === '/events/create'" type="submit">種目追加</v-btn>
          <v-btn v-else type="submit">種目更新</v-btn>
        </form>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import { OK } from '../util'

export default {
  props: {
    event: {
      type: Object,
      default: () => ({
        part: '',
        event_name: '',
        event_explanation: ''
      })
    }
  },
  data() {
    return {
      itemParts: ["胸", "背中", "肩", "脚", "上腕二頭筋", "上腕三頭筋", "腹筋", "その他"],
      eventPart: '',
      eventName: '',
      eventExplanation: '',
      msg: '',
      errora: null,
    }
  },
  methods: {
    async getEvent () {
      const response = await axios.get(`/api/events/${this.$route.params.eventId}`)
  
      // if (response.status !== OK) {
      //   this.$store.commit('error/setCode', response.status)
      //   return false
      // }
  
      console.log(response.data)
      this.event = response.data
    },
    async eventEdit () {
      this.$emit('event-edit', {
        eventPart: this.eventPart,
        eventName: this.eventName,
        eventExplanation: this.eventExplanation
      })
    }
  },
  created () {
    // this.getEvent()

  },
  mounted () {
    // this.getEvent()

    // this.$nextTick(()=>{
    //   this.eventPart = this.event.part
    //   this.eventPart = this.event.event_name
    //   this.eventPart = this.event.event_explanation
    
    //     // console.log(this.event)
    // })
  },
  // computed: {
  //   // totalPrice(){
  //   //   return this.number * this.price
  //   // }
  //     totalPrice: app => app.number * app.price
  // },
  watch: {
    event: {
      immediate: true,
      handler() {
        this.eventPart = this.event.part
        this.eventName = this.event.event_name
        this.eventExplanation = this.event.event_explanation
      }
    }
  }
  // beforeRouteEnter (to, from, next) {
  //   next(vm => {
  //     // `vm` を通じてコンポーネントインスタンスにアクセス
      
  //       if(typeof vm.event !== 'undefined'){
  //         vm.eventPart = vm.event.part
  //         vm.eventPart = vm.event.event_name
  //         vm.eventPart = vm.event.event_explanation
  //       }
  //         vm.eventPart = vm.event.part
  //         vm.eventPart = vm.event.event_name
  //         vm.eventPart = vm.event.event_explanation
      
  //       console.log(vm.event)
  //   })
  // }
  // beforeRouteEnter (to, from, next) {
  //   next(vm => {
  //     console.log(vm.event)
  //     // `vm` を通じてコンポーネントインスタンスにアクセス
  //     vm.$nextTick(()=>{
  //       if(vm.event){
  //         vm.eventPart = vm.event.part
  //         vm.eventPart = vm.event.event_name
  //         vm.eventPart = vm.event.event_explanation
  //       }
  //     })
  //   })
  // }
}
</script>

<style>

</style>