<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class DLinksController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 * @param  int  $rel_type_src
     * @param  int  $rel_id_src
	 * @return Response
	 */
	public function getIndex()
	{
        $tSrc = \Input::get('rel_type_src');
        $kSrc = \Input::get('rel_id_src');

        $dRef = \App\WRef::getRefById($tSrc);
        if (is_array($dRef)) {
            $pRow = \DB::table($dRef['table'])->find($kSrc);
            if (!$pRow) {
                return redirect('console');
            }
        } else {
            return redirect('console');
        }

        $treeData = [];
        $links = \App\DLink::where('rel_type_src', $tSrc)->where('rel_id_src', $kSrc)->paginate(10);
        foreach ($links as $link) {
            $tTar = $link->rel_type_tar;
            $kTar = $link->rel_id_tar;
            if (isset($treeData[$tTar]) && isset($treeData[$tTar][$kTar])) {
                continue;
            }

            $ref = \App\WRef::getRefById($tTar);
            if (is_array($ref)) {
                $kItem = \DB::table($ref['table'])->find($kTar);
            }
            $treeData[$tTar][$kTar] = ['tName'=>is_array($ref) ? $ref['name'] : '', 'kName'=>$kItem ? $kItem->name : ''];
        }

        return view('console.link.list', ['rows'=>$links, 'pItem'=>$pRow, 'tItem'=>$dRef, 'tree'=>$treeData]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
        $tTar = \Input::get('rel_type_tar');
        $kNameTar = \Input::get('rel_id_tar_name');
        $kTar = 0;
        $dRef = \App\WRef::getRefById($tTar);
        if (is_array($dRef)) {
            $dItem = \DB::table($dRef['table'])->where('name', $kNameTar)->first();
            if ($dItem) {
                $kTar = $dItem->id;
            }
        }

        if ($kTar > 0) {
            $link = new \App\DLink();
            $link->rel_type_src = \Input::get('rel_type_src');
            $link->rel_id_src = \Input::get('rel_id_src');
            $link->rel_type_tar = $tTar;
            $link->rel_id_tar = $kTar;
            $link->save();

            \App\DLink::setLinksBySrcTypeID($link->rel_type_src, $link->rel_id_src);
        }

        return redirect()->back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
        $link = \App\DLink::find($id);

        return \Response::json($link->toArray());
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
        $tTar = \Input::get('rel_type_tar');
        $kNameTar = \Input::get('rel_id_tar_name');
        $kTar = 0;
        $dRef = \App\WRef::getRefById($tTar);
        if (is_array($dRef)) {
            $dItem = \DB::table($dRef['table'])->where('name', $kNameTar)->first();
            if ($dItem) {
                $kTar = $dItem->id;
            }
        }

        if ($kTar > 0) {
            $link = \App\DLink::find($id);
            $link->rel_type_src = \Input::get('rel_type_src');
            $link->rel_id_src = \Input::get('rel_id_src');
            $link->rel_type_tar = $tTar;
            $link->rel_id_tar = $kTar;
            $link->save();

            \App\DLink::setLinksBySrcTypeID($link->rel_type_src, $link->rel_id_src);
        }

        return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
        $link = \App\DLink::find($id);
        if ($link) {
            $sType = $link->rel_type_src;
            $sId = $link->rel_id_src;
            $link->delete();

            \App\DLink::setLinksBySrcTypeID($type, $sId);
        }

        return redirect()->back();
	}

}
