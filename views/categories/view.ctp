    <!-- File: /app/views/category/view.ctp -->

    <h1><?=$wall['Wall']['title']?></h1>

    <p><small>Created: <?=$wall['Wall']['created']?></small></p>

    <?=$this->Html->image('upload/'.$wall['Wall']['id'], array('alt' => $wall['Category']['title']))?>

    <?=$this->Html->link('Edit',   array('action' => 'download', $category['Category']['id']))?>
    <?=$this->Html->link('Delete', array('action' => 'delete',   $category['Category']['id']), null, 'Are you sure?')?>