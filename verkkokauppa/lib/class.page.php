<?php
session_start();
include_once "lib/class.base.php";

class Page extends Base
{
    public $name;
    public $alias;
    public $user_id;
    public $id_to_modify;
    public $content;
    public $create_date;
    public $page_order;
    public $page_id;

    public function init($id, $sql, $name, $alias, $content) {
        $result = $this->$sql->query("SELECT * FROM pages WHERE id = '".$id."'");
        $row = $result->fetch_assoc();

        $this->$name = $row['name'];
        $this->$alias = $row['alias'];
        $this->user_id = $row['user_id'];
        $this->$content = $row['content'];
        $this->create_date = $row['create_date'];
    }

    public function getAllPages() : array {
        $result = $this->sql->query("SELECT * FROM pages ORDER BY page_order DESC");
        $pages = [];
        while ($row = $result->fetch_assoc()) {
            $pages[] = $row;
        }
        return $pages;
    }
}
