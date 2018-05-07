<?php

class AuthModel
{

    /**
     * Check for emailexists in system
     *
     * @param string $email
     * @return bool
     */
    public function checkLogin($email)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                COUNT(*) as cnt
            FROM
                `smp_user`
            WHERE
                login = ?
            ';
        $res = $db->fetchOne($query, array($email));

        return ($res == 1) ? true : false;
    }

    /**
     * Set new password for user with selected email
     *
     * @param string $password
     * @param string $email
     * @return
     */
    public function setNewPassword($password, $email)
    {
        $db = Zend_Registry::get('db');

        $query = '
            UPDATE
                smp_user
            SET
                pass = MD5(?)
            WHERE
                login = ?
            ';

        $stmt = $db->prepare($query);
        return $stmt->execute(array($password, $email));
    }

    /**
     * Get user info by it`s email
     *
     * @param string $email
     */
    public function getUserByLogin($email)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                *
            FROM
                smp_user
            WHERE
                login = ?
            ';

        $stmt = $db->prepare($query);
        $stmt->execute(array($email));
        return $stmt->fetch();
    }

    /**
     * Достать пользователя по его внешним идентификаторам
     *
     * @param string $idUser - идентификатор пользователя во внешней сети
     * @param string $prefix - перфикс сети, паример vk - вконтакте
     * @return array
     */
    public function getUserByExternalId($idUser, $prefix)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                u.*
            FROM
                smp_user AS u
            LEFT JOIN
                smp_userpeople AS up
                ON u.idUser = up.idUser
            WHERE
                up.externalRegistrationCode = ?
            AND up.externalPrefix = ?
            ';

        $stmt = $db->prepare($query);
        $stmt->execute(array($idUser, $prefix));
        return $stmt->fetch();
    }

    /**
     * Достать пользователя по его идентификатору
     *
     * @param bigint $idUser - Идентификатор пользователя
     * @return array
     */
    public function getUserById($idUser)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                *
            FROM
                smp_user
            WHERE
                idUser = ?
            ';

        $stmt = $db->prepare($query);
        $stmt->execute(array($idUser));
        return $stmt->fetch();
    }

    /**
     * Добавить основную информацию о пользователе при его регистрации извне
     *
     * @param unknown_type $idUser
     * @param unknown_type $prefix
     * @param unknown_type $name
     * @param string $email - мыло пользователя
     *
     * @return bigint - идентификатор нового пользователя системы
     */
    public function addExternalUser($idUser, $prefix, $name, $email)
    {
        $db = Zend_Registry::get('db');

        $data = array($name);
        $data2 = array();
        $addSql = '';
        if (!empty($email))
        {
            $addSql = ', login = ?';
            $data[] = $email;
            $addSql2 = ', email = :email';
            $data2['email'] = $email;
        }

        $query = '
            INSERT INTO
                smp_user
            SET
                idRole = (SELECT idRole FROM `smp_role` WHERE name = \'user\'),
                status = \'active\',
                name = ?
            ' . $addSql;

        $stmt = $db->prepare($query);
        $stmt->execute($data);
        $newIdUser = $db->lastInsertId();

        $query = '
            INSERT INTO
                smp_userpeople
            SET
                idUser = :idUser,
                name = :name,
                registrationType = \'external\',
                externalRegistrationCode = :rc,
                externalPrefix = :ep,
                subscription = 2
            ' . $addSql2;

        $data2['idUser'] = $newIdUser;
        $data2['name'] = $name;
        $data2['rc'] = $idUser;
        $data2['ep'] = $prefix;

        $stmt = $db->prepare($query);
        $stmt->execute($data2);

        //Добавить группу "друзья" для текущего пользователя
        $idGroup = $this->_addFriendGroup($newIdUser);
        //Добавить самого пользоватлея в список группы
        $this->_addGroupUser($newIdUser, $idGroup);
        //Добавляем пользователя в общедоступную группу
        $this->_addAnyoneGroup($newIdUser);

        return $newIdUser;
    }

    /**
     * Достать данный о приглашении
     *
     * @param int $iid - идентификатор приглашения
     * @return array - данные о приглашении
     */
    public function getInvite($key)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                *
            FROM
                `smp_invite`
            WHERE
                `key` = ?
        ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($key));
        return $stmt->fetch();
    }

    /**
     * Достать
     *
     * @param unknown_type $key
     * @return int - идентификатор группы "друзья" пользователя, который отправил приглашение
     */
    public function getFriendsGroupIdByInviteKey($key)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                g.idGroup
            FROM
                `smp_invite` AS i
            JOIN
                `smp_group` AS g
            ON i.idUser = g.idCreator
            WHERE
                i.`key` = ?
            AND g.groupType = \'friends\'
        ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($key));
        $rez = $stmt->fetch();
        if (!empty($rez))
        {
            return $rez['idGroup'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Удалить из таблицы приглашений приглашение по его ключу
     *
     * @param string $key - ключ приглашения
     */
    public function clearInviteByKey($key)
    {
         $db = Zend_Registry::get('db');

        $query = '
            DELETE FROM
                `smp_invite`
            WHERE
                `key` = ?
        ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($key));
    }

    /**
     * Достать идентификатор группы "друзья" для указанного пользователя
     *
     * @param int $id - идентификатор пользователя
     * @return int - идентификатор группы
     */
    public function getFriendsGroupByUserId($id)
    {
        $db = Zend_Registry::get('db');

        $query = '
            SELECT
                g.idGroup
            FROM
                `smp_group` AS g
            WHERE
                g.idCreator = ?
            AND g.groupType = \'friends\'
        ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($id));
        $rez = $stmt->fetch();
        if (!empty($rez))
        {
            return $rez['idGroup'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Добавляет пользователя 1 в группу 2 и пользователя 2 в группу 1
     *
     * @param int $idUser1 - идентификатор первого пользователя
     * @param int $idGroup1 - идентификатор группы первого пользователя
     * @param int $idUser2 - идентификатор втрого пользователя
     * @param int $idGroup2 - идентификатор группы второго пользователя
     */
    public function addFriends($idUser1, $idGroup1, $idUser2, $idGroup2)
    {
        $db = Zend_Registry::get('db');

        $query = '
            INSERT INTO
                `smp_groupuser`
            SET
                idGroup = ?,
                idUser = ?
        ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($idGroup1, $idUser2));
        $stmt->execute(array($idGroup2, $idUser1));
    }


// for private --------------------------------------------------
    /**
     * Добавляем к пользователю группу "друзья"
     *
     * @param int $idUser - идентификатор пользователя, для которого создается группа "друзья"
     * @return int - Идентификатор созданной группы
     */
    private function _addFriendGroup($idUser)
    {
        $db = Zend_Registry::get('db');

        $query = '
            INSERT INTO
                `smp_group`
            SET
                idCreator = ?,
                groupType = \'friends\'
            ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($idUser));

        return $db->lastInsertId();
    }

    /**
     * Добавляем пользоватлея в общедоступную группу
     * @param int $idUser - идентификатор пользователя
     */
    private function _addAnyoneGroup($idUser)
    {
        $db = Zend_Registry::get('db');

        $query = '
            INSERT INTO
                `smp_groupuser`
            SET
                idGroup = 0,
                idUser = ?
            ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($idUser));
    }

    /**
     * Добавить пользователя к списку группы
     *
     * @param int $idUser - Идентификатор пользователя
     * @param int $idGroup - Идентификатор группы
     */
    private function _addGroupUser($idUser, $idGroup)
    {
        $db = Zend_Registry::get('db');

        $query = '
            INSERT INTO
                `smp_groupuser`
            SET
                idGroup = ?,
                idUser = ?
            ';
        $stmt = $db->prepare($query);
        $stmt->execute(array($idGroup, $idUser));
    }
}