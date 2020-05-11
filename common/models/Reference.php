<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reference".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $type
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */

/**
 * @OA\Schema(
 *     description=""
 * )
 */
class Reference extends ActiveRecord
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
     *   property="phone",
     *   type="string",
     *   description="Phone"
     * )
     */
    /**
     * @OA\Property(
     *   property="description",
     *   type="string",
     *   description="Description"
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
        return 'reference';
    }

    public function behaviors()
    {
        return [
          TimestampBehavior::className()
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name', 'phone'], 'string', 'max' => 254],
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
            'phone' => 'Phone',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
