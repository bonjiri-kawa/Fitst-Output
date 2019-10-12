<footer id="footer">
      Copyright <a href="">For You</a>. All Rights Reserved.
    </footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
    <script>
      $(function(){
        var $ftr = $('#footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
          $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
        }
      //メッセージ表示
        var $jsShowMsg = $('#js-show-msg');
        var msg = $jsShowMsg.text();
        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
          $jsShowMsg.slideToggle('slow');
          setTimeout(function(){$jsShowMsg.slideToggle('slow'); }, 5000);
        }
      //画像ライブプレビュー
        var $dropArea = $('.area-drop');
        var $fileInput = $('.input-file');
        $dropArea.on('dragover', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', '3px #ccc dashed');
        });
        $dropArea.on('dragleave', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', 'none');
        });
        $fileInput.on('change', function(e){
          $dropArea.css('border', 'none');
          var file = this.files[0],
              $img = $(this).siblings('.prev-img'),
              fileReader = new FileReader();

        fileReader.onload = function(event){
          $img.attr('src', event.target.result).show();
        };

        fileReader.readAsDataURL(file);

      });

      //テキストエリアカウント
        var $countUp = $('#js-count'),
            $countView = $('#js-count-view');
        $countUp.on('keyup', function(e){
          $countView.html($(this).val().length);
        });

      //画像切り替え
        var $switchImgSubs = $('.js-switch-img-sub'),
            $switchImgMain = $('#js-switch-img-main');
        $switchImgSubs.on('click', function(e){
          $switchImgMain.attr('src', $(this).attr('src'));
        });

        //お気に入り登録
        var $like,
            likeProductId;
        $like = $('.js-click-like') || null;
        likeProductId = $like.data('productid') || null;
        if(likeProductId !== undefined && likeProductId !== null){
          $like.on('click',function(){
            var $this = $(this);
            $.ajax({
              type: "POST",
              url: "ajaxLike.php",
              data: {productId : likeProductId}
            }).done(function(data){
              console.log('Ajax Success');
              $this.toggleClass('active');
            }).fail(function(msg){
              console.log('Ajax Error');
            });
          });
        }else{
          console.log('なにも入ってないよ！！');
        }
      });
    </script>
  </body>
</html>