<?php
$session = session();
$_SESSION['id_bdd'] = 0;
if ($connected == FALSE) {
  echo "<script type=\"text/javascript\">window.alert ('Vous devez être connecté pour accéder à cette page'); 
  window.location='/Front/index'; </script>";
} else if ($categorie == 'Utilisateur') {
  echo "<script type=\"text/javascript\">window.alert ('Vous devez être comptable ou administrateur pour accéder à cette page'); 
  window.location='/Front/FicheFrais2'; </script>";
}







?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<?php include "menu-connectée.php";
include "fonction-code-validation.php";
include "config-frais.php";
?>

<body>


  <style>
    .Liam {
      margin-top: 50px;
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 70%;
      text-align: center;
      margin-left: 300px;
      box-shadow: 0px 0px 15px black;
    }

    .Liam td,
    .Liam th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    body {
      background: linear-gradient(90deg, #C7C5F4, #776BCC);
    }

    .Liam tr:nth-child(even) {
      background-color: #FFFFFF;
    }

    .Liam tr:nth-child(odd) {
      background-color: #C7C5F4;
    }


    .Liam tr:hover {
      background-color: #ddd;
    }

    .Liam th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #5D54A4;
      color: white;
    }

    .login__submit {
      background: #fff;
      font-size: 9px;
      margin-top: 13px;
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid #D4D3E8;
      text-transform: uppercase;
      font-weight: 700;
      display: flex;
      align-items: center;
      width: 95%;
      color: #4C489D;
      box-shadow: 0px 2px 2px #5C5696;
      cursor: pointer;
      transition: .2s;
      text-align: center;
      justify-content: center;
      align-items: center;
    }
  </style>

  <table class="Liam">
    <tr>
      <th>ID fiche frais</th>
      <th>Nombre de Kilomètres</th>
      <th>Indémnité kilométrique</th>
      <th>Restauration</th>
      <th>Hôtel</th>
      <th>Evènementiel</th>
      <th>ID_Salarié</th>
      <th>Statut</th>
    </tr>

    <?php
    $_SESSION['id_bdd'] = [];
    $compteur = 0;
    foreach ($dataToDisplay as $fetch20) { ?>
      <tr>
        <?php $_SESSION['id_bdd'][$compteur] =  intval($fetch20['id']); ?>
        <td><?php echo $fetch20['id']; ?></td>
        <td><?php echo $fetch20['nbr_km']; ?></td>
        <td><?php echo $fetch20['cout_km']; ?></td>
        <td><?php echo $fetch20['restauration']; ?></td>
        <td><?php echo $fetch20['hotel']; ?></td>
        <td><?php echo $fetch20['evenementiel']; ?></td>
        <td><?php echo $fetch20['id_authentification']; ?></td>
        <td> 
          <form action="<?php echo base_url("Back/fraisStatusUpdate");?>"  method="POST">
          <select class="login__submit select" name="categorie_fiche">
            <option> En cours
            <option> Fiche Remboursée
            <option> Fiche Refusée
          </select>
          <input type="hidden" name="element_id" value="<?php echo $fetch20['id']; ?>">
          <input class="login__submit" type="submit" value="Sauvegarder"></i>
          </form>
        </td>
      </tr>
    <?php

      $compteur = $compteur + 1;
    } ?>
  </table>



</body>

</html>