<?php

namespace app\models;

use app\models\base\Role as BaseRole;

class Role extends BaseRole {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'สิทธิในการใช้งาน',
        ]);
    }

    public function getPermissions() {
        return $this->hasMany(Permission::className(), ['id' => 'permission_id'])->via('rolePermissions');
    }

    public function getRolePermissions() {
        return $this->hasMany(RolePermission::className(), ['role_id' => 'id']);
    }

}
