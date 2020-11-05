$("#btn_validate").click( function () {
  $("#warnings").html("");
  $("#errors").html("");
  $("#results").html("");
  
  validate($("#ta_turtle").val(), function (feedback) {
    $.each(feedback.warnings, function (index, warning) {
      $("#warnings").append($('<li id="warning' + index + '">').text(warning));
    });
    
    $.each(feedback.errors, function (index, error) {
      $("#errors").append($('<li id="error' + index + '">').text(error));
    });

    if (feedback.errors.length === 0 && feedback.warnings.length === 0) {
      $("#results").append("Geen fouten in de syntax!");
    }
  });
});

$("#btn_store").click( function () {
  $("#warnings").html("");
  $("#errors").html("");
  $("#results").html("");
  
  validate($("#ta_turtle").val(), function (feedback) {
    $.each(feedback.warnings, function (index, warning) {
      $("#warnings").append($('<li id="warning' + index + '">').text(warning));
    });
    
    $.each(feedback.errors, function (index, error) {
      $("#errors").append($('<li id="error' + index + '">').text(error));
    });

    if (feedback.errors.length === 0 && feedback.warnings.length === 0) {
      $("#kladblok").submit();
	  $("#results").append("Het kladblok is opgeslagen!");
    }
  });
});

$(".lined").linedtextarea();
