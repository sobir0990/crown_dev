<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "market".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $working_hours
 * @property int $longitude
 * @property int $latitude
 * @property int $created_at
 * @property int $updated_at
 */

/**
 * @OA\Schema(
 *     description=""
 * )
 */
class Market extends \yii\db\ActiveRecord
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
     *   property="address",
     *   type="string",
     *   description="Address"
     * )
     */
    /**
     * @OA\Property(
     *   property="working_hours",
     *   type="string",
     *   description="Working Hours"
     * )
     */
    /**
     * @OA\Property(
     *   property="longitude",
     *   type="integer",
     *   description="Longitude"
     * )
     */
    /**
     * @OA\Property(
     *   property="latitude",
     *   type="integer",
     *   description="Latitude"
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
        return 'market';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['longitude', 'latitude', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['longitude', 'latitude', 'created_at', 'updated_at'], 'integer'],
            [['name', 'address', 'working_hours'], 'string', 'max' => 254],
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
            'address' => 'Address',
            'working_hours' => 'Working Hours',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
