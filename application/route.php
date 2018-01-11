<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
Route::get([
    '/'                 => 'index/Index/index',
    'create-user-group' => 'index/UserGroup/create',
    'edit-userGroup'    => 'index/UserGroup/edit',
    'show-framework'    => 'index/Framework/index',    // 显示组织架构
    'create-framework'  => 'index/Framework/create',    // 显示组织架构添加页面
    'edit-framework'    => 'index/Framework/edit',
    'show-users'        => 'index/UserManage/index',
    'create-users'      => 'index/UserManage/create',
    'login'             => 'index/Login/index',       // 登录页面
    'show-userinfo'     => 'index/UserManage/read',       // 用户详情
]);

Route::post([
    'save-userGroup'    => 'index/UserGroup/save',
    'update-userGroup'  => 'index/UserGroup/update',
    'del-userGroup'     => 'index/UserGroup/delete',
    'save-frameword'    => 'index/Framework/save',
    'update-frameword'  => 'index/Framework/update',  // 组织架构修改提交
    'del-frameword'     => 'index/Framework/delete',  // 删除组织架构
    'save-users'        => 'index/UserManage/save',
    'doLogin'           => 'index/Login/doLogin',       // 登陆提交
    'update-user'       => 'index/Login/update',      // 修改用户信息提交
    'singOut'           => 'index/Login/singOut' ,   // 退出登录
    'update-password'   => 'index/UserManage/updatePassword'  // 修改密码
]);

Route::delete([
    'del-customer'   => 'index/CustomerManage/delete',  // 删除
    'del-user'       => 'index/UserManage/delete',   // 删除用户
]);
