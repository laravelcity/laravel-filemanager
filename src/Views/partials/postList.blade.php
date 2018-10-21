<table class="table table-striped">
    <tbody class="table table-striped" style="background: #fff">
    @forelse($posts as $post)
        <tr>
            <td>
                <a class="getLink" data-title="{{$post->{config('filemanager.posts.title_field')} }}" href="javascript:void(0)" data-href="{{$post->{config('filemanager.posts.url_field')} }}">  {{$post->{config('filemanager.posts.title_field')} }}</a>
            </td>
        </tr>
    @empty
        <p style="color: #f00">@lang('FileManager::filemanager.search-no-result')</p>
    @endforelse

    </tbody>
</table>

<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>

<script type="">

    $(document).ready(function () {
        $(".getLink").click(function () {
            var title=$(this).data('title');
            var url=$(this).data('href');
            returnUrl(title,url);
            window.close();
        });

        $('#title').change(function () {
            $.ajax({
                url:'{{route('filemanager.searchpost')}}',
                type:'get',
                json:true,
                data:{
                    title:$('input[name=title]').val(),
                },
                beforeSend:function(){
                    $('#post-box').html('{{trans('FileManager::filemanager.loading')}}')
                },
                success:function(data) {
                    $('#post-box').html(data.view);
                }
            });
        });
    });

    function returnUrl(title,url) {
        var parameter = {
            url: url,
            title: title
        };

        top.tinymce.activeEditor.windowManager.getParams().oninsert(parameter);
        top.tinymce.activeEditor.windowManager.close();
    }
</script>