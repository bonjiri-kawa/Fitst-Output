<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「ログイン');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
  debug('POST送信があります');
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false;

  validEmail($email, 'email');
  validMaxLen($email, 'email');

  validHalf($pass, 'pass');
  validMaxLen($pass, 'pass');
  validMinLen($pass, 'pass');

  validRequired($email, 'email');
  validRequired($pass, 'pass');

  if(empty($err_msg)){
    try{
      $dbh = $dbConnect();
      $sql = 'SELECT password, id FROM users WHERE email = :email';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('クエリの中身'.print_r($result, true));

      if(!empty($result) && password_verify($pass, array_shift($result))){
        debug('パスワードがマッチしました');
        $sesLimit = 60 * 60;
        $_SESSION['login_date'] = time();
        if($pass_save){
          debug('ログイン保持にチェックがあります');
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else{
          debug('ログイン保持チェックがありません');
          $_SESSION['login_limit'] = $setLimit;
        }
        $_SESSION['user_id'] = $result['id'];
        debug('セッション変数の中身'.print_r($SESSION, true));
        debug('マイページへ遷移します');
        header("Location:mypage.php");
      }else{
        debug('パスワードがアンマッチです');
        $err_msg['common'] = MSG09;
      }
    }catch(Exception $e){
      error_log('エラー発生' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
?>