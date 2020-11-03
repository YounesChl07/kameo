$( ".fleetmanager" ).click(function() {
    $.ajax({
        url: 'apis/Kameo/initialize_counters.php',
        type: 'post',
        data: { "email": email, "type": "ordersAdmin"},
        success: function(response){
            if(response.response == 'error') {
                console.log(response.message);
            }
            if(response.response == 'success'){
                document.getElementById('counterOrdersAdmin').innerHTML = "<span data-speed=\"1\" data-refresh-interval=\"4\" data-to=\""+response.ordersNumber+"\" data-from=\"0\" data-seperator=\"true\">"+response.ordersNumber+"</span>";
            }
        }
    })
})



function get_orders_listing() {
    var email= "<?php echo $user_data['EMAIL']; ?>";
    $.ajax({
      url: 'apis/Kameo/orders_management.php',
      type: 'get',
      data: {"action": "list"},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          var dest="";
          var temp="<table id=\"ordersListingTable\" data-order='[[ 0, \"asc\" ]]' data-page-length='25' class=\"table table-condensed\"><thead><tr><th>ID</th><th><span class=\"fr-inline\">Société</span><span class=\"en-inline\">Company</span><span class=\"nl-inline\">Company</span></th><th><span class=\"fr-inline\">Utilisateur</span><span class=\"en-inline\">User</span><span class=\"nl-inline\">User</span></th><th><span class=\"fr-inline\">Vélo</span><span class=\"en-inline\">Bike</span><span class=\"nl-inline\">Bike</span></th><th><span class=\"fr-inline\">Taille</span><span class=\"en-inline\">Size</span><span class=\"nl-inline\">Size</span></th><th><span class=\"fr-inline\">Status</span><span class=\"en-inline\">Status</span><span class=\"nl-inline\">Status</span></th><th>Test ?</th><th>Date Livraison</th><th>Montant</th></tr></thead><tbody>";
          dest=dest.concat(temp);
          var i=0;

          while (i < response.ordersNumber){
            if(response.order[i].testBoolean){
                if(response.order[i].testStatus=="done"){
                    var test = "Done";
                }else if(response.order[i].testStatus=="cancelled"){
                    var test = "Cancelled";
                }else{
                    if(response.order[i].testDate){
                        var test = response.order[i].testDate.shortDate();
                    }else{
                        var test = "TBC";
                    }
                }
            }else{
                    var test = "N";
            }
              if(response.order[i].estimatedDeliveryDate == null){
                  var estimatedDeliveryDate = "TBC";
              }else{
                  var estimatedDeliveryDate = response.order[i].estimatedDeliveryDate.shortDate();
              }
            temp="<tr><td><a href=\"#\" class=\"updateCommand\" data-target=\"#orderManager\" data-toggle=\"modal\" name=\""+response.order[i].ID+"\" data-company=\""+response.order[i].companyID+"\">"+response.order[i].ID+"</td><td><a href=\"#\" class=\"internalReferenceCompany\" data-target=\"#companyDetails\" data-toggle=\"modal\" name=\""+response.order[i].companyID+"\">"+response.order[i].companyName+"</a></td><td>"+response.order[i].user+"</td><td>"+response.order[i].brand+" - "+response.order[i].model+"</td><td>"+response.order[i].size+"</td><td>"+response.order[i].status+"</td><td>"+test+"</td><td>"+estimatedDeliveryDate+"</td><td>"+response.order[i].leasingPrice+" €/mois</td></tr>";
            dest=dest.concat(temp);
            i++;

          }
          var temp="</tobdy></table>";
          dest=dest.concat(temp);
          document.getElementById('ordersListingSpan').innerHTML = dest;

          displayLanguage();

            $('#ordersListingTable thead tr').clone(true).appendTo('#test thead');

            $('#ordersListingTable thead tr:eq(1) th').each(function(i){
                var title=$(this).text();
                $(this).html('<input type="text" placeholder="Search" />');

                $('input', this).on('keyup change', function(){
                    if (table.column(i).search() !== this.value){
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table=$('#ordersListingTable').DataTable({
            });

        var classname = document.getElementsByClassName(
            "internalReferenceCompany"
          );
          for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener(
              "click",
              function () {
                get_company_details(this.name, email, true);
              },
              false
            );
          }


        }
      }
    })

}

$('body').on('click', '.updateCommand',function(){
  $("#widget-order-form select[name=name]").find("option")
  .remove()
  .end();
  $("#widget-order-form select[name=company]").val($(this).data('company'));
  retrieve_command(this.name);
  $("#widget-order-form div[name=ID]").show();
  $("#widget-order-form select[name=name]").show();
  $("#widget-order-form input[name=name]").hide();
  $(".orderManagementTitle").html("Gestion de la commande client");
  $("#widget-order-form input[name=action]").val("update");
});

$('body').on('click', '.addOrder',function(){
  $('#widget-order-form')[0].reset();
  $("#widget-order-form select[name=name]").find("option")
  .remove()
  .end();
  list_bikes();
  $("#widget-order-form div[name=ID]").hide();
  $("#widget-order-form input[name=name]").hide();
  $("#widget-order-form select[name=name]").show();
  $(".orderManagementTitle").html("Ajouter une commande");
  $("#widget-order-form input[name=action]").val("add");
});

function list_bikes(){
  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"action": "list"},
    success: function(response){
          if(response.response == 'error') {
            console.log(response.message);
          }
          if(response.response == 'success'){
              $('#widget-order-form select[name=portfolioID]').empty();
              var i=0;
              while(i<response.bikeNumber){
                  $('#widget-order-form select[name=portfolioID]').append('<option value='+response.bike[i].ID+'>'+response.bike[i].ID+' - '+response.bike[i].brand+' '+response.bike[i].model+' - '+response.bike[i].frameType+'<br>');
                  i++;
              }
          }
    }
  });
}

