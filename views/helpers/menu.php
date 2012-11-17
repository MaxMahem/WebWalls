<?php
class MenuHelper extends AppHelper
{
    var $helpers = array('Html');

    function menu($links = array(),$htmlAttributes = array())
    {
        $this->tags['ul'] = '<ul%s>%s</ul>';
        $this->tags['li'] = '<li%s>%s</li>';
        $out = array();

        // loop through all the links to output each one of them.
        foreach ($links as $title => $link) {
            // check to see if we actually got a well formed link
            if (isset($link['controller']) && isset ($link['action'])) {
                // check to see if it matches our current location
                if(($this->params['controller'] == $link['controller']) && ($this->params['action'] == $link['action'])) {
                    // it matches, set class to active, but DON'T send a link
                    $out[] = sprintf($this->tags['li'],' class="active"',$title);
                } else {
                    $out[] = sprintf($this->tags['li'],'',$this->Html->link($title, $link));
                }
            } else {
                $out[] = sprintf($this->tags['li'],'',$this->Html->link($title, $link));
            }
        }
        
        $tmp = join("\n", $out);
        return $this->output(sprintf($this->tags['ul'],$this->_parseAttributes($htmlAttributes), $tmp));
    }
}
?>
