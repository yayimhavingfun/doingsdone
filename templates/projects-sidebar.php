
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project) : ?>
            <?php if ($_SESSION['id'] === (int)$project['user_id']) : ?>
                <li class="main-navigation__list-item <?php if (isset($_GET['project_id'])) {
                    if ($project['id'] == ($_GET['project_id'])):?> main-navigation__list-item--active <? endif;
                } ?>">
                    <a class="main-navigation__list-item-link" href="/index.php?project_id=<?= $project['id']?>"><?= htmlspecialchars($project['name']) ?></a>
                    <span class="main-navigation__list-item-count"><?= count_projects($tasks_sb, $project['id']);?></span>
                </li>
            <?php endif; ?>
            <?php endforeach;?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="add_project.php" target="project_add">Добавить проект</a>

