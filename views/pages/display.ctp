    <!-- File: /app/views/pages/home.ctp -->

    <p>This will be the introductary text.</p>

    <table>
    <thead>
    <tr>
        <!-- <th>Id</th> -->
        <th>Title</th>
        <th>Category</th>
        <th>Tags</th>
        <th>Filename</th>
        <th>Uploaded</th>
        <th>Downloads</th>
    </tr>
    </thead>
    <tbody>
    <!-- Here is where we loop through our $walls array, printing out our wall info -->
    <?php foreach ($walls as $wall): ?>
    <tr>
        <td>
            <?=$this->Html->link($wall['Wall']['title'], array('controller' => 'Walls', 'action' => 'view', $wall['Wall']['id']))?><br />
            <?=$this->Html->link(
                $this->Thumb->resize('upload/'.$wall['Wall']['id'], 150, 150, true, array('alt' => $wall['Wall']['title'])), /* image block */
                array('controller' => 'Walls', 'action' => 'show', $wall['Wall']['id'], $wall['Wall']['filename']), /* link destination */
                array('escape' => false) // don't escape the image block
            )?>
        </td>
        <td><?=$this->Nest->dispPath($wall['Category']['path'])?></td>
        <td>Tags</td>
        <td>
            <?=$this->Html->link($wall['Wall']['filename'], array('controller' => 'Walls', 'action' => 'download', $wall['Wall']['id']))?><br />
            <small>MD5: <?=$wall['Wall']['md5']?>, <?=$this->Number->format($wall['Wall']['filesize']/1024)?>KB</small>
        </td>
        <td><?=$this->Time->niceShort($wall['Wall']['created'])?></td>
        <td><?=$wall['Wall']['downloads']?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>

    <?=$this->Html->link('Upload', array('controller' => 'Walls', 'action' => 'upload'));?>