$(function() {

  $("body").mouseup(function (e)
  {
    var container = $(".pop-up-wrapper");

    if (!container.is(e.target) // if the target of the click isn't the container...
      && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
      container.removeClass("visible");
    }
  });

  $(".popup-container .close").click(function() {
    $(this).closest(".pop-up-wrapper").removeClass("visible");
  });

  $("a.yeomi-popup-link").click(function(e) {
    var targetClass = $(this).data("popup") + "-popup";
    var target = $("." + targetClass);
    var popupWrapper = target.parent().parent();
    var responsiveWrapper = target.parent();

    if(popupWrapper.hasClass("visible")) {
      popupWrapper.removeClass("visible");
      return false;
    } else {
      $(".pop-up-wrapper").removeClass("visible");
      popupWrapper.addClass("visible");
    }
    setPopupXY(target, e.pageX, e.pageY - $(window).scrollTop());

    setPopupXY(responsiveWrapper, e.pageX, e.pageY + 25);

    var arrow = target.parent().find(".popup-arrow");
    setArrowXY(arrow, e.pageX, e.pageY - $(window).scrollTop());

    return false;
  });

  function setPopupXY(target, x, y) {
    var leftPosition;

    if (x < (target.outerWidth() / 2)) {
      leftPosition = 0;
    } else if($(window).width() < x + (target.outerWidth() / 2)) {
      leftPosition = $(window).width() - target.outerWidth();
    } else {
      leftPosition = x - (target.outerWidth() / 2);
    }

    target.css({
      top: y + 30,
      left: leftPosition
    });
  }

  function setArrowXY(arrow, x, y) {
    arrow.css({
      top: y + (30 - arrow.outerHeight() + 1 ),
      left: x - (arrow.outerWidth() / 2)
    });
  }

  $(".menu-toggle").click(function() {
    var menu = $(".category-nav");
    var body = $("body");
    if(menu.hasClass("open")) {
      menu.removeClass("open");
      body.removeClass("menu-open");
    } else {
      menu.addClass("open");
      body.addClass("menu-open");
    }

    return false;
  });
  $(".category-nav .close").click(function() {
    $(".category-nav").removeClass("open");
    $("body").removeClass("menu-open");
    return false;
  });

  $(".category-nav a").click(function() {
    $(".category-nav .close").click();
    setTimeout(function() {
      return true;
    }, 500);

  });

  $('.yeo-slideshow').slick({
    infinite: true,
    dots: true
  });

  $('.yeo-detailed-slideshow').slick({
    infinite: true,
    dots: true
  });


  $("body").on("click", "#status-message>div, .additional-message>div", function() {
    $(this).addClass("closed");
  });


  setTimeout(function() {
    $("#status-message, .additional-message").find(".flash").trigger("click");
  }, 2000);

  $(".link-attach-file").click(function() {
    var target = ".add-"+$(this).data("target")+"-field";

    if($(target).hasClass("show")) {
      $(".field-attach-file").removeClass("show");
    } else {
      $(".field-attach-file").removeClass("show");
      $(target).addClass("show");
    }

    return false;
  });


  $(".site-search-container a.search").click(function() {
    $(this).parent().parent().toggleClass("show-form");
    return false;
  });


  function checkCookie(){
    var cookieEnabled=(navigator.cookieEnabled)? true : false;
    if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){
      document.cookie="testcookie";
      cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
    }
    return (cookieEnabled)?true:showCookieFail();
  }

  function showCookieFail(){
    $(".check-cookie-fail").removeClass("hide");
  }

  checkCookie();



  $(".reset-image-upload").on("click", function () {
    console.log("here");
    var input = $(this).parent().find("input");
    var id = input.attr("id");
    var name = input.attr("name");
    input.replaceWith('<input type="file" name="' + name + '" id="' + id + '">');
    return false;
  });
});
