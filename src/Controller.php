<?php

namespace Laravelcity\FileManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravelcity\FileManager\Lib\FileManagerException;
use Laravelcity\FileManager\Models\FileManager;


class Controller extends BaseController
{
    protected $fileManager;


    public function __construct (FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * return index view for files
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function index ($type = 'image')
    {
        if ($type == 'post') {
            $model_config = config('filemanager.posts');

            if ($model_config['model'] && $model_config['title_field']) {
                if (app($model_config['model']) instanceof Model) {
                    $posts = app($model_config['model'])->orderby($model_config['publish_date'] , 'desc')->paginate();
                    return view('FileManager::index-posts')->with(['posts' => $posts]);
                }
                return $model_config['model'] . ': is not model';

            }
            return 'posts model is not config';
        } else {
            if (Input::get('single'))
                return view('FileManager::filemanager_single')->with(['files' => [] , 'type' => $type]);
            else
                return view('FileManager::filemanager')->with(['files' => [] , 'type' => $type]);
        }

    }

    /**
     * upload files
     * @param Request $request
     * @return array
     */
    function upload (Request $request)
    {
        $type = $request->input('type');
        $file_ids = [];

        foreach ($request->files as $file) {
            $file_ids[] = $this->uploadFile($file , $type);
        }

        return $file_ids;

    }

    /**
     * upload file
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param $fileType
     * @return bool|mixed
     */
    function uploadFile (\Symfony\Component\HttpFoundation\File\UploadedFile $file , $fileType)
    {

        if ($file->isValid()) {

            $storeFile = $this->fileManager->fill([
                'ext' => $file->getClientOriginalExtension() ,
                'mime' => $file->getMimeType() ,
                'name' => strtotime(date('Y-m-d H:i:s')) ,
                'original_name' => $file->getClientOriginalName() ,
                'size' => $file->getSize() ,
                'type' => $fileType ,
                'user_id' => Auth::user()->id ,
            ]);

            if ($storeFile->save()) {
                if ($fileType == 'image')
                    $this->storeImage($file , $storeFile);
                else {
                    if ($this->fileManager->is_image($file->getClientOriginalExtension()) == true)
                        $this->storeImage($file , $storeFile);
                    else
                        $this->storeFile($file , $storeFile);
                }


                return $storeFile->id;

            }

            return false;
        }


    }

    /**
     * store images
     * @param $image
     * @param $model
     */
    function storeImage ($image , $model)
    {
        $filename = $image->getClientOriginalName();
        $driver = config('filemanager.disk');

        Storage::disk($driver)->put($this->fileManager->makeFilePath($model) . $filename , \Illuminate\Support\Facades\File::get($image->getPathName()));
        $t_image = Image::make($image->getRealPath())->fit(config('filemanager.upload.image-size.thumb.width' , 150) , config('filemanager.upload.image-size.thumb.height' , 150) , function ($constraint) {
            $constraint->aspectRatio();
        })->encode($image->getClientOriginalExtension());
        $s_image = Image::make($image->getRealPath())->fit(config('filemanager.upload.image-size.small.width' , 300) , config('filemanager.upload.image-size.small.height' , 169) , function ($constraint) {
            $constraint->aspectRatio();
        })->encode($image->getClientOriginalExtension());
        $m_image = Image::make($image->getRealPath())->fit(config('filemanager.upload.image-size.medium.width' , 493) , config('filemanager.upload.image-size.medium.height' , 278) , function ($constraint) {
            $constraint->aspectRatio();
        })->encode($image->getClientOriginalExtension());
        $l_image = Image::make($image->getRealPath())->fit(config('filemanager.upload.image-size.large.width' , 1024) , config('filemanager.upload.image-size.large.height' , 576) , function ($constraint) {
            $constraint->aspectRatio();
        })->encode($image->getClientOriginalExtension());

        $round_image = Image::make($image->getRealPath())->fit(config('filemanager.upload.image-size.round.width' , 300) , config('filemanager.upload.image-size.round.width' , 300) , function ($constraint) {
            $constraint->aspectRatio();
        })->encode($image->getClientOriginalExtension());

        Storage::disk($driver)->put($this->fileManager->makeFilePath($model , 't') . $filename , (string)$t_image);
        Storage::disk($driver)->put($this->fileManager->makeFilePath($model , 's') . $filename , (string)$s_image);
        Storage::disk($driver)->put($this->fileManager->makeFilePath($model , 'm') . $filename , (string)$m_image);
        Storage::disk($driver)->put($this->fileManager->makeFilePath($model , 'l') . $filename , (string)$l_image);
        Storage::disk($driver)->put($this->fileManager->makeFilePath($model , 'r') . $filename , (string)$round_image);
    }

    /**
     * store file
     * @param $file
     * @param $model
     */
    function storeFile ($file , $model)
    {
        $filename = $file->getClientOriginalName();
        Storage::disk(config('filemanager.disk'))->put($this->fileManager->makeFilePath($model) . $filename , \Illuminate\Support\Facades\File::get($file->getPathName()));
    }

    /**
     * return json of files
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function list ($type = 'image')
    {
        $files = $this->fileManager->select();

        if ($type != 'both')
            $files->where('type' , $type);


        if ($name = Input::get('name') && !empty(Input::get('name')))
            $files->where('original_name' , 'like' , "%$name%");
        if ($user_id = Input::get('user_id') && !empty(Input::get('user_id')))
            $files->where('user_id' , $user_id);
        if ($date = Input::get('date') && !empty(Input::get('date'))) {
            $start = Input::get('date');
            $end = date('Y-m-29' , strtotime($start));
            $files->where('created_at' , '>=' , $start)->where('created_at' , '<=' , $end);
        }


        $files = $files->orderby('id' , 'desc')->paginate(21);

        return \Illuminate\Support\Facades\Response::json([
            'files' => $this->fileManager->makeFilesJson($files , $type) ,
            'pagination' => [
                'total' => $files->total() ,
                'current_page' => $files->currentPage() ,
                'last_page' => $files->lastPage() ,
                'next_page_url' => $files->nextPageUrl() ,
                'prev_page_url' => $files->previousPageUrl() ,
                'limit' => $files->perPage()
            ]
        ]);
    }

    /**
     * update file
     * @param $id
     */
    public function update ($id)
    {
        if ($file = $this->fileManager->where('id' , $id)->first()) {
            $file->alt = Input::get('title');
            $file->save();
        }
    }

    /**
     * delete file
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function delete ($id)
    {
        try {
            $this->fileManager->deleteFile($id);

        } catch (FileManagerException $e) {
            return \response()->json(['error' => true , 'message' => $e->getMessage()]);
        }
    }

    /**
     * run action to files
     * @throws FileManagerException
     */
    function actions ()
    {
        if ($action = Input::get('action')) {
            $files = Input::get('files');

            if ($action == 'delete') {
                foreach ($files as $file)
                    $this->fileManager->deleteFile($file['id']);
            }
        }
    }

    /**
     * search in posts
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    function searchPost ()
    {
        $title = Input::get('title' , '');
        $model_config = config('filemanager.posts');

        $posts = app($model_config['model'])->select();

        if ($title != '')
            $posts->where($model_config['title_field'] , 'like' , "%$title%");

        $posts = $posts->orderby($model_config['publish_date'] , 'desc')->take(30)->get();

        $view = view('FileManager::partials.postList')->with(['posts' => $posts])->render();

        return \response()->json(['view' => $view]);

    }

}