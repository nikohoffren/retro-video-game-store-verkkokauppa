<?php

class Base
{
    protected $id;
    protected $sql;
    protected $tablename;

    public function __construct($id, $sql, $tablename) {
        $this->id = $id;
        $this->sql = $sql;
        $this->tablename = $tablename;
    }

    public function delete() : bool {
        $query = $this->sql->query("UPDATE $this->tablename SET deleted = '1' WHERE id = '$this->id'");

        if ($query) {
            return true;
        }
    }
}

?>
