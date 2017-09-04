$("#searchForm").submit(function(event){
    event.preventDefault(); //prevent default action
    var post_url = $(this).attr("action"); //get form action url
    var form_data = $(this).serialize(); //Encode form elements for submission

    $.getJSON( post_url , form_data,function( response ) {
        var items = [];
        if (response.status == 200)
        {
            //iterate json response
            $.each( response.results, function(key, val) {
                items.push( "<li>" + val.person_name + ' - ' + val.feature_name + ' : ' + val.feature_value  + "</li>" );
            });
            var header = $("<h3>", {
                html: "Resultados"
            });
            var replacement = $( "<ul/>", {
                "class": "person-list",
                html: items.join("")
            });
            $("#server-results").empty().append(header).append(replacement);
        } else {
            var replacement = $( "<div/>", {
                html: "response error"
            });
            $("#server-results").empty().append(header).append(replacement);

        }
    });
});