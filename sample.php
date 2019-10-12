<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>マイページ|For You</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    </head>
    <body class="page-login page-1colum">
      <header>
          <div class="site-width">
              <h1 class="foryou"><a href="" class="a_name">For You</a></h1>
              <nav id="top-nav">
                  <ul>
                      <li><a href="">商品一覧</a></li>
                      <li><a href="">ログイン</a></li>
                  </ul>
              </nav>
          </div>
        </header>
        <!-- メインコンテンツ -->
        <div id="contents" class="site-width">
          <section id="main">
              <div class="form-container">
                <form action="" method="post" class="form">
                    <h2 class="title">ログイン</h2>
                    <div class="area-msg">
                      <?php
                        if(!empty($err_msg['common'])) echo $err_msg['common'];
                      ?>
                    </div>
                    <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                      メールアドレス
                      <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                    </label>
                    <div class="area-msg">
                      <?php
                        if(!empty($err_msg['email'])) echo $err_msg['email'];
                      ?>
                    </div>
                    <label class="<?php if($err_msg['pass']) echo 'err'; ?>">
                      パスワード
                      <input type="password" name="pass" value="<?php if(!empty($err_msg['pass'])) echo $_POST['pass'];  ?>">
                    </label>
                    <div class="area-msg">
                      <?php
                        if(!empty($err_msg['pass'])) echo $err_msg['pass'];
                      ?>
                    </div>
                    <label>
                      <input type="checkbox" name="pass_save">次回ログインを省略する
                    </label>
                    <div class="btn-container">
                      <input type="submit" class="btn btn-mid" value="ログイン">
                    </div>
                  パスワードを忘れた方は<a href="">コチラ</a>
                </form>
              </div>
          </section>
        </div>

        <!-- footer -->
        <footer id="footer">
          Copyright <a href="">For You</a>. All Rightts Reserved.
        </footer>
        <script src="js/vendor/jquery-3.4.1.min.js"></script>
        <script>
          $(function(){
            var $ftr = $('#footer');
            if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
              $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;' });
            }
          });
        </script>
    </body>
</html>