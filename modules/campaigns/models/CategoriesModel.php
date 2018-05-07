<?php

class CategoriesModel extends NestedSet_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_db = Zend_Registry::get('db');
        $this->setTableName('smp_category');
        $this->setStructureName('categoryName');
        $this->setStructureId('idCategory');
        $this->setStructureIdParent('idParentCategory');
        $this->setStructureOrder('orderNum');
        $this->setAdditional(array('categoryDescr'));
    }

    public function addCategory(array $form, $idParentCategory)
    {
        $this->add($form['categoryName'], $idParentCategory, array (
            'categoryDescr' => $form['categoryDescr']
        ));
    }

    public function getCategory($idCategory)
    {
        $sql = '
            SELECT
                *
            FROM
                smp_category
            WHERE
                idCategory = ?
        ';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($idCategory));
        return $stmt->fetch();
    }

    public function editCategory(array $form, $idCategory)
    {
        $sql = '
            UPDATE
                smp_category
            SET
                categoryDescr =:categoryDescr,
                categoryName =:categoryName
            WHERE
                idCategory =:idCategory
        ';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'categoryDescr' => $form['categoryDescr'],
            'categoryName' => $form['categoryName'],
            'idCategory' => $idCategory
        ));

    }

    public function existsCategory($idCategory)
    {
        $sql = '
            SELECT
                1
            FROM
                smp_category
            WHERE
                idCategory = ?
        ';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($idCategory));
        return $stmt->fetch();
    }

    public function moveCategory(array $aCategory, $idParentCategory, $orderNum)
    {
        if ($aCategory['idParentCategory'] != $idParentCategory)
        {
            $sql = '
                UPDATE
                    smp_category
                SET
                    orderNum = orderNum - 1
                WHERE
                    idParentCategory =:idParentCategory AND
                    orderNum >:orderNum
            ';
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(array(
                'idParentCategory' => $aCategory['idParentCategory'],
                'orderNum' => $aCategory['orderNum'],
            ));
            $this->move($aCategory['idCategory'], $idParentCategory);
        }

        if ($aCategory['orderNum'] != $orderNum || $aCategory['idParentCategory'] != $idParentCategory)
        {
            $sql = '
                UPDATE
                    smp_category
                SET
                    orderNum = orderNum + 1
                WHERE
                    idParentCategory =:idParentCategory AND
                    orderNum >=:orderNum
            ';

            $stmt = $this->_db->prepare($sql);
            $stmt->execute(array(
                'idParentCategory' => $idParentCategory,
                'orderNum' => $orderNum,
            ));
            $sql = '
                UPDATE
                    smp_category
                SET
                    orderNum = :orderNum
                WHERE
                    idCategory =:idCategory
            ';
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(array(
                'idCategory' => $aCategory['idCategory'],
                'orderNum' => $orderNum,
            ));
        }
    }
}