<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「商品出品登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//ログイン認証
//require('auth.php');
//画面処理
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'], $p_id) : '';
$edit_flg = (empty($dbFormData)) ? false : true;
$dbCategoryData = getCategory();
debug('商品ID'.$p_id);
debug('フォーム用DBデータ'.print_r($dbFormData, true));
debug('カテゴリーデータ'.print_r($dbCategoryData, true));
//パラメーター改ざんチェック
if(!empty($p_id) && empty($dbFormData)){
  debug('GETパラメーターの商品IDが違います');
  header("Location:mypage.php");
}
//POST送信処理
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：'.print_r($_POST, true));
  debug('FILE情報：'.print_r($_FILES, true));

  $name = $_POST['name'];
  $category = $_POST['category_id'];
  $price = (!empty($_POST['price'])) ? $_POST['price'] : 0;
  $comment = $_POST['comment'];
  $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'],'pic1') : '';
  $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $pic1;
  $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'],'pic2') : '';
  $pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $pic2;
  $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'],'pic3') : '';
  $pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $pic3;

  //更新の場合はDBの情報と異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    validRequired($name, 'name');
    validMaxLen($name, 'name');
    validSelect($category, 'category_id');
    validMaxLen($comment, 'comment', 500);
    validRequired($price, 'price');
    validNumber($price, 'price');
  }else{
    if($dbFormData['name'] !== $name){
      validRequired($name, 'name');
      validMaxLen($name, 'name');
    }
    if($dbFormData['category_id'] !== $category){
      validSelect($category, 'category_id');
    }
    if($dbFormData['comment'] !== $comment){
      validMaxLen($comment, 'comment', 500);
    }
    if($dbFormData['price'] != $price){
      validRequired($price, 'price');
      validNumber($price, 'price');
    }
  }
  if(empty($err_msg)){
    debug('バリデーションOKです。');
    try{
      $dbh = dbConnect();
      if($edit_flg){
        debug('DB更新です');
        $sql = 'UPDATE product SET name = :name, category_id = :category, price = :price, comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE user_id = :u_id AND id = :p_id';
        $data = array(':name' => $name, ':category' => $category, ':price' => $price, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
      }else{
        debug('新規登録です');
        $sql = 'INSERT INTO product (name, category_id, price, comment, pic1, pic2, pic3, user_id, create_date) VALUES (:name, :category, :price, :comment, :pic1, :pic2, :pic3, :u_id, :date)';
        $data = array(':name' => $name, ':category' => $category, ':price' => $price, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      }
      debug('SQL:'.$sql);
      debug('流し込みデータ'.print_r($data, true));
      $stmt = queryPost($dbh, $sql, $data);
      
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('マイページへ遷移します');
        header("Location:mypage.php");
      }
    }catch(Exception $e){
      error_log('エラー発生' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = (!$edit_flg) ? '商品登録' : '商品編集';
require('head.php');
?>
  <body class="page-profEdit page-2colum page-logined">
    <!-- メニュー -->
    <?php
    require('header.php');
    ?>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <h1 class="page-title"><?php echo (!$edit_flg) ? '商品を出品する' : '商品を編集する'; ?></h1>
      <!-- Main -->
      <section id="main">
        <div class="form-container">
          <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
            <div class="area-msg">
              <?php
              echo getErrMsg('common');
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
              商品名<span class="label-require">必須</span>
              <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
            </label>
            <div class="area-msg">
              <?php echo getErrMsg('name'); ?>
            </div>
            <label class="<?php if(!empty($err_msg['category_id'])) echo 'err'; ?>">
              カテゴリー<span class="label-require">必須</span>
              <select name="category_id">
                <option value="0" <?php if(getFormData('category_id') == 0) echo 'selected'; ?>>選択してください</option>
                <?php
                  foreach($dbCategoryData as $key => $val){
                ?>
                  <option value="<?php echo $val['id']; ?>" <?php if(getFormData('category_id') == $val['id']){echo 'select'; } ?> >
                    <?php echo $val['name']; ?>
                  </option>
                <?php
                  }
                ?>
              </select>
            </label>
            <div class="area-msg">
              <?php echo getErrMsg('category_id'); ?>
            </div>
            <label class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
              詳細
              <textarea name="comment" id="js-count" cols="30" rows="10" style="height:150px;"><?php echo getFormData('comment'); ?></textarea>
            </label>
            <div class="area-msg">
              <?php echo getErrMsg('comment'); ?>
            </div>
            <label class="<?php if(!empty($err_msg['price'])) echo 'err'; ?>">
              金額<span class="label-require">必須</span>
              <div class="form-group">
                <input type="text" name="price" style="width:150px" placeholder="50,000" value="<?php echo (!empty(getFormData('price'))) ? getFormData('price') : 0; ?>">
              </div>
            </label>
            <div class="area-msg">
              <?php echo getErrMsg('price'); ?>
            </div>
            <div style="overflow:hidden;">
              <div class="imgDrop-container">
                画像1
                <label class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="pic1" class="input-file">
                  <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) {echo 'display:none;';} ?>">
                  ドラッグ＆ドロップ
                </label>
                <div class="area-msg">
                  <?php echo getErrMsg('pic1'); ?>
                </div>
              </div>
              <div class="imgDrop-container">
                画像2
                <label class="area-drop <?php if(!empty($err_msg['pic2'])) echo 'err'; ?>">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="pic2" class="input-file">
                  <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) {echo 'display:none;';} ?>">
                  ドラッグ＆ドロップ
                </label>
                <div class="area-msg">
                  <?php echo getErrMsg('pic2'); ?>
                </div>
              </div>
              <div class="imgDrop-container">
                画像3
                <label class="area-drop <?php if(!empty($err_msg['pic3'])) echo 'err'; ?>">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="pic3" class="input-file">
                  <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic3'))) {echo 'display:none;';} ?>">
                  ドラッグ＆ドロップ
                </label>
                <div class="area-msg">
                  <?php echo getErrMsg('pic3'); ?>
                </div>
              </div>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="<?php echo (!$edit_flg) ? '出品する' : '更新する'; ?>">
            </div>
          </form>
        </div>
      </section>
      <!-- サイドバー -->
      <?php
      require('sidebar_mypage.php');
      ?>
      </div>
      <!-- footer -->
      <?php
      require('footer.php');
      ?>