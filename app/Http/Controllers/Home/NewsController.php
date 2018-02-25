<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\NewsService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = [
            "goodsTypes" => []
        ];

        return view("news.news")->with($pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $newsService = new NewsService($request);
        $news = $newsService->create($input);
        if ($news) {
            return DataStandard::getStandardData();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $newsService = new NewsService($request);
        $news = $newsService->show($id);
        return DataStandard::getStandardData($news);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        $newsService = new NewsService($request);
        $ret = $newsService->update($input, $id);
        if ($ret) {
            return DataStandard::getStandardData();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $newsService = new NewsService($request);
        $newsService->delete($id);
        return DataStandard::getStandardData();
    }

    public function getList(Request $request)
    {
        $newsService = new NewsService($request);
        $news = $newsService->getList();
        return DataStandard::getStandardData($news);
    }
}
