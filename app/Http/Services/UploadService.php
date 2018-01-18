<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/18
 * Time: 16:31
 */

namespace App\Http\Services;


use App\Utils\UploadHelper;

class UploadService
{
    /**
     * 上传到本地
     * @param $action
     * @return array
     */
    public function uploadfile($action)
    {
        header("Content-Type:text/html;charset=utf-8");
        date_default_timezone_set("Asia/chongqing");
        $filepath = config() . "/upload.json";
        $initConfig = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($filepath)), true);
        /* 上传配置 */
        switch (htmlspecialchars($action)) {
            case 'uploadimage' :
                $config = array(
                    "pathFormat" => $initConfig ['imagePathFormat'],
                    "maxSize" => $initConfig ['imageMaxSize'],
                    "allowFiles" => $initConfig ['imageAllowFiles']
                );
                $fieldName = $initConfig ['imageFieldName'];
                break;
            case 'uploadfile' :
            default :
                $config = array(
                    "pathFormat" => $initConfig ['filePathFormat'],
                    "maxSize" => $initConfig ['fileMaxSize'],
                    "allowFiles" => $initConfig ['fileAllowFiles']
                );
                $fieldName = $initConfig ['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new UploadHelper ($fieldName, $config);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         * "state" => "", //上传状态，上传成功时必须返回"SUCCESS"
         * "url" => "", //返回的地址
         * "title" => "", //新文件名
         * "original" => "", //原始文件名
         * "type" => "" //文件类型
         * "size" => "", //文件大小
         * )
         */

        /* 返回数据 */
        return $up->getFileInfo();
    }
}