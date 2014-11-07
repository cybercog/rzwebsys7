<?php
namespace common\db\fields;

/**
 * Class HasOneTrait
 * Поле для связей Has One.
 * @package common\db\fields
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
trait HasOneTrait {

	/**
	 * @var string имя связи
	 */

	public $relation;

	/**
	 * @var string имя атрибута связанной модели отображаемого в гриде
	 */

	public $gridAttr = "title";

	/**
	 * @inheritdoc
	 */
	protected function grid()
	{

		$grid = $this->defaultGrid();

		$grid["value"] = function ($model, $index, $widget) {

			return ArrayHelper::getValue($model, "{$this->relation}.{$this->gridAttr}", $model->{$this->attr});

		};

		return $grid;

	}

	/**
	 * @inheritdoc
	 */
	protected function view()
	{

		$view = $this->defaultView();

		$view["value"] = ArrayHelper::getValue($this->model, "{$this->relation}.{$this->gridAttr}", $this->model->{$this->attr});

		return $view;

	}

}