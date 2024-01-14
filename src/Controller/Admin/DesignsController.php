<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\DesignsTable;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\ORM\TableRegistry;

/**
 * Designs Controller
 *
 * @method \App\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DesignsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Designs = TableRegistry::getTableLocator()->get('Designs');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');

        // トランザクション変数
        $this->connection = $this->Designs->getConnection();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // ログインidからデータ取得
        $design = $this->Designs->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        $top_layout = 'top_layout/pattern' . $design->fv_design . '.jpg';

        // viewに渡すデータセット
        $this->set('design', $design);
        $this->set('top_layout', $top_layout);
    }

    public function editFvDesign()
    {
        // ログインidからデータ取得
        $design = $this->Designs->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $design = $this->Designs->patchEntity($design, $data);

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Designs
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Designs->save($design);
                if (!$ret) {
                    throw new DatabaseException();
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }

            // 完了画面へリダイレクト
            $this->session->write('message', Configure::read('alert_message.complete'));
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('design', $design);
    }

    public function editFvImagePc()
    {
        return $this->editFvImage('fv_image_path', DesignsTable::ROOT_FV_IMAGE_PC_PATH);
    }

    public function editFvImageSp()
    {
        return $this->editFvImage('fv_image_sp_path', DesignsTable::ROOT_FV_IMAGE_SP_PATH);
    }

    public function settingFvImagePc()
    {
        return $this->settingFvImage();
    }

    public function settingFvImageSp()
    {
        return $this->settingFvImage();
    }

    private function editFvImage($column, $root_path)
    {
        // ログインidからデータ取得
        $design = $this->Designs->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            if ($data[$column]->getClientFilename() == '' || $data[$column]->getClientMediaType() == '') {

                // アップロードされていなければ処理せず変更完了
                $this->session->write('message', Configure::read('alert_message.complete'));
                return $this->redirect(['action' => 'index']);
            }

            // 画像データを変数に格納
            $image = $data[$column];

            // 画像名をリクエストデータに代入
            $data[$column] = $data[$column]->getClientFilename();

            // バリデーション
            if (!in_array(pathinfo($data[$column])['extension'], Configure::read('extensions'))) {
                $design->setError($column, Configure::read('alert_message.file_extensions_faild'));
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                $this->set('design', $design);
                return;
            }

            // エンティティにデータセット
            $design = $this->Designs->patchEntity($design, $data);
            if ($design->getErrors()) {
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Designs
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Designs->save($design);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // ディレクトリに画像保存
                $path = $root_path . $this->AuthUser->username;
                if (file_exists($path)) {
                    // 既に画像がある場合は削除
                    foreach (glob($path . '/*') as $old_file) {
                        unlink($old_file);
                    }
                    $image->moveTo($path . '/' . $data[$column]);
                } else {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }

            // 完了画面へリダイレクト
            $this->session->write('message', Configure::read('alert_message.complete'));
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('design', $design);
    }

    private function settingFvImage()
    {
        $this->viewBuilder()->disableAutoLayout();

        // ログインidからデータ取得
        $design = $this->Designs->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $design = $this->Designs->patchEntity($design, $data);
            if ($design->getErrors()) {
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Designs
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Designs->save($design);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }
            $this->session->write('message', Configure::read('alert_message.complete'));
        }

        // viewに渡すデータセット
        $this->set('design', $design);
        $this->set('profile', $profile);
    }
}
