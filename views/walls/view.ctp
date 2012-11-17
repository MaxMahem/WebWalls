    <!-- File: /app/views/wall/view.ctp -->

    <p><small>Created: <?=$wall['Wall']['created']?></small></p>

    <?=$this->Html->image('upload/'.$wall['Wall']['id'], array('alt' => $wall['Wall']['title'], 'url' => array('action' => 'show', $wall['Wall']['id'], $wall['Wall']['filename'])))?><br />

    <?=$this->Html->link('Download', array('action' => 'download', $wall['Wall']['id']))?>
    <?=$this->Html->link('Edit',     array('action' => 'edit',     $wall['Wall']['id']))?>
    <?=$this->Html->link('Delete',   array('action' => 'delete',   $wall['Wall']['id']), null, 'Are you sure?')?>