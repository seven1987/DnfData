<?php
defined("ROOT_PATH") or define("ROOT_PATH", dirname(dirname(__DIR__)));

return [
    'adminEmail' => 'admin@example.com',

    /****************************** 上传相关参数 START ******************************/
    'upload_config' => [
        //图片上传
        'image' => [
            'file_data_field' => 'Filedata',//上传文件的文件域名称
            'max_size' => 5 * 1024 ,//最大上传大小
            'ext' => implode(',', ['gif', 'jpg', 'jpeg', 'png', 'bmp']),//扩展名
            'upload_path' => ROOT_PATH . 'backend/web/uploads/images/',//默认上传目录
            'rename' => 1,//是否重命名, 0-否 1-是  默认1
        ],
        //文件上传
        'file' => [
            'file_data_field' => 'Filedata',//上传文件的文件域名称
            'max_size' => 1000 * 1024 * 1024,//最大上传大小
            'ext' => implode(',', ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2']),//扩展名
            'upload_path' => ROOT_PATH . 'backend/web/uploads/file/',//默认上传目录
            'rename' => 1,//是否重命名, 0-否 1-是  默认1
        ],
        //媒体相关上传
        'media' => [
            'file_data_field' => 'Filedata',//上传文件的文件域名称
            'max_size' => 2000 * 1024 * 1024,//最大上传大小
            'ext' => implode(',', ['swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb']),//扩展名
            'upload_path' => ROOT_PATH . 'backend/web/uploads/media/',//默认上传目录
            'rename' => 1,//是否重命名, 0-否 1-是  默认1
        ],
    ],
    //keditor上传form名称
    'keditor_post_name' => 'keditor_upload',
    /****************************** 上传相关参数 END ******************************/

    //密码aes加密 iv值
    'IV' => '1234577290ABCDEF1264147890ACAE45',
];
