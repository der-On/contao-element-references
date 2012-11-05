<?php

class ElementReferencesField extends Widget
{
    protected $strTemplate = 'el_references';

    public $references = array();
    public $element = null;
    public $type = null;

    public function __construct($arrAttributes=false)
    {
        parent::__construct($arrAttributes);

        $table = $_GET['table'];
        $act = $_GET['act'];
        $edit_id = $_GET['id'];

        if (!empty($edit_id) && $act == 'edit' && !empty($table)) {
            $db = Database::getInstance();
            $element = $db->prepare("SELECT * FROM `$table` WHERE id = %s")->execute(array($edit_id))->fetchAllAssoc();

            if (count($element) > 0) {
                $element = $element[0];

                $this->element = $element;

                if ($table == 'tl_content') {
                    $this->type = $this->element['type'];
                } else $this->type = str_replace('tl_','',$table);

                if ($table == 'tl_content') {
                    $this->references = $db->prepare("SELECT * FROM `tl_content` WHERE type = %s AND cteAlias = %s")->execute(array($this->type,$this->element['id']))->fetchAllAssoc();
                } else {
                    $this->references = $db->prepare("SELECT * FROM `tl_content` WHERE type = %s AND articleAlias = %s")->execute(array($this->type,$this->element['id']))->fetchAllAssoc();
                }

                foreach($this->references as $i => $reference) {
                    $this->references[$i]['_edit_url'] = 'contao/main.php?do=article&table=tl_content&act=edit&id='.$reference['id'];
                    $this->references[$i]['_title'] = 'ID '.$reference['id'];

                    if (!empty($reference['headline'])) {
                        $headline = unserialize($reference['headline']);
                        $headline = $headline['value'];
                        if (!empty($headline)) $this->references[$i]['_title'].=' - '.$headline;
                    }

                    $parent_article = $db->prepare('SELECT title,id FROM `tl_article` WHERE id = %s')->execute(array($reference['pid']))->fetchAllAssoc();
                    if (count($parent_article) > 0) {
                        $parent_article = $parent_article[0];
                        $this->references[$i]['_parent_url'] = 'contao/main.php?do=article&table=tl_content&id='.$parent_article['id'];
                        $this->references[$i]['_parent_title'] = $parent_article['title'];
                    }
                }
            }
        }
    }

    public function generate()
    {

    }
}
