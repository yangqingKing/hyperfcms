<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * AttachmentService.php
 *
 * User：YM
 * Date：2020/2/5
 * Time：下午8:57
 */


namespace Core\Services;


/**
 * AttachmentService
 * 附件服务
 * @package Core\Services
 * User：YM
 * Date：2020/2/5
 * Time：下午8:57
 *
 * @property \App\Models\Attachment $attachmentModel
 */
class AttachmentService extends BaseService
{
    /**
     * getInfo
     * 获取附件信息
     * User：YM
     * Date：2020/2/5
     * Time：下午8:59
     * @param $id
     * @return \App\Models\BaseModel|array|\Hyperf\Database\Model\Model|null
     */
    public function getInfo($id)
    {
        if (!$id) {
            return [];
        }
        $info = $this->attachmentModel->getInfo($id);
        if ($info && $info['path']) {
            $info['full_path'] = $this->getImageFullUrl($info['path']);
        }

        return $info;
    }

    /**
     * getImageFullUrl
     * 获取图片的全路径
     * User：YM
     * Date：2020/2/5
     * Time：下午9:21
     * @param $path
     * @return string
     */
    public function getImageFullUrl($path)
    {
        if (!$path) {
            return '';
        }
        $isOss = config('upload.oss');
        if ($isOss) {
            $host = '';
        } else {
            $domain = config('app_domain');
            $attachments = config('upload.attachments');
            $host = $attachments?$domain.'/'.$attachments:$domain;
        }
        $fullUrl = $host.$path;

        return $fullUrl;
    }

    /**
     * newFileName
     * 生成一个文件名
     * User：YM
     * Date：2020/2/6
     * Time：下午8:54
     * @return mixed|null|string|string[]
     */
    public function newFileName()
    {
        //替换日期事件
        $t = date('YmdHis');
        $format = config('upload.file_name_format');
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法字符,并替换文件名
//        $oriName = substr($oldName, 0, strrpos($oldName, '.'));
//        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
//        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, (int)$matches[1]), $format);
        }

        return $format;
    }

    /**
     * addAttachment
     * 添加附件
     * @param mixed $userId
     * @access public
     * @return void
     */
    public function addAttachment($userId)
    {
        $saveData = [
            'title' => time(),
            'user_id' => $userId
        ];
        return $this->attachmentModel->saveInfo($saveData);
    }

}