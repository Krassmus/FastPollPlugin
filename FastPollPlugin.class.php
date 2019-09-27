<?php

require_once __DIR__."/lib/FastPoll.class.php";

class FastPollPlugin extends StudIPPlugin implements SystemPlugin {

    static public $msg = array();

    public function __construct()
    {
        parent::__construct();
        if ($this->isAdmin()) {
            if (Navigation::hasItem("/messaging")) {
                $tab = new AutoNavigation(_("Schnellumfragen"), PluginEngine::getURL($this, array(), "polls/index"));
                Navigation::addItem("/messaging/fastpoll", $tab);
            }
        }
    }

    public function isAdmin()
    {
        return $GLOBALS['perm']->have_perm('dozent')
            || RolePersistence::isAssignedRole($GLOBALS['user']->id, 'Prüfungsamt');
    }
}