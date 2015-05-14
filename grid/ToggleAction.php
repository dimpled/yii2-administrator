<?php

namespace dimple\administrator\grid;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\web\MethodNotAllowedHttpException;

class ToggleAction extends Action
{
    /**
     * @var string name of the model
     */
    public $modelClass;

    /**
     * @var string model attribute
     */
    public $attribute = 'active';

    /**
     * @var string|array additional condition for loading the model
     */
    public $Where=[];

    /**
     * @var string|int|boolean what to set active models to
     */
    public $onValue = 1;

    /**
     * @var string|int|boolean what to set inactive models to
     */
    public $offValue = 0;

    /**
     * @var bool whether to set flash messages or not
     */
    public $setFlash = false;

    /**
     * @var string flash message on success
     */
    public $flashSuccess = "Model saved";

    /**
     * @var string flash message on error
     */
    public $flashError = "Error saving Model";

    /**
     * @return string flash message on error
     */
    public $redirect;

    public $setAttributes;


    public function getSetValue($model, $value, $attribute)
    {
        return call_user_func($this->setAttributes, $model, $value, $attribute);
    }

    /**
     * Run the action
     * @param $id integer id of model to be loaded
     *
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
    public function run($id)
    {
        if (!Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }
        $id = (int)$id;

        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }

        $modelClass = $this->modelClass;
        $attribute = $this->attribute;

        if(is_array($this->Where) && count($this->Where)>0){
            $model = $modelClass::findOne($this->Where);
        }else{
            $model = $modelClass::findOne($id);
        }

        if (!$model->hasAttribute($this->attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist");
        }

        $model->$attribute = $this->getSetValue($model,ArrayHelper::getValue($model, $this->attribute),$this->attribute);

        if ($model->save()) {
            if ($this->setFlash) {
                Yii::$app->session->setFlash('success', $this->flashSuccess);
            }
        } else {
            if ($this->setFlash) {
                Yii::$app->session->setFlash('error', $this->flashError);
            }
        }
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->end();
        }

        /* @var $controller \yii\web\Controller */
        $controller = $this->controller;
        if (!empty($this->redirect)) {
            return $controller->redirect($this->redirect);
        }
        return $controller->redirect(Yii::$app->request->getReferrer());
    }
}