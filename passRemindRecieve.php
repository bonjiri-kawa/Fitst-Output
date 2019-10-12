<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「パスワード再発行認証キー入力ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//SESSIONに認証キーがあるか確認、なければリダイレクト
if(empty($_SESSION['auth_key'])){
  header("Location:passRemindSend.php"); //認証キー送信ページへ
}
//画面処理開始
if(!empty($_POST)){
  debug('POST情報があります');
  debug('POST情報'.print_r($_POST, true));

  $auth_key = $_POST['token'];
  validRequired($auth_key, 'token');
  if(empty($err_msg)){
    debug('未入力チェックOK');
    validLength($auth_key, 'token');
    validHalf($auth_key, 'token');
    if(empty($err_msg)){
      debug('バリデーションOK');
      if($auth_key !== $_SESSION['auth_key']){
        $err_msg['common'] = MSG15;
      }
      if(time() > $_SESSION['auth_key_limit']){
        $err_msg['common'] = MSG16;
      }
      if(empty($err_msg)){
        debug('認証OK');
        $pass = makeRandKey();
        try{
          $dbh = dbConnect();
          $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
          $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass, PASSWORD_DEFAULT));
          $stmt = queryPost($dbh, $sql, $data);
          if($stmt){
            debug('クエリ成功');
            $from = 'info@◯◯.com';
            $to = $_SESSION['auth_email'];
            $subject = '[パスワード再発行完了]|ForYou';
            $comment = <<<EOT
            本メールアドレス宛にパスワードの再発行を致しました。
            下記のURLにて再発行パスワードをご入力頂き、ログインください。

            ログインページ：http://localhost:8888/webservice_practice07/login.php
            再発行パスワード：{$pass}
            ※ログイン後、パスワードのご変更をお願い致します

            ////////////////////////////////////////
            ウェブカツマーケットカスタマーセンター
            URL  http://webukatu.com/
            E-mail info@webukatu.com
            ////////////////////////////////////////
EOT;
            sendMail($from, $to, $subject, $comment);
            session_unset();
            $_SESSION['msg_success'] = NSG07;
            debug('セッションの中身'.print_r($_SESSION, true));
            header("Location:login.php");
        }else{
          debug('クエリに失敗しました');
          $err_msg['common'] = MSG07;
        }
      }catch(Exception $e){
        error_log('エラー発生' . $e->getMessage());
        $err_msg['common'] = MSG07;
      }
      }
    }
  }
}
?>
<?php
$siteTitle = 'パスワード再発行認証';
require('head.php');
?>

  <body class="page-signup page-1colum">
    <!-- メニュー -->
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main">
        <div class="form-container">
          <form action="" method="post" class="form">
          <p>ご指定のメールアドレスお送りした[パスワード再発行認証]　メール内にある「認証キー」を誤入力ください。</p>
          <div class="area-msg">
            <?php
            echo getErrMsg('common');
            ?>
            <label class="<?php if(!empty($err_msg['token'])) echo 'err'; ?>">
              認証キー
              <input type="text" name="token" value="<?php getFormData('token'); ?>">
            </label>
            <div class="area-msg">
              <?php
              echo getErrMsg('token');
              ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="送信">
            </div>
          </div>
          </form>
        </div>
        <a href="passRemindSend.php">&lt; パスワード再発行メールを再送信する</a>
      </section>
    </div>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>