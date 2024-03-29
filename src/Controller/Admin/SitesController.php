<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Sites Controller
 *
 * @property SitesTable $Sites
 * @property ProfilesTable $Profiles
 */
class SitesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Sites = TableRegistry::getTableLocator()->get('Sites');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');

        // トランザクション変数
        $this->connection = $this->Sites->getConnection();
    }

    /**
     * サイト管理画面
     * 
     * @return Response|void|null
     */
    public function index()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        // viewに渡すデータセット
        $this->set('site', $site);
    }

    /**
     * サイト編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function edit()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);

            // バリデーション処理
            if ($site->getErrors()) {
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                $this->set('site', $site);
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
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
        $this->set('site', $site);
    }

    /**
     * ファビコン編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function editFaviconImage()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            if ($data['favicon_path']->getClientFilename() == '' || $data['favicon_path']->getClientMediaType() == '') {

                // アップロードされていなければ処理せず変更完了
                $this->session->write('message', Configure::read('alert_message.complete'));
                return $this->redirect(['action' => 'index']);
            }

            // 画像データを変数に格納
            $image = $data['favicon_path'];

            // 画像名をリクエストデータに代入
            $data['favicon_path'] = $data['favicon_path']->getClientFilename();

            // バリデーション
            if (!in_array(pathinfo($data['favicon_path'])['extension'],  Configure::read('extensions'))) {
                $site->setError('favicon_path', Configure::read('alert_message.file_extensions_faild'));
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                $this->set('site', $site);
                return;
            }

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);
            if ($site->getErrors()) {
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // ディレクトリに画像保存
                $path = SitesTable::ROOT_FAVICON_PATH . $this->AuthUser->username;
                if (file_exists($path)) {
                    // 既に画像がある場合は削除
                    foreach (glob($path . '/*') as $old_file) {
                        unlink($old_file);
                    }
                    $image->moveTo($path . '/' . $data['favicon_path']);
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
        $this->set('site', $site);
    }
}
