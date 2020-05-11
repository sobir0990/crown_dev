<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 17.07.2019
 * Time: 9:22
 */

namespace common\components;

use oks\langs\components\Lang;
use yii\base\Behavior;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class ModelLangBehavior
 * @package common\components
 *
 * /**
 *  public function behaviors()
 *  {
 *      return array(
 *          'langs' => [
 *              'class' => ModelLangBehavior::class,
 *              'modelClass' => "This called class"
 *          }
 *      );
 *  }
 */
class ModelLangBehavior extends Behavior
{
    /**
     * @var string
     */
    public $lang = 'lang';

    /**
     * @var string
     */
    public $lang_hash = 'lang_hash';

    /**
     * @var string Your lang table
     */
    public $langs_table = 'langs';

    /**
     * @var string Lang attribute = "en", "ru"
     */
    public $lang_attribute_code = 'code';

    /**
     * @var string
     */
    public $lang_attribute_id = 'lang_id';

    /**
     * @var string
     */
    public $modelTable;

    /**
     * @var string
     */
    public $modelTablePK = 'id';

    /**
     * @return array
     */
    public function events()
    {
        return array(
            ActiveRecord::EVENT_AFTER_FIND => 'beforeFind'
        );
    }


    /**
     * @return array
     */
    public function langs()
    {
        return $this->beforeFind();
    }

    public function beforeFind()
    {
        $query = new Query();
        $query->select([$this->lang_attribute_id, $this->lang_attribute_code])
            ->from($this->langs_table);

        $langs = $query->all();

        return $this->getObjects($langs);
    }


    private function getObjects($langs)
    {
        $data = [];
        foreach ($langs as $lang) {
            if ($lang[$this->lang_attribute_code] == \Yii::$app->language) continue;
            $data[$lang[$this->lang_attribute_code]] = $this->getData($lang);
        }
        return $data;
    }

    private function getData($lang)
    {
        $query = new Query();
        $query->select($this->modelTablePK)
            ->from($this->modelTable)
            ->andWhere([$this->lang_hash => $this->owner->{$this->lang_hash}]);

        $id = $query->andWhere([$this->lang => $lang[$this->lang_attribute_id]])
            ->one()[$this->modelTablePK];

        return $id;
    }
}