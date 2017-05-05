<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class StandardController extends Controller
{
    /**
     * 标准分类种类
     * @var array
     */
    private static $modes = ['color', 'certificates', 'clarities', 'cuts', 'grades', 'shapes'];

    private $mod = null;

    public function __construct()
    {
        $mod = \Input::get('mod');
        if (in_array($mod, self::$modes)) {
            $this->mod = $mod;
        } else {
            // TODO 404
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
    public function index()
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

        $mTitle = $mid > 0 ? \DB::table('materials')->where('id', $mid)->value('name') : '';
        return view('console.standard.list', [
            'rows' => $lists,
            'mid' => $mid,
            'mTitle' => $mTitle,
            'query' => $query,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $name = \Input::get('name');
        $materials = \Input::get('materials');
        if (!$name || !$materials) {
            return Response::json(['state' => false, 'msg' => '无效的参数']);
        }

        $mArr = explode(',', $materials);
        \DB::table($this->getTable())->where('name', $name)->whereNotIn('material_id', $mArr)->delete();
        $oIdArr = \DB::table();
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
