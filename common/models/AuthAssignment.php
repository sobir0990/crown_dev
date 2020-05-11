<?php

namespace common\models;

use Yii;

/**
* This is the model class for table "auth_assignment".
*

    * @property string $item_name
    * @property string $user_id
    * @property int $created_at
        *
            * @property AuthItem $itemName
    */
/**
* @OA\Schema(
*     description="include=itemName"
* )
*/
class AuthAssignment extends \yii\db\ActiveRecord
{
        /**
    * @OA\Property(
    *   property="item_name",
    *   type="string",
    *   description="Item Name"
    * )
    */
        /**
    * @OA\Property(
    *   property="user_id",
    *   type="string",
    *   description="User ID"
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
* {@inheritdoc}
*/
public static function tableName()
{
return 'auth_assignment';
}

/**
* {@inheritdoc}
*/
public function rules()
{
return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'default', 'value' => null],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
//            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
}

/**
* {@inheritdoc}
*/
public function attributeLabels()
{
return [
    'item_name' => 'Item Name',
    'user_id' => 'User ID',
    'created_at' => 'Created At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
//    public function getItemName()
//    {
//    return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
//    }


    public function extraFields()
    {
    $fields = parent::extraFields();
            $fields['itemName'] = "itemName";
        return $fields;
    }
}
