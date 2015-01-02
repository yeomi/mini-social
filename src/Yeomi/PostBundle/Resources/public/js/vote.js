$(function() {

  $(".vote-btn").click(function() {

    var btn = $(this);
    var target = $(this).data("target");

    lastTarget = target;

    $.ajax({
      url: target,
      type: "POST",
      context: document.body,
      success: function(data){
        console.log(data);
        addOne(btn);
      }
    })

    return false;

  });

  function addOne(btn)
  {
    var cur = btn.find("span").html();
    cur++
    btn.find("span").html(cur);
  }

});