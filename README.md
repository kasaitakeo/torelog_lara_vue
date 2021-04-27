# 無料ブラウザアプリ「トレログ」
<img src="https://user-images.githubusercontent.com/73113050/116174311-1ca5a100-a749-11eb-9bec-66e64fdee4b3.png" width="100">
[URL] https://torelog.work

トレーニング中にノートを取ると汗で濡れてしまう…

あの人はどんなトレーニングメニューを行っているんだろう…

そんなあなたの問題をこのアプリが解決します！

# デモ
![toreloghome mypage](https://user-images.githubusercontent.com/73113050/116174410-4f4f9980-a749-11eb-9b1c-7654802da62b.gif) ![torelogsakusei](https://user-images.githubusercontent.com/73113050/116174451-61c9d300-a749-11eb-9ff9-52bcbd8b90bd.gif)

# 実装機能一覧
* ゲストログイン機能
* プロフィール画像をAWS S3へ保存・管理(league/flysystem-aws-s3-v3)
* ユーザーオリジナル種目のCRUD機能
* ユーザー同士のフォロー機能(フォローしたユーザーのトレログtimelineのみ表示)
* フロントエンドの完全SPA化(vue-router, vuex)
* ローディング画面(vue-loading-template)
* ユーザー一覧画面の無限スクロール機能(vue-infinite-loading)
* アイコンにmdi使用
* レイアウト構成にvuetify使用
* Route53 による独自ドメイン + SSL化
* Docker/docker-compose による開発環境の完全コンテナ化

# ER図
![torelog_ERD](https://user-images.githubusercontent.com/73113050/116173986-84a7b780-a748-11eb-974a-1fbe7b1305b6.png)

# クラウドアーキテクチャ
![2FF39ED7-A9AD-431F-8FD8-C67D5DFAB1C3_1_105_c](https://user-images.githubusercontent.com/73113050/116175247-e9641180-a74a-11eb-90f6-7b8cff3b13a0.jpeg)

# 技術スタック
* フロントエンド Vue.js2.5（Vue Router, Vuetify, Vuex）を使用しSPA化
* バックエンド PHP(Laravel6.0)を使用 ストレージにAWS(S3)を使用
* インフラ 開発環境、本番環境ともにDockerを使用(LEMP環境)
* 本番環境にAWS(VPC, EC2, RDS(MySQL8.0), Route53,ECR, ECS, ELB, ACMでhttps化)を使用
## Infra詳細
### Docker/docker-compose
開発環境は、全てDockerコンテナ内で完結。また、AWS ECSへのコンテナデプロイより、開発環境と本番環境の差異を最小化。

### AWS(Amazon Web Service)
主にBackend( Laravel+Nginx )のデプロイで使用。

※利用サービス

* ECS : コンテナ向けサーバーレスコンピューティングエンジン。この中に Laravel と Nginx の Docker イメージを入れて稼働
* ECR: Rails と Nginx の Docker イメージを保存
* RDS (MySQL): AWS が用意しているスケーラブルなデータベースエンジン
* ALB: 負荷分散を担うロードバランシングサービス
* Route53: サイトの独自ドメイン化に使用
* ACM: サイトの https 化に使用
* S3: 静的ホスティングサービス。書籍画像も保存・管理に使用
