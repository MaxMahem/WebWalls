    <!-- File: /app/views/categories/edit.ctp -->

    <?=$this->Html->link('Back',array('action'=>'index'))?>

    <?=$this->Form->create('Category')?>
    <?=$this->Form->hidden('id')?>
    <?=$this->Form->input('name')?>
    <?=$this->Form->input('parent_id', array('selected'=>$this->data['Category']['parent_id']))?>
    <?=$this->Form->end('Update')?>