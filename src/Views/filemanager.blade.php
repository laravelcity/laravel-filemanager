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

                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $(this).find('.check').prop('checked', false);
                    removeSomeResultArray(image);
                    if (imagesForResultArray.length > 0) {
                        img = imagesForResultArray[imagesForResultArray.length - 1];
                        setImageForSelect(img, false);
                    }
                }
                else {
                    $(this).addClass('active');
                    $(this).find('.check').prop('checked', true);
                    setImageForSelect(image);
                }
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
        function setImageForSelect(file, addToImageArray) {

            if (addToImageArray == undefined)
                addToImageArray = true;

            $(".img-name").html(file.original_name);
            $(".img-size").html(file.size);
            $(".img-width").html(file.width);
            $(".img-height").html(file.height);
            $(".img-mime").html(file.mimeType);
            $(".img-user").html(file.user);
            $(".img-id").html(file.id);
            $(".img-url").val(file.url);
            $(".img-title").val(file.title);
            $(".img-element").attr('src', file.image);
            selectedImage = file;

            if (addToImageArray)
                addToResultArray(selectedImage);

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
         * add image ro imagesForResultArray
         * @param image
         */
        function addToResultArray(image) {
            imagesForResultArray.push(image);
        }

        /**
         * select and unselect image when on click event
         * @param image
         */
        function removeSomeResultArray(image) {
            imagesForResultArray.splice($.inArray(image, imagesForResultArray), 1);
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

        /**
         * show selected images
         */
        function showSelectionImage() {
            $(".select-preview").html('');
            $(".s-img-preview label span").html(imagesForResultArray.length);
            $.each(imagesForResultArray, function (key, value) {
                var img = document.createElement('img');
                console.log(value);
                $(img).attr('src', value.image);
                $(img).addClass('img-preview');
                $(".select-preview").append($(img));
            });


        }

        /**
         * show image and file details when is selected
         */
        function showButtonsAndDetails() {
            if (imagesForResultArray.length > 0) {
                $(".details").show();
                $(".top-buttons").show();
            }
            else {
                $(".details").hide();
                $(".top-buttons").hide();
            }
        }

        function returnFileUrl(imageUrl) {
            try {
                var funcNum = getCkEditorUrlParam('CKEditorFuncNum');

                window.opener.CKEDITOR.tools.callFunction(funcNum, imageUrl);
            } catch (exp) {

                var filePaths = "";
                var description = "";
                var counter = 1;
                var count = imagesForResultArray.length;

                if (imagesForResultArray.length === 1) {
                    filePaths = imagesForResultArray[0].url;
                    description = imagesForResultArray[0].title;
                }
                else {

                    $(imagesForResultArray).each(function (index) {
                        filePaths += this.url + ",";

                        if (this.title) {
                            description += this.title;

                            if (index < imagesForResultArray.length - 1) {
                                description += ",";
                            }
                        }

                        if (counter++ < count) {
                            filePaths += "\\\\ArrayImages:";
                        }
                    });
                }

                var parameter = {
                    filePaths: filePaths,
                    description: description,
                    alignment: $("#ddlAlignment").val(),
                    isGallary: false
                };
                top.tinymce.activeEditor.windowManager.getParams()
                    .oninsert(parameter);
                top.tinymce.activeEditor.windowManager.close();
            }
        }

        function getImageRepository() {
            returnFileUrl(selectedImage.image);
            window.close();
        }

    </script>
@endsection
