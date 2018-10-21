<textarea @if(isset($name)) name="{{$name}}" id="{{$name}}" @else name="content" id="content" @endif class="lydaweb-editor">{{@$content}}</textarea>

@section('css')@parent
    <style>
        .mce-tinymce{
            border: 1px solid #e3e3e3 !important;
        }
    </style>
@endsection

<script>
    window.editor={};
    window.editor.filemanager={};
    window.editor.linkmanager={};

    @forelse(config('filemanager.editor.tinymce') as $key=>$val)
        window.editor.{{$key}}='{{$val}}';
    @empty
    @endforelse

    window.editor.filemanager.file='{{route('filemanager.index','both')}}';
    window.editor.filemanager.title="{{trans('FileManager::filemanager.editor-title')}}";

    window.editor.linkmanager.file='{{route('filemanager.index','post')}}';
    window.editor.linkmanager.title="{{trans('FileManager::filemanager.linkmanager-browser-title')}}";

    @forelse(config('filemanager.editor.filemanager') as $key=>$val)
        window.editor.filemanager.{{$key}}='{{$val}}';
    @empty
    @endforelse

</script>

<script src="{{asset("vendor/lydaweb/filemanager/tinymce/tinymce.min.js")}}"></script>
<script src="{{asset("vendor/lydaweb/filemanager/tinymce/config.js")}}"></script>

