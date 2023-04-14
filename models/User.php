<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property int $status
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property string $createAt
 * @property string $updateAt
 * @property int $role_id
 *
 * @property Roles $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 10;
    CONST STATUS_INACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'email', 'password', 'status', 'role_id'], 'required'],
            [['role_id'], 'integer'],
            [['createAt', 'updateAt'], 'safe'],
            [['firstName', 'lastName', 'password'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
            [['authKey', 'accessToken'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::class, 'targetAttribute' => ['role_id' => 'id']],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'createAt' => 'Create At',
            'updateAt' => 'Update At',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::class, ['id' => 'role_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function getFullName()
    {
        return $this->firstName." R".$this->lastName;
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }
}
