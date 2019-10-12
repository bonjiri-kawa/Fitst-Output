<?php
ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

$debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}
//セッション準備・セッション有効期限を延ばす
//セッションファイルの置き場所変更
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime', 60*60*24*30);
ini_set('session.cookie_lifetime', 60*60*24*30);
session_start();
session_regenerate_id();

//画面表示処理開始ログ吐き出し関数
function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>画面表示処理開始');
    debug('セッションID:'.session_id());
    debug('現在の日時タイムスタンプ:'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug('ログイン日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

//定数
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '半角数字のみご利用いただけます');
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');

//グローバル関数
$err_msg = array();

//バリデーション関数（未入力チェック）
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
//バリデーション関数（Email形式チェック）
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//バリデーション関数（Email重複チェック）
function validEmailDup($email){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty(array_shift($result))){
            $err_msg['email'] = MSG08;
        }
    }catch(Exception $e){
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
//同値チェック
function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
//最少文字チェック
function validMinLen($str, $key, $min = 6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//最大文字チェック
function validMaxLen($str, $key, $max = 255){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
//半角チェック
function validHalf($str, $key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//電話番号形式チェック
function validTel($str, $key){
  if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}
//郵便番号形式チェック
function validZip($str, $key){
  if(!preg_match("/^\d{7}$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG11;
  }
}
//半角数字チェック
function validNumber($str, $key){
  if(!preg_match("/^[0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG17;
  }
}
//固定長チェック
function validLength($str, $key, $len = 8){
  if(mb_strlen($str) !== $len){
    global $err_msg;
    $err_msg[$key] = $len . MSG14;
  }
}
//パスワードチェック
function validPass($str, $key){
  validHalf($str, $key);
  validMaxLen($str, $key);
  validMinLen($str, $key);
}
//selectboxチェック
function validSelect($str, $key){
  if(!preg_match("/^[1-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG15;
  }
}
//エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];
  }
}
//ログイン認証
function isLogin(){
  //ログインしている場合
  if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。');
    //現在日時が最終ログイン日時＋有効期限を超えていた場合
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('ログイン有効期限オーバーです');
      session_destroy();
      return false;
    }else{
      debug('ログイン期限有効期限内です');
      return true;
    }
  }else{
    debug('未ログインユーザーです');
    return false;
  }
}

//データベース
//DB接続関数
function dbConnect(){
    $dsn = 'mysql:dbname=foryou;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}
//SQL実行関数
//function queryPost($dbh, $sql, $data){
//    //$stmt = $dbh->prepare($sql);
//    //$stmt->execute($data);
//    //return $stmt;
//}
function queryPost($dbh, $sql, $data){
  $stmt = $dbh->prepare($sql);
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました');
    debug('失敗したSQL:'.print_r($stmt, true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功');
  return $stmt;
}
function getUser($u_id){
  debug('ユーザー情報を取得します');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
//    if($result){
//      debug('クエリ成功');
//    }else{
//      debug('クエリに失敗しました');
//    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getProduct($u_id, $p_id){
  debug('商品データを取得します');
  debug('ユーザーデータ：'.$u_id);
  debug('商品ID：'.$p_id);
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM product WHERE user_id = :u_id AND id = :p_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id, ':p_id' => $p_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getProductList($currentMinNum = 1, $category, $sort, $span = 20){
  debug('商品情報を取得します');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT id FROM product';
    if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch($sort){
        case 1:
          $sql .= ' ORDER BY price ASC';
          break;
        case 2:
          $sql .= ' ORDER BY price DESC';
          break;
      }
    }
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount();//総レコード数
    $rst['total_page'] = ceil($rst['total']/$span);//総ページ数
    if(!$stmt){
      return false;
    }
    //ページング用のSQL
    $sql = 'SELECT * FROM product';
    if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch($sort){
        case 1:
          $sql .= ' ORDER BY price ASC';
          break;
        case 2:
          $sql .= ' ORDER BY price DESC';
          break;
      }
    }
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array();
    debug('SQL:'.$sql);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      //クエリ結果のデータを全レコード格納
      $rst['data'] = $stmt->fetchAll();
      return $rst;
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getProductOne($p_id){
  debug('商品情報を取得します');
  debug('商品ID'.$p_id);
  //例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT p.id, p.name, p.comment, p.price, p.pic1, p.pic2, p.pic3, p.user_id, p.create_date, p.update_date, c.name AS category 
            FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
    $data = array(':p_id' => $p_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getMyProducts($u_id){
  debug('自分の商品情報を取得します');
  debug('ユーザーID:'.$u_id);
  //例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM product WHERE user_id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      //クエリ結果のデータを全レコード返却
      return $stmt->fetchAll();

    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
//bord取得関数
function getBord($m_id){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT sale_user, buy_user, product_id, create_date FROM bord WHERE id = :id AND delete_flg = 0';
    $data = array(':id' => $m_id);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
  }
}
//message取得関数
function getMsg($m_id){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT send_date, to_user, from_user, msg FROM message WHERE bord_id = :id AND delete_flg = 0 ORDER BY send_date ASC';
    $data = array(':id' => $m_id);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    return $result;
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
  }
}
function AndBord($u_id){
  debug('自分のmsg情報を取得します');
  try{
    $dbh = dbConnect();
    //まず掲示板レコード取得
    $sql = 'SELECT * FROM bord AS b WHERE b.sale_user = :id OR b.buy_user = :id AND b.delete_flg = 0 LIMIT 10 OFFSET 1';
    $data = array(':id' => $u_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst = $stmt->fetchAll();
    if(!empty($rst)){
      foreach($rst as $key => $val){
        //SQL文作成
        $sql = 'SELECT * FROM `message` AS m LEFT JOIN users AS u ON m.to_user = u.id WHERE bord_id = :id AND m.delete_flg = 0 AND u.delete_flg = 0 ORDER BY send_date DESC';
        $data = array(':id' => $val['id']);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst[$key]['msg'] = $stmt->fetchAll(); //＄rstにmsgを追加することで、見れる
      }
    if(!empty($rst)){
      foreach($rst as $key => $val){
        $sql = 'SELECT * FROM users WHERE id = :id AND delete_flg = 0';
        $data = array(':id' => $val['sale_user']);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst[$key]['partner_info'] = $stmt->fetchAll();
      }
    }
    }
    if($stmt){
      //クエリ結果の全データを返却
      return $rst;
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
  }
}
function getMyMsgAndBord($u_id){
  debug('自分のmsg情報を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    
    // まず、掲示板レコード取得
    // SQL文作成
    $sql = 'SELECT * FROM bord AS b WHERE b.sale_user = :id OR b.buy_user = :id AND b.delete_flg = 0 LIMIT 10 OFFSET 1';
    $data = array(':id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst = $stmt->fetchAll();
    //var_dump($rst);
    if(!empty($rst)){
      foreach($rst as $key => $val){
        // SQL文作成
        //var_dump($val);
        $sql = 'SELECT * FROM message WHERE bord_id = :id AND delete_flg = 0 ORDER BY send_date DESC';
        //$sql = 'SELECT * FROM `message` AS m LEFT JOIN users AS u ON m.to_user = u.id WHERE bord_id = :id AND m.delete_flg = 0 AND u.delete_flg = 0 ORDER BY send_date DESC';
        $data = array(':id' => $val['id']);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst[$key]['msg'] = $stmt->fetchAll();
        //var_dump($val);
      }
    if(!empty($rst)){
      foreach($rst as $key => $val){
        $sql = 'SELECT * FROM users WHERE id = :id AND delete_flg = 0';
        $data = array(':id' => $val['sale_user']);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst[$key]['partner_info'] = $stmt->fetchAll();
      }
    }
    }
    if($stmt){
      // クエリ結果の全データを返却
      return $rst;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getCategory(){
  debug('カテゴリー情報を取得します');
  //例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM category';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果の全データを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
  }
}
function isLike($u_id, $p_id){
  debug('お気に入り情報があるか確認します');
  debug('ユーザーID:'.$u_id);
  debug('商品ID:'.$p_id);
  //例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
    $data = array(':u_id' => $u_id, ':p_id' => $p_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt->rowCount()){
      debug('お気に入りです');
      return true;
    }else{
      debug('特に気に入ってません');
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getMyLike($u_id){
  debug('自分のお気に入り情報を取得します');
  debug('ユーザーID：'.$u_id);
  //例外処理
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `like` AS l LEFT JOIN product AS p ON l.product_id = p.id WHERE l.user_id = :u_id';
    $data = array(':u_id' => $u_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果の全データを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生' . $e->getMessage());
  }
}
//メール送信
function sendMail($from, $to, $subject, $comment){
  if(!empty($to) && !empty($subject) && !empty($comment)){
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    $result = mb_send_mail($to, $subject, $comment, "From:".$from);
    if($result){
      debug('メールを送信しました');
    }else{
      debug('[エラー発生] メールの送信に失敗しました。');
    }
  }
}
//サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}
//フォーム入力保持
function getFormData($str, $flg = false){
  if($flg){
    $method = $_GET;
  }else{
    $method = $_POST;
  }
  global $dbFormData;
  //global $err_msg;
  //ユーザーデータがある場合
  if(!empty($dbFormData)){
    if(!empty($err_msg[$str])){
      if(isset($method[$str])){
        return sanitize($method[$str]);
      }else{
        return sanitize($dbFormData[$str]);
      }
    }else{
      if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
        return sanitize($method[$str]);
      }else{
        return sanitize($dbFormData[$str]);
      }
    }
  }else{
    if(isset($method[$str])){
      return sanitize($method[$str]);
    }
  }
}
//sessionを一回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}
//認証キー生成
function makeRandKey($length = 8){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for($i = 0; $i < $length; $i++){
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}
//画像処理
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('file情報'.print_r($file, true));

  if(isset($file['error']) && is_int($file['error'])){
    try{
      switch($file['error']){
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが選択されていません');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default: //その他
          throw new RuntimeException('その他のエラーが発生しました');
      }
      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
        throw new RuntimeException('画像形式が未対応です');
      }
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if(!move_uploaded_file($file['tmp_name'], $path)){
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      chmod($path, 0644);
      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス'.$path);
      return $path;
    }catch(RuntimeException $e){
      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}
//ページング
// $currentPageNum : 現在のページ
// $totalPageNum : 数ページ
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination($currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
  if($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 4;
    $maxPageNum = $currentPageNum;
  }elseif($currentPageNum == ($totalPageNum - 1) && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 3;
    $maxPageNum = $currentPageNum + 1;
  }elseif($currentPageNum == 2 && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 1;
    $maxPageNum = $currentPageNum + 3;
  }elseif($currentPageNum == 1 && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum;
    $maxPageNum = 5;
  }elseif($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
  }else{
    $minPageNum = $currentPageNum - 2;
    $maxPageNum = $currentPageNum + 2;
  }

  echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
      if($currentPageNum != 1){
        echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
      }
      for($i = $minPageNum; $i <= $maxPageNum; $i++){
        echo '<li class="list-item';
        if($currentPageNum == $i){echo 'active';}
        echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
      }
      if($currentPageNum != $maxPageNum){
        echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
      }
    echo '</ul>';
  echo '</div>';
}
//画面表示用関数
function showImg($path){
  if(empty($path)){
    return 'img/sample-img.png';
  }else{
    return $path;
  }
}
//GETパラメータ付与
//$del_key : 付与から取り除きたいGETパラメーターのキー
function appendGetParam($arr_del_key = array()){
  if(!empty($_GET)){
    $str = '?';
    foreach($_GET as $key => $val){
      if(!in_array($key,$arr_del_key,true)){
        $str .= $key.'='.$val.'&';
      }
    }
    $str = mb_substr($str, 0, -1, "UTF-8");
    return $str;
  }
}
