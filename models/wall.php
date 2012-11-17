<?php

class Wall extends AppModel {

    var $name = 'Wall';
    var $displayField = 'title';
    var $belongsTo = array(
        'Category' => array(
            'counterCache' => true,
            'counterScope' => array('Wall.status' => 'enabled') // only count if status is enabled.
            )
        );

    // since we can't get the CategoryID after the delete of an item, we will store the Category ID here
    private $tmpCategoryID;

    var $validate = array(
        // a wall must have a title, no more then 255 characters long.
        'title' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Title cannot be blank.',
                'required' => true,
                'last'     => true
            ),
            'maxLength' => array(
                'rule'     => array('maxLength', 255),
                'message'  => 'Error: Title may not be longer then 255 characters.',
                'required' => true,
                'last'     => true
            )
        ),

        // must be leaf node (no children)
        'category_id' => array(
            'category' => array(
                'rule'     => array('validateLeaf'),
                'message'  => 'Error: Wallpaper may only be attached to  bottom most node',
                'required' => true,
                'last'     => true
            )
        ),

        // we must have a valid file
        'image' => array(
            'validUpload' => array(
                'rule'     => array('validateUploadedFile', true),
                'message'  => 'Error: You did not upload a file, or there was a problem with the upload.',
                'required' => true,
                'last'     => true,
                'on'       => 'create'
            ),
            'validImageType' => array(
                'rule'     => array('validateImageType', true),
                'message'  => 'Error: Not a valid image type.',
                'required' => true,
                'on'       => 'create'
            )
        ),

        // a wall must have a filename
        'title' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Error: Filename cannot be blank.',
                'required' => true,
                'last'     => true
            )
        ),

        // md5 must be unique
        'md5' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
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

    /**
     * Custom validation rules checks to see if file is being added to root most node only
     *
     * @param string $id Wall's category_id, a UUID forgien key to the category in the category table.
     * @return Boolean True if is a leaf node, false if it has children
     */
    function validateLeaf($id) {
        // since childCount will return the number of children a node has, and we want nodes with no children
        // returning NOT childCount will give us true on 0 children and false on anything else.
        return !$this->Category->childCount($id['category_id'], true);
    }

    /**
     * Custom validation rule for uploaded files.
     *
     *  @param Array $data CakePHP File info.
     *  @param Boolean $required Is this field required?
     *  @return Boolean
     */
    function validateUploadedFile($file, $required = false) {
        // Remove first level of Array ($file['Image']['size'] becomes $file['size'])
        $file_info = array_shift($file);

        // No file uploaded.
        if ($required && $file_info['size'] == 0) { return false; }

        // Check for Basic PHP file errors.
        if ($file_info['error'] !== 0) { return false; }

        // Finally, use PHP's own file validation method.
        return is_uploaded_file($file_info['tmp_name']);
    }

    /**
     * Checks to see if file is a valid image type
     *
     * @param Array $image CakePHP File info
     * @return Boolean
     */
    function validateImageType($file) {
        // List of acceptable image types
        $acceptable_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);

        // Remove first level of Array ($file['Image']['size'] becomes $file['size'])
        $file_info = array_shift($file);

        // Get image type
        $image_type = exif_imagetype($file_info['tmp_name']);

        // check if the image type is allowed
        if (in_array($image_type, $acceptable_types, true )) {
            return true;
        } else {
            return false;
        }
    }

    function afterSave() {
        // after save the counter cache will update automatically for the walls category
        // we need to update the category counts for the parent categories as well. The
        // category model function updateCount does this, we will send it our current walls parent

        $parent = $this->Category->getparentnode($this->data['Wall']['category_id']);
        $this->Category->updateCount($parent);
    }

    function beforeDelete() {
//        $parent = $this->Category->getparentnode($this->data['Wall']['category_id']);

        $data = $this->read(null, $this->id);
        $this->tmpCategoryID = $this->Category->getparentnode($data['Wall']['category_id']);
        
        return true;
    }

    function  afterDelete() {
        $this->Category->updateCount($this->tmpCategoryID);
    }
}

?>