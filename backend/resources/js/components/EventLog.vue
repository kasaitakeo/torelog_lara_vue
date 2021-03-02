<template>
  <div>
    <p>
      <span>部位：{{ item.event.part }}</span>  
      <span>種目：{{ item.event.event_name }}</span>  
    </p>
    <span>{{ item.weight }}kg</span>
    <span>{{ item.rep }}rep</span>
    <span>{{ item.set }}set</span>
    <form @submit.prevent="deleteEventLog"><button type="submit">削除</button></form>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
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