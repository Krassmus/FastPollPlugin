<?php

class PollsController extends PluginController
{
    protected $with_session = false;
    protected $allow_nobody = true;

    public function yes_action($poll_id) {
        $poll = new FastPoll($poll_id);
        $user_id = get_userid(Request::get("u"));
        if (!$poll->isNew() && $user_id && Request::get("u")) {
            $success = $poll->add($user_id);
            if ($success) {
                $this->messagebox = MessageBox::success(_("Teilnahme wurde erfasst. Danke für die Teilnahme!"));
            } else {
                $this->messagebox = MessageBox::info(_("Sie sind schon erfasst gewesen. Danke für die Teilnahme!"));
            }
        } else {
            if ($poll->isNew()) {
                $this->messagebox = MessageBox::error(_("Umfrage ist schon abgelaufen. Tut uns leid."));
            } else {
                $this->messagebox = MessageBox::error(_("Nutzer unbekannt."));
            }
        }
    }

    public function index_action() {
        if (!$this->plugin->isAdmin()) {
            throw new AccessDeniedException("kein Zugriff");
        }
        if (Request::isPost()) {
            if (Request::get("create")) {
                $poll = new FastPoll();
                $poll['name'] = Request::get("name");
                $poll['user_id'] = $GLOBALS['user']->id;
                $poll->store();
                FastPollPlugin::$msg[] = array("success", _("Schnellumfrage erstellt."));
            }
            if (Request::option("delete")) {
                $poll = new FastPoll(Request::option("delete"));
                if ($GLOBALS['perm']->have_perm("root") || $poll['user_id'] === $GLOBALS['user']->id) {
                    $poll->delete();
                    FastPollPlugin::$msg[] = array("success", _("Schnellumfrage wurde gelöscht."));
                }
            }
        }

        if ($GLOBALS['perm']->have_perm("root")) {
            $this->polls = FastPoll::findBySQL("1=1 ORDER BY mkdate DESC");
        } else {
            $this->polls = FastPoll::findBySQL("user_id = ? ORDER BY mkdate DESC", array($GLOBALS['user']->id));
        }
    }

    public function create_action()
    {
        if (!$this->plugin->isAdmin()) {
            throw new AccessDeniedException("kein Zugriff");
        }
    }

    public function show_users_action() {
        $poll = new FastPoll(Request::option("poll_id"));
        if (!$poll['user_id'] === $GLOBALS['user']->id && !$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException("Kein Zugriff");
        }
        PageLayout::setTitle(sprintf(_("Teilnehmende an '%s'"), $poll['name']));
        $this->users = $poll->getUsers();
    }

    public function export_users_action() {
        $poll = new FastPoll(Request::option("poll_id"));
        if (!$poll['user_id'] === $GLOBALS['user']->id && !$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException("Kein Zugriff");
        }
        $users = $poll->getUsers();
        $csv_arr = array();
        $csv_arr[] = array(
            "username",
            "Vorname",
            "Nachname",
            "Email"
        );
        foreach ($users as $user_id) {
            $email = DBManager::get()->query("SELECT Email FROM auth_user_md5 WHERE user_id = ".DBManager::get()->quote($user_id))->fetch(PDO::FETCH_COLUMN, 0);
            $csv_arr[] = array(
                get_username($user_id),
                get_vorname($user_id),
                get_nachname($user_id),
                $email
            );
        }

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"".$poll['name'].".csv\"");
        echo $this->getCSV($csv_arr);
        die();
    }

    protected function getCSV($arr) {
        $output = "";
        foreach ($arr as $line) {
            foreach ($line as $number => $cell) {
                if ($number > 0) {
                    $output .= ";";
                }
                $output .= '"'.str_replace('"', '""', $cell).'"';
            }
            $output .= "\n";
        }
        return $output;
    }

}