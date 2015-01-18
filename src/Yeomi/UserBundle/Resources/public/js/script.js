$(function() {

  $(".ajax-profile-link").click(function() {

    var wrapper = $(".ajax-wrapper");
    var target = $(this).data("target");

    $.ajax({
      url: target,
      type: "POST",
      context: document.body,
      success: function(data){
        $(wrapper).html(data);
      }
    });
    $(".ajax-profile-link").removeClass("active");
    $(this).addClass("active");

    return false;

  });

  $("body").on("submit", ".ajax-submission-form", function() {
    var wrapper = $(".ajax-wrapper");
    var form = $(this);
    var target = $(".ajax-profile-link.active").data("target");
    $.post(target, form.serialize(),
      function success(data){
        $(wrapper).html(data);
    });

    return false;
  });

});