    <!-- File: /app/views/walls/edit.ctp -->

    <?=$this->Form->create('Wall', array('action' => 'edit'))?>
    <?=$this->Form->hidden('id')?>
    <?=$this->Form->input('title')?>
    <?=$this->Form->input('filename')?>
    <?=$this->Form->input('category_id', array('selected'=>$this->data['Wall']['category_id']))?>
    <?=$this->Form->end('Save Changes')?>

    <?=$this->Html->link('Delete',   array('action' => 'delete',   $this->data['Wall']['id']), null, 'Are you sure?')?>
