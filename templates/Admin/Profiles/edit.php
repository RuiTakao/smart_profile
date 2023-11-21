<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
プロフィール設定 > 編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/profiles') ?>
<?php $this->end() ?>

<p class="content_title">プロフィール編集<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
<?= $this->Form->create($profile, [
    'url' => ['controller' => 'Profiles', 'action' => 'edit'],
    'onSubmit' => 'return checkEdit()'
]) ?>
<div class="profile">
    <div class="flex">
        <div class="flex_left ">
            <div class="profile_image">
                <?php if (is_null($profile->image_path) || !file_exists(WWW_ROOT . 'img/users/profiles/' . $auth->username . '/' . $profile->image_path)) : ?>
                    <div class="image_blank"></div>
                <?php else : ?>
                    <?= $this->Html->image('users/profiles/' . $auth->username . '/' . $profile->image_path) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex_right">
            <ul class="profile_content_list" style="padding: 0;">
                <li class="profile_content_item">
                    <?= $this->Form->control('view_name', [
                        'label' => '名前（表示名）',
                        'value' => $profile->view_name
                    ]) ?>
                </li>
                <li class="profile_content_item">
                    <?= $this->Form->control('works', [
                        'label' => '肩書（仕事名）',
                        'value' => $profile->works
                    ]) ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile_text">
        <?= $this->Form->control('profile_text', [
            'type' => 'textarea',
            'label' => 'プロフィール文',
            'value' => $profile->profile_text
        ]) ?>
    </div>
</div>
<?= $this->Form->submit('この内容で変更する',  ['class' => 'button']) ?>
<?= $this->Form->end() ?>