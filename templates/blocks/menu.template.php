<ul>
    <?php foreach ($groups[$key] as $group) : ?>
    <li>
        <a <?=(!isset($group['active']) ? '' : 'class="active"')?> href="?group=<?=$group['id']?>"><?=$group['name']?></a>
        <span><?=$group['products_count']?></span>
        <?php if (isset($groups[$group['id']])) {
            echo \classes\Core::getBlock('menu', ['groups' => $groups, 'key' => $group['id']]);
        } ?>
    </li>
    <?php endforeach; ?>
</ul>