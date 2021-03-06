<?php
namespace common\db\fields;

use yii\helpers\ArrayHelper;
use Yii\widgets\ActiveForm;

/**
 * Class PasswordField
 * Поле ввода пароля модели
 * @package common\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class PasswordField extends TextField
{

    /**
     * Длина пароля
     */

    const PASSWORD_LENGTH = 6;

    /**
     * @inheritdoc
     */

    public function getForm(ActiveForm $form, Array $options = [], $index = false)
    {

		$options = ArrayHelper::merge($this->options, $options);

        return $form->field($this->model, $this->getFormAttrName($index))->passwordInput($options);

    }

    /**
     * Правила валидации
     * @return array
     */

    public function rules()
    {

        $rules = parent::rules();

        $rules[] = [$this->attr, 'string', 'min' => self::PASSWORD_LENGTH];

        return $rules;

    }

}