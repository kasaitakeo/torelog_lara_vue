import { getCookieValue } from './util'

window.axios = require('axios');

// Ajaxリクエストであることを示すヘッダーを付与する
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.interceptors.request.use(config => {
  // クッキーからトークンを取り出してヘッダーに添付する
  config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')

  return config
})

// axiosのresponseインターセプターはレスポンスを受けた後の処理を上書きす
// 第一引数が成功時の処理（変更せずにそのままresponseを返す）
// 第二引数は失敗時の処理
// 通信エラーの取得に await/catch パターンを用いると、API 呼び出しが増えた場合.catch(error => error.response || error) が重複する。(auth.jsのaxiosの.catch(error => error.response || error)のこと)
// エラーレスポンスが返ってきた場合はエラーそのものではなくレスポンスオブジェクトを返す、という処理はどの API 呼び出しにも共通しているのでインターセプターにまとめました。
window.axios.interceptors.response.use(
  response => response,
  error => error.response || error
)
