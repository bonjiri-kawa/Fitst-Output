<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「パスワード再発行メール送信ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
  debug('POST情報があります');
  debug('POST情報'.print_r($_POST, true));
  $email = $_POST['email'];
  validRequired($email, 'email');
  if(empty($err_msg)){
    debug('未入力チェックOK');
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    if(empty($err_msg)){
      debug('バリデーションOK');
      try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE emial = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt && array_shift($result)){
          $_SESSION['msg_success'] = SUC03;
          $auth_key = makeRandKey();
          //メール送信
          $from = 'iiii@webukatu.com';
          $to = $email;
          $subject = '[パスワード発行] | For You';
          $comment = <<<EOT
          本メールアドレス宛にパスワード再発行のご依頼がありました。
          下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。
          
          パスワード再発行認証キー入力ページ：http://localhost:8888/webservice_practice07/passRemindRecieve.php
          認証キー：{$auth_key}
          ※認証キーの有効期限は30分となります
          
          認証キーを再発行されたい場合は下記ページより再度再発行をお願い致します。
          http://localhost:8888/webservice_practice07/passRemindSend.php
          
          ////////////////////////////////////////
          ウェブカツマーケットカスタマーセンター
          URL  http://webukatu.com/
          E-mail info@webukatu.com
          ////////////////////////////////////////
EOT;
          sendMail($from, $to, $subject, $comment);
          $_SESSION['auth_key'] = $auth_key;
          $_SESSION['auth_mail'] = $email;
          $_SESSION['auth_key_limit'] = time() + (60*30);
        }
      }
    }
  }

}
?>