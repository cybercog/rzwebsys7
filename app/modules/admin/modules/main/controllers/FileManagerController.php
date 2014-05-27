<?

namespace app\modules\admin\modules\main\controllers;

use common\controllers\Admin;

/**
 * Class FileManagerController
 * Контроллер файлового менеджера
 * @package app\modules\admin\modules\main\controllers
 * @author Churkin Anton <webadmin87@gmail.com>
 */

class FileManagerController extends Admin {


    /**
     * Отображение менеджера файлов
     * @return string
     */

    public function actionIndex() {

        return $this->render('index');

    }


}