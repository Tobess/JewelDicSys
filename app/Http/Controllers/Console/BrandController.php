<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;
use Illuminate\Support\Facades\Log;
use Storage;
use Illuminate\Http\Request;

class BrandController extends ConsoleController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $query = \Input::get('query', '');
        if ($query) {
            $rows = \App\Brand::
            where(function ($que) use ($query) {
                $que->where('pinyin', 'like', $query . '%')
                    ->orWhere('letter', 'like', $query . '%')
                    ->orWhere('name', 'like', $query . '%');
            })->paginate(10);
        } else {
            $rows = \App\Brand::paginate(10);
        }

        return view('console.brand.list', ['rows' => $rows, 'query' => $query]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore(Request $request)
    {
        
        $name = \Input::get('name');
        $pinyin = \Input::get('pinyin');
        $letter = \Input::get('letter');

        $brand = \DB::table("brands")->select("id")->where("name",'=',$name)->first();
        if($brand){
            return redirect('console/brands')->with('message',"`{$name}` 该品牌名称已被占用！");
        }
        if($pinyin && !preg_match('/^[a-zA-Z]+$/',$pinyin)){
            return redirect('console/brands')->with('message','非法的字符!请输入正确的拼音');
        }
        if($letter && !preg_match('/^[a-zA-Z]+$/',$letter)){
            return redirect('console/brands')->with('message','非法的字符!请输入正确的短拼');
        }

        $brand = new \App\Brand;
        if ($name) {
            $brand->name = $name;
            $brand->pinyin = $pinyin ?: pinyin($name);
            $brand->letter = $letter ?: letter($name);
            $brand->save();
        } else {
            return redirect('console/brands')->with('message','品牌名称不能为空');
        }
        //品牌logo
        $file = $request->file('picture');
        $fileName = $this->uploadFile($file, $brand);
        $brand->address = $fileName;
        $brand->update();
        return redirect('console/brands')->with('message','品牌信息上传成功!');
    }

    /**
     * 文件上传
     * @param $file
     * @param $brand
     * @return mixed
     */
    private function uploadFile($file, $brand)
    {
        $defaultImage = "default.png";
        $localName = "uploads";
        if (isset($file)) {
            if ($file->isValid()) {
                $imageTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                $type = $file->getClientMimeType();
                $realPath = $file->getRealPath();
                if(!in_array($type, $imageTypes)){
                    return redirect('console/brands')->with('message','上传文件格式不正确');
                }
                if((($file->getSize() / 1000) > 100)){
                    return redirect('console/brands')->with('message','图片大小不能超过100kb！');
                }

                $imgInfo =  getimagesize($file);
                if( !$this->is_proportion($imgInfo[0],300) || !$this->is_proportion($imgInfo[1],132) ){
                    return redirect('console/brands')->with('message','图片尺寸不符合规范！');
                }
                
                $filename = 'brand' . '_' . $brand->id . '.' . 'png';
                $exists = \Storage::disk($localName)->exists($filename);
                if ($exists) {
                    \Storage::disk($localName)->delete($filename);
                }
                $bool = \Storage::disk($localName)->put($filename, file_get_contents($realPath));
                $image = $bool ? $filename : $defaultImage;

                return $image;
            }
        }
        return $defaultImage;
    }


    private function is_proportion($upload_num,$standard_num){
        if($upload_num > $standard_num){
            return is_int($upload_num/$standard_num);
        }
        return is_int($standard_num/$upload_num);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postUpdate($id, Request $request)
    {
        $name = \Input::get('name');
        $pinyin = \Input::get('pinyin');
        $letter = \Input::get('letter');
        $brand = \App\Brand::find($id);
        $file = $request->file('picture');


        $brand_id = \DB::table("brands")->select("id")->where("name",'=',$name)->first();
        if($name != $brand->name && $brand_id){
            return redirect()->back()->with('message',"`{$name}` 该品牌名称已被占用！");
        }
        if($pinyin && !preg_match('/^[a-zA-Z]+$/',$pinyin)){
            return  redirect()->back()->with('message','非法的字符!请输入正确的拼音');
        }
        if($letter && !preg_match('/^[a-zA-Z]+$/',$letter)){
            return  redirect()->back()->with('message','非法的字符!请输入正确的短拼');
        }

        if ($name && $brand) {
            $brand->name = $name;
            $brand->pinyin = $pinyin ?: pinyin($name);
            $brand->letter = $letter ?: letter($name);
            $brandLogo = $this->uploadFile($file, $brand);
            $brand->address = $brandLogo;
            $brand->save();
        }else{
            return redirect()->back()->with('message','品牌名称不能为空');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $brand = \App\Brand::find($id);
        if ($brand) {
            $brand->delete();
        }

        return redirect('console/brands');
    }

    /**
     * 获得品牌LOGO图片
     *
     * @param $id
     * @return mixed
     */
    public function getLogo($id)
    {
        return response()->download(self::logo($id));
    }

    public static function logo($id)
    {
        $path = storage_path('app/logo/default.png');
        $brand = \App\Brand::find($id);
        if ($brand && isset($brand->address)) {
            $bPath = storage_path('app/logo/' . $brand->address);
            if (is_file($bPath) && file_exists($bPath)) {
                $path = $bPath;
            }
        }

        return $path;
    }

}