<section class="content__side"><?= $sidebar; ?></section>
<main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"   method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="title">Название <sup>*</sup></label>
                <?php $classname = $errors["title"] ? "form__input--error" : ""; ?>
                <input class="form__input <?= $classname; ?>" type="text" name="title" id="title" value="<?=get_post_val('title');?>" placeholder="Введите название">
                <?php if ($errors["title"]) : ?>
                    <p class="form__message"><?= $errors["title"];?></p>
                <?php endif; ?>
            <div>

            <div class="form__row">
                <label class="form__label" for="project">Проект <sup>*</sup></label>
                <?php $classname = $errors['project'] ? "form__input--error" : ""; ?>
                <select class="form__input form__input--select <?=$classname;?>" name="project" id="project">
                    <?php foreach ($projects as $project) : ?>
                    <?php if ($_SESSION['id'] === (int)$project['user_id']) : ?>
                    <option value="<?= $project['id']?>"><?= $project['name'];?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <?php if ($errors['project']) : ?>
                    <p class="form__message"> <?= $errors['project'];?></p>
                <?php endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="date_finish">Дата выполнения</label>
                <?php $classname = $errors['date_finish'] ? "form__input--error" : ""; ?>

                <input class="form__input form__input--date <?=$classname;?>" name="date_finish" id="date_finish" value="<?=get_post_val('date_finish');?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <?php if ($errors['date_finish']) : ?>
                    <p class="form__message"> <?= $errors['date_finish'];?></p>
                <?php endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="file">Файл</label>

                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="file" id="file" value="<?= $_POST['file'];?>">
                    <?php if ($errors["file"]) : ?>
                        <p class="form__message"><?= $errors["file"];?></p>
                    <?php endif; ?>
                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                </div>
            </div>
            <?php if ($errors["name"]) : ?>
                <p class="form__message"><?= $errors["name"];?></p>
            <?php endif; ?>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>

