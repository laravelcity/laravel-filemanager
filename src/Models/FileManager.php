<?php

namespace Laravelcity\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravelcity\FileManager\Lib\FileManagerException;

class FileManager extends Model
{
    protected $guarded = [];
    protected $table = 'file_manager';

    /**
     * return files by json
     * @param $files
     * @param $type
     * @return array|bool
     */
    public function makeFilesJson ($files , $type)
    {
        $array = [];
        if (count($files) > 0) {
            foreach ($files as $file) {

                if (file_exists($file->path())) {
                    $size = '';
                    $w = '';
                    $h = '';

                    if ($this->is_image($file->ext)) {
                        list($width , $height) = getimagesize($file->path());
                        $size = "$width * $height";
                        $w = $width;
                        $h = $height;
                    }
                    $img = '';

                    if ($this->is_image($file->ext))
                        $img = $file->url('t');
                    else
                        $img = $this->makeImgForFile($file->ext);

                    $array[] = [
                        'url' => $file->url() ,
                        'image' => $img ,
                        'original_name' => $file->original_name ,
                        'size' => $size ,
                        'mimeType' => $file->mime ,
                        'type' => $file->type ,
                        'user' => @$file->user->email ?: 'نامشخص' ,
                        'id' => $file->id ,
                        'title' => $file->alt ,
                        'width' => $w ,
                        'height' => $h ,
                    ];
                }
            }
            return $array;
        }
        return false;
    }

    /**
     * make file path
     * @param null $file
     * @param null $size
     * @param bool $fullpath
     * @return string
     */
    function makeFilePath ($file = null , $size = null , $fullpath = false)
    {
        $whatFolder = ($file->type == 'image') ? 'image/' : 'files/';
        $date = $file->created_at;

        $path = $fullpath
            ? public_path($whatFolder . $this->makeDateFolderPath($date) . '/' . $file->id . '/')
            : $whatFolder . $this->makeDateFolderPath($date) . '/' . $file->id . '/';

        if ($size != null) {
            switch ($size) {
                case 'small' :
                    $size = 's';
                    break;
                case 'medium' :
                    $size = 'm';
                    break;
                case 'large' :
                    $size = 'l';
                    break;
                case 'thumb' :
                    $size = 't';
                    break;
                case 'round' :
                    $size = 'r';
                    break;
            }
            $path = $path . "$size/";
        }
        return $path;

    }

    /**
     * make data published of file
     * @param null $date
     * @return false|string
     */
    function makeDateFolderPath ($date = null)
    {
        if (!$date) {
            return date('F_Y' , strtotime(date('Y-m-d')));
        }
        return date('F_Y' , strtotime($date));
    }

    /**
     * return file url
     * @param null $size
     * @return mixed
     */
    function url ($size = null)
    {
        return Storage::disk(config('filemanager.disk'))->url($this->makeFilePath($this , $size) . $this->original_name);
    }

    /**
     * return file path
     * @param null $size
     * @return string
     */
    public function path ($size = null)
    {
        $storagePath = Storage::disk(config('filemanager.disk'))->getDriver()->getAdapter()->getPathPrefix();
        return $storagePath . $this->makeFilePath($this , $size) . $this->original_name;
    }

    /**
     * delete file with file id
     * @param $file_id
     * @throws FileManagerException
     */
    public function deleteFile ($file_id)
    {
        if ($fileinfo = self::select()->where('id' , $file_id)->first()) {
            Storage::disk(config('filemanager.disk'))->deleteDirectory($this->makeFilePath($fileinfo));
            $fileinfo->delete();
        } else
            throw new FileManagerException(config('filemanager.lang.fileNotFound'));
    }

    /**
     * user relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user ()
    {
        return $this->belongsTo(config('filemanager.relation.user.class') , 'user_id' , config('filemanager.relation.user.primary_key'));
    }

    /**
     * check file that is image
     * @param $ext
     * @return bool
     */
    function is_image ($ext)
    {
        if (in_array($ext , array('jpg' , 'jpeg' , 'png' , 'gif' , 'PNG' , 'JPG' , 'JPEG' , 'GIF' , 'BMP' , 'bmp' , 'webp' , 'WEBP'))) {
            return true;
        }
        return false;
    }

    /**
     * return image url for file cover
     * @param $type
     * @return string
     */
    private function makeImgForFile ($type)
    {
        return '/assets/images/cms/ext/' . $type . '.jpg';
    }

}
