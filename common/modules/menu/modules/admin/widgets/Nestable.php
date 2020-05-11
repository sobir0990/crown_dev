<?php
/**
 * @author Jakhar <jakharbek@gmail.com>
 * @author Maqsud <https://github.com/maqsudkarimov>
 * @author O`tkir   <https://t.me/Utkir24>
 * @company OKS Technologies <info@oks.uz>
 * @package Task Manager
 */

namespace common\modules\menu\modules\admin\widgets;

use common\modules\menu\models\Menu;
use jakharbek\langs\components\Lang;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Class Nestable
 * @package common\modules\subjects\modules\admin\widgets
 */
class Nestable extends Widget
{
    /**
     * @var string
     */
    public $id = 'nestable';

    /**
     * @var
     */
    public $items;

    /**
     * @var
     */
    public $url;

    /**
     * @return string
     */
    public function run()
    {
        $html = '';

        foreach ($this->items as $item)
            $html .= $this->renderItem($item);

        $delete_action = Url::to(['menu/delete']);

        $confirm_text = Yii::t('main', '"Do you Really wont to remove this?"');

        $script = <<<JS

			$('#{$this->id}').nestable({
				maxDepth: 100
			}).on('change', function () 
			{
				var items = $('#{$this->id}').nestable('serialize');
				var data = {};
				
				data.data = JSON.stringify(items);
				
				$.post('{$this->url}', data);
			});

			$('#{$this->id} .menu-item-delete').on('click', function(e) 
			{
				e.preventDefault();
				var id = $(this).data('menu-id');
				if( id )
				{
					var isConfirmed = confirm('{$confirm_text}');
					
					if( isConfirmed )
					{
						$.ajax({
							url: '{$delete_action}?id=' + id,
							type: 'POST',
							success: function(){
								$.pjax.reload({container: "#menu-pjax"});
							}
						});
					}
				}
			});

JS;
        Yii::$app->view->registerJS($script);

        return $html;
    }

    /**
     * @param $item
     * @return string
     */
    protected function renderItem($item)
    {
        $html = '';
        $html .= $this->startRenderItem($item);
        $html .= $this->renderItemContent($item);

        $child_items = Menu::find()->where(['parent_id' => $item->id, 'lang' => Lang::getLangId()])->orderBy('sort', SORT_ASC);

        if ($child_items->exists()) {
            $html .= $this->renderItemChilds($child_items->all());
        }

        $html .= $this->endRenderItem($item);

        return $html;
    }

    /**
     * @param $item
     * @return string
     */
    protected function startRenderItem($item)
    {
        return '<li class="dd-item dd3-item" data-id="' . $item->id
            . '"><div class="dd-handle dd3-handle">Drag</div>';
    }

    /**
     * @param $item
     * @return string
     */
    protected function renderItemContent($item)
    {
        $html = '';
        $html .= '<div class="dd3-content">
                    ' . $item->title . '
                    <div class="dd-action-btns" style="float: right">
					    <a data-pjax="0" href="' . Url::to(['menu/update-menu', 'id' => $item->id]) . '" class="btn dd-edit-btn">
					        <i class="fa fa-pencil"></i>
                        </a>
					    <a class="btn menu-item-delete" data-menu-id="' . $item->id . '">
					        <i class="fa fa-trash"></i>
                        </a>
				   </div>
				  </div>
				  ';

        return $html;
    }

    /**
     * @param $child_items
     * @return string
     */
    protected function renderItemChilds($child_items)
    {
        $html = '';
        $html .= $this->startRenderItemChild($child_items);
        foreach ($child_items as $child_item) {
            $html .= $this->renderItem($child_item);
        }
        $html .= $this->endRenderItemChild($child_items);

        return $html;
    }

    /**
     * @return string
     */
    protected function startRenderItemChild()
    {
        return '<ol class="dd-list">';
    }

    /**
     * @return string
     */
    protected function endRenderItemChild()
    {
        return '</ol>';
    }

    /**
     * @return string
     */
    protected function endRenderItem()
    {
        return '</li>';
    }
}
