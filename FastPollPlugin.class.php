<?php

require_once __DIR__."/lib/FastPoll.class.php";

class FastPollPlugin extends StudIPPlugin implements SystemPlugin {

    static public $msg = array();

    public function __construct()
    {
        parent::__construct();
        if ($this->isAdmin()) {
            if (Navigation::hasItem("/start/serienbriefe")) {
                $tab = new AutoNavigation(_("Schnellumfragen"), PluginEngine::getURL($this, array(), "polls/index"));
                Navigation::addItem("/serienbriefe/fastpoll", $tab);
                $tab = new Navigation(_("Schnellumfragen"), PluginEngine::getURL($this, array(), "polls/index"));
                Navigation::addItem("/start/serienbriefe/fastpoll", $tab);
            } else {
                $tab = new AutoNavigation(_("Schnellumfragen"), PluginEngine::getURL($this, array(), "polls/index"));
                Navigation::addItem("/tools/fastpoll", $tab);
            }
        }
    }

    public function isAdmin()
    {
        return $GLOBALS['perm']->have_perm('root')
            || RolePersistence::isAssignedRole($GLOBALS['user']->id, 'Prüfungsamt');
    }
}