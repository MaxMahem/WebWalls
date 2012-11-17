<?php

App::import('Sanitize');

class WallsController extends AppController {

    var $components = array('Session');
    var $helpers = array ('Html','Form', 'Number', 'Js', 'Nest');
    var $uses = array ('Wall', 'Category');
    var $name = 'Walls';

    function index() {
        // set the page title.
        $this->set('title_for_layout', 'Wall Index');

        // get all arguments passed to this function.
        $allwalls = $this->Wall->find('all');

        // add the path to the wall to the array.
        $allwalls = $this->Category->addPath($allwalls, 'Category');

        // get the total number of downloads
        $this->set('downloads', 0);
        
        $this->set('walls', $allwalls);
    }

    function view($id = null) {
        // set our wall variable for the view, if we get random, redirect to a random wall
        if ($id=='random') {
            $random = $this->Wall->find('random');
            $this->redirect(array('action' => 'view', $random['Wall']['id']));
        } else {
            $this->Wall->id = $id;
            $wall = $this->Wall->read();
            $this->set('wall', $wall);

            // set the page title
            $this->set('title_for_layout', 'Viewing "'.$wall['Wall']['title'].'"');
        }

        // just in case its been deleted, or someone is getting frisky
        if(!isset($wall['Wall']['title'])) {
            $this->Session->setFlash("Error. Either:<ul><li>We no longer have that wall</li><li>Or we never did.</li></ul>");
            $this->redirect($this->referer());
        }
    }

    function find() {
        
    }

    function edit($id = null) {
        // set form defaults.
        $categorylist = $this->Category->generatetreelist(null,null,null," - ");
        $this->set('categories', $categorylist);

        if (empty($this->data)) {
            // We didn't get any data so go ahead and read in the data from $id
            $this->data = $this->Wall->read(array('id', 'title', 'filename', 'category_id'), $id);

            // check to see if we got any data
            if(empty($this->data)) {
                // No data! Probably someone passed us a bad ID! Are they up to no good?
                $this->Session->setFlash('Error either:<ul><li>We no longer have that wall</li><li>We never did</i>');
                $this->redirect($this->referer());
            } else {
                // got data, set the page title.
                $this->set('title_for_layout', 'Editing "'.$this->data['Wall']['title'].'"');
            }
        } else {
            // we got data, lets save it.
            if ($this->Wall->save($this->data, true, array('title', 'filename', 'category_id'))) {
                $this->Session->setFlash('Changes to wall '.$this->data['Wall']['id'].' have been saved.');
                $this->redirect(array('action' => 'index'));
            }
	}
    }

    function download($id = null) {
        // set our wall variable for the view, if we get random, redirect to a random wall
        if ($id=='random') {
            $random = $this->Wall->find('random');
            $this->redirect(array('action' => 'download', $random['Wall']['id']));
        } else {
            $this->Wall->id = $id;
            $wall = $this->Wall->read();
            $this->set('wall', $wall);
        }

        // just in case its been deleted, or someone is getting frisky
        if(!isset($wall['Wall']['title'])) {
            $this->Session->setFlash("Error. Either:<ul><li>We no longer have that wall</li><li>Or we never did.</li></ul>");
            debug($wall['Wall']['downloads']);
            $this->redirect($this->referer());
        } else {
            // update our counter
            $wall['Wall']['downloads'] = $wall['Wall']['downloads'] + 1;
            $this->Wall->save($wall);

            // we'll use a new layout, file, that will allow custom headers
            Configure::write('debug', 0);
            $this->render(null,'file');
        }
    }

    function show($id) {
        //set up a variable, so the view well knwo to show it, not prompt to download
        $this->set('inpage',true);

        // set our wall variable for the view, if we get random, redirect to a random wall
        if ($id=='random') {
            $random = $this->Wall->find('random');
            $this->redirect(array('action' => 'download', $random['Wall']['id']));
        } else {
            $this->Wall->id = $id;
            $wall = $this->Wall->read();
            $this->set('wall', $wall);
        }

        // just in case its been deleted, or someone is getting frisky
        if(!isset($wall['Wall']['title'])) {
            $this->Session->setFlash("Error. Either:<ul><li>We no longer have that wall</li><li>Or we never did.</li></ul>");
            $this->redirect($this->referer());
        } else {
            // update our counter
            $wall['Wall']['downloads'] = $wall['Wall']['downloads'] + 1;
            $this->Wall->save($wall);

            // we'll use a new layout, file, that will allow custom headers, setting download as 
            Configure::write('debug', 0);
            $this->render('download','file');
        }
    }

