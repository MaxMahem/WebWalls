    <!-- File: /app/views/walls/index.ctp -->

    <table>
    <thead>
    <tr>
        <!-- <th>Id</th> -->
        <th>Title</th>
        <th>Category</th>
        <th>Tags</th>
        <th>Filename</th>
        <th>Uploaded</th>
        <th>Size</th>
        <th>Ratio</th>
        <th>mimetype</th>
        <th>Downloads</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <!-- Here is where we loop through our $walls array, printing out our wall info -->
    <?php foreach ($walls as $wall): ?>
    <tr>
        <!-- <td><?=$wall['Wall']['id']; ?></td> -->
        <td><?=$this->Html->link($wall['Wall']['title'], array('action' => 'view', $wall['Wall']['id']))?></td>
        <td><?=$this->Nest->dispPath($wall['Category']['path'])?></td>
        <td>Tags</td>
        <td>
            <?=$wall['Wall']['filename']?><br />
            <small>MD5: <?=$wall['Wall']['md5']?>, <?=$this->Number->format($wall['Wall']['filesize']/1024)?>KB</small>
        </td>
        <td><?=$this->Time->niceShort($wall['Wall']['created'])?></td>
        <td><?=$wall['Wall']['size_x']?> x <?=$wall['Wall']['size_y']?></td>
        <td><?=$wall['Wall']['ratio']?></td>
        <td><?=$wall['Wall']['mimetype']?></td>
        <td><?=$wall['Wall']['downloads']?><?php $downloads += $wall['Wall']['downloads']; ?></td>
        <td>
            <?=$this->Html->link('Download', array('action' => 'download', $wall['Wall']['id']))?>
            <?=$this->Html->link('Edit',     array('action' => 'edit',     $wall['Wall']['id']))?>
            <?=$this->Html->link('Delete',   array('action' => 'delete',   $wall['Wall']['id']), null, 'Are you sure?')?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="9">Total</th>
        <th><?=$downloads?></th>
        <th><?=count($walls)?></th>
        </tr>
    </tfoot>
    </table>

    <?=$this->Html->link('Upload', array('action' => 'upload'));?>