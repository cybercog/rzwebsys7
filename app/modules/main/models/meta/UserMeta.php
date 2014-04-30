<?php
namespace app\modules\main\models\meta;

use \Yii;
use common\db\MetaFields;

/**
 * Class UserMeta
 * Описание полей модеди User
 * @package app\modules\main\models\meta
 * @author Churkin Anton <webadmin87@gmail.com>
 */

class UserMeta extends MetaFields {

    /**
     * @inheritdoc
     */

    public function config() {

        return [

            [
                "definition"=>[
                    "class"=>\common\db\fields\CheckBoxField::className(),
                    "title"=>Yii::t('core', 'Active'),
                ],
                "params"=>[$this->owner, "active"]
            ],

            [
                "definition"=>[
                    "class"=>\common\db\fields\ListField::className(),
                    "title"=>Yii::t('core', 'Role'),
                    "isRequired"=>true,
                    "data"=>$this->owner->getRolesNames(),
                ],
                "params"=>[$this->owner, "role"]
            ],

            [
                "definition"=>[
                    "class"=>\common\db\fields\TextField::className(),
                    "title"=>Yii::t('core', 'Username'),
                    "isRequired"=>true,
                ],
                "params"=>[$this->owner, "username"]
            ],

            [
                "definition"=>[
                    "class"=>\common\db\fields\PasswordField::className(),
                    "title"=>Yii::t('core', 'Password'),
                    "isRequired"=>false,
                ],
                "params"=>[$this->owner, "password"]
            ],

            [
                "definition"=>[
                    "class"=>\common\db\fields\PasswordField::className(),
                    "title"=>Yii::t('core', 'Confirm password'),
                    "isRequired"=>false,
                ],
                "params"=>[$this->owner, "confirm_password"]
            ],


            [
                "definition"=>[
                    "class"=>\common\db\fields\EmailField::className(),
                    "title"=>Yii::t('core', 'Confirm password'),
                    "isRequired"=>true,
                ],
                "params"=>[$this->owner, "email"]
            ],


        ];

    }

}