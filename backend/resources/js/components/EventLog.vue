<template>
  <div>
    <v-col>
      <v-card raised
        color="#B3E5FC"
        class="my-2"
      >
        <v-card-title class="px-2 py-1">
          <span>{{ item.event.part }}:</span>  
          <span>{{ item.event.event_name }}</span>  
        </v-card-title>

        <v-card-text class="px-2 pb-1">
          <span>{{ item.weight }}kg</span>
          <span>{{ item.rep }}rep</span>
          <span>{{ item.set }}set</span>
          <span v-show="ableDelete">
            <v-btn @click.prevent="deleteEventLog" color="blue darken-1" text>削除</v-btn>
          </span>
        </v-card-text>
      </v-card>
    </v-col>
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