<?php
namespace common\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class Delete
 * Класс действия для удаления модели
 * @package common\actions\crud
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class Delete extends Base
{

    /**
     * Запуск действия удаления модели
     * @param integer $id идентификатор модели
     * @throws \yii\web\ForbiddenHttpException
     * @return void|\yii\web\Response
     */

    public function run($id)
    {

        if (Yii::$app->request->isPost) {

            $model = $this->findModel($id);

            if (!Yii::$app->user->can('deleteModel', array("model" => $model)))
                throw new ForbiddenHttpException('Forbidden');

            $model->delete();

        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

    }

}