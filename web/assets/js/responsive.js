$(function() {

  $(".mobile-btn").click(function() {
    $(this).parent().toggleClass("show");
  });
 $("#site-header .add-btn").click(function() {
   $(this).closest(".main-nav").removeClass("show");
 })
});
