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
            })
                ->paginate(10);
        } else {
            $rows = \App\Brand::paginate(10);
        }

        $path = $this->getPath();
        return view('console.brand.list', ['rows' => $rows, 'query' => $query, 'path' => $path]);
    }

    /**
     * @return string
     */
    private function getPath()
    {
        $ip = gethostbyname($_SERVER['SERVER_NAME']);
        $pathNode = explode('\\', base_path());
        $baseName = end($pathNode);
        $otherPath = "storage/app/logo";
        return 'http://' . $ip . '/' . $baseName . '/' . $otherPath . '/';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore(Request $request)
    {

        $name = \Input::get('name');
        $brand = new \App\Brand;
        if ($name) {
            $brand->name = $name;
            $brand->pinyin = \Input::get('pinyin') ?: pinyin($name);
            $brand->letter = \Input::get('letter') ?: letter($name);
            $brand->save();
        } else {
            return redirect('console/brands');
        }
        //品牌logo
        $file = $request->file('picture');
        $fileName = $this->uploadFile($file, $brand);
        $brand->address = $fileName;
        $brand->update();
        return redirect('console/brands');
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
                if (!in_array($type, $imageTypes) || (($file->getSize() / 1000) > 100)) {
                    $image = $defaultImage;
                    return $image;
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

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postUpdate($id, Request $request)
    {
        $name = \Input::get('name');
        $brand = \App\Brand::find($id);
        $file = $request->file('picture');

        if ($name && $brand) {
            $brand->name = $name;
            $brand->pinyin = \Input::get('pinyin') ?: pinyin($name);
            $brand->letter = \Input::get('letter') ?: letter($name);
            $brandLogo = $this->uploadFile($file, $brand);
            $brand->address = $brandLogo;
            $brand->save();
        }


        return redirect('console/brands');
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
        $path = storage_path('app/logo/default.png');
        $brand = \App\Brand::find($id);
        if ($brand && isset($brand->address)) {
            $bPath = storage_path('app/logo/' . $brand->address);
            if (is_file($bPath) && file_exists($bPath)) {
                $path = $bPath;
            }
        }

        return response()->download($path);
    }

}