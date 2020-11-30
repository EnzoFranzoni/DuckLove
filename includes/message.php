<?php

class Message {

    //--- Attributs de classe --------------------------------------------------

    /* @var $htmlSuccesses string */
    private static $htmlSuccesses = ''; // Message(s) de confirmation.

    /* @var $htmlInfo string */
    private static $htmlInfos = ''; // Message(s) informatif(s).

    /* @var $htmlError string */
    private static $htmlErrors = ''; // Message(s) d'erreur.

    /* @var $htmlWarning string */
    private static $htmlWarnings = ''; // Message(s) d'avertissement.

    /* @var $indent string */
    private static $indent = ''; // Chaîne utilisée pour indenter les blocs de messages.

    /* @var $blockTagName string */
    private static $blockTagName = 'div'; // Nom de la balise servant à regrouper les messages.

    /* @var $messageTagName string */
    private static $messageTagName = 'div'; // Nom de la balise dans laquelle les messages sont placés.

    /* @var $lock boolean */
    private static $blocking = false; // Indicateur pour enregistrer l'état bloquant causé par l'ajout d'un message.

    //--- Méthodes et fonctions de classe --------------------------------------

    /**
     * Renvoie un message formaté en HTML en utilisant les paramètres actuels.
     * @see function configure
     * @see function isBlocking
     *
     * @param string $message Message à ajouter.
     * @param boolean $blocking optional Paramètre pour pouvoir changer l'état bloquant pour l'ensemble des messages
     *
     * @return string Message formaté en HTML
     */
    private static function getHtmlMessage($message, $blocking = null) {
        if (!is_null($blocking))
            self::$blocking = $blocking;
        return self::$indent . "\t" . '<' . self::$messageTagName . '>' . $message . '</' . self::$messageTagName . '>' . "\n";
    }

    /**
     * Ajoute une confirmation à la liste des messages de confirmation.
     * Le message ajouté est formaté en HTML en utilisant les paramètres actuels.
     * @see function configure
     * @see function isBlocking
     *
     * @param string $message Message à ajouter.
     * @param boolean $blocking optional Paramètre pour pouvoir changer l'état bloquant pour l'ensemble des messages
     */
    public static function addSuccess($message, $blocking = null) {
        self::$htmlSuccesses .= self::getHtmlMessage($message, $blocking);
    }

    /**
     * Ajoute une information à la liste des messages informatifs.
     * Le message ajouté est formaté en HTML en utilisant les paramètres actuels.
     * @see function configure
     * @see function isBlocking
     *
     * @param string $message Message à ajouter.
     * @param boolean $blocking optional Paramètre pour pouvoir changer l'état bloquant pour l'ensemble des messages
     */
    public static function addInfo($message, $blocking = null) {
        self::$htmlInfos .= self::getHtmlMessage($message, $blocking);
    }

    /**
     * Ajoute une erreur à la liste des messages d'erreur.
     * Le message ajouté est formaté en HTML en utilisant les paramètres actuels.
     * @see function configure
     * @see function isBlocking
     *
     * @param string $message Message à ajouter.
     * @param boolean $blocking optional Paramètre pour pouvoir changer l'état bloquant pour l'ensemble des messages
     */
    public static function addError($message, $blocking = null) {
        self::$htmlErrors .= self::getHtmlMessage($message, $blocking);
    }

    /**
     * Ajoute un avertissement à la liste des messages d'avertissement.
     * Le message ajouté est formaté en HTML en utilisant les paramètres actuels.
     * @see function configure
     * @see function isBlocking
     *
     * @param string $message Message à ajouter.
     * @param boolean $blocking optional Paramètre pour pouvoir changer l'état bloquant pour l'ensemble des messages
     */
    public static function addWarning($message, $blocking = null) {
        self::$htmlWarnings .= self::getHtmlMessage($message, $blocking);
    }

    /**
     * Permet de savoir si aucune confirmation n'a été ajoutée à la liste des messages de confirmation.
     *
     * @return boolean Vrai si aucune confirmation n'a été ajoutée.
     */
    public static function hasNoSuccess() {
        return (self::$htmlSuccesses == '');
    }

    /**
     * Permet de savoir si aucune information n'a été ajoutée à la liste des messages d'information.
     *
     * @return boolean Vrai si aucune confirmation n'a été ajoutée.
     */
    public static function hasNoInfo() {
        return (self::$htmlInfos == '');
    }

