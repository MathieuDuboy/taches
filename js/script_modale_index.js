$(function() {
  //modale_projets
  $("#add_projet").click(function() {
    $("#modale_projets").modal("show");
  })
  // add le projet
  $("#button_add_projet").click(function() {
    // serialize mon_projet
    event.preventDefault();
    var serial = $("#mon_projet").serialize();
    console.log(serial);

    $.ajax({
      url: "https://mon-chatbot.com/taches/php/ajouter_projet.php", //this is the submit URL
      type: 'GET', //or POST
      data: $("#mon_projet").serialize(),
      success: function(data) {
        console.log(data);
        $("#modale_projets").modal("hide");
        window.location.reload();
      }
    });

  })

  $( "#date_debut" ).datepicker({ dateFormat: 'dd/mm/yy' });
  $( "#date_installation" ).datepicker({ dateFormat: 'dd/mm/yy' });
  $('#manager').typeahead({
      source:  function (query, process) {
        return $.get('https://mon-chatbot.com/taches/php/recherche_collab_users.php?type=manager', { client : query }, function (data) {
          //	console.log(data);
            data = $.parseJSON(data);
              return process(data);
          });
      },
      updater: function(item){
      //  console.log(item);
        // split et ajoute dans l'input hidden
        var val = item.split(" ##Id::");
        var val2 = item.split(" ##cid:");
        var val3 = item.split(" - ");
        var val4 = item.split("::");
        var val5 = val4[1].split(" - ");
        $('#manager').val(val[0]);
        $('#id_manager').val(val5[0]);
      //  $('#id_client_eko').val(val2[1]);
        var nom_email = val[0];
        var tab_nom_email = nom_email.split(" : ");
        var reste = tab_nom_email[0];
        var tab_nom_email2 = reste.split(" / ");
        //  $('#nom_client_eko').val(tab_nom_email2[0]);
        //  $('#email_client_eko').val(tab_nom_email[1]);
        //console.log(val[1]);
        return val[0];
      }
  })
  $('#client').typeahead({
      source:  function (query, process) {
        return $.get('https://mon-chatbot.com/taches/php/recherche_collab_users.php?type=client', { client : query }, function (data) {
          //	console.log(data);
            data = $.parseJSON(data);
              return process(data);
          });
      },
      updater: function(item){
      //  console.log(item);
        // split et ajoute dans l'input hidden
        var val = item.split(" ##Id::");
        var val2 = item.split(" ##cid:");
        var val3 = item.split(" - ");
        var val4 = item.split("::");
        var val5 = val4[1].split(" - ");
        $('#client').val(val[0]);
        $('#id_client').val(val5[0]);
      //  $('#id_client_eko').val(val2[1]);
        var nom_email = val[0];
        var tab_nom_email = nom_email.split(" : ");
        var reste = tab_nom_email[0];
        var tab_nom_email2 = reste.split(" / ");
        //  $('#nom_client_eko').val(tab_nom_email2[0]);
        //  $('#email_client_eko').val(tab_nom_email[1]);
        //console.log(val[1]);
        return val[0];
      }
  })

  $('#tech').typeahead({
      source:  function (query, process) {
        return $.get('https://mon-chatbot.com/taches/php/recherche_collab_users.php?type=tech', { client : query }, function (data) {
          //	console.log(data);
            data = $.parseJSON(data);
              return process(data);
          });
      },
      updater: function(item){
      //  console.log(item);
        // split et ajoute dans l'input hidden
        var val = item.split(" ##Id::");
        var val2 = item.split(" ##cid:");
        var val3 = item.split(" - ");
        var val4 = item.split("::");
        var val5 = val4[1].split(" - ");
        $('#tech').val(val[0]);
        $('#id_tech').val(val5[0]);
      //  $('#id_client_eko').val(val2[1]);
        var nom_email = val[0];
        var tab_nom_email = nom_email.split(" : ");
        var reste = tab_nom_email[0];
        var tab_nom_email2 = reste.split(" / ");
        //  $('#nom_client_eko').val(tab_nom_email2[0]);
        //  $('#email_client_eko').val(tab_nom_email[1]);
        //console.log(val[1]);
        return val[0];
      }
  })

  //modale_taches
  $("#add_tache_button").click(function() {
    $("#modale_taches").modal("show");
  });
  var max_fields = 10;
  var wrapper = $(".input_fields_wrap");
  var add_button = $(".add_field_button");
  var x = 1;
  $("#liste_sous_taches").hide();
  $("#div_ajout_simple").hide();
  $("#div_ajout_multiple").hide();
  $("#div_modification").hide();
  $('#add_sous_tache').hover(function() {
    $(this).css('cursor', 'pointer');
  });
  $('#refresh').click(function() {
    window.location.reload();
  });
  $('#sous_tache').focus(function() {
    $('#valider').prop('disabled', false);
  });
  $('#tache').change(function() {
    var valeur = $(this).val();
    $('#valider').prop('disabled', false);
    if (valeur == 'add_simple') {
      $("#step1").hide();
      $("#div_ajout_simple").show();
      $("#div_ajout_multiple").hide();
      $("#div_modification").hide();
      $("#modification").val("simple");
    } else if (valeur == 'add_multiple') {
      $("#step1").hide();
      $("#modification").val("multiple");
      x = 1;
      $("#liste_sous_taches").show();
      $("#div_ajout_multiple").show();
      add_input();
      $("#id_tache").val(valeur);
      $("#div_ajout_simple").hide();
      $("#div_modification").hide();
    } else {
      $("#step1").hide();
      $("#modification").val("modification");
      $("#div_ajout_simple").hide();
      $("#div_ajout_multiple").hide();
      $("#div_modification").show();
      $("#id_tache").val(valeur);
      var nom_tache = $("#tache option:selected").text();
      console.log(nom_tache)
      $.ajax({
        url: "https://mon-chatbot.com/taches/php/get_infos_tache.php",
        type: 'GET',
        data: {
          id_tache: valeur
        },
        success: function(data) {
          if(data == '')  {
            // ne pas afficher La liste des sous-taches
            $("#liste_sous_taches_modification").hide();
          }else {
            $(".input_fields_wrap_modification").append(data);
            $("#tache_de_base_modification").val(nom_tache);
          }
        },
        error: function(err) {
          console.log(err);
        }
      });
    }
  });
  $(add_button).click(function(e) {
    e.preventDefault();
    add_input();
  });

  function add_input() {
    if (x < max_fields) {
      $(wrapper).append('<div id="'+x+'" class="form-group"><label>N°' + x +
        ' - A déplacer</label><p><input class="form-control" type="text" name="mes_taches[]" placeholder="Exemple : Avertir le Client"/><span class="remove_field" style="float:right;text-align:right"><button type="button" class="btn btn-info btn-sm">- Retirer</button></span></p></div>'
      ); //add input box
      x++;
    }
  }

  $(wrapper).on("click", ".remove_field", function(e) {
    e.preventDefault();
    var ancien = x-1;
    $("#"+ancien).remove();
    x--;
  })
  $(".input_fields_wrap").sortable();
  $(".input_fields_wrap_modification").sortable();
  $("#valider").click(function(event) {
    console.log("ok")
    event.preventDefault();
    var serial = $("#mon_formulaire").serialize();
    console.log(serial);
    // faire les verifs sur les inputs ...
    var type_de_modif = $("#modification").val();
    $.ajax({
      url: "https://mon-chatbot.com/taches/php/ajouter_tache.php", //this is the submit URL
      type: 'GET', //or POST
      data: $("#mon_formulaire").serialize(),
      success: function(data) {
        console.log(data);
        $("#modale_taches").modal("hide");
        window.location.reload();
      }
    });
  })
  $("#delete_tache_and_sous_taches").click(function() {
    var id_tache = $("#id_tache").val();
    swal({
      title: 'Etes-vous certain ?',
      text: "Cette action est irréversible",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if (result.value) {
        console.log("delete ok");
        $.ajax({
          url: "https://mon-chatbot.com/taches/php/delete_tache.php", //this is the submit URL
          type: 'GET', //or POST
          data: {
            id_tache: id_tache
          },
          success: function(data) {
            console.log(data);
            $("#modale_taches").modal("hide");
            window.location.reload();
          }
        });
      }
    })
  })
});
