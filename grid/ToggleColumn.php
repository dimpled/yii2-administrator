<?php

namespace dimple\administrator\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;
use Yii;

class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     * @var string
     */
    public $action = 'toggle';

    public $linkOptions = [
      'class'=>'toggle-column ',
      'data-method' => 'post',
      'data-pjax' => '0'
    ];

    public $url;

    public $optionOn = ['icon'=>'check', 'label'=>'Yes', 'btnColor'=>'btn-success'];

    public $optionOff = ['icon'=>false, 'label'=>'No', 'btnColor'=>'btn-default'];

    /**
     * Whether to use ajax or not
     * @var bool
     */
    public $enableAjax = true;

    public function init()
    {
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }


    protected function renderDataCellContent($model, $key, $index)
    {
      $this->url = [$this->action, 'id' => $model->primaryKey];
      $fnc = $this->getDataCellValue($model, $key, $index);
      if(is_bool($fnc)){
        return $this->renderDataCellContentDefault($model, $key, $index);
      }else{
        return $this->grid->formatter->format($fnc, 'html');
      }

    }
    /**
     * @inheritdoc
     */
    protected function renderDataCellContentDefault($model, $key, $index)
    {
        $status = $this->getDataCellValue($model, $key, $index);

        if($status===true){
            extract ($this->optionOn);
        }else{
            extract ($this->optionOff);
        }

        if(!isset($btnColor)) $btnColor='';
        if(!isset($icon)) $icon=false;
        if(!isset($label)) $label='';

        return Html::a(
            ($icon===false?'':'<span class="glyphicon glyphicon-' . $icon . '"></span> ').$label,
            $this->url,
            ArrayHelper::merge($this->linkOptions,[
                'title' => $label,
                'class' => 'toggle-column btn btn-block btn-sm '.$btnColor,
                'data-method' => 'post',
                'data-pjax' => '0'
            ])
        );
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $js = <<<'JS'

/*================= ToggleColumn Update ==============================*/
$("a.toggle-column").on("click", function(e) {
    e.preventDefault();
    $.post($(this).attr("href"), function(data) {
        var pjaxId = $(e.target).closest(".grid-view").parent().attr("id");
        $.pjax.reload({container:"#" + pjaxId});
    });
    return false;
});

JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'pheme-toggle-column');
    }
}