<?php
$session = session();
$_SESSION['id_bdd'] = 0;
if ($connected == FALSE) {
  echo "<script type=\"text/javascript\">window.alert ('Vous devez être connecté pour accéder à cette page'); 
  window.location='/Front/index'; </script>";
} else if ($categorie != 'Administrateur') {
  echo "<script type=\"text/javascript\">window.alert ('Vous devez être administrateur pour accéder à cette page'); 
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
  </style>
  <table class="Liam">
    <tr>
      <th>Compteur</th>


      <th>Nom</th>
      <th>Prénom</th>
      <th>Email</th>
      <th>Catégorie</th>
      <th>Code de Validation</th>
      <th>Activer ?</th>

      <th>Désactiver</th>
    </tr>


    <?php
    $_SESSION['id_bdd'] = [];
    $compteur = 1;

    foreach ($dataToDisplay as $fetch20) {
    ?>
      <tr>
        <?php $_SESSION['id_bdd'][$compteur] =  intval($fetch20['id']); ?>
        <td><?php echo $compteur; ?>
        <td><?php echo $fetch20['nom']; ?></td>
        <td><?php echo $fetch20['prenom']; ?></td>
        <td><?php echo $fetch20['mail']; ?></td>
        <td><?php echo $fetch20['categorie_utilisateur']; ?></td>
        <td><?php echo $fetch20['code'] ?></td>
        <td><?php echo $fetch20['Activation3']; ?></td>
        <td>
          <?php switch ($fetch20['Activation3']) {
            case 0:
              $_SESSION['activé'][$compteur] = TRUE; ?>
              <a id="activé" name="<?php echo $compteur; ?>" href="<?php echo base_url("Back/Activation/$compteur"); ?>">Activer</a>

            <?php break;

            case 1:
              $_SESSION['activé'][$compteur] = FALSE; ?>
              <a id="désactivé" name="<?php echo $compteur; ?>" href="<?php echo base_url("Back/Activation/$compteur"); ?>">Désactiver</a>
          <?php break;
          }                                             ?>

        </td>
      </tr>

    <?php
      $compteur = $compteur + 1;
    }
    ?>
  </table>
<br><br>

  <table class="Liam"> 
      <td>Code de Validation :<?php echo $code ?></td>
    </table>


          


</body>

</html>