<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    @section('css')@show
    <style type="">
        body {
            background: #222;
        }

        #sticky-sidebar {
            position: fixed;
            width: 230px;
            background: #2c2b2b;
            height: 100%;
            float: right;
            top: 40px;
            right: 0;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            color: #ddd;
        }

        #sticky-sidebar .details .img-element {
            width: 100px;
            height: 100px;
            max-width: 100%;
            margin: 0 auto;
            display: block
        }

        #sticky-sidebar .details .deleteFile {
            font-size: 12px;
            cursor: pointer;
            text-align: center;
            display: block
        }

        #sticky-sidebar * {
            font-size: 10px;
        }

        #sticky-sidebar .details {
            display: none;
        }

        #main {
            width: 100%;
            padding: 30px;
            right: 230px;
            padding-left: 260px;
            top: 40px;
            position: fixed;
            height: 100%;
            background: #ddd;
            overflow-y: auto;

        }

        #top-main {
            width: 100%;
            padding-right: 250px;
            background: #222;
            height: 50px;
        }

        .nav-link {
            border-radius: 0 !important;
        }

        .uploading {
            color: #fff;
            background: #2d2d2d;
            padding: 30px;
        }

        .dropzone {
            min-height: 200px !important;
            border: 2px dashed rgb(89, 89, 89) !important;
            background: #252525 !important;
            padding: 20px 20px !important;

        }

        .dropzone .dz-message {
            text-align: center;
            margin: 3em 0;
            color: #908585;
            font-size: 21px;
        }

        .img-filemanager {
            border: 2px solid #cfcfcf;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 2px;
            width: 100%;
        }

        .img-filemanager:hover {
            border: 2px solid #0270ca;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.28);
        }

        .images {
            height: 450px;
            width: 100%;
            overflow-y: auto;
        }

        .images .active {
            border: 6px solid #0270ca;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.28);
        }

        .images .img {
            width: 130px;
            height: 130px;
            display: inline-block;
            margin: 6.7px;
            position: relative;
        }

        .images .img img {
            width: 100%;
            height: 100%;
        }

        .images .check {
            position: absolute;
            height: 25px;
            width: 25px;
            right: 10px;
            top: 10px;
        }

        .s-img-preview {
            background: #2c2b2b;
            padding: 0 10px;
            text-align: right;
            direction: rtl;
            position: absolute;
            bottom: 40px;
            left: 0;
            width: 100%;
            height: 41px;
            font-size: 11px;
            color: #fff;
        }

        .s-img-preview label {
            display: inline-block;
            float: right;
            margin-top: 16px;
        }

        .s-img-preview .select-preview {
            float: right;
        }

        .s-img-preview .img-preview {
            height: 40px;
            width: 40px;
            display: inline-block;
            margin: 5px;
        }

        .disabled {
            pointer-events: none;
            cursor: not-allowed;
            filter: alpha(opacity=65);
            -webkit-box-shadow: none;
            box-shadow: none;
            opacity: .65;
        }
    </style>
