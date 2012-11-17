<?php
/* /app/views/helpers/nest.php */

class NestHelper extends AppHelper {

    var $helpers = array('Html', 'Time');

    function dispCategories($categories) {
        // Not sure if I have to set these due to recursion, but can't hurt.
        $output = '';
        $hasChildren = false;

        // check to see if we have any kids (so we can know to use the header or not.
        foreach ($categories as $category) {
            if (!empty($category['children'])) { $hasChildren = true; }
        }

        if (empty($categories)) {
            return;
        } else {
            $output  = '<table>';
            $output .= '<tr>';
            $output .= '<th>Category</th>';
            if ($hasChildren) { $output .= '<th>Children</th>'; }
            $output .= '<th>Count</th>';
            $output .= '<th>Downloads</th>';
            $output .= '<th>Action</th>';
            $output .= '</tr>';

            foreach($categories as $category) {
                $output .= '<tr>';
                $output .= '<td>'.$this->dispPath($category['Category']['path'], true).'</td>';

                // little tricky here, if $hasChildren is true an item in this category has children, so we have that row
                // and we need an empty column to compensate. And of course if we have a child category we need to recurse
                // this could be done with two ifs and some boolean operators, but I think this is a bit easier to understand.
                if ($hasChildren)                  { $output .= '<td class="children">'; }
                    if (!empty($category['children'])) { $output .= $this->dispCategories($category['children']); }
                if ($hasChildren)                  { $output .= '</td>'; }

                $output .= '<td>'.$category['Category']['wall_count'].'</td>';
                $output .= '<td>'.$category['Category']['download_count'].'</td>';
                $output .= '<td>';
                    $output .= $this->Html->link('Edit',  array('action' => 'edit', $category['Category']['id'])).' ';
                    $output .= $this->Html->link('Delete',  array('action' => 'delete', $category['Category']['id']));
                $output .= '</td>';
                $output .= '</tr>';
            }

            $output .= '</table>';

            return $output;
        }
    }

    /**
     * Outputs a set of links pointing to each node in the path.
     *
     * @param array $path An array containing the name and id of the path we want.
     * @param boolean $finalOnly Do we give a whole series of links, or just the last one.
     * @return string a formated string of links.
     */
    function dispPath($path, $finalOnly = false, $omitLast = false) {
        $output = '';
        $patharray = array();

        foreach ($path as $part) {
            // set $path as the name, this grows each iteration building a set of links like node, node/node, node/nodes/node, ect.
            $patharray[] = $part['name'];

            if ($finalOnly) {
                // display the last one only, so output is not concatinated.
                $output = $this->Html->link($part['name'],
                    array_merge(array('controller' => 'categories', 'action' => 'browse'), $patharray)
                );
            } else {
                // display the entire set of links, so output is concatinated
                $output .= $this->Html->link($part['name'],
                    array_merge(array('controller' => 'categories', 'action' => 'browse'), $patharray)
                ).' ';
            }
        }

        return $output;
    }
}

?>
