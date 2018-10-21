@extends('FileManager::layout')

@section('js')
    <script>
        /**
         * make img for dispaly
         * @param image
         * @returns {Mixed|jQuery|*|HTMLElement}
         */
        function makeImage(image) {
            var img = document.createElement('img');
            $(img).attr('src', image.image);

            $.each(image, function (key, value) {
                $(img).attr('data-' + key, value);
            });

            $(img).addClass('img-filemanager');
            var div_img = document.createElement('div');
            $(div_img).addClass('img');
            var div_check = document.createElement('input');
            $(div_check).attr('type', 'checkbox');
            $(div_check).attr('name', 'imagesSelected[]');
            $(div_check).addClass('check');

            $(div_img).html($(img));
            $(div_img).append($(div_check));


            $(div_img).click(function () {
                $('.showFiles .img').removeClass('active');
                $('.showFiles .check').prop('checked', false);
                $(this).addClass('active');
                $(this).find('.check').prop('checked', true);
                setImageForSelect(image);
                showSelectionImage();

            });

            $(".img-title").blur(function () {
                if (image.id == selectedImage.id)
                    image.title = $(this).val();
            });

            return $(div_img);
        }

        /**
         * add img info to elements
         * @param file
         * @param addToImageArray
         */
        function setImageForSelect(image) {
            $(".img-name").html(image.original_name);
            $(".img-size").html(image.size);
            $(".img-width").html(image.width);
            $(".img-height").html(image.height);
            $(".img-mime").html(image.mimeType);
            $(".img-user").html(image.user);
            $(".img-id").html(image.id);
            $(".img-url").val(image.url);
            $(".img-title").val(image.title);
            $(".img-element").attr('src', image.image);
            selectedImage = image;

//        if(addToImageArray)
//            addToResultArray(selectedImage);

            showButtonsAndDetails();
        }

        /**
         * unselected images
         */
        function cleanImageForSelect() {
            $(".img-name").html('');
            $(".img-size").html('');
            $(".img-width").html('');
            $(".img-height").html('');
            $(".img-mime").html('');
            $(".img-user").html('');
            $(".img-id").html('');
            $(".img-url").val('');
            $(".img-title").val('');
            $(".img-element").attr('src', defaultImage);
            selectedImage = null;
            imagesForResultArray = [];
            imagesForResul = null;
            self.showSelectionImage();
            $('.showFiles .img').removeClass('active');
            $('.showFiles .check').prop('checked', false);
            showButtonsAndDetails();
        }

        /**
         * add images to display element
         * @param files
         */
        function appendTo(files) {
            $.each(files, function (key, value) {
                $imageDisplay.append(makeImage(value));
            });
        }

        function getImageRepository() {
            window.top.getImageSelected(selectedImage);
        }

        /**
         * show selected images
         */
        function showSelectionImage() {
            $(".select-preview").html('');
            count = 0;
            if (selectedImage != null)
                count = 1;


            $(".s-img-preview label span").html(count);
            var img = document.createElement('img');
            $(img).attr('src', selectedImage.image);
            $(img).addClass('img-preview');
            $(".select-preview").append($(img));

        }

        /**
         * show image and file details when is selected
         */
        function showButtonsAndDetails() {
            if (selectedImage != null) {
                $(".details").show();
            }
            else {
                $(".details").hide();
            }
        }



    </script>
@endsection



