<h2>Poster un message</h2>
                <?php
                /**
                 * BD
                 */
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                /**
                 * Récupération de la liste des auteurs
                 */
                $listAuteurs = [];
                $laQuestionEnSql = "SELECT * FROM users";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                while ($user = $lesInformations->fetch_assoc()) {
                    $listAuteurs[$user['id']] = $user['alias'];
                }


                /**
                 * TRAITEMENT DU FORMULAIRE
                 */
                // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                $enCoursDeTraitement = isset($_POST['auteur']);
                if ($enCoursDeTraitement) {
                    // on ne fait ce qui suit que si un formulaire a été soumis.
                    // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                    // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                    //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                    // et complétez le code ci dessous en remplaçant les ???
                    $authorId = $_POST['auteur'];
                    $postContent = $_POST['message'];


                    //Etape 3 : Petite sécurité
                    // pour éviter les injections sql : https://www.w3schools.com/sql/sql_injection.asp
                    $authorId = intval($mysqli->real_escape_string($authorId));
                    $postContent = $mysqli->real_escape_string($postContent);
                    //Etape 4 : construction de la requete
                    $lInstructionSql = "INSERT INTO posts "
                        . "(id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . $authorId . ", "
                        . "'" . $postContent . "', "
                        . "NOW(),"
                        . "NULL);";
                    //echo $lInstructionSql;
                    // Etape 5 : execution
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else {
                        echo "Message posté en tant que :" . $listAuteurs[$authorId];
                    }
                }
                ?>
                <form action="wall.php?user_id=<?php echo $userId ?>" method="post">
                    <input type='hidden' name='auteur' value='<?php echo $userId?>'>
                    <dt><label for='message'>Message</label></dt>
                    <dd><textarea name='message'></textarea></dd>
                    </dl>
                    <input type='submit'>
                </form>