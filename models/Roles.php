<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Roles".
 *
 * @property int $id
 * @property string $name
 * @property string $information
 * @property string $createAt
 * @property string $updateAt
 *
 * @property User[] $users
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'information'], 'required'],
            [['createAt', 'updateAt'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['information'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'information' => 'Information',
            'createAt' => 'Create At',
            'updateAt' => 'Update At',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }
}
