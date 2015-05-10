<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace dimple\administrator\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class SystemInformationController extends Controller
{
    public function actionIndex()
    {
        $provider = \probe\Factory::create();
        echo $provider->getCpuModel();
        $cpu =  $provider->getCpuUsage();
        print_r($cpu);
        echo $provider->getFreeMem();


        // if ($provider) {
        //     if (Yii::$app->request->isAjax) {
        //         Yii::$app->response->format = Response::FORMAT_JSON;
        //         if ($key = Yii::$app->request->get('data')) {
        //             switch($key){
        //                 case 'cpu_usage':
        //                     return$provider->getCpuUsage();
        //                     break;
        //                 case 'memory_usage':
        //                     return ($provider->getTotalMem() - $provider->getFreeMem()) / $provider->getTotalMem();
        //                     break;
        //             }
        //         }
        //     } else {
        //         return $this->render('index', ['provider' => $provider]);
        //     }
        // } else {
        //     return $this->render('fail');
        // }
    }
}