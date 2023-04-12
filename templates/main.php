<section class="content__side"><?= $sidebar; ?></section>
<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?=get_post_val('search')?>" placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" name="" value="Искать">
    <p><?= $search_empty_response; ?></p>
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/index.php?filter=all" class="tasks-switch__item <?= isset($_GET['filter']) && $_GET['filter'] == 'all' ? "tasks-switch__item--active" : "" ?>">Все задачи</a>
            <a href="/index.php?filter=today" class="tasks-switch__item <?= isset($_GET['filter']) && $_GET['filter'] == 'today' ? "tasks-switch__item--active" : "" ?>">Повестка дня</a>
            <a href="/index.php?filter=tomorrow" class="tasks-switch__item <?= isset($_GET['filter']) && $_GET['filter'] == 'tomorrow' ? "tasks-switch__item--active" : "" ?>">Завтра</a>
            <a href="/index.php?filter=yesterday" class="tasks-switch__item <?= isset($_GET['filter']) && $_GET['filter'] == 'yesterday' ? "tasks-switch__item--active" : "" ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
             <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks == 1) : ?> checked <?php endif; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">

        <?php foreach ($tasks_main as $task) : ?>
        <?php if ($show_complete_tasks == 0 && $task['status'] == 1) {
            continue;} ?>
            <?php if ($_SESSION['id'] === (int)$task['user_id']) : ?>
            <?php if ($task['project_id'] == ($_GET['project_id'] ?? null) || empty($_GET['project_id'])) : ?>
            <tr class="tasks__item task
                <?php if ($task['status'] == 1) : ?> task--completed <?php endif; ?>
                <?php if ($task['date_finish'] !== null && get_hours_left($task['date_finish']) <= 24) : ?> task--important <?php endif; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" <?php if ($task['status'] == 1): ?> checked <?php endif; ?> value="<?= $task['id']?>">
                        <span class="checkbox__text"><?= htmlspecialchars($task['title'])?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?php if ($task["file"]) : ?>
                        <a class="download-link" href="<?=htmlspecialchars($task['file'])?>"><?=htmlspecialchars($task['title'])?></a>
                    <?php endif; ?>
                </td>
                <td class="task__date"><?= $task['date_finish'] ?></td>
            </tr>
            <?php elseif (($_GET['project_id'] ?? "") > count($projects)) : {
                http_response_code(404);
            }?>
            <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</main>
