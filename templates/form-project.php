<section class="content__side"><?= $sidebar; ?></section>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="project_name">Название <sup>*</sup></label>
                <?php if (isset($errors)) {
                    $classname = $errors["name"] ? "form__input--error" : "";
                } ?>
                <input class="form__input <?= $classname; ?>" type="text" name="name" id="project_name" value="<?=get_post_val('name');?>" placeholder="Введите название проекта">
                <?php if (isset($errors)) {
                    if ($errors["name"]) : ?>
                        <p class="form__message"><?= $errors["name"];?></p>
                    <?php endif;
                } ?>

                <?php if (isset($errors)) {
                    if ($errors['project']) : ?>
                        <p class="form__message"><?= $errors["project"];?></p>
                    <?php endif;
                } ?>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>