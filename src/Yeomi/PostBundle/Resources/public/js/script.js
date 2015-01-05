$(function() {

  var quantity = 2;

  $(".loader-list").click(function() {

    var container = $(this).closest(".column-container");
    var wrapper = container.find(".ajax-wrapper");
    var target = $(this).data("target");
    var offset = $(this).parent().parent().data("offset");

    $.ajax({
      url: target + "/" + (quantity + offset),
      type: "POST",
      context: document.body,
      success: function(data){
        $(wrapper).html(data);
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
    offset += quantity;
    container.find(".index-menu").data("offset", offset);

    $.ajax({
      url: target + "/" + quantity + "/" + offset,
      type: "POST",
      context: document.body,
      success: function(data){
        $(wrapper).append(data);
      }
    });

    return false;
  });

});