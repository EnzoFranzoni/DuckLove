<?php

class Pagination {

    /**
     * Affiche la pagination à l'endroit où cette fonction est appelée
     * @param string $url L'URL ou nom de la page appelant la fonction, ex: 'index.php' ou 'http://example.com/'
     * @param string $link La nom du paramètre pour la page affichée dans l'URL, ex: '?page=' ou '?&p='
     * @param int $total Le nombre total de pages
     * @param int $current Le numéro de la page courante
     * @param int $count Le nombre de lignes par page
     * @param int $adj (facultatif) Le nombre de pages affichées de chaque côté de la page courante (défaut : 3)
     * @return La chaîne de caractères permettant d'afficher la pagination
     */
    public static function paginate($url, $link, $total, $current, $count, $adj = 3) {
        // Initialisation des variables
        $prev = $current - 1; // numéro de la page précédente
        $next = $current + 1; // numéro de la page suivante
        $penultimate = $total - 1; // numéro de l'avant-dernière page
        $pagination = ''; // variable retour de la fonction : vide tant qu'il n'y a pas au moins 2 pages

        if ($total > 1) {
            // Remplissage de la chaîne de caractères à retourner
            $pagination .= "<ul class=\"pagination\">";

            /* =================================
             *  Affichage du bouton [début]
             * ================================= */
            if ($current > 1) {
                // la page courante est supérieure à 1, le bouton renvoie sur la page 1
                $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}?count={$count}\" title=\"" . PAGINATION_FIRST . "\">" . PAGINATION_FIRST . "</a></li>";
            } else {
                // dans tous les autres, cas la page est 1 : désactivation du bouton [début]
                $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">" . PAGINATION_FIRST . "</a></li>";
            }

            /* =================================
             *  Affichage du bouton [précédent]
             * ================================= */
            if ($current == 2) {
                // la page courante est la 2, le bouton renvoie donc sur la page 1, remarquez qu'il est inutile de mettre $url{$link}1
                $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}?count={$count}\" title=\"" . PAGINATION_PREVIOUS . "\">" . PAGINATION_PREVIOUS . "</a></li>";
            } elseif ($current > 2) {
                // la page courante est supérieure à 2, le bouton renvoie sur la page dont le numéro est immédiatement inférieur
                $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$prev}&count={$count}\" title=\"" . PAGINATION_PREVIOUS . "\">" . PAGINATION_PREVIOUS . "</a></li>";
            } else {
                // dans tous les autres, cas la page est 1 : désactivation du bouton [précédent]
                $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">" . PAGINATION_PREVIOUS . "</a></li>";
            }

            /**
             * Début affichage des pages, l'exemple reprend le cas de 3 numéros de pages adjacents (par défaut) de chaque côté du numéro courant
             * - CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
             * - CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 numéros de pages au total
             */
            /* ===============================================
             *  CAS 1 : au plus 12 pages -> pas de troncature
             * =============================================== */
            if ($total < 7 + ($adj * 2)) {
                // Ajout de la page 1 : on la traite en dehors de la boucle pour n'avoir que index.php au lieu de index.php?p=1 et ainsi éviter le duplicate content
                $pagination .= ($current == 1) ? "<li class=\"page-item active\"><span class=\"page-link\">1 <span class=\"sr-only\">(current)</span></span></li>" : "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}?count={$count}\">1</a></li>"; // Opérateur ternaire : (condition) ? 'valeur si vrai' : 'valeur si fausse'
                // Pour les pages restantes on utilise itère
                for ($i = 2; $i <= $total; $i++) {
                    if ($i == $current) {
                        // Le numéro de la page courante est mis en évidence (cf. CSS)
                        $pagination .= "<li class=\"page-item active\"><span class=\"page-link\">{$i} <span class=\"sr-only\">(current)</span></span></li>";
                    } else {
                        // Les autres sont affichées normalement
                        $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$i}&count={$count}\">{$i}</a></li>";
                    }
                }
            }
            /* =========================================
             *  CAS 2 : au moins 13 pages -> troncature
             * ========================================= */ else {
                /**
                 * Troncature 1 : on se situe dans la partie proche des premières pages, on tronque donc la fin de la pagination.
                 * l'affichage sera de neuf numéros de pages à gauche ... deux à droite
                 * 1 2 3 4 5 6 7 8 9 … 16 17
                 */
                if ($current < 2 + ($adj * 2)) {
                    // Affichage du numéro de page 1
                    $pagination .= ($current == 1) ? "<li class=\"page-item active\"><span class=\"page-link\">1 <span class=\"sr-only\">(current)</span></span></li>" : "<li class=\"page-item\"><a href=\"{$url}?count={$count}\">1</a></li>";

                    // puis des huit autres suivants
                    for ($i = 2; $i < 4 + ($adj * 2); $i++) {
                        if ($i == $current) {
                            $pagination .= "<li class=\"page-item active\"><span class=\"page-link\">{$i} <span class=\"sr-only\">(current)</span></span></li>";
                        } else {
                            $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$i}&count={$count}\">{$i}</a></li>";
                        }
                    }

                    // ... pour marquer la troncature
                    $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">&hellip;</a></li>";

                    // et enfin les deux derniers numéros
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$penultimate}&count={$count}\">{$penultimate}</a></li>";
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$total}&count={$count}\">{$total}</a></li>";
                }
                /**
                 * Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le début et la fin de la pagination.
                 * l'affichage sera deux numéros de pages à gauche ... sept au centre ... deux à droite
                 * 1 2 … 5 6 7 8 9 10 11 … 16 17
                 */ elseif ((($adj * 2) + 1 < $current) && ($current < $total - ($adj * 2))) {
                    // Affichage des numéros 1 et 2
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}?count={$count}\">1</a></li>";
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}2&count={$count}\">2</a></li>";
                    $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">&hellip;</a></li>";

                    // les pages du milieu : les trois précédant la page courante, la page courante, puis les trois lui succédant
                    for ($i = $current - $adj; $i <= $current + $adj; $i++) {
                        if ($i == $current) {
                            $pagination .= "<li class=\"page-item active\"><span class=\"page-link\">{$i} <span class=\"sr-only\">(current)</span></span></li>";
                        } else {
                            $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$i}&count={$count}\">{$i}</a></li>";
                        }
                    }

                    $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">&hellip;</a></li>";

                    // et les deux derniers numéros
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$penultimate}&count={$count}\">{$penultimate}</a></li>";
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$total}&count={$count}\">{$total}</a></li>";
                }
                /**
                 * Troncature 3 : on se situe dans la partie de droite, on tronque donc le début de la pagination.
                 * l'affichage sera deux numéros de pages à gauche ... neuf à droite
                 * 1 2 … 9 10 11 12 13 14 15 16 17
                 */ else {
                    // Affichage des numéros 1 et 2
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}?count={$count}\">1</a></li>";
                    $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}2&count={$count}\">2</a></li>";
                    $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">&hellip;</a></li>";

                    // puis des neuf derniers numéros
                    for ($i = $total - (2 + ($adj * 2)); $i <= $total; $i++) {
                        if ($i == $current) {
                            $pagination .= "<li class=\"page-item active\"><span>{$i} <span class=\"sr-only\">(current)</span></span></li>";
                        } else {
                            $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$i}&count={$count}\">{$i}</a></li>";
                        }
                    }
                }
            }

            /* ===============================
             *  Affichage des boutons [suivant] et [fin]
             * =============================== */
            if ($current == $total) {
                $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">" . PAGINATION_NEXT . "</a></li>";
                $pagination .= "<li class=\"page-item disabled\"><a class=\"page-link\" href=\"#\">" . PAGINATION_LAST . "</a></li>";
            } else {
                $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$next}&count={$count}\" title=\"" . PAGINATION_NEXT . "\">" . PAGINATION_NEXT . "</a></li>";
                $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$url}{$link}{$total}&count={$count}\" title=\"" . PAGINATION_LAST . "\">" . PAGINATION_LAST . "</span></a></li>";
            }

            // Fermeture de la <ul> d'affichage
            $pagination .= "</ul>";
        }

        return ($pagination);
    }

    /**
     * Affiche le compteur de pages à l'endroit où cette fonction est appelée
     * @param int $total Le nombre total de pages
     * @param int $current Le numéro de la page courante
     * @param int $row Le nombre de lignes totales affichées sur toutes les pages
     * @return La chaîne de caractères permettant d'afficher le compteur de pages
     */
    public static function count($total, $current, $row) {
        // Initialisation des variables
        $counter = ''; // variable retour de la fonction : vide tant qu'il n'y a pas au moins 2 pages

        if ($total > 1) {
            // Remplissage de la chaîne de caractères à retourner pour le compteur de pages
            $counter .= sprintf(PAGINATION_PAGE_COUNTER, $current, $total);
        }

        // Remplissage de la chaîne de caractères à retourner pour le nombre de lignes totales
        $counter .= sprintf(PAGINATION_ROW_COUNTER, $row);

        return ($counter);
    }

}
