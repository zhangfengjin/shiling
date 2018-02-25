<?php
/**
 * 前端模块菜单
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/2/23
 * Time: 10:18
 */
return [
    [
        "name" => "用户管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "用户列表", "href" => "/user"
            ]
        ]
    ],
    [
        "name" => "学校管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "学校列表", "href" => "/school"
            ]
        ]
    ],
    [
        "name" => "角色管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "角色列表", "href" => "/role"
            ]
        ]
    ],
    [
        "name" => "会议管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "会议列表", "href" => "/meet"
            ],
            [
                "name" => "参会人员", "href" => "/meetuser"
            ],
            [
                "name" => "中奖列表", "href" => "/muprize"
            ]
        ]
    ],
    [
        "name" => "商城管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "商品列表", "href" => "/goods"
            ],
            /*[
                "name" => "商品统计", "href" => "/goods/tj"
            ],
            [
                "name" => "中奖列表", "href" => "/muprize"
            ]*/
        ]
    ],
    [
        "name" => "订单管理", "icon" => "cog", "href" => "", "childrens" =>
        [
            [
                "name" => "订单列表", "href" => "/order"
            ],
            /*[
                "name" => "商品统计", "href" => "/goods/tj"
            ],
            [
                "name" => "中奖列表", "href" => "/muprize"
            ]*/
        ]
    ]
];