    /**
     * Permet de savoir si aucune erreur n'a été ajoutée à la liste des messages d'erreur.
     *
     * @return boolean Vrai si aucune erreur n'a été ajoutée.
     */
    public static function hasNoError() {
        return (self::$htmlErrors == '');
    }

    /**
     * Permet de savoir si aucun avertissement n'a été ajouté à la liste des messages d'avertissement.
     *
     * @return boolean Vrai si aucune avertissement n'a été ajouté.
     */
    public static function hasNoWarning() {
        return (self::$htmlWarnings == '');
    }

    /**
     * Écrit un bloc de messages formaté en HTML en utilisant les paramètres actuels.
     * Aucun texte n'est écrit si la liste de messages est vide.
     * @see function configure
     *
     * @param string $htmlMessages Liste de messages formatés en HTML.
     * @param string $cssClass optional Valeur de la classe CSS du bloc.
     * @param string $closeLink optional Lien permettant de masquer le message.
     */
    private static function getHtmlBlock($htmlMessages, $cssClass = null, $closeLink = null) {
        $htmlBlock = '';
        if ($htmlMessages != '') {
            $class = (!is_null($cssClass)) ? ' class="alert alert-' . $cssClass . '"' : '';
            $link = (is_null($closeLink)) ? '<a class="close" data-dismiss="alert">×</a>' : '';
            $htmlBlock.= self::$indent . '<' . self::$blockTagName . $class . '>' . "\n" . $link . "\n";
            $htmlBlock.= $htmlMessages;
            $htmlBlock.= self::$indent . '</' . self::$blockTagName . '>' . "\n";
        }
        return $htmlBlock;
    }

    /**
     * Écrit le bloc de messages de confirmation en utilisant les paramètres actuels.
     * Aucun texte n'est écrit si la liste de messages est vide.
     * @see function configure
     */
    public static function echoSuccesses() {
        echo self::getHtmlBlock(self::$htmlSuccesses, 'success');
    }

    /**
     * Écrit le bloc de messages informatifs en utilisant les paramètres actuels.
     * Aucun texte n'est écrit si la liste de messages est vide.
     * @see function configure
     */
    public static function echoInfos() {
        echo self::getHtmlBlock(self::$htmlInfos, 'info');
    }

    /**
     * Écrit le bloc de messages d'erreur en utilisant les paramètres actuels.
     * Aucun texte n'est écrit si la liste de messages est vide.
     * @see function configure
     */
    public static function echoErrors() {
        echo self::getHtmlBlock(self::$htmlErrors, 'danger');
    }

    /**
     * Écrit le bloc de messages d'avertissement en utilisant les paramètres actuels.
     * Aucun texte n'est écrit si la liste de messages est vide.
     * @see function configure
     */
    public static function echoWarnings() {
        echo self::getHtmlBlock(self::$htmlWarnings, 'warning');
    }

    /**
     * Pemet de régler les paramètres pour l'affichage des messages.
     * Si vous l'utilisez, il est conseillé d'appeler cette fonction avant toute autre fonction de cette classe.
     *
     * @param string $indent optional Chaîne utilisée pour indenter les blocs de messages (1 tabulation si omis).
     * @param string $blockTagName optional Nom de la balise servant à regrouper les messages ('div' si omis).
     * @param string $messageTagName optional Nom de la balise dans laquelle les messages sont placés ('p' si omis).
     */
    public static function configure($indent = "\t", $blockTagName = 'div', $messageTagName = 'p') {
        self::$indent = $indent;
        self::$blockTagName = $blockTagName;
        self::$messageTagName = $messageTagName;
    }

    /**
     * Permet de savoir si un bloquage a été créé par l'ajout d'un message.
     * On peut s'en servir par exemple pour éviter d'afficher une partie d'une
     * page si des tests préalables ont ajouté des erreurs.
     *
     * Par défaut, aucun bloquage n'est créé.
     *
     * @return boolean Valeur indiquant si un bloquage a été créé pour l'ensemble des messages.
     */
    public static function isBlocking() {
        return self::$blocking;
    }

    /**
     * Efface tous les messages en mémoire et enlève l'éventuel bloquage qui aurait pu être créé.
     * La configuration n'est pas modifiée.
     * @see function configure
     * @see function isBlocking
     */
    public static function reset() {
        self::$htmlSuccesses = '';
        self::$htmlInfos = '';
        self::$htmlErrors = '';
        self::$htmlWarnings = '';
        self::$blocking = false;
    }

}
