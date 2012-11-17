<?php

class PagesController extends AppController {

    var $components = array('Session');
    var $helpers = array ('Html','Form', 'Number', 'Js', 'Thumb', 'Nest');
    var $uses = array ('Wall', 'Category');

    function display() {
        // set the page title.
        $this->set('title_for_layout', 'Wall Index');

        // get the most recent walls
        $allwalls = $this->Wall->find('all');

        // add the path to the wall to the array.
        $allwalls = $this->Category->addPath($allwalls, 'Category');

        // get the total number of downloads
        $this->set('downloads', 0);

        $this->set('walls', $allwalls);
    }
}
?>