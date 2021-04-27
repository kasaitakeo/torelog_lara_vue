<template>
  <v-simple-table>
    <template v-slot:default>
      <thead  class="brown lighten-4">
        <tr>
          <th class="text-left">
            部位
          </th>
          <th class="text-left">
            種目名
          </th>
          <th class="text-left">
            重量
          </th>
          <th class="text-left">
            回数
          </th>
          <th class="text-left">
            セット数
          </th>
          <th v-if="$route.name === 'log.edit'" class="text-left">
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in items"
          :key="item.id"
        >
          <td>{{ item.event.event_part }}</td>
          <td>{{ item.event.event_name }}</td>
          <td>{{ item.weight }}</td>
          <td>{{ item.rep }}</td>
          <td>{{ item.set }}</td>
          <td v-if="$route.name === 'log.edit'">
            <v-btn @click.prevent="deleteEventLog(item.id)" color="blue darken-1" text>削除</v-btn>
          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
// Log LogCreateが親コンポーネント
export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },
  data () {
    return {
      headers: [
        {
          text: 'トレログ',
          align: 'start',
          value: 'name',
        },
        { text: '部位', value: 'event.event_part' },
        { text: '種目名', value: 'event.event_name' },
        { text: '重量', value: 'weight' },
        { text: '回数', value: 'rep' },
        { text: 'セット数', value: 'set' },
      ],
    }
  },
  methods: {
    // 種目ログ削除後ページの更新が必要な為、親にemitで投げる
    async deleteEventLog (id) {
      this.$emit('deleteEventLog', {
        id: id,
      })
    }
  }
}
</script>

<style>

</style>