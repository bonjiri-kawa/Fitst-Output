$(function () {

  // フロートヘッダーメニュー
  var targetHeight = $('.js-float-menu-target').height();
  $(window).on('scroll', function() {
    $('.js-float-menu').toggleClass('float-active', $(this).scrollTop() > targetHeight);
  });

  // SPメニュー
  $('.js-toggle-sp-menu').on('click', function () {
    $(this).toggleClass('active');
    $('.js-toggle-sp-menu-target').toggleClass('active');
  });

  //画像順番に現れる
  $('.js-fadein-img').css("opacity", "0");
  $(window).scroll(function(){
    $('.js-fadein-img').each(function(e){
      var imgPos = $(this).offset().top;
      var scroll = $(window).scrollTop();
      var windowHeight = $(window).height();
      if(scroll > imgPos - windowHeight + windowHeight/5){
        //$('.js-fadein-img').css("opacity", "1");
        $(this).delay(50 * e).animate({opacity:1}, 1500);
      }else{
        $(this).css("opacity","0" );
      }
    });
  });
  /* $('.js-fadein a').hide();
  $('.js-fadein a').each(function(e){
    $(this).delay(100 * e).fadeIn(600, $(this).scrollTop() >  );
  }); */
});