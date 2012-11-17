    <!-- File: /app/views/elements/navmenu.ctp -->

    <?=$this->Menu->menu(
        array(
            'Home'   => '/',
            'Browse' => array('controller' => 'categories', 'action' => 'browse'),
            'Search' => array('controller' => 'walls', 'action' => 'index'),
            'Random' => array('action' => 'view', 'random')
            ),
        array('class' => 'menu')
    );?>
    <?='Controller: '.$this->name.' Action: '.$this->action ?>