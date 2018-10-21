<div style="position: relative" class="filemanager-cover-image">
    @if($image)
        <button type="button"  data-type="image" class="filemanager-remove-image removeFileSelected" style="    position: absolute;
    top: -8px;
    right: -7px;
    background: #f00;
    color: #fff;
    width: 26px;
    height: 26px;
    font-size: 16px;
    border: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
    /* opacity: 0.7; */
    padding: 0 6px 2px 6px;
    border-radius: 50%;
    cursor: pointer;">x</button>
    @endif
    <img style="display: block;margin: 0 auto;margin-bottom: 8px;" width="90%"   src="{{$image?$image->url('m'):config('filemanager.default-image')}}"  id="img-holder">
    <button style="display: block;width: 90%;margin: 0 auto" type="button" onclick="getFileManager('image','.filemanager-cover')" class="btn btn-cms waves-effect waves-light" ><i class="ti-image"></i> {{trans('FileManager::filemanager.cover')}} </button>
    <input id="cat-img-id" value="{{@$image->id}}" type="hidden" name="image">

</div>


<div id="full-width-modal" class="modal fade filemanager-cover" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <button style="color: #fb0000;
box-shadow: none;
text-shadow: none;
opacity: 1;
font-size: 32px;
margin-top: -21px;
margin-bottom: 6px;
cursor: pointer;
z-index: 2226666;" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <div style="padding: 2px;background: #201c1c;" class="modal-content">
                <iframe style="width: 100%;border: none;overflow: hidden"  height="600" ></iframe>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@include('FileManager::script',['type'=>@$type])

