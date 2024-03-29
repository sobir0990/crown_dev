<?php
/**
 *  @author Jakhar <jakharbek@gmail.com>
 *  @company OKS Technologies <info@oks.uz>
 *  @package YoshlarTV
 */

namespace common\modules\translations\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $id0
 */
class Message extends \yii\db\ActiveRecord
{
    const DESCRIPTION = "Переводы";
    const PERMESSION_ACCESS = "permession_translations";
    const ROLE = "admin";
    const ROLE_PARENT = null;

    public static function primaryKey($asArray = false)
    {
        return array(
            'id', 'language'
        );
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_system_message_translation';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[

        ]); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'default', 'value' => null],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language' => 'Language',
            'translation' => 'Translation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
}
