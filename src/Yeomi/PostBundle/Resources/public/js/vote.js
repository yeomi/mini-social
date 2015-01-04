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
        if(data == "add") {
          addOne(btn);
        } else if(data == "remove") {
          removeOne(btn);
        }
      }
    })

    return false;

  });



  function removeOne(btn)
  {
    var cur = btn.find("span").html();
    cur--;
    btn.find("span").html(cur);
  }

  function addOne(btn)
  {
    var cur = btn.find("span").html();
    cur++;
    btn.find("span").html(cur);
  }

});