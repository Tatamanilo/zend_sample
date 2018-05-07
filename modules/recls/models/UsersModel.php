<?php

class UsersModel
{
    /**
     * Получить пользователей для списка с автодополнением
     * @param $idRecl айди рекла
     * @param $idRole айди роли
     * @return mixed
     */
    public function getUsers($idRecl, $idRole)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_user
            WHERE
                status = "active" AND
                idRecl = ? AND
                idRole = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl, $idRole));
        return $stmt->fetchAll();
    }

}
