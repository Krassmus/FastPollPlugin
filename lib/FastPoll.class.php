<?php


class FastPoll extends SimpleORMap {

    protected static function configure($config = array())
    {
        $config['db_table'] = 'fastpoll';
        parent::configure($config);
    }

    public function getUsers()
    {
        $statement = DBManager::get()->prepare("
            SELECT fastpoll_users.user_id
            FROM fastpoll_users
                INNER JOIN auth_user_md5 ON (auth_user_md5.user_id = fastpoll_users.user_id)
            WHERE poll_id = :poll_id
            ORDER BY auth_user_md5.Nachname ASC, auth_user_md5.Vorname ASC
        ");
        $statement->execute(array('poll_id' => $this->getId()));
        return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function add($user_id)
    {
        if (in_array($user_id, $this->getUsers())) {
            return false;
        }
        $statement = DBManager::get()->prepare("
            INSERT IGNORE INTO fastpoll_users
            SET poll_id = :poll_id,
                user_id = :user_id,
                mkdate = UNIX_TIMESTAMP()
        ");
        return $statement->execute(array(
            'poll_id' => $this->getId(),
            'user_id' => $user_id
        ));
    }

    public function delete() {
        $statement = DBManager::get()->prepare("
            DELETE FROM fastpoll_users
            WHERE poll_id = :poll_id
        ");
        $statement->execute(array(
            'poll_id' => $this->getId()
        ));
        parent::delete();
    }



}