    function upload() {
        // Check if any data has been uploaded.
        if (empty($this->data)) {
            // we didn't get any data, so this is the first time the page has been uploaded.
                        
            // set the page title
            $this->set('title_for_layout', 'Wall Upload');

            // set form defaults.
            $categorylist = $this->Category->generatetreelist(null,null,null," - ");
            $this->set('categories', $categorylist);
        } else {
            // we got data, so we need to process the upload

            // set the image data for validation
            $this->Wall->set('image', $this->data['Wall']['image']);
            // Check to see if it is a valid image.
            if ($this->Wall->validates(array('fieldList' => array('image')))) {
                // it's a valid image

                // GetImageSize also returns other image data as well.
                $image_info = GetImageSize($this->data['Wall']['image']['tmp_name']);

                // set all the data
                $this->Wall->set('title',       $this->data['Wall']['title']);
                $this->Wall->set('category_id', $this->data['Wall']['category_id']);
                $this->Wall->set('filename',    $this->data['Wall']['image']['name']);
                $this->Wall->set('filesize',    filesize($this->data['Wall']['image']['tmp_name']));
                $this->Wall->set('md5',         md5_file($this->data['Wall']['image']['tmp_name']));
                $this->Wall->set('size_x',      $image_info[0]);
                $this->Wall->set('size_y',      $image_info[1]);
                $this->Wall->set('ratio',       aspectRatio($image_info[0], $image_info[1]));
                $this->Wall->set('mimetype',    $image_info['mime']);

                // save the database data
                if ($this->Wall->save()) {
                    // save succsesful, data validated
                    // get the transmited file data
                    $file = new File($this->data['Wall']['image']['tmp_name']);
                    $data = $file->read();
                    $file->close();

                    // write the file to the upload directory
                    $file = new File(WWW_ROOT.'img/upload/'.$this->Wall->id,true);
                    $file->write($data);
                    $file->close();

                    $this->Session->setFlash('Wall "'. $this->data['Wall']['title'] . '" has been uploaded.');
                    $this->redirect(array('action' => 'index'));
                }
            }
        }
    }

    function delete($id = null) {
        // this page has no view.

        $title = $this->Wall->read('title', $id);

        if ($this->Wall->delete($id)) {
            // record was deleted.
            // TODO: Move this to the afterDelete function model
            $file = new File(WWW_ROOT.'img/upload/'.$id);

            if ($file->delete()) {
                // file was deleted
                $this->Session->setFlash('The wall "' . $title['Wall']['title'] . '" has been deleted.');
            } else {
                // Error deleting file
                $this->Session->setFlash('File not deleted');
            }
        } else {
            // Error deleting recoard
            $this->Session->setFlash('Record not deleted');
        }

        $this->redirect(array('action' => 'index'));
    }

}

/**
 * Calculates Greatest Common Denominator
 *
 *  @param Int $a First Number
 *  @param Int $b Second Number
 *  @return Int GCD
 */
function gcd($a, $b){
    $b = ( $a == 0 )? 0 : $b;
    return ( $a % $b )? gcd($b, abs($a - $b)) : $b;
}

/**
 * Calculates Greatest Common Denominator
 *
 *  @param Int $size_x Width
 *  @param Int $size_y Height
 *  @return String Ratio in format x:y
 */
function aspectRatio($size_x, $size_y) {
    $gcd = gcd($size_x, $size_y);
    $ratio_x = $size_x / $gcd;
    $ratio_y = $size_y / $gcd;
    $ratio = $ratio_x . ':' . $ratio_y;

    return $ratio;
}