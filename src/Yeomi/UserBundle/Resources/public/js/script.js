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

    return false;

  });


  $("body").on("submit", "form", function() {
    console.log("salut");
    var form = $(this);

    $.post("/app_dev.php/create/profile/39", form.serialize(),
      function success(data){
        console.log(data);
    });

    return false;
  });

});