function retrieve_command(ID){
  list_bikes();
  $.ajax({
    url: 'apis/Kameo/companies/companies.php',
    type: 'get',
    data: {"action":"retrieve", "ID": $('#widget-order-form select[name=company]').val()},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        for (var i = 0; i < response.userNumber; i++){
          $("#widget-order-form select[name=name]").append('<option id= "' + response.user[i].email +  '_' + response.user[i].firstName + '_' + response.user[i].phone +'" value="'+response.user[i].name+ '">' + response.user[i].name + "<br>");
        }
      }
    }
  }).done(function(response){
    $.ajax({
      url: 'apis/Kameo/orders_management.php',
      type: 'get',
      data: {"action": "retrieve", "ID": ID, "email": email},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          $('#widget-order-form input[name=ID]').val(ID);
          $('#widget-order-form input[name=leasingPrice]').val(response.order.leasingPrice);
          $('#widget-order-form select[name=portfolioID]').val(response.order.portfolioID).attr('disabled', false);
          $('#widget-order-form input[name=brand]').val(response.order.brand).attr('disabled', false);
          $('#widget-order-form input[name=model]').val(response.order.model).attr('disabled', false);
          $('#widget-order-form select[name=frameType]').val(response.order.frameType).attr('disabled', false);
          $('#widget-order-form select[name=size]').val(response.order.size).attr('disabled', false);
          $('#widget-order-form select[name=status]').val(response.order.status).attr('disabled', false);
          $('#widget-order-form select[name=name]').val(response.order.name).attr('disabled', false);
          $('#widget-order-form input[name=firstName]').val(response.order.firstname).attr('disabled', false);
          $('#widget-order-form input[name=mail]').val(response.order.email).attr('disabled', false);
          $('#widget-order-form input[name=phone]').val(response.order.phone).attr('disabled', false);

          if(response.order.testBoolean=="Y"){
              $('#widget-order-form input[name=testBoolean]').prop('checked', true);
              $('#widget-order-form .testAddress').removeClass("hidden");
              $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
              $('#widget-order-form .testDate').removeClass("hidden");
              $('#widget-order-form input[name=testDate]').val(response.order.testDate);
              $('#widget-order-form .testStatus').removeClass("hidden");
              $('#widget-order-form select[name=testStatus]').val(response.order.testStatus);
              $('#widget-order-form .testResult').removeClass("hidden");
              $('#widget-order-form textarea[name=testResult]').val(response.order.testResult);
          }else{
              $('#widget-order-form input[name=testBoolean]').prop('checked', false);
              $('#widget-order-form .testAddress').addClass("hidden");
              $('#widget-order-form input[name=testAddress]').val("");
              $('#widget-order-form .testDate').addClass("hidden");
              $('#widget-order-form input[name=testDate]').val("");
              $('#widget-order-form .testStatus').addClass("hidden");
              $('#widget-order-form select[name=testStatus]').val("");
              $('#widget-order-form .testResult').addClass("hidden");
              $('#widget-order-form textarea[name=testResult]').val("");
          }
          $('#widget-order-form input[name=testDate]').val(response.order.testDate);
          $('#widget-order-form input[name=testAddress]').val(response.order.testAddress);
          $('#widget-order-form input[name=deliveryAddress]').val(response.order.deliveryAddress);
          $('#widget-order-form input[name=emailUser]').val(response.order.email);
          $('#widget-order-form .commandBike').attr('src', "images_bikes/"+response.order.brand.toLowerCase().replace(/ /g, '-')+"_"+response.order.model.toLowerCase().replace(/ /g, '-')+"_"+response.order.frameType.toLowerCase()+".jpg");

          if(response.order.estimatedDeliveryDate != null){
              $('#widget-order-form input[name=deliveryDate]').val(response.order.estimatedDeliveryDate);
          }
        }
      }
    });
  })
}

