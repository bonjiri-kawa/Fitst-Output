<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
  debug('POST送信があります');
  try{
    $dbh = dbConnect();
    $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
    $sql2 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
    $sql3 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
    $data = array(':us_id' => $_SEESION['user_id']);
    $stmt1 = queryPost($dbh, $sql1, $data);
    $stmt2 = queryPost($dbh, $sql2, $data);
    $stmt3 = queryPost($dbh, $sql3, $data);

    if($stmt1){
      session_destory();
      debug('セッション変数の中身：'.print_r($_SESSION, true));
      debug('トップページへ遷移します');
      debug('Location:index.php');
    }else{
      debug('クエリが失敗しました');
      $err_msg['common'] = MSG07;
    }
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
?>