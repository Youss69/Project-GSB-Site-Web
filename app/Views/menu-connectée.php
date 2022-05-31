<div class="container">
		<!DOCTYPE html>
		

		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<style>
				body {
					font-family: "Lato", sans-serif;
					
				}
				#activé{
					color: green;
				}

				#désactivé{
					color: red;
				}
				a {
					text-decoration: none;
				}

				a:hover {
				
  				text-decoration: underline;
				}
				/* a:visited {
				color: #000000;
				} */
				#one:active {
				background-color: white;
				color: #000000;
				} 
				.sidenav {
					height: 100%;
					width: 200px;
					position: fixed;
					z-index: 1;
					top: 0;
					left: 0;
					background-color: #5C5696;
					overflow-x: hidden;
					padding-top: 20px;

					box-shadow: 0px 0px 24px white;
					/* box-shadow: 0px 0px 24px #5C5696; */
					text-align: center;
				}

				.sidenav a {
					padding: 6px 8px 6px 16px;
					text-decoration: none;
					font-size: 25px;
					color: #fff;
					display: block;
				}

				.sidenav a:hover {
					color: #D4D3E8;
				}

				.main {
					margin-left: 250px;
					/* Same as the width of the sidenav */
					font-size: 28px;
					/* Increased text to enable scrolling */
					padding: 0px 10px;
				}

				@media screen and (max-height: 450px) {
					.sidenav {
						padding-top: 15px;
					}

					.sidenav a {
						font-size: 18px;
					}
				}
				img {
	   height : 200px;
	
   }
			</style>
		</head>

	

			<div class="sidenav wrap">
   				<img src="https://cdn.discordapp.com/attachments/903635476836139078/939172409645334558/Sans_titre_1.png" alt="">
				<a href="/Front/deconnection">Deconnexion</a></br></br><br>
                <?php 
                #$session = session();
                #$data = array('user_idd' => $session->get("idd"), 'connected'=> $session->get("connecté")); ?>
				<a id="one"href="/Front/FicheFrais2">Fiche de Frais <?php # $data ?> </a></br><br></br>
				<a id="one"href="/Front/noteDeFrais">Historique</a>					</br><br></br>
				<?php if ($categorie != 'Utilisateur') { ?>
					
				
				<a id="one"href="/Front/comptable">Gestion Fiche Frais</a> <br><br><br>
					<?php if ($categorie == 'Administrateur') { ?>

						<a id="one"href="/Front/droit">Gestion accès</a>	
					<?php } ?>	
				<?php } ?>				
			</div>