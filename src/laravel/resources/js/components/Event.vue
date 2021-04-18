<template>
  <div class="ma-1">
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
        <v-card-title class="headline grey lighten-2">
          {{ event.event_name }}
        </v-card-title>
        <!-- ログ作成及び更新でない場合は種目名と種目解説のみ表示 -->
        <div v-if="this.$route.name === 'log.create' || this.$route.name === 'log.edit'">
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
          </form>
        </div>
        <div v-else>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6" md="4">
                  {{ event.event_explanation }}
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn  @click="close">Cancel</v-btn>
            <RouterLink v-if="userId === loginUserId" :to="{name: 'event.update', params: {eventId: event.id}}">
              <v-btn color="blue darken-1">
                <v-icon dark>
                  mdi-pencil
                </v-icon>
              </v-btn>
            </RouterLink>
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      required: true
    },
    userId: Number
  },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },  
  data () {
    return {
      dialog: false,
      weightItems: [...Array(301).keys()],
      repItems: [...Array(31).keys()],
      setItems: [...Array(21).keys()],
      weight: 50,
      rep: 10,
      set: 3,
    }
  },
  methods: {
    async eventPost () {
      this.$emit('eventPost', {
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