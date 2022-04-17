<?php

namespace Drupal\actividad\Entity;

class ActividadEntity {

    static function selectAll($table) {
        $query = 'SELECT * FROM {' . $table . '}';
        $result = db_query($query);
        return $result;
    }

    static function selectOne($table, $campo, $id) {
        $query = 'SELECT * FROM {' . $table . '} WHERE ' . $campo . ' = ' . $id;
        $result = db_query($query);
        return $result;
    }
    
    static function selectOneDouble($table, $campo, $id, $campo2, $id2) {
        $query = 'SELECT * FROM {' . $table . '} WHERE ' . $campo . ' = ' . $id.' AND '. $campo2 . ' = ' . $id2;
        $result = db_query($query);
        return $result;
    }

    static function selectOneText($table, $campo, $id) {
        $query = 'SELECT * FROM {' . $table . '} WHERE ' . $campo . ' = ' . $id;
        //$result = db_query($query);
        return $query;
    }
    
    static function selectOneDoubleText($table, $campo, $id, $campo2, $id2) {
        $query = 'SELECT * FROM {' . $table . '} WHERE ' . $campo . ' = ' . $id.' AND '. $campo2 . ' = ' . $id2;
        //$result = db_query($query);
        return $query;
    }

    static function selectOneGroup($table, $select_campo, $campo, $id) {
        $query = 'SELECT ' . $select_campo . ' FROM {' . $table . '} WHERE ' . $campo . ' = ' . $id;
        $result = db_query($query);
        return $result;
    }

}
