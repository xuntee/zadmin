<?php

// +----------------------------------------------------------------------
// | ZAdmin
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/xuntee/z-admin
// +----------------------------------------------------------------------

namespace ZAdmin\upload\driver;

use ZAdmin\upload\FileBase;
use ZAdmin\upload\driver\qnoss\Oss;
use ZAdmin\upload\trigger\SaveDb;

/**
 * 七牛云上传
 * Class Qnoss
 * @package ZAdmin\upload\driver
 */
class Qnoss extends FileBase
{

    /**
     * 重写上传方法
     * @return array|void
     */
    public function save()
    {
        parent::save();
        $upload = Oss::instance($this->uploadConfig)
            ->save($this->completeFilePath, $this->completeFilePath);
        if ($upload['save'] == true) {
            SaveDb::trigger($this->tableName, [
                'upload_type'   => $this->uploadType,
                'original_name' => $this->file->getOriginalName(),
                'mime_type'     => $this->file->getOriginalMime(),
                'file_ext'      => strtolower($this->file->getOriginalExtension()),
                'url'           => $upload['url'],
                'create_time'   => time(),
            ]);
        }
        $this->rmLocalSave();
        return $upload;
    }

}