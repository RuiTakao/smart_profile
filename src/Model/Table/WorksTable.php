<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Works Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Work newEmptyEntity()
 * @method \App\Model\Entity\Work newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Work[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Work get($primaryKey, $options = [])
 * @method \App\Model\Entity\Work findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Work patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Work[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Work|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Work saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Work[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Work[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Work[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Work[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorksTable extends Table
{

    /**
     * メッセージ
     */
    const INVALID_EXTENSION_MESSAGE = '拡張子が無効です。';

    /**
     * 画像ファイルパス
     */
    // 画像表示用のパス
    const WORKS_IMAGE_PATH = 'users/works/';
    // ルートからの相対パス
    const ROOT_WORKS_IMAGE_PATH = WWW_ROOT . 'img/' . self::WORKS_IMAGE_PATH;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('works');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->maxLength('title', 50, 'タイトルは50文字以内で入力してください。')
            ->notBlank('title', 'タイトルは必須です。');

        $validator
            ->maxLength('overview', 400, '概要は400文字以内で入力してください。')
            ->notBlank('overview', '概要は必須です。');

        $validator
            ->maxLength('url_path', 255, 'URLは255文字以内で入力してください。')
            ->url('url_path', 'URLを入力してください。')
            ->allowEmptyString('url_path');

        $validator
            ->maxLength('url_name', 50,  'URL名は50文字以内で入力してください。')
            ->allowEmptyString('url_name');


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
