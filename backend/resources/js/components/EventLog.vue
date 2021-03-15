<template>
  <div>
    <v-card
      raised 
      width="400" 
      class="mx-auto"
    >
      <span>{{ item.event.part }}</span>  
      <span>{{ item.event.event_name }}</span>  
      <span>{{ item.weight }}kg</span>
      <span>{{ item.rep }}rep</span>
      <span>{{ item.set }}set</span>
      <div class="tab__content" v-show="ableDelete">
        <form @submit.prevent="deleteEventLog"><button type="submit">削除</button></form>
      </div>
    </v-card>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
    ableDelete: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
    }
  },
  methods: {
    async deleteEventLog () {
      console.log(this.item.id)
      this.$emit('deleteEventLog', {
        id: this.item.id,
      })
    }
  }
}
</script>

<style>

</style>