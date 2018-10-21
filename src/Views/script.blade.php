<script>
    window.modal = null;

    window.getImageSelected = function (image) {
        setImageInCover(image);
    };

    function fileManagerCoverModal(modal, type) {
        $(modal).modal(type)
    }

    function getFileManager(type, modal) {
        window.modal = modal;
        if (type == 'image') {
            var url = '{{route('filemanager.index',['type'=>$type]).'?single=true'}}';
            $(modal).find('iframe').attr('src', url);
            fileManagerCoverModal(modal, 'show');
        }
        else {
            var url = "/admin/file-manager/index/file";
            $(modal).find('iframe').attr('src', url);
            fileManagerCoverModal(modal, 'show');
        }

    }

    function setImageInCover(image) {
        if (image.type == 'image') {
            $("#img-holder").attr('src', image.image);
            $("#cat-img-id").val(image.id);
            fileManagerCoverModal(window.modal, 'hide');
        }
        else {
            var img= (image.img==undefined) ? image.image : image.img;
            $("#file-holder").attr('src', image.img);
            $("#cat-file-id").val(image.id);
            $("#file-name").html(image.name).show();

            fileManagerCoverModal(window.modal, 'hide');
        }
        window.modal = null;
    }

    function clearImageSelected(type) {
        $("#img-holder").attr('src', '/images/noimage.jpg');
        $("#cat-img-id").val('');
    }

    window.onload = function (ev) {


        $('.removeFileSelected').click(function () {
            type = $(this).data('type');
            if (type == 'image') {
                //video
                $("#img-holder").attr('src', '{{config('filemanager.default-image')}}');
                $("#cat-img-id").val('');
            }
            else {
                //files
                $("#file-holder").attr('src', '');
                $("#file-name").html('');
                $("#cat-file-id").val('');
            }
            $(this).hide();
        });

    }

</script>