$('body').on('change', '#widget-order-form input[name=testBoolean]',function(){
  if($('#widget-order-form input[name=testBoolean]').is(':checked')){
      $('#widget-order-form .testAddress').removeClass("hidden");
      $('#widget-order-form .testDate').removeClass("hidden");
      $('#widget-order-form .testStatus').removeClass("hidden");
      $('#widget-order-form .testResult').removeClass("hidden");
  }else{
      $('#widget-order-form .testAddress').addClass("hidden");
      $('#widget-order-form .testDate').addClass("hidden");
      $('#widget-order-form .testStatus').addClass("hidden");
      $('#widget-order-form .testResult').addClass("hidden");
  }
});

$('body').on('change', '#widget-order-form select[name=portfolioID]',function(){
  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"action": "retrieve", "ID": $('#widget-order-form select[name=portfolioID]').val()},
    success: function(response){
          if(response.response == 'error') {
            console.log(response.message);
          }
          if(response.response == 'success'){
              $('#widget-order-form input[name=brand]').val(response.brand);
              $('#widget-order-form input[name=model]').val(response.model);
              $('#widget-order-form select[name=frameType]').val(response.frameType);
              $('#widget-order-form .commandBike').attr('src', "images_bikes/"+response.brand.toLowerCase().replace(/ /g, '-    ')+"_"+response.model.toLowerCase().replace(/ /g, '-')+"_"+response.frameType.toLowerCase()+".jpg");

          }
    }
  });

});

$('body').on('change', '#widget-order-form select[name=company]',function(){
  $.ajax({
    url: 'apis/Kameo/companies/companies.php',
    type: 'get',
    data: {"action":"retrieve", "ID": $('#widget-order-form select[name=company]').val()},
    success: function(response){
      if(response.response == 'error') {
        console.log(response.message);
      }
      if(response.response == 'success'){
        $('#widget-order-form input[name=mail]').val("");
        $('#widget-order-form input[name=firstName]').val("");
        $("#widget-order-form select[name=name]").find("option")
        .remove()
        .end();

        for (var i = 0; i < response.userNumber; i++){
          $("#widget-order-form select[name=name]").append('<option id= "' + response.user[i].email +  '_' + response.user[i].firstName + '_' + response.user[i].phone +'" value="'+response.user[i].name+ '">' + response.user[i].name + "<br>");
        }

        if($("#widget-order-form select[name=name]").has('option').length > 0){
          $("#widget-order-form input[name=mail]").val(response.user[0].email);
          $("#widget-order-form input[name=firstName]").val(response.user[0].firstName);
        }
      }
    }
  });
});

$('body').on('change', '#widget-order-form select[name=name]',function(){
  var value = $(this).children("option:selected").attr('id').split("_");
  var user_mail = value[0];
  var user_fn = value[1];
  var user_phone = value[2];
  $('#widget-order-form input[name=mail]').val(user_mail);
  $('#widget-order-form input[name=firstName]').val(user_fn);
  $('#widget-order-form input[name=phone]').val(user_phone);
});