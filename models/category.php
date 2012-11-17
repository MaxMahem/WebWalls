<?php
// app/models/category.php

class Category extends AppModel {
    var $name = 'Category';
    var $displayField = 'name';
    var $actsAs = array('Tree');
    var $hasMany = 'Wall';

    var $validate = array(
        // a category must have a unique name, no more then 255 characters long.
        'name' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Name cannot be blank.',
                'required' => true,
                'last'     => true
            ),
            'maxLength' => array(
                'rule'     => array('maxLength', 255),
                'message'  => 'Error: Name may not be longer then 255 characters.',
                'required' => true,
                'last'     => true
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message'  => 'Error: That file has already been uploaded.',
                'required' => true,
                'last'     => true
            )
        )
    );

    function afterSave() {
        // when we save a category there is the possibility the order has changed,
        // so we need to reorder. Maybe in the future we can check to see if this
        // is actually needed

        $this->reorder();
    }

    function updateCount($category) {
        $wall_count = 0;

        $children = $this->children($category['Category']['id'], true);

        foreach($children as $child) { $wall_count += $child['Category']['wall_count']; }

        $category['Category']['wall_count'] = $wall_count;

        $this->id = $category['Category']['id'];
        $this->saveField('wall_count', $wall_count);

        // check to see if this category has any parents, if so we need to update them to.
        // parent_id will be null or 0 if it is a root node, so we can check on it directly
        if($category['Category']['parent_id']) {
            $parent = $this->getparentnode($category['Category']['id']);

            $this->updateCount($parent);
        }
    }
}

?>