</head>
<body>
<ul class="nav nav-pills nav-pills nav-justified" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" onclick="reload();" id="pills-home-tab" data-toggle="pill" href="#pills-home"
           role="tab" aria-controls="pills-home"
           aria-selected="true"> {{trans('FileManager::filemanager.select-file')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
           aria-controls="pills-profile" aria-selected="false"> {{trans('FileManager::filemanager.file-upload')}}</a>
    </li>
</ul>
<div id="file-manager" class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div id="main">
            <div class="images">
                <div class="showFiles"></div>
                <div class="clearfix"></div>
                <div class="s-img-preview">
                    <label>{{trans('FileManager::filemanager.selected')}} <span>0</span> </label>
                    <div class="select-preview"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="sticky-sidebar">
            <div class="details">
                <img src="{{config('filemanager.default-image')}}" class="img-element">
                <a class="text-danger deleteFile">{{trans('FileManager::filemanager.delete')}}</a>
                <div class="clearfix"></div>

                <hr>
                @if($type=="image")
                    <p><b>{{trans('FileManager::filemanager.quality')}}</b> : <span class="img-size"></span></p>
                @endif


                <p><b> {{trans('FileManager::filemanager.filetype')}}</b> : <span class="img-mime"></span></p>
                <p><b> {{trans('FileManager::filemanager.author')}} </b> : <span class="img-user"></span></p>
                <p><b> {{trans('FileManager::filemanager.name')}} </b> : <span class="img-name"></span></p>
                <hr>
                <div class="form-horizontal">
                    <div class="form-group">
                        <input type="text" placeholder="{{trans('FileManager::filemanager.url')}}"
                               class="img-url form-control form-control-sm" value="" readonly="">
                    </div>

                    <div id="group-alt" class="form-group ">
                        <input type="text" placeholder="{{trans('FileManager::filemanager.replace-text')}}"
                               class="img-title form-control form-control-sm" value="">
                    </div>

                    @if($type=="image")
                        <div id="group-position" class="form-group ">
                            <select id="ddlAlignment" class="form-control form-control-sm">
                                <option value="right">{{trans('FileManager::filemanager.rtl')}} </option>
                                <option value="center"
                                        selected="">{{trans('FileManager::filemanager.center')}} </option>
                                <option value="left"> {{trans('FileManager::filemanager.ltr')}}</option>
                                <option value="none">{{trans('FileManager::filemanager.nothing')}} </option>
                            </select>
                        </div>

                    @endif
                    <div class="form-group ">
                        <select id="ddlAlignment" class=" form-control form-control-sm">
                            <option value="right">  {{trans('FileManager::filemanager.right')}}</option>
                            <option selected value="center">  {{trans('FileManager::filemanager.center')}}</option>
                            <option value="left">  {{trans('FileManager::filemanager.left')}}</option>
                            <option value="none">  {{trans('FileManager::filemanager.none')}}</option>
                        </select>
                    </div>


                </div>
                <div class="bottom-button ">
                    <button type="button" class="btn  btn-block btn-success btn-sm"
                            onclick="getImageRepository()">{{trans('FileManager::filemanager.select')}}</button>
                    <button type="button" class="btn  btn-block btn-warning btn-sm"
                            onclick="cleanImageForSelect()">{{trans('FileManager::filemanager.clean')}}</button>
                    <button style="display: none" onclick="sendActions('delete')"
                            class="btn btn-block btn-danger btn-sm  top-buttons"> {{trans('FileManager::filemanager.delete-all')}}  </button>

                </div>

            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="uploading">
            {{trans('FileManager::filemanager.upload-max-filesize')}} : {{ ini_get('upload_max_filesize') }}
            <div class="clearfix"></div>
            <br>
            <form action="{{route('filemanager.upload')}}" method="post" enctype="multipart/form-data" class="dropzone"
                  id="my-awesome-dropzone">
                {{csrf_field()}}
                <input name="type" type="hidden" value="{{$type}}"/>

                <div class="fallback">
                    <input name="files" type="file" multiple/>
                </div>
                <div class="dz-message" data-dz-message><span>{{trans('FileManager::filemanager.dz-message')}} </span>
                </div>
            </form>
        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>

<script>

    /*
    / config
   */

    window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token()
            ]) !!};

    (function ($) {
        $.ajaxSetup({
            data: {
                _token: window.Laravel.csrfToken
            },
            beforeSend: function (xhr) {
                $("#loading-tf").show();
            },
            complete: function (xhr) {
                $("#loading-tf").hide();
            }
        });
    })(jQuery);

    /*
   / drop zone
  */

    Dropzone.options.myAwesomeDropzone = {
        maxFilesize: {{$type=="image"?config('filemanager.upload.max-filesize.image'):config('filemanager.upload.max-filesize.file')}},
        dictResponseError: "{{trans('FileManager::filemanager.dictResponseError')}}",
        acceptedFiles: "<?php
            switch ($type) {
                case 'image' :
                    echo join(',' , config('filemanager.upload.mimes.image'));
                    break;
                case 'file' :
                    echo join(',' , config('filemanager.upload.mimes.file'));
                    break;
                default :
                    echo join(',' , config('filemanager.upload.mimes.both'));
                    break;
            }
            ?>",
    };

    /*
    / file manager
    */

    // define vars
    var selectedImage = null;
    var imagesForResultArray = [];
    var imagesForResul = null;
    var defaultImage = "{{config('filemanager.default-image')}}";
    var imageRepository = "";
    $pageNumber = 1;
    $total = 1;
    $total = 1;
    $fileManager = $("#file-manager");
    $imageDisplay = $("#file-manager").find(".showFiles");


    // load files or images
    function load() {
        var data = {
            page: $pageNumber,
            name: '',
            date: '',
            user_id: 0,
            type: '{{$type}}',
            _token: '{{csrf_token()}}'
        };
        $.ajax({
            url: '{{route('filemanager.list',['type'=>$type])}}',
            type: 'post',
            data: data,
            success: function (data) {
                $total = data.pagination.last_page;
                $pageNamber = data.pagination.current_page;
                appendTo(data.files);
            }
        });
    }

    function reload() {
        $imageDisplay.html('');
        load();
    }

    function loadMore() {
        $pageNumber++;
        load();
    }

    function sendActions(action) {
        if (imagesForResultArray.length == 0) {
            alert("{{trans('FileManager::filemanager.not-select-file')}}");
        }
        else {
            var data = {
                files: imagesForResultArray,
                action: action,
                _token: window.Laravel.csrfToken
            };

            if (action == 'delete') {
                if (confirm("{{trans('FileManager::filemanager.delete-all-message-confirm')}}")) {
                    $imageDisplay.html('');
                    cleanImageForSelect();
                    $.ajax({
                        type: 'post',
                        url: '{{route('filemanager.actions')}}',
                        data: data,
                        success: function () {
                            reload();
                        }
                    });
                }

            }
        }


    }

    window.onload = function (ev) {

        load();


        $(".deleteFile").click(function () {
            if (selectedImage != null) {
                if (confirm("{{trans('FileManager::filemanager.delete-message-confirm')}}")) {
                    $.ajax({
                        type: 'GET',
                        url: '/{{config("filemanager.route.prefix").'/index/delete'}}/' + selectedImage.id,
                        success: function () {
                            reload();
                        }
                    });
                }
            }
            else
                alert("{{trans('FileManager::filemanager.not-select-file')}}");
        });
        $(".img-title").blur(function () {
            $(".bottom-button").addClass('disabled');

            $.ajax({
                type: 'POST',
                data: {title: $(this).val()},
                url: '/{{config("filemanager.route.prefix").'/index/update'}}/' + selectedImage.id,
                success: function () {
                    $(".bottom-button").removeClass('disabled');
                }
            });
        });


        $(".images").scroll(function () {
            if ($('.images').scrollTop() === (($imageDisplay.height() - $('.images').height()))) {
                if ($pageNumber >= $total) {
                    return;
                }
                if ($pageNamber <= $total)
                    loadMore();
            }
        });


        $(".search").change(function () {
            reload();
        });

    };

</script>

@yield('js')
</body>
</html>