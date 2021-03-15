<template>
  <div>
      <v-card
        class="mx-auto"
        max-width="800"
      >
        <p>{{ event.event_name }}</p>
        <div v-if="this.$route.path === '/logs/create'">
        <form @submit.prevent="eventPost">
          <v-row
            align="center"
            justify="center"
          >
          <v-card raised width="200" class="ma-2">
            <v-select 
            width="60"
            v-model="weight"
            :items="weightItems" 
            label="重量" 
            data-vv-name="select" 
            required 
            >
            </v-select>
          </v-card>
          <v-card raised width="200" class="ma-2">
            <v-select 
            v-model="rep" 
            :items="repItems"
            label="回数" 
            data-vv-name="select" 
            required 
            >
            </v-select>
          </v-card>
          <v-card raised width="200" class="ma-2">
            <v-select 
            v-model="set"
            :items="setItems"
            label="セット数" 
            data-vv-name="select" 
            required 
            >
            </v-select>
          </v-card>
          <v-btn
            type="submit"
            class="mx-2"
            fab
            dark
            small
            color="indigo"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn>
          </v-row>
          <!-- <button type="submit">追加する</button> -->
        </form>
        </div>
      </v-card>
  </div>
</template>

<script>
import eventBus from '../eventBus.js'

export default {
  props: {
    event: {
      type: Object,
      required: true
    },
  },
  data () {
    return {
      weightItems: [...Array(251).keys()],
      repItems: [...Array(31).keys()],
      setItems: [...Array(21).keys()],
      weight: '',
      rep: '',
      set: '',
    }
  },
  methods: {
    async eventPost () {
      
      eventBus.$emit('eventPost', {
        id: this.event.id,
        weight: this.weight,
        rep: this.rep,
        set: this.set
      })
    }
  }
}
</script>