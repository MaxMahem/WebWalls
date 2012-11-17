    <!-- File: /app/views/wall/upload.ctp -->

    <?=$this->Form->create('Wall',array('type'=>'file'))?>
    <?=$this->Form->input('title')?>
    <?=$this->Form->input('category_id')?><?=$this->Html->link('Add Category', array('controller' => 'categories', 'action'=>'add'))?>
    <?=$this->Form->file('image')?>
    <?=$this->Form->error('image')?><?=$this->Form->error('md5')?><?=$this->Form->error('mimetype')?>
    <?=$this->Form->end('Submit')?>