$(function() {

  $(".loader-list").click(function() {

    var container = $(this).closest(".column-container");
    var wrapper = container.find(".ajax-wrapper");
    var target = $(this).data("target");
    var offset = $(this).parent().parent().data("offset");
    var quantity = $(this).parent().parent().data("quantity");

    $.ajax({
      url: target + "/" + (quantity + offset),
      type: "GET",
      context: document.body,
      success: function(data){
        $(wrapper).html(data[0]);
        if(!data[1]) {
          noMorePost(container);
        } else {
          container.find(".more-posts").show();
        }
      }
    });

    container.find(".tab").removeClass("active");
    $(this).parent().addClass("active");

    return false;

  });

  $(".loader-list:even").click();

  $(".more-posts").click(function() {

    var container = $(this).closest(".column-container");
    var wrapper = container.find(".ajax-wrapper");
    var target = container.find(".tab.active .loader-list").data("target");
    var offset = container.find(".index-menu").data("offset");
    var quantity = container.find(".index-menu").data("quantity");
    offset += quantity;
    container.find(".index-menu").data("offset", offset);

    $.ajax({
      url: target + "/" + quantity + "/" + offset,
      type: "GET",
      context: document.body,
      success: function(data){
        $(wrapper).append(data[0]);
        if(!data[1]) {
          noMorePost(container);
        }
      }
    });

    return false;
  });

  function noMorePost(container)
  {
    container.find(".more-posts").fadeOut();
  }
});