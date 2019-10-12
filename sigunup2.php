<?php
require('function.php');
if(!empty($_POST)){
  $email = $_POST['emial'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  validRequired($emial, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if(empty($err_msg)){
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    validMinLen($email, 'email');

    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');

    validMaxLen($pass_re, 'pass_re');
    validMinLen($pass_re, 'pass_re');

    if(empty($err_msg)){
      validMatch($pass, $pass_re, 'pass_re');
      if(empty($err_msg)){
        try{
          $dbh = dbConnect();
          $sql = 'INSERT INTO users (email, password, login_time, create_date) VALUES (:email, :pass, :login_time, :create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':create_date' => date('Y-m-d H:i:s'));
          $stmt = queryPost($dbh, $sql, $data);
          if($stmt){
            $sesLimit = 60 * 60;
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            $_SESSION['user_id'] = $dbh->lastInsertID();
            debug('セッション変数の中身'.print_r($_SESSION, true));
            header("mypage.php");
          }
        }catch(Exception $e){
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['commmon'] = MSG07;
        }
      }
    }
  }

}
?>