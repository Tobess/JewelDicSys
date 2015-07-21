<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class JError extends Model {

	protected $primaryKey = 'file_id';

    /**
     * feedback
     */
    public static function feedback($file, $domain, $companyName, $mobile, $userName, $contents, $fileGroup, $fileName)
    {
        if (!$file || !$domain || !$mobile || !$userName || !$fileName) {
            return ['state'=>false, 'message'=>'无效的参数.'];
        }

        $error = self::find($file);
        if (!$error) {
            $error = new self();
            $error->file_id = $file;
        }

        $error->domain = $domain;
        $error->companyName = $companyName;
        $error->mobile = $mobile;
        $error->userName = $userName;
        $error->contents = $contents;
        $error->file_group = $fileGroup;
        $error->file_name = $fileName;
        $error->save();

        return ['state'=>true, 'message'=>'错误反馈成功.'];
    }

}
