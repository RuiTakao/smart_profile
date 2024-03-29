<div class="fv">
    <div class="fv_bg"></div>
    <div class="fv_user_icon">
        <?php if (!empty($profile_image)) : ?>
            <?= $this->Html->image($profile_image) ?>
        <?php else : ?>
            <i class="fa-solid fa-user"></i>
        <?php endif; ?>
    </div>
    <div class="fv_user_content">
        <p class="fv_user_name"><?= h($profile->view_name) ?></p>
        <p class="fv_user_works"><?= h($profile->works) ?></p>
    </div>
</div>

<div class="pr">
    <div class="container">
        <div class="pr_content"><?= !empty($profile->profile_text) ? nl2br(h($profile->profile_text)) : '' ?></div>
    </div>
</div>