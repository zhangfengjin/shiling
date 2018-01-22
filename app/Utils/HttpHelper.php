<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class HttpHelper
{

    /**
     * 用file_get_contents 以get方式获取内容
     *
     * @param unknown $url
     * @return string
     */
    public static function http_get_contents($url)
    {
        return file_get_contents($url);
    }

    /**
     * 用fopen打开url, 以get方式获取内容：
     *
     * @param unknown $url
     */
    public static function http_get_open($url)
    {
        $result = "";
        $fp = fopen($url, 'r');
        stream_get_meta_data($fp);
        while (!feof($fp)) {
            $result .= fgets($fp, 1024);
        }
        fclose($fp);
        return $result;
    }

    /**
     * 用file_get_contents函数,以post方式获取url
     *
     * @param unknown $url
     * @param unknown $data
     */
    public static function http_post_fcontents($url, array $data)
    {

        /*
         * $data = array ( 'foo' => 'bar', 'baz' => 'boom' );
         */
        $data = http_build_query($data);
        // $postdata = http_build_query($data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $data
                // 'timeout' => 60 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * 使用curl库，使用curl库之前，可能需要查看一下php.ini是否已经打开了curl扩展 稳定速度快
     *
     * @param unknown $url
     * @param array $data
     * @return mixed
     */
    public static function http_post_curlcontents($url, $header, $data)
    {
        try {
            $ch = curl_init();
            if (substr($url, 0, 5) == 'https') {
                // 跳过证书检查
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // 从证书中检查SSL加密算法是否存在
                // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // 设置允许查看请求头信息
            // curl_setopt($ch,CURLINFO_HEADER_OUT,true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $response = curl_exec($ch);
            // 查看请求头信息
            // dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));
            if ($error = curl_error($ch)) {
                curl_close($ch);
                return $error;
            } else {
                curl_close($ch);
                return $response;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 断点续传下载文件
     *
     * @param unknown $filePath
     */
    public static function download($filePath)
    {
        $filePath = iconv('utf-8', 'gb2312', $filePath);
        // 设置文件最长执行时间和内存
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        // 检测文件是否存在
        if (!is_file($filePath)) {
            die ("<b>404 File not found!</b>");
        }
        $filename = basename($filePath); // 获取文件名字
        // 开始写输出头信息
        header("Cache-Control: public");
        // 设置输出浏览器格式
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Transfer-Encoding: binary");
        header("Accept-Ranges: bytes");
        $size = filesize($filePath);
        $range = 0;
        // 如果有$_SERVER['HTTP_RANGE']参数
        if (isset ($_SERVER ['HTTP_RANGE'])) {
            /*
             * Range头域 　　Range头域可以请求实体的一个或者多个子范围。 例如， 表示头500个字节：bytes=0-499 表示第二个500字节： bytes=500-999 表示最后500个字节：bytes=-500 表示500字节以后的范围：bytes=500- 第一个和最后一个字节：bytes=0-0,-1 同时指定几个范围：bytes=500-600,601-999 但是服务器可以忽略此请求头，如果无条件GET包含Range请求头，响应会以状态码206（PartialContent）返回而不是以200 （OK）.
             */
            // 断点后再次连接 $_SERVER['HTTP_RANGE'] 的值 bytes=4390912-
            list ($a, $range) = explode("=", $_SERVER ['HTTP_RANGE']);
            // if yes, download missing part
            $size2 = $size - 1; // 文件总字节数
            $new_length = $size2 - $range; // 获取下次下载的长度
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: {$new_length}"); // 输入总长
            header("Content-Range: bytes {$range}-{$size2}/{$size}"); // Content-Range: bytes 4908618-4988927/4988928 95%的时候
        } else {
            // 第一次连接
            $size2 = $size - 1;
            header("Content-Range: bytes 0-{$size2}/{$size}"); // Content-Range: bytes 0-4988927/4988928
            header("Content-Length: " . $size); // 输出总长
        }
        $buffer = 1024;
        // 打开文件
        $fp = fopen("{$filePath}", "rb");
        // 设置指针位置
        fseek($fp, $range);
        // 虚幻输出
        while (!feof($fp)) {
            print (fread($fp, $buffer)); // 输出文件
            flush(); // 输出缓冲
            ob_flush();
        }
        fclose($fp);
        exit ();
    }
}

?>