$(function() {

  $("a.yeomi-popup-link").click(function(e) {
    var targetClass = $(this).data("popup") + "-popup";
    var target = $("." + targetClass);
    var popupWrapper = target.parent();

    if(popupWrapper.hasClass("visible")) {
      popupWrapper.removeClass("visible");
      return false;
    } else {
      popupWrapper.addClass("visible");
    }
    setPopupXY(target, e.pageX, e.pageY);

    var arrow = target.parent().find(".popup-arrow");
    setArrowXY(arrow, e.pageX, e.pageY);

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

});
