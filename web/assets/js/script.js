$(function() {
  $("a.yeomi-popup-link").click(function(e) {
    var targetClass = $(this).data("popup") + "-popup";
    var target = $("." + targetClass);

    target.parent().addClass("visible");
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


});
