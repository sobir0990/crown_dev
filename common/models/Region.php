<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User[] $users
 */

/**
 * @OA\Schema(
 *     description="include=users"
 * )
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   description="ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="name",
     *   type="string",
     *   description="Name"
     * )
     */
    /**
     * @OA\Property(
     *   property="status",
     *   type="integer",
     *   description="Status"
     * )
     */
    /**
     * @OA\Property(
     *   property="created_at",
     *   type="integer",
     *   description="Created At"
     * )
     */
    /**
     * @OA\Property(
     *   property="updated_at",
     *   type="integer",
     *   description="Updated At"
     * )
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 254],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['region_id' => 'id']);
    }


    public function extraFields()
    {
        $fields = parent::extraFields();
        $fields['users'] = "users";
        return $fields;
    }
}
