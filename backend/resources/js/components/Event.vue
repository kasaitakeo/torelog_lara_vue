<template>
  <div>
    
      <v-dialog
        v-model="dialog"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
          color="#B3E5FC"
            v-bind="attrs"
            v-on="on"
          >
            {{ event.event_name }}
          </v-btn>
        </template>
      <v-card
        class="mx-auto"
      >
        <v-card-title class="headline grey lighten-2">{{ event.event_name }}</v-card-title>
        <div v-if="this.$route.path === '/logs/create'">
        <form @submit.prevent="eventPost">
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="4">
                  <v-select 
                    width="60"
                    v-model="weight"
                    :items="weightItems" 
                    label="重量" 
                    data-vv-name="select" 
                    required 
                  />
                </v-col>
                <v-col cols="12" sm="6" md="4">
                  <v-select 
                    v-model="rep" 
                    :items="repItems"
                    label="回数" 
                    data-vv-name="select" 
                    required 
                  />
                </v-col>
                <v-col cols="12" sm="6" md="4">
                  <v-select 
                    v-model="set"
                    :items="setItems"
                    label="セット数" 
                    data-vv-name="select" 
                    required 
                  />
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn  @click="close">Cancel</v-btn>
            <v-btn color="blue darken-1" type="submit">
              <v-icon dark>
                mdi-plus
              </v-icon>
            </v-btn>
          </v-card-actions>
          <!-- <v-row
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
          </v-row> -->
        </form>
        </div>
      </v-card>
      </v-dialog>
    
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
      dialog: false,
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

      this.dialog = false
    },
    close () {
      this.dialog = false
    },
  }
}
</script>