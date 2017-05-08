<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class StandardController extends Controller
{
    /**
     * 标准分类种类
     * @var array
     */
    private static $modes = ['colors' => '颜色', 'certificates' => '证书', 'clarities' => '净度', 'cuts' => '切工', 'grades' => '等级', 'shapes' => '形状'];

    private $mod = null;

    public function __construct()
    {
        $mod = \Input::get('mod');
        if (in_array($mod, array_keys(self::$modes))) {
            $this->mod = $mod;
        } else {
            abort(404, '未定义的URL');
        }
    }

    private function getTable()
    {
        return 's_' . $this->mod . ' as s';
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $query = \Input::get('query');
        $mid = \Input::get('mid', 0);
        $que = \DB::table($this->getTable())
            ->leftJoin('materials as m', 's.material_id', '=', 'm.id')
            ->select('s.name', 's.letter', 's.pinyin', \DB::raw('group_concat(s.id) as ids'),
                \DB::raw('group_concat(m.id) as m_ids'), \DB::raw('group_concat(m.name) as m_titles'))
            ->groupBy('s.name')
            ->orderBy('s.name');
        if ($query) {
            $que->where(function ($sql) use ($query) {
                $sql->where('s.name', 'like', $query . '%')
                    ->orWhere('s.pinyin', 'like', $query . '%')
                    ->orWhere('s.letter', 'like', $query . '%');
            });
        }
        if ($mid > 0) {
            $que->where('m.id', $mid);
        }

        $lists = $que->paginate(10);

        $mTitle = $mid > 0 ? \DB::table('materials')->where('id', '=', $mid)->pluck('name') : '';


        return view('console.standard.list', [
            'rows' => $lists,
            'mid' => $mid,
            'mTitle' => $mTitle,
            'query' => $query,
            'modName' => self::$modes[$this->mod],
            'mod' => $this->mod
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function getStore()
    {
        $origin = \Input::get('origin');
        $name = \Input::get('name');
        $materials = \Input::get('materials');

        if (!$name || !$materials) {
            return response()->json(['state' => false, 'msg' => '无效的参数']);
        }

        $pinyin = pinyin($name);
        $letter = letter($name);

        $mArr = explode(',', $materials);
        $tableName = 's_' . $this->mod;

        $ids = [];
        if ($origin) {
            \DB::table($tableName)->where('name', $origin)->whereNotIn('material_id', $mArr)->delete();
            $ids = \DB::table($tableName)->where('name', $origin)->list('material_id');
            if (count($ids) > 0) {
                \DB::table($tableName)->where('name', $origin)->update(['name' => $name, 'pinyin' => $pinyin, 'letter' => $letter ?: $pinyin]);
            }
        }
        $saveIds = array_diff($mArr, $ids);
        if ($saveIds) {
            $values = [];
            foreach ($saveIds as $item) {
                $values[] = [
                    'name' => $name,
                    'pinyin' => $pinyin,
                    'letter' => $letter ?: $pinyin,
                    'material_id' => $item
                ];

            }
            \DB::table($tableName)->insert($values);
        }

        return response()->json(['state' => true, 'msg' => '保存成功']);

    }

    /**
     * 批量移除标准分类.
     *
     * @param  int $ids
     * @return Response
     */
    public function getDestroy($ids)
    {
        // $ids 为index中查询出的ids字段
        $tableName = 's_' . $this->mod;
        if ($ids) {
            DB::table($tableName)->whereIn('id', explode(',', $ids))->delete();
        }

        return \redirect()->back();
    }

}
