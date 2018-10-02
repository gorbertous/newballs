<?php

namespace common\rbac\models;

use Yii;
use yii\db\ActiveRecord;
use common\dictionaries\RBACTypes;
use voskobovich\linker\LinkerBehavior;

/**
 * Class Authitem
 * This is the model class for table "auth_item".
 *
 * @property string  $name
 * @property integer $type
 * @property string  $description
 * @property string  $rule_name
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class Authitem extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['auth_children_r_ids', 'auth_children_p_ids'], 'safe'],
            [['name', 'rule_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'     => LinkerBehavior::class,
                'relations' => [
                    'auth_children_r_ids' => [
                        'children',
                        'updater' => [
                            'viaTableAttributesValue' => ['childtype' => RBACTypes::RBAC_ROLES],
                            'viaTableCondition'       => ['childtype' => RBACTypes::RBAC_ROLES]
                        ]
                    ],
                    'auth_children_p_ids' => [
                        'children',
                        'updater' => [
                            'viaTableAttributesValue' => ['childtype' => RBACTypes::RBAC_PERMISSIONS],
                            'viaTableCondition'       => ['childtype' => RBACTypes::RBAC_PERMISSIONS]
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'                => Yii::t('modelattr', 'Name'),
            'type'                => Yii::t('modelattr', 'Type'),
            'description'         => Yii::t('modelattr', 'Description'),
            'rule_name'           => Yii::t('modelattr', 'Rule Name'),
            'data'                => Yii::t('modelattr', 'Data'),
            'auth_children_r_ids' => Yii::t('modelattr', 'Child Roles (this role inherits from)'),
            'auth_children_p_ids' => Yii::t('modelattr', 'Additional permisssions'),
        ];
    }

    /**
     * @return string
     */
    public function getTitleSuffix()
    {
        return $this->name;
    }

    /**
     * Return roles.
     * NOTE: used for updating user role (user/update).
     *
     * @return array|ActiveRecord[]
     */
    public static function getChildroles()
    {
        // get user role
        $userrole = array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
        // get child roles
        $childroles = array_keys(Yii::$app->authManager->getChildRoles($userrole));
        // we make sure that consultant can not see users with developer role
        return static::find()->select('name')
                        ->where(['type' => RBACTypes::RBAC_ROLES])
                        ->andWhere(['name' => $childroles])
                        ->all();
    }

    /**
     * @param $roles
     * @return array
     */
    public function getRolePermissions($roles)
    {
        $chilpermissions = [];
        # search for child permissions of the role
        foreach ($roles as $role) {
            $children = array_keys(Yii::$app->authManager->getChildRoles($role));
            $rolermissions = Authitemchild::find()->select('child')
                        ->where(['childtype' => RBACTypes::RBAC_PERMISSIONS])
                        ->andFilterWhere(['or',['parent' => $children], ['child' => $children]])
                        ->all();
            
            foreach ($rolermissions as $permission) {
                $name = $permission->child;
                if (!in_array($name, $chilpermissions)) {
                    array_push($chilpermissions, $name);
                }
            }
        }
        return $chilpermissions;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(Role::class, ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthitemchildren()
    {
        return $this->hasMany(Authitemchild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthitemchildren0()
    {
        return $this->hasMany(Authitemchild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Authitem::class, ['name' => 'child'])
                        ->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getChildrenR()
    {
        return $this->hasMany(Authitem::class, ['name' => 'child'])
                        ->viaTable('auth_item_child', ['parent' => 'name'])
                        ->where(['type' => RBACTypes::RBAC_ROLES]);
    }

  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentsR()
    {
        return $this->hasMany(Authitem::class, ['name' => 'parent'])
                        ->viaTable('auth_item_child', ['child' => 'name'])
                        ->where(['type' => RBACTypes::RBAC_ROLES]);
    }

   
    /**
     * @inheritdoc
     * @return \common\rbac\models\AuthitemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthitemQuery(get_called_class());
    }

}
