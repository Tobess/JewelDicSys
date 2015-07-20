<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class JError extends Model {

	protected $primaryKey = 'file_id';

    /**
     * feedback
     */
    public static function feedback($file, $domain, $mobile, $userName, $contents)
    {
        if (!$file || !$domain || !$mobile || !$userName) {
            return ['state'=>false, 'message'=>'无效的参数.'];
        }

        $error = self::find($file);
        if (!$error) {
            $error = new self();
            $error->file_id = $file;
        }

        $error->domain = $domain;
        $error->mobile = $mobile;
        $error->userName = $userName;
        $error->contents = $contents;
        $error->save();

        return ['state'=>true, 'message'=>'错误反馈成功.'];
    }

}
