<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <link href="css/style.css" rel="stylesheet">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
   <title>Création / Modifications / Ajouts Tickets Deskero</title>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js">
   </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

</head>
<body>
  <?php
    $id_client_eko = "6870535";
   ?>
    <div class="row" style="margin:30px">
      <div class="col" style="padding:30px">
        <button type="button" style="margin-bottom:25px" id="add_ticket" data-toggle="modal" data-target="#add_ticket_modal" data-id_client_eko="<?php echo $id_client_eko; ?>" data-remote="false" class="btn btn-primary">Ajouter Ticket</button>
        <ul class="list-group">
        <?php
          $client_id  = "5ac25ffce4b0aae104e4df15";
          //your Authorized API Token (см. http://www.deskero.com/en/documentation/api/configuration)
          $auth_token = "NTMwZDk4OTBlNGIwYWU2MjM4NGI0ZGQ0OmJkOWE5YTU1LTZjMGItNDY5YS04MTA1LTQ1ZDM0ZTYyNGJlZg==";
          $chp        = curl_init("https://api.deskero.com/oauth/token?grant_type=client_credentials");
          $headers    = array(
              "Accept: application/json",
              "Authorization: Basic " . $auth_token
          );
          curl_setopt($chp, CURLOPT_HEADER, 0);
          curl_setopt($chp, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($chp, CURLOPT_HTTPHEADER, $headers);
          $result = curl_exec($chp);
          $info   = curl_getinfo($chp);
          if (curl_errno($chp)) {
              echo 'error:' . curl_error($chp);
          } else if (!in_array($info['http_code'], array(
                  200,
                  201,
                  204
              ))) {
              echo 'error: response code ' . $info['http_code'];
          }
          curl_close($chp);
          $json         = json_decode($result);
          $access_token = $json->access_token;
          $chp          = curl_init("https://api.deskero.com/ticket/list?limit=1000");
          $headers      = array(
              "Accept: application/json",
              "Authorization: Bearer " . $access_token,
              "clientId: " . $client_id
          );
          curl_setopt($chp, CURLOPT_HEADER, 0);
          curl_setopt($chp, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($chp, CURLOPT_HTTPHEADER, $headers);
          $result2 = curl_exec($chp);
          $info    = curl_getinfo($chp);
          if (curl_errno($chp)) {
              echo 'error:' . curl_error($chp);
          } else if (!in_array($info['http_code'], array(
                  200,
                  201,
                  204
              ))) {
              echo 'error: response code ' . $info['http_code'];
          }
          curl_close($chp);
          $sHTML = '';
          $json2 = json_decode($result2, true);


          foreach ($json2['ticket']['records'] as $item) {
            if($item['externalId1'] == $id_client_eko) {

            //  echo '<pre>'; print_r($item); echo '</pre>';
              $code     = $item['number'];
              $subject  = $item['subject'];
              $desc     = $item['description'];
              $status   = $item['status']['status'];
              $opener   = $row['collab_pname'] . ' ' . $row['collab_name'];
              $assignee = $item['assignedTo']['name'];
              $openedBy = $item['openedBy']['id'];
              $type     = $item['type']['id'];

              $datemaj  = '';
              if ($status == "solved" OR $status == "closed") {
                  $stat = '<font style="color:green;"><span style="font-size : 18px;" class="glyphicon glyphicon-ok-sign"></span> Résolue</font>';
              } else if ($status == "onhold") {
                  $stat = '<font style="color:red;"><span style="font-size : 18px;" class="glyphicon glyphicon-time"></span> Traitement retardé</font>';
              } else if ($assignee == "") {
                  $stat = '<font style="color:black;"><span style="font-size : 18px;" class="glyphicon glyphicon-hourglass"></span> En attente de prise en charge</font>';
              } else if ($status == "opened") {
                  $stat = '<font style="color:blue;"><span style="font-size : 18px;" class="glyphicon glyphicon-refresh"></span> En cours</font>';
              }
              $sHTML .= '<li id="'.$item["id"].'" list-group-item"><a href="details.php?id='.$item["id"].'">' . $code . ' : ' . $subject . ' / ' . $stat . '</a><span style="float:right"><span class="reply_ticket" id="'.$item["id"].'">Répondre</span> /
              <span data-openedby="'.$openedBy.'" data-description="'.$desc.'" data-subject="'.$subject.'" data-type="'.$type.'" class="modify_ticket" id="'.$item["id"].'">Modifier Statut</span></span></li>';
              # code...
            }
          }
          echo $sHTML;
          ?>
     </ul>
      </div>
    </div>
    <div class="row" style="margin:30px">
      <div class="col" style="padding:30px">
      <h3>Tous ceux qui n'ont pas d'agent affecté</h3>
      <ul class="list-group">
        <?php
          $sHTML2 = "";
          foreach ($json2['ticket']['records'] as $item2) {
            if(count($item2['assignedTo']) == 0) {

            //  echo '<pre>'; print_r($item); echo '</pre>';
              $code     = $item2['number'];
              $subject  = $item2['subject'];
              $desc     = $item2['description'];
              $status   = $item2['status']['status'];
              $assignee = $item2['assignedTo']['name'];
              $openedBy = $item2['openedBy']['id'];
              $type     = $item2['type']['id'];

              $datemaj  = '';
              if ($status == "solved" OR $status == "closed") {
                  $stat = '<font style="color:green;"><span style="font-size : 18px;" class="glyphicon glyphicon-ok-sign"></span> Résolue</font>';
              } else if ($status == "onhold") {
                  $stat = '<font style="color:red;"><span style="font-size : 18px;" class="glyphicon glyphicon-time"></span> Traitement retardé</font>';
              } else if ($assignee == "") {
                  $stat = '<font style="color:black;"><span style="font-size : 18px;" class="glyphicon glyphicon-hourglass"></span> En attente de prise en charge</font>';
              } else if ($status == "opened") {
                  $stat = '<font style="color:blue;"><span style="font-size : 18px;" class="glyphicon glyphicon-refresh"></span> En cours</font>';
              }

              if($assignee == "") {
                $sHTML2 .= '<li id="'.$item2["id"].'" list-group-item"><a href="details.php?id='.$item2["id"].'">' . $code . ' : ' . $subject . ' / ' . $stat . ' / '.count($item2['externalId1']).'</a>
                <span style="float:right"><span data-openedby="'.$openedBy.'" data-description="'.strip_tags($desc).'" data-subject="'.$subject.'" data-type="'.$type.'" class="affecter_ticket" id="'.$item2["id"].'">Affecter</span></span></li>';
              }else {
                $sHTML2 .= '<li id="'.$item2["id"].'" list-group-item"><a href="details.php?id='.$item2["id"].'">' . $code . ' : ' . $subject . ' / ' . $stat . ' / '.count($item2['externalId1']).'</a>
                <span style="float:right"><span class="reply_ticket" id="'.$item2["id"].'">Répondre</span> /
                <span data-openedby="'.$openedBy.'" data-description="'.strip_tags($desc).'" data-subject="'.$subject.'" data-type="'.$type.'" class="modify_ticket" id="'.$item2["id"].'">Modifier Statut</span></span></li>';

              }
              # code...
            }
          }
          echo $sHTML2;
          ?>
       </ul>
      </div>
    </div>

    <div class="row" style="margin:30px">
      <div class="col" style="padding:30px">
      <h3>Tous ceux qui n'ont pas d'externalId1</h3>
      <ul class="list-group">
        <?php
          $sHTML2 = "";
          foreach ($json2['ticket']['records'] as $item2) {
            if(count($item2['externalId1']) == 0) {

            //  echo '<pre>'; print_r($item); echo '</pre>';
              $code     = $item2['number'];
              $subject  = $item2['subject'];
              $desc     = $item2['description'];
              $status   = $item2['status']['status'];
              $assignee = $item2['assignedTo']['name'];
              $openedBy = $item2['openedBy']['id'];
              $type     = $item2['type']['id'];

              $datemaj  = '';
              if ($status == "solved" OR $status == "closed") {
                  $stat = '<font style="color:green;"><span style="font-size : 18px;" class="glyphicon glyphicon-ok-sign"></span> Résolue</font>';
              } else if ($status == "onhold") {
                  $stat = '<font style="color:red;"><span style="font-size : 18px;" class="glyphicon glyphicon-time"></span> Traitement retardé</font>';
              } else if ($assignee == "") {
                  $stat = '<font style="color:black;"><span style="font-size : 18px;" class="glyphicon glyphicon-hourglass"></span> En attente de prise en charge</font>';
              } else if ($status == "opened") {
                  $stat = '<font style="color:blue;"><span style="font-size : 18px;" class="glyphicon glyphicon-refresh"></span> En cours</font>';
              }
              $sHTML2 .= '<li id="'.$item2["id"].'" list-group-item"><a href="details.php?id='.$item2["id"].'">' . $code . ' : ' . $subject . ' / ' . $stat . ' / '.count($item2['externalId1']).'</a>
              <span style="float:right"><span class="reply_ticket" id="'.$item2["id"].'">Répondre</span> /
              <span data-openedby="'.$openedBy.'" data-description="'.strip_tags($desc).'" data-subject="'.$subject.'" data-type="'.$type.'" class="modify_ticket" id="'.$item2["id"].'">Modifier Statut</span></span></li>';
              # code...
            }
          }
          echo $sHTML2;
          ?>
       </ul>
      </div>
    </div>

      <div class="modal fade" id="add_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nouveau Ticket Client #<span id="numero_client"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="content_add_ticket">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button type="submit" id="submit_add" class="btn btn-primary">Ajouter</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="add_reply_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nouvelle réponse</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="content_reply">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          <button type="submit" id="submit_reply" class="btn btn-primary">Ajouter</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="add_modify_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier statut</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="content_modify">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" id="submit_modify" class="btn btn-primary">Mofifier</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add_affecter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Affecter le ticket</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body" id="content_affecter">

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      <button type="submit" id="submit_affecter" class="btn btn-primary">Valider</button>
    </div>
  </div>
</div>
</div>

   <script src="https://unpkg.com/popper.js@1.14.3/dist/umd/popper.min.js">
   </script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js">
   </script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.24.4/dist/sweetalert2.all.min.js">
   </script>

   <script src="js/script.js"></script></body>

 </body>
</html>
