$("#createFeatureForm").submit(function(event){
    event.preventDefault(); //prevent default action
    var post_url = $(this).attr("action"); //get form action url
    var form_data = $(this).serialize(); //Encode form elements for submission
    $.post( post_url , form_data, function( response ) {
        if (response.status == 200)
        {

            var items = [];
            //iterate json response
            $.each( response.results, function(key, val) {
                items.push( "<li>" + val.name + "</li>" );
            });
            var header = $("<h3>", {
                html: "Creación"
            });
            var replacement = $( "<ul/>", {
                "class": "feature-list",
                html: items.join("")
            });
            $("#server-results").empty().append(header).append(replacement);
        } else {
            var replacement = $( "<div/>", {
                html: "Error on request"
            });
            $("#server-results").empty().append(header).append(replacement);
        }
    });
});