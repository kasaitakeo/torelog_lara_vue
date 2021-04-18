<template>
  <v-row>
    <v-col cols="12">
      <!-- <v-card> -->
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
            :counter="200"
            clearable
            clear-icon="mdi-close-circle"
            label="種目解説"
            placeholder="大胸筋上部、コンパウンド種目、高重量狙い"
          ></v-textarea>
          <div v-if="this.$route.path === '/events/create'" class="d-flex justify-center">
            <v-btn v-if="this.$route.path === '/events/create'" type="submit">種目追加</v-btn>
            <v-btn @click="back">戻る</v-btn>
          </div>
          <div v-else>
            <v-btn type="submit" class="d-flex justify-center mb-2">種目更新</v-btn>
          </div>
      
        </form>
      <!-- </v-card> -->
    </v-col>
  </v-row>
</template>

<script>
// EventCreate,EventUpdateの子コンポーネント
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
    async eventEdit () {
      this.$emit('event-edit', {
        eventPart: this.eventPart,
        eventName: this.eventName,
        eventExplanation: this.eventExplanation
      })
      this.eventPart = ''
      this.eventName = ''
      this.eventExplanation = ''
    },
    back () {
      this.$router.go(-1)
    }
  },
  watch: {
    event: {
      handler() {
        this.eventPart = this.event.part
        this.eventName = this.event.event_name
        this.eventExplanation = this.event.event_explanation
      },
      immediate: true,
    }
  }
}
</script>

<style>

</style>