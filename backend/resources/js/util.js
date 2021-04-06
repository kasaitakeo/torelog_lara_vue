/**
 * クッキーの値を取得する
 * @param {String} searchkey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue (searchkey) {
  if (typeof searchKey === 'undefindes') {
    return ''
  }

  let val = ''

  // document.cookieでname=12345;token=67890;key=abcdeのような形式で参照
  // split(';')で[name=12345,token=67890,key=abcde]のような配列を作る
  // さらに = で split することで引数の searchKey と一致するものを探す
  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchkey) {
      return val = value
    }
  })
 
  return val
}

// アプリケーション的に意味のある数字がハードコードされるのを避けるため
export const OK = 200
export const CREATED = 201
export const NOT_FOUND = 404
// 認証切れの場合のレスポンスコードはLaravelが独自で定義している419
export const UNAUTHORIZED = 419
// Laravelはバリデーションエラーでは422をレスポンス
export const UNPROCESSABLE_ENTITY = 422
export const INTERNAL_SERVER_ERROR = 500