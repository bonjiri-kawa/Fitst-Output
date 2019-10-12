<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ForYouホームページ（レスポンシブデザインページ）</title>
  <link href="https://fonts.googleapis.com/css?family=Amatic+SC" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="./css/reset.css">
  <link type="text/css" rel="stylesheet" href="./css/style.css">
</head>
<body>
<header class="header js-float-menu">
  <h1 class="title">For You</h1>

  <div class="menu-trigger js-toggle-sp-menu">
    <span></span>
    <span></span>
    <span></span>
  </div>
  <nav class="nav-menu js-toggle-sp-menu-target">
    <ul class="menu">
      <li class="menu-item"><a class="menu-link" href="">TOP</a></li>
      <li class="menu-item"><a class="menu-link" href="#news">NEWS</a></li>
      <li class="menu-item"><a class="menu-link" href="#about">ABOUT</a></li>
      <li class="menu-item"><a class="menu-link" href="#staff">STAFF</a></li>
      <li class="menu-item"><a class="menu-link" href="#cource">COURCE</a></li>
      <li class="menu-item"><a class="menu-link" href="#contact">CONTACT</a></li>
    </ul>
  </nav>
</header>
<main>
  <section class="hero container-fluid js-float-menu-target">
    <h2 class="hero-title">For You</h2>
  </section>

  <section class="container container-ornament" id="news">
    <h2 class="container-title"><span data-0="padding:10%;" data-350="padding:0%;">NEWS</span></h2>
    <div class="container-body">
      <ul class="news">
        <li class="news-item">
          <a class="news-link" href="">
            <span class="news-date">2019.12.31</span>
            <span class="news-title">サンプルNEWSタイトルサンプルNEWSタイトル</span>
          </a>
        </li>
        <li class="news-item">
          <a class="news-link" href="">
            <span class="news-date">2019.12.31</span>
            <span class="news-title">サンプルNEWSタイトルサンプルNEWSタイトル</span>
          </a>
        </li>
        <li class="news-item">
          <a class="news-link" href="">
            <span class="news-date">2019.12.31</span>
            <span class="news-title">サンプルNEWSタイトルサンプルNEWSタイトル</span>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <section class="bgColor-lightGray" id="about">
    <div class="container container-lightGray">
      <h2 class="container-title container-title-lightGray"><span>ABOUT</span></h2>
      <div class="container-body">
        <p>このサービスは、お花に関する商品専門のショッピングサイトです。大切な方への贈り物。なんとなく誰かに感謝を伝えたいと思った日。思いっきり誰かをお祝いしてあげたい日。
          お部屋で一人で癒されたい日。そんな時、お花を購入することも多いのではないでしょうか。しかし、今日、お花に関する商品は沢山あります。近くのお花やさんにはないお花も多数存在します。
          あの人にはこんなお花をあげたいな。と思っても、そのお花が近くのお花やさんにあるとは限りません。せっかく送るのであれば、その方にぴったりなお花をお送りしたくありませんか？<br>
          For Youであれば、多くの商品の中からお選びいただけます。さらに、自分で育てたお花や、個人でつくったフラワーアートを出品することができます。誰かの大切な肩への贈り物に、自分で作った物をつかっていただけるのは嬉しいですよね。このサービスは、全お花が好きな方々に使っていただきたいサービスです。
        </p>
      </div>
    </div>
  </section>

  <section class="container container-ornament js-fadein" id="staff">
    <h2 class="container-title"><span data-1000="padding:10%;" data-1300="padding:0%;">STAFF</span></h2>
    <div class="container-body">
      <div class="panel-group panel-group-float">
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower1.jpg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower2.jpeg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower3.jpeg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower4.jpg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower5.jpg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower6.jpg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower7.jpg" alt="">
          </div>
        </a>
        <a href="" class="panel panel-hover panel-staff">
          <div class="panel-body js-fadein-img">
            <img src="./images/flower8.jpeg" alt="">
          </div>
        </a>
      </div>
    </div>
  </section>

  <section class="bgColor-lightGray" id="cource">
    <div class="container container-lightGray">
      <h2 class="container-title"><span>HOW TO USE</span></h2>
      <div class="container-body">
        <div class="panel-group panel-group-flex">
          <div class="panel panel-border panel-cource panel-active">
            <span class="panel-badge">
              購入
            </span>
            <div class="panel-head">
              <span class="ft-corp ft-l">Buy</span>
            </div>
            <div class="panel-body">
              <img src="./images/price4.jpg" alt="">
            </div>
            <div class="panel-foot">
              <p>多数の商品の中から選ぶことができます。届けて欲しい場所へお花をお届けすることもできます。</p>
            </div>
          </div>
          <div class="panel panel-border panel-cource panel-active">
            <span class="panel-badge">
              販売
            </span>
            <div class="panel-head">
              <span class="ft-corp ft-l">Sell</span>
            </div>
            <div class="panel-body">
              <img src="./images/price2.jpg" alt="">
            </div>
            <div class="panel-foot">
              <p>自分で育てたお花や、自分で作ったフラワークラフトを出品することができます。誰かの大切な日にあなたが作ったものが使われたら嬉しいですね。</p>
            </div>
          </div>
          <div class="panel panel-border panel-cource panel-active">
            <span class="panel-badge">
              仕事
            </span>
            <div class="panel-head">
              <span class="ft-corp ft-l">Business</span>
            </div>
            <div class="panel-body">
              <img src="./images/price3.jpg" alt="">
            </div>
            <div class="panel-foot">
              <p>法人としてお花やフラワークラフト等をを出品することができます。販路拡大に繋がる可能性もございます。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container container-ornament" id="contact">
    <h2 class="container-title"><span data-2400="padding:10%;" data-2700="padding:0%;">CONTACT</span></h2>
    <div class="container-body">
      <form action="" class="form form-m">
        <input class="input input-l" type="text" placeholder="お名前">
        <input class="input input-l" type="email" placeholder="email">
        <textarea class="input input-l input-textarea mb-xxl" placeholder="お問い合わせ内容"></textarea>
        <button class="btn btn-corp btn-l">送信</button>
      </form>
    </div>
  </section>
</main>

<footer class="footer">
  <p>Copyright © For You. All Rights Reserved</p>
</footer>

<script src="../js/vendor/jquery-3.4.1.min.js"></script>
<script src="./js/app.js"></script>
<script type="text/javascript" src="js/skrollr/dist/skrollr.min.js"></script>
<script type="text/javascript">
var s = skrollr.init();
</script>
</body>
</html>