<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/25
 * Time: 19:14
 */

namespace App\Http\Services;


use App\Models\News;
use App\Models\NewsAtt;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsService extends CommonService
{
    public function create($input)
    {
        DB::beginTransaction();
        try {
            $news = new News();
            $news->title = $input['title'];
            $news->url = $input['url'];
            $news->content = $input['content'];
            $news->creator = $this->user['uid'];
            $news->save();
            if (isset($input['atts'])) {
                $newsAtts = [];
                foreach ($input['atts'] as $att) {
                    $newsAtts[] = [
                        "news_id" => $news->id,
                        "att_id" => $att,
                        "creator" => $this->user['uid']
                    ];
                }
                if (!empty($newsAtts)) {
                    DB::table("news_atts")->insert($newsAtts);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }


    public function show($id)
    {
        $where = [
            'n.id' => $id,
            'n.flag' => 0
        ];
        $select = [
            'n.id', 'n.title', 'n.content', 'n.url', 'n.creator'
        ];
        $userName = DB::raw("u.name as user_name");
        array_push($select, $userName);
        $news = DB::table("news as n")
            ->leftJoin("users as u", 'u.id', '=', 'n.creator')
            ->where($where)
            ->get($select)->first();
        if ($news) {
            $news->atts = [];
            $gaWhere = [
                "news_id" => $id,
                "na.flag" => 0
            ];
            $newsAtts = DB::table("news_atts as na")
                ->leftJoin("attachments as att", 'att.id', '=', 'na.att_id')
                ->where($gaWhere)
                ->get(["att.diskposition", "att.filename", "att.id"]);
            foreach ($newsAtts as $newsAtt) {
                $att = [
                    "url" => $newsAtt->diskposition . $newsAtt->filename,
                    "id" => $newsAtt->id
                ];
                $news->atts[] = $att;
            }
        }
        return $news;
    }

    public function update($input, $id)
    {
        DB::beginTransaction();
        try {
            $news = News::find($id);
            $news->title = $input['title'];
            $news->url = $input['url'];
            $news->content = $input['content'];
            $news->creator = $this->user['uid'];
            $news->save();
            $gaWhere = [
                "news_id" => $id,
                "flag" => 0
            ];
            $atts = [];
            if (isset($input['atts'])) {
                $atts = $input['atts'];
            }
            if (empty($atts)) {
                DB::table("news_atts")->where($gaWhere)->update(["flag" => 1]);
            } else {
                $dbAtts = NewsAtt::where($gaWhere)->get(["att_id"]);
                $oldAtts = [];
                foreach ($dbAtts as $dbAtt) {
                    $oldAtts[] = $dbAtt->att_id;
                }
                foreach ($atts as $idx => $att) {
                    if (($key = array_search($att, $oldAtts)) === FALSE) {
                        $goodAtts[] = [
                            "news_id" => $id,
                            "att_id" => $att,
                            "creator" => $this->user['uid']
                        ];
                    } else {
                        array_splice($oldAtts, $key, 1); // 删除存在的
                    }
                }
                if (!empty ($oldAtts)) {
                    DB::table("news_atts")->where($gaWhere)->whereIn("att_id", $oldAtts)->update(["flag" => 1]);
                }
                if (!empty($goodAtts)) {
                    DB::table("news_atts")->insert($goodAtts);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return true;
    }

    public function delete($ids)
    {
        $ids = array_filter(explode(",", $ids));
        if ($ids) {
            $updateInfo = [
                'flag' => 1
            ];
            DB::beginTransaction();
            try {
                News::whereIn("id", $ids)->update($updateInfo);
                NewsAtt::whereIn("news_id", $ids)->update($updateInfo);
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        }
        return true;
    }

    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("news as n")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'n.id', 'n.title', 'n.content', 'n.url'
        ];
        $userName = DB::raw("u.name as user_name");
        array_push($select, $userName);
        //获取查询结果
        $sortField = "n.id";
        $sSortDir = "asc";
        $rows = DB::table("news as n")
            ->leftJoin("users as u", 'u.id', '=', 'n.creator')
            ->where("n.flag", 0)->whereRaw($where)
            ->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        foreach ($rows as $row) {
            $row->id = strval($row->id);
        }
        return DataStandard::getListData($this->sEcho, $total, $rows);
    }


    /**
     * 获取查询条件
     * @param $search
     * @return array|bool
     */
    private function getSearchWhere($searchs)
    {
        $sql = "1=1";
        if ((!count($searchs) || empty($searchs)) && empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}