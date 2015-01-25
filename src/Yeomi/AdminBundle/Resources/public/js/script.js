$(document).ready(function() {
  $('.table-paginator').dataTable();

  $("body").on("click", ".need-confirmation", function() {
    var confirmation = confirm("Êtes-vous sur de vouloir procéder à cette action ?");
    return confirmation;
  });

} );