<template>
  <v-form
    v-model="valid"
    lazy-validation
  >
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          v-model="name"
          :counter="20"
          :rules="nameRules"
          label="名前"
          required
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          v-model="screenName"
          :counter="20"
          :rules="nameRules"
          label="アカウント名"
          required
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="E-mail"
          required
        ></v-text-field>        
      </v-col>
      <v-col cols="12" md="6">
        <v-textarea
          outlined
          name="input-7-4"
          label="自己紹介文"
          :counter="250"
          v-model="userText"
        ></v-textarea>
      </v-col>
      <v-col cols="8" md="8">
        <input class="form__item" type="file" @change="onUpload">
      </v-col>
      <v-col cols="4" md="4">
          <output class="form-output" v-if="confirmedImage">
            <img :src="`${confirmedImage}`" alt="" style="width: 150px height: 20px">
          </output>
      </v-col>
      <v-col cols="4">
        <v-btn
          @click.prevent="update"
          color="success"
          class="mr-4"
        >
          更新
        </v-btn>
      </v-col>
    </v-row>
    <!-- <v-file-input
      type="file"
      accept="image/*"
      label="プロフィール画像"
      @change="onImageUploaded"
    >
      <output class="form-output" v-if="image !== null">
        <img :src="'storage/profile_image/'.image" alt="">
      </output>
    </v-file-input> -->
    
  </v-form>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    userId: Number
  },
  data () {
    return {
      user: {},
      screenName: '',
      name: '',
      email: '',
      userText: '',
      image: null,
      confirmedImage: '',
      valid: false,
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
    }
  },
  methods: {
    async getUser () {
      const response = await axios.get('/api/users/' + this.userId)
      console.log(response.data)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.screenName = response.data.user_data.screen_name
      this.name = response.data.user_data.name
      this.email = response.data.user_data.email
      this.userText = response.data.user_data.user_text
      // if (response.data.user_data.profile_image !== null) {
      //   this.confirmedImage = response.data.user_data.profile_image
      // }
    },
    async update () {
      
      let formData = new FormData()
      formData.append('name', this.name)
      formData.append('screen_name', this.screenName)
      formData.append('email', this.email)
      formData.append('user_text', this.userText)
      formData.append('profile_image', this.image)
      console.log(formData.get('name'))
      console.log(...formData.entries())

      const response = await axios.post('/api/users/', formData, {
        headers: {
          'X-HTTP-Method-Override': 'PUT',
          'Content-Type': 'multipart/form-data'
        }
      })  

      console.log(response)

       if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push({name: 'user'})
    },
    // フォームでファイルが選択されたら実行される
    // onUpload (event) {
    //   this.preview = event.target.files[0]
    //   this.image = event.target.files[0]
    // },
    onUpload (event) {
      // event(=e)から画像データを取得する
      this.image = event.target.files[0]
      this.createImage(this.image)
    },
    createImage(image) {
      // FileReaderのインスタンスを作成しfileを読み込む
      const reader = new FileReader()
      // 画像の場合はreadAsDataURLを利用し読み込む
      // imageをreaderにDataURLとしてattachする
      reader.readAsDataURL(image)
      // readAdDataURLが完了したあと実行される処理
      reader.onload = e => {
        this.confirmedImage = e.target.result;
      }
    },
  },
  mounted () {
    if (!this.$store.getters['auth/check']) {
      this.$router.push('/')
    }
    this.getUser()
  }
}
</script>

<style>

</style>