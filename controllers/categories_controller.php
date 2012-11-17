<?php

class CategoriesController extends AppController {

    var $components = array('Session');
    var $helpers = array ('Html','Form', 'Number', 'Js', 'Time', 'Nest');
    var $uses = array ('Wall', 'Category');
    var $name = 'Categories';

    function index() {
        // get all the categories
        $categories = $this->Category->find('all', array('order' => array('Category.lft')));

        // process each category to add the path
        foreach($categories as &$category) {
            // get the depth of the category.
            $depth = count($this->Category->getpath($category['Category']['id']));

            // blank out the path string
            $path = '';

            // build the path string we want. We need a number of spacers 1 less then the depth.
            for($count = 0; $count < $depth-1; $count++) { $path = $path.' - '; }
            // append the name.
            $path = $path.$category['Category']['name'];

            // add the path to the array.
            $category['Category']['path'] = $path;
        }

        $this->set('categories', $categories);
    }

    function browse() {
        // get all arguments passed to this function.
        $args = $this->params['pass'];
        if($args == null) {
            // did not recieve any arguments so get all the walls
            $dispCategories = $this->Category->find('threaded', array(
                'contain'    => false,
                'order'      => 'Category.lft'
            ));
        } else {
            // we got an argument, we need to find the details of that category.
            // $args[func_num_args()-1 will be equal to the last element passed (which should be our root category
            $lastArgument = $args[count($args)-1];

            $rootCategory = $this->Category->find('first', array(
                'conditions' => array('Category.name' => $lastArgument),
                'fields'     => array('Category.lft', 'Category.rght', 'Category.parent_id'),
                'contain'    => false
            ));

            // check the results of our find.
            if(empty ($rootCategory)) {
                // we didn't get any results from our find, someone up to no good?
                $this->Session->setFlash('Error. Either:<ul><li>We no longer have that category</li><li>Or we never did.</li></ul>');
                $this->redirect(array('action' => 'browse'));
            } else {
                // find was good, so find all the child categories, who's cattegory falls between lft and rght
                $dispCategories = $this->Category->find('threaded', array(
                    'conditions' => array('Category.lft BETWEEN ? AND ?' => array($rootCategory['Category']['lft'], $rootCategory['Category']['rght'])),
                    'contain'    => false
                ));
            }
        }

        $dispCategories = $this->Category->addPath($dispCategories, 'Category');

        $this->set('categories', $dispCategories);
    }

    function view() {
        // this is going to take some figuring out...
    }

    function add() {
        if (!empty($this->data)) {
            // if we got data save it.
            $this->Category->save($this->data);

            // sort the data after adding node.
            $this->Category->id = $this->Category->getparentnode();
            $this->Category->reorder();

            $this->redirect(array('action'=>'browse'));
        } else {
            // if we didn't get data, show it.
            $parents[0] = "[ No Parent ]";
            $categorylist = $this->Category->generatetreelist(null,null,null," - ");
            if($categorylist) {
                foreach ($categorylist as $key=>$value) { $parents[$key] = $value; }
            }
            $this->set(compact('parents'));
        }
    }

    function delete($id = null) {
        if ($this->Category->delete($id)) {
            // record was deleted.
            $this->Session->setFlash('Record deleted');
        } else {
            // Error deleting recoard
            $this->Session->setFlash('Record not deleted');
        }

        $this->redirect(array('action' => 'browse'));
    }

    function edit($id = null) {
        // set the list of categories
        $parents = $this->Category->generatetreelist(null,null,null," - ");
        array_unshift($parents, '[ No Parent ]');
        $this->set(compact('parents'));

        // Check and see if we got some data
        if (!empty($this->data)) {
            // We got the data so we need to save it
            if($this->Category->save($this->data)) {
                // Data saved

                // sort the data after adding node.
                $this->Category->id = $this->Category->getparentnode();
                $this->Category->reorder();

                $this->Session->setFlash('Category '.$this->data['Category']['name'].' Updated.');
                $this->redirect(array('action' => 'browse'));
            } else {
                // Likely a validation error
                $this->Session->setFlash('Error updating category.');
            }
        } else {
            // We didn't get any data so go ahead and read in the data from the category ID.
            $this->data = $this->Category->read(null, $id);

            // set the page title.
            $this->set('title_for_layout', 'Editing Category '.$this->data['Category']['name']);

            // check to see if we got any data
            if(empty($this->data)) {
                // No data! Probably someone passed us a bad ID! Are they up to no good?
                $this->Session->setFlash('Error either:<ul><li>We no longer have that category</li><li>We never did</i>');
                $this->redirect($this->referer());
            }
        }
    }
}
?>
