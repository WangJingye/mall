<?php

class Controller extends ObjectAccess
{
    /** @var Controller $this */

    public function init()
    {
        $siteInfo = Db::table('SiteInfo')->find();
        $siteInfo['wechat'] = json_decode($siteInfo['wechat'], true);
        \App::$config['site_info'] = $siteInfo ? $siteInfo : new ObjectAccess();
    }

    public function before()
    {

    }

    public function after($result)
    {
        if (is_array($result)) {
            echo json_encode($result);
        } else {
            echo $result;
        }
    }

    public function __construct()
    {
        $this->init();
    }

    public function assign($key, $value)
    {
        $this->$key = $value;
    }


    public function success($message = '', $data = [])
    {
        $result = [
            'code' => 200,
            'message' => $message,
            'data' => empty($data) ? null : $data,
        ];
        return $result;
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function error($message = '', $data = [], $code = 400)
    {
        if ($message instanceof \Exception) {
            $result = [
                'code' => $message->getCode(),
                'message' => $message->getMessage(),
                'data' => empty($data) ? null : $data,
            ];
        } else {
            $result = [
                'code' => $code,
                'message' => $message,
                'data' => empty($data) ? null : $data,
            ];
        }
        return $result;
    }


    /**
     * 文件上传处理
     * @param $file
     * @param bool $is_image
     * @return array
     * @throws \Exception
     */
    public function parseFile($file, $path = '/')
    {
        $ext_arr = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'mp4'];
        $fileList = $file['name'];
        if (is_string($file['name'])) {
            $fileList = [$file['name']];
        }
        $tmpList = $file['tmp_name'];
        if (is_string($file['tmp_name'])) {
            $tmpList = [$file['tmp_name']];
        }
        $res = [];
        $path = trim($path, '/') != '' ? trim($path, '/') . '/' : '';
        foreach ($fileList as $i => $f_name) {
            if (!$f_name) {
                continue;
            }
            $arr = explode('.', $f_name);
            $ext = end($arr);
            if (!in_array($ext, $ext_arr)) {
                throw new \Exception('不允许的文件类型,只支持' . implode('/', $ext_arr));
            }
            $filePath = PUBLIC_PATH . 'upload/' . $path;
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
            $filename = 'upload/' . $path . md5_file($tmpList[$i]) . '.' . $ext;
            if (!file_exists(PUBLIC_PATH . $filename)) {
                if (@!move_uploaded_file($tmpList[$i], PUBLIC_PATH . $filename)) {
                    throw new \Exception('文件保存失败');
                }
            }
            $res[$i] = '/' . $filename;
        }
        return $res;
    }

    /**
     * @param $key
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function parseFileOrUrl($key, $path = '/')
    {
        $res = [];
        //如果是common目录下的文件需要移动到对应目录
        if ($urlList = \App::$request->params[$key]) {
            if (!is_array($urlList)) {
                $urlList = explode(',', $urlList);
            }
//            $baseUrl = \App::$config['site_info']['web_host'];
            foreach ($urlList as $i => $url) {
                if (strpos($url, '/upload/common') === 0) {
                    $filename = str_replace('/upload/common/', '', $url);
                    $oldFilePath = PUBLIC_PATH . 'upload/common/';
                    $oldFilename = $oldFilePath . $filename;
                    $newFilePath = PUBLIC_PATH . 'upload/' . $path;
                    $newFilename = $newFilePath . $filename;
                    if (!file_exists($newFilePath)) {
                        mkdir($newFilePath, 0755, true);
                    }
                    if (file_exists($oldFilename)) {
                        copy($oldFilename, $newFilename);
                        unlink($oldFilename);
                    }
                    $res[$i] = '/upload/' . $path . $filename;
                } else {
                    $res[$i] = $url;
                }
            }
        }
        $path = trim($path, '/') != '' ? trim($path, '/') . '/' : '';
        if (!empty($_FILES[$key])) {
            $files = $this->parseFile($_FILES[$key], $path);
            foreach ($files as $i => $new) {
                $res[$i] = $new;
            }
        }
        if (!empty($_FILES[$key . '_add'])) {
            $files = $this->parseFile($_FILES[$key . '_add'], $path);
            foreach ($files as $i => $new) {
                $res[($i + 1000)] = $new;
            }
        }
        if (count($res)) {
            ksort($res);
        }
        return implode(',', $res);
    }
}