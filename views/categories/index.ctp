    <!-- File: /app/views/categories/index.ctp -->
    <h1>Categories</h1>

    <?=$this->Html->link("Add Category", array('action'=>'add'))?>

    <table>

    <tr>
        <th>Category</th>
        <th>Count</th>
        <th>Action</th>
    </tr>

    <?php foreach ($categories as $category): ?>
    <tr>
        <td><?=$category['Category']['path']?></td>
        <td><?=$category['Category']['wall_count']?></td>
        <td><?=$this->Html->link('Edit', array('action'=>'edit', $category['Category']['title']))?></td>
    </tr>
    <?php endforeach; ?>

    </table>