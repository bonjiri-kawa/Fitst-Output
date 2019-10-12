<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('商品登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証

//画面処理
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'] ,$p_id) : '';
$edit_flg = (empty($dbFormData)) ? false : true;
$dbCategory = getCategory();
debug('商品ID'.$p_id);
debug('フォーム用データ：'.print_r($dbFormData, true));
debug('カテゴリーデーター'.print_r($dbCategory, true));
//パラメーター改ざんチェック
if(!empty($p_id) && empty($dbFormData)){
  debug('GETパラメータの商品IDが違います');
  header("Location:mypage.php");
}
//post送信処理
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報'.print_r($_POST, true));
  debug('FILE情報'.print_r($_FILE, true));

  $name = $_POST['name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $comment = $_POST['comment'];
  $pic1 = (!empty($_POST['pic1']['name'])) ? uploadImg($_FILES['pic1'] ,'pic1') : '';
  $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $pic1;
  $pic2 = (!empty($_POST['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
  $pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $pic2;
  $pic3 = (!empty($_POST['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
  $pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : '';

  if(empty($dbFormData)){
    validRequired($name, 'name');
    validMaxLen($name, 'name');
    validSelect($category, 'category_id');
    validMaxLen($comment, 'comment');
    validRequired($pice, 'price');
    validNumber($price, 'price');
  }else{
    if($dbFormData['name'] !== $name){
      validRequired($name, 'name');
      validMaxLen($name, 'name');
    }
    if($dbFormData['category'] !== $category){
      validSelect($category, 'category_id');
    }
    if($dbFormData['comment'] !== $comment){
      validMaxLen($comment, 'comment');
    }
    if($dbFormData['price'] !== $price){
      validRequired($price, 'price');
      validNumber($price, 'price');
    }
  }
  if(empty($err_msg)){
    debug('バリデーションOKです');
    try{
      $dbh = dbConnect();
      if($edit_flg){
        debug('DB更新です');
        $sql = 'UPDATE product SET name = :name, category_id = :category, comment = :comment, price = :price, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE user_id = :u_id AND id = :p_id';
        $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':price' => $price, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
      }else{
        debug('新規登録です');
        $sql = 'INSERT INTO product (name, category, comment, price, pic1, pic2, pic3, user_id, create_date) VALUES (:name, :category, :comment, :price, :pic1, :pic2, :pic3, :u_id, :date)';
        $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':price' => $price, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      }
      debug('SQL:'.$sql);
      debug('流し込みデータ'.print_r($data, true));
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('マイページへ遷移します');
        debug("Location:mypage.php");
      }
    }catch(Exception $e){
      error_log('エラー発生' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

?>