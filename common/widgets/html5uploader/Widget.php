<?php

namespace common\widgets\html5uploader;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class Widget
 * Виджет загрузки файлов основанный на html5
 * @package common\widgets\html5uploader
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class Widget extends InputWidget
{

    /**
     * @var string шаблон
     */
    public $tpl = "uploader";

	/**
     * @var string алиас DOCUMENT ROOT
     */
    public $webroot = "@webroot";

	/**
     * @var string url для загрузки файлов
     */
    public $uploadUrl;

    /**
     * @var int максимальный размер загружаемого файла
     */
    public $maxFileSize;

	/**
	 * @var array расширения доступные для загрузки
	 */
	public $allowedExt = ["jpg", "jpeg", "gif", "png", "pdf", "doc", "docx", "xsl", "xslx", "odt", "ppt", "zip", "rar", "gz", "tar", "swf", "csv"];

	/**
     * @var \yii\web\AssetBundle класса бандла ресурсов
     */
    protected $assetClass;

    /**
     * @inheritdoc
     */
    public function init()
    {

        $assetClass = $this->getAssetClass();

        $assetClass::register($this->view);

        $this->name = Html::getInputName($this->model, $this->attribute);

        $this->options = array_merge(["multiple" => true, "id" => $this->id], $this->options);

        $this->registerScripts();

    }

    /**
     * Возвращает класс модуля ресурсов
     * @return string
     */

    public function getAssetClass()
    {

        if ($this->assetClass === null) {

            $this->assetClass = AssetBundle::className();

        }

        return $this->assetClass;

    }

    /**
     * Устанавливает класс модуля ресурсов
     * @param string $class имя класса
     */

    public function setAssetClass($class)
    {

        $this->assetClass = $class;

    }

    /**
     * Решистрирует javascript инициализации виджета
     */

    protected function registerScripts()
    {

        // @TOFIX сделать ограничение на загружаемые расширения

		$params = [
			'uploadUrl' => $this->uploadUrl,
			'maxFileSize' => $this->maxFileSize,
			'allowedExt' => $this->allowedExt,
		];

        $this->view->registerJs("
            $('#{$this->id}').html5Uploader(".Json::encode($params).");

            $('.uploader-widget-files-list').sortable({
                update: function( event, ui ) {

                    ui.item.parents('ul').find('li').each(function(){

                        var li = $(this);
                        var i = li.index();

                        li.find('input').each(function(){

                            var name = $(this).attr('name');
                            name = name.replace(/\\[\\d+\\]/, '['+i+']');
                            $(this).attr('name', name);

                        });
                    });
                }
            });
        ");

    }

    /**
     * @inheritdoc
     */

    public function run()
    {

        $files = $this->model->{$this->attribute};

        return $this->render($this->tpl, [

            "name" => $this->name,
            "options" => $this->options,
            "maxFileSize" => $this->model->getMaxFileSize(),
            "files" => $files,
            "webroot" => $this->webroot,
        ]);

    }

}
