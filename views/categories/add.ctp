    <!-- File: /app/views/categories/add.ctp -->

    <?=$this->Html->link('Back',array('action'=>'index'))?>

    <?=$this->Form->create('Category')?>
    <?=$this->Form->input('name',array('label'=>'Name'))?>
    <?=$this->Form->input('parent_id',array('label'=>'Parent'))?>
    <?=$this->Form->end('Add')?>