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

}