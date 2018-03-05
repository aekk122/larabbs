<?php

use App\Models\Category;

return [
	'title' => '分类',
	'single' => '分类',
	'model' => Category::class,

	//对 CRUD 动作的单独权限控制，其他动作不指定默认为通过
	'action_permissions' => [
		//删除权限控制
		'delete' => function() {
			//只有站长才能删除话题分类
			return Auth::user()->hasRole('Founder');
		},
	],

	'columns' => [
		'id' => [
			'title' => 'ID',
		],
		'name' => [
			'title' => '名称',
			'sortable' => false,
		],
		'description' => [
			'title' => '描述',
			'sortable' => false,
		],
		'operation' => [
			'title' => '管理',
			'sortable' => false,
		],
	],

	'edit_fields' => [
		'name' => [
			'title' => '名称',
		],
		'description' => [
			'title' => '描述',
			'type' => 'textarea',
		],
	],

	'filters' => [
		'id' => [
			'title' => '分类 ID',
		],
		'name' => [
			'title' => '分类名称',
		],
		'description' => [
			'title' => '描述',
		],
	],

	'rules' => [
		'name' => 'required|min:1|unique:categories',
	],

	'messages' => [
		'name.required' => '分类名称不能为空。',
		'name.unique' => '分类名称在数据库中有重复，请更换。',
	],
];