<?php
namespace app\modules\shop\components;

use yii\di\ServiceLocator;
use app\modules\shop\models\Good;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class Basket
 * Корзина. Сохраняет и получает заказ из сессии
 * @property \app\modules\shop\components\OrderManager $orderManager компонент получения текущего заказа
 * @package app\modules\shop\components
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class Basket extends ServiceLocator
{

	/**
	 * @var array массив атрибутов моделей, которые необходимо сохранять в заказе.
	 *
	 * Должен иметь следующий вид:
	 *
	 * [
	 * 		'app\modules\catalog\models\Catalog' => [
	 *
	 * 			"articul"=>"articul",
	 * 			"color"=>"color.title",
	 * 		]
	 * ]
	 */
	public $attributesToSave = [];

	/**
	 * Добавление элемента каталога в корзину
	 * @param int $id идентификтор элемента каталога
	 * @param string $class класс элемента каталога
	 * @param int $qty количество
	 * @throws ErrorException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function add($id, $class, $qty = 1)
	{

		$model = $class::findOne($id);

		if(!$model OR !$model instanceof IShopItem)
			throw new ErrorException("Request model does`t implement \\app\\modules\\shop\\components\\IShopItem interface");

		$good = Yii::createObject(Good::className());

		$good->qty = $qty;

		$this->configureGood($good, $model);

		$order = $this->getOrder();

		$order->addNewGood($good);

		$this->orderManager->saveOrder($order);

	}

	/**
	 * Обновляет количество добавленного в корзину товара
	 * @param int $id идентификатор товара
	 * @param string $class класс модели товара
	 * @param int $qty количество
	 */
	public function updateNewQty($id, $class, $qty)
	{

		$this->removeNew($id, $class);

		$this->add($id, $class, $qty);

	}

	/**
	 * Удаляет новый товар из заказа
	 * @param int $itemId идентификатор элемента каталога
	 * @param string $itemClass класс элемента каталога
	 * @return bool
	 */
	public function removeNew($itemId, $itemClass)
	{
		$order = $this->getOrder();

		$res = $order->removeNewGood($itemId, $itemClass);

		if($res) {
			$this->orderManager->saveOrder($order);
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Удаляет товар из заказа
	 * @param int $id идентификатор заказанного товара
	 * @throws \yii\base\InvalidConfigException
	 */
	public function remove($id)
	{
		$model = Good::findOne($id);
		$this->getOrder()->removeGood($model);
	}

	/**
	 * Установка свойств модели заказанного товара из модели товара
	 * @param Good $good заказанный товар
	 * @param IShopItem $model товар
	 */
	protected function configureGood(Good $good, IShopItem $model)
	{

		$class = get_class($model);

		$good->item_id = $model->id;
		$good->item_class = $class;
		$good->title = $model->getShopTitle();
		$good->price = $model->getPrice();
		$good->discount = $model->getDiscount();
		$good->link = Url::toRoute($model->getLink());

		$arr = [];

		if(!empty($this->attributesToSave[$class])) {

			foreach($this->attributesToSave[$class] AS $k=>$v) {

				$arr[$k]= ArrayHelper::getValue($model, $v);

			}


		}

		$good->attrs = $arr;

	}

	/**
	 * Возвращает объект заказа
	 * @return \app\modules\shop\models\Order
	 */
	public function getOrder()
	{
		return $this->orderManager->getOrder();
	}

	/**
	 * Возвращает данные по количеству товаров в заказ и их общей стоимости
	 * @return mixed
	 */
	public function getStat()
	{

		$order = $this->getOrder();

		$arr["count"] = $order->countAllGoods();

		$arr["summ"] = $order->getGoodsPrice();

		return $arr;

	}

    /**
     * Установка свойств заказа
     * @param array $data
     * @return \app\modules\shop\models\Order
     */
    public function setOrder($data)
    {

        $order = $this->getOrder();

        if($order->load($data)) {

			$order->reloadRelated();

            $order->calcDeliveryPrice();

            $this->orderManager->saveOrder($order);

        }

        return $order;

    }

}