<?php

use App\Model\Table\ProfilesTable;
use Cake\Core\Configure;

// プロフィール画像が設定されているか判定
if (is_null($profile->image_path) || !file_exists(ProfilesTable::ROOT_PROFILE_IMAGE_PATH)) {
    $profile_image_path = ProfilesTable::BLANK_PROFILE_IMAGE_PATH;
} else {
    $profile_image_path = ProfilesTable::PROFILE_IMAGE_PATH .  $auth->username . '/' . $profile->image_path;
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
プロフィール設定
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/profiles') ?>
<?php $this->end() ?>

<div class="profile">
    <div class="flex">
        <div class="flex_left ">
            <div class="profile_image">
                <?= $this->Html->image($profile_image_path) ?>
            </div>
            <?= $this->Html->link(Configure::read('button.image_edit'), ['action' => 'edit_image'], ['class' => 'button edit_image']) ?>
        </div>
        <div class="flex_right" style="padding-bottom: 48px;">
            <ul class="profile_content_list">
                <li class="profile_content_item border_bottom">
                    <span class="title">名前（表示名）</span><span class="text"><?= h($profile->view_name) ?></span>
                </li>
                <li class="profile_content_item border_bottom">
                    <span class="title">肩書（仕事名）</span><span class="text"><?= h($profile->works) ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile_text">
        <p class="title">プロフィール文</p>
        <p class="text"><?= !empty($profile->profile_text) ? nl2br(h($profile->profile_text)) : '' ?></p>
    </div>
</div>
<?= $this->Html->link(
    Configure::read('button.edit'),
    [
        'controller' => 'Profiles', 'action' => 'edit'
    ],
    [
        'class' => 'button',
        'style' => 'margin-top: 32px;'
    ]
) ?>