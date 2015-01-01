$(function() {

  var wrapper = $(".ajax-wrapper");
  var quantity = 2;
  var lastTarget;
  var offset = quantity;


  $(".loader-list").click(function() {

    var target = $(this).data("target");
    lastTarget = target;
    offset = quantity;
    $.ajax({
      url: target + "/" + quantity,
      type: "POST",
      context: document.body,
      success: function(data){
        $(wrapper).html(data);
      }
    })

    $(".loader-list").parent().removeClass("active");
    $(this).parent().addClass("active");

    return false;

  });

  $(".loader-list").eq(0).click();

  $(".more-posts").click(function() {
    console.log(lastTarget);

    $.ajax({
      url: lastTarget + "/" + quantity + "/" + offset,
      type: "POST",
      context: document.body,
      success: function(data){
        $(wrapper).append(data);
      }
    });

    offset += quantity;

    return false;
  });

});