<template>
    <v-col cols="12">
      <v-card 
        color="#B3E5FC"
        class="mt-1"
      >
        <v-card-title class="px-2 py-1">
          <span>{{ item.event.event_part }}:</span>  
          <span>{{ item.event.event_name }}</span>  
        </v-card-title>
        <v-card-text class="px-2 pb-1">
          <span>{{ item.weight }}kg</span>
          <span>{{ item.rep }}rep</span>
          <span>{{ item.set }}set</span>
          <span v-if="$route.name === 'log.edit'">
            <v-btn @click.prevent="deleteEventLog" color="blue darken-1" text>削除</v-btn>
          </span>
        </v-card-text>
      </v-card>
    </v-col>
</template>

<script>
// Log LogCreateが親コンポーネント
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
  },
  methods: {
    // 種目ログ削除後ページの更新が必要な為、親にemitで投げる
    async deleteEventLog () {
      this.$emit('deleteEventLog', {
        id: this.item.id,
      })
    }
  }
}
</script>

<style>

</style>