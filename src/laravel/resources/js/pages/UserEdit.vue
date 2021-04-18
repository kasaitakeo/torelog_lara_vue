<template>
  <v-form
    v-model="valid"
    lazy-validation
  >
    <div class="errors" v-if="errors">
      <ul v-if="errors.name">
        <li v-for="msg in errors.name" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.screen_name">
        <li v-for="msg in errors.screen_name" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.email">
        <li v-for="msg in errors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.profile_text">
        <li v-for="msg in errors.profile_text" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="errors.profile_image">
        <li v-for="msg in errors.profile_image" :key="msg">{{ msg }}</li>
      </ul>
    </div>
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
          v-model="profileText"
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
  </v-form>
</template>

<script>
import { OK, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data () {
    return {
      errors: null,
      user: {},
      screenName: '',
      name: '',
      email: '',
      profileText: '',
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
    // 編集するユーザーの情報を取得
    async getUser () {
      const response = await axios.get(`/api/users/${this.$route.params.userId}`)

      console.log(response.data)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.screenName = response.data.user_data.screen_name
      this.name = response.data.user_data.name
      this.email = response.data.user_data.email
      this.profileText = response.data.user_data.profile_text
    },
    // 入力情報で更新する
    async update () {
      // 画像データの送信を行うためFormDataインスタンスを生成し、入力情報を追加後サーバーに送る
      let formData = new FormData()
      formData.append('name', this.name)
      formData.append('screen_name', this.screenName)
      formData.append('email', this.email)
      formData.append('profile_text', this.profileText)
      formData.append('profile_image', this.image ? this.image : '')

      // フォームデータ情報の表示
      console.log(formData.get('name'))
      console.log(...formData.entries())

      // 画像データ送信の際にコンテンツタイプにmultipartを指定する、
      const response = await axios.post('/api/users/', formData, {
        headers: {
          'X-HTTP-Method-Override': 'PUT',
          'Content-Type': 'multipart/form-data'
        }
      })  

      console.log(response)
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log(response)
        this.errors = response.data.errors
        return false
      }

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push('/')
    },
    // フォームでファイルが選択されたら実行される
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
  watch: {
    $route: {
      async handler () {
        if (!this.$store.getters['auth/check']) {
          this.$router.push('/')
        }
        await this.getUser()
      },
      immediate: true
    }
  },
}
</script>

<style>

</style>