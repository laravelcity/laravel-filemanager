<script>
    var inFormOrLink;
    var more_tinymce = "<!--more-->";
    var align_image_tinymce = "";
    var gallary_tinymce = false;
    var direct_image_tinymce = false;


    function elFinderBrowser(callback) {
        tinymce.activeEditor.windowManager.open({
            file: '/admin/file-manager/index/image',// use an absolute path!
            title: '{{trans('FileManager::filemanager.image-library')}}',
            width: 1200,
            height:750,
            resizable: 'yes'
        }, {
            oninsert: function (parameter) {

                align_image_tinymce = parameter.alignment;
                gallary_tinymce = parameter.isGallary;

                callback(parameter.filePaths, {
                    alt: parameter.description
                });

                if (direct_image_tinymce) {
                    $(".mce-title").first().closest(".mce-reset").find(".mce-txt").each(function () {
                        if ($(this).html() === "Ok") {
                            $(this).click();
                            return;
                        }
                    });

                    direct_image_tinymce = false;
                }
            }
        });
        return false;
    }
    function getNormalImagesFormat(editor, content) {
        var align = align_image_tinymce;
        var imagesArray = getImages(editor, content);

        var output = "";
        var alignment = "";

        switch (align) {
            case "right":
            {
                alignment = "float:right;";
                break;
            }
            case "center":
            {
                alignment = "display:block;margin-right:auto;margin-left:auto;";
                break;
            }
            case "left":
            {
                alignment = "float:left;";
                break;
            }
            default:
            {
                break;
            }

        }

        $(imagesArray).each(function () {
            if (alignment) {
                output += "<p style='clear:both;'><img class='img-responsive' style='" + alignment + "' src='" + this.imagePath + "' alt='" + this.description + "' /></p>";
            } else {
                output += "<p><img class='img-responsive' src='" + this.imagePath + "' alt='" + this.description + "' /></p>";
            }
        });

        return output;
    }
    function getGallaryFormat(editor, content) {
        var output = "";
        var imagesArray = getImages(editor, content);

        output += "<div class='demo-gallery'><ul class='list-unstyled row lightgallery'>";

        var images = [];

        $(imagesArray).each(function () {
            images.push({
                ImagePath: this.imagePath
            });
        });

        $.ajax({
            type: "post",
            contentType: "application/json",
            dataType: "json",
            url: "/basic/image/ThumbnailGallaries",
            async: false,

            data: JSON.stringify(images),

            success: function (data) {
                $(imagesArray).each(function (index) {
                    this.thumbnail = data[index];
                });
            }
        });

        $(imagesArray).each(function () {
            output += String.format("<li class='col-xs-6 col-sm-4 col-md-3' data-responsive='{0} 375, {0} 480, {0} 800'\
                                       data-src='{0}'\
                                       data-sub-html='<h4>{2}</h4>'>\
                                    <a href=''>\
                                        <img class='img-responsive' src='{1}' alt='{2}'>\
                                    </a>\
                                </li>", this.imagePath, this.thumbnail, this.description);
        });

        output += "</ul></div>";

        return output;
    }
    function getImages(editor, content) {
        var imagesArray = [];
        var align = align_image_tinymce;

        if (content.indexOf("\\\\ArrayImages:") > -1) {
            var startIndex = content.indexOf("\\\\ArrayImages:");

            if (startIndex > -1) {
                content = content.replace(":Alignment:" + align, "");
            }

            var startSrc = content.lastIndexOf("src=\"", startIndex) + 5;
            var endSrc = content.indexOf("\"", startSrc);
            var images = content.substring(startSrc, endSrc);

            if (startIndex > -1) {
                if (startSrc > -1 && endSrc > -1) {
                    $(images.split("\\\\ArrayImages:")).each(function () {
                        var splits = this.toString().split(",");
                        imagesArray.push({
                            imagePath: splits[0],
                            description: splits[1]
                        });
                    });
                }
            }
        } else {
            var node = $(editor.selection.getNode());

            if (!node.attr("src")) {
                node = node.find("img").first();
            }

            imagesArray.push({
                imagePath: node.attr("src"),
                description: node.attr("alt")
            });
        }

        return imagesArray;
    }

    tinymce.init({
        selector: '.laravelcity-editor',
        menubar: true,
        setup: function (editor) {
            editor.addButton('ReadMore', {
                tooltip: 'Read More',
                icon: 'hr',
                onclick: function () {
                    if (editor.getContent().indexOf("<hr class=\"read-more\" />") === -1) {
                        editor.insertContent("<hr class=\"read-more\" />");
                    }
                }
            });
            editor.addButton('CBlockQuote', {
                tooltip: 'BlockQuote',
                icon: 'mce-ico mce-i-blockquote',
                onclick: function() {
                    editor.focus();
                    editor.selection.setContent(' <blockquote> <div class="rq ion-quote"></div>'+ editor.selection.getContent() + '<div class="lq ion-quote"></div></blockquote> ');
                }
            });

            editor.addButton('customAanchor', {
                text: 'Insert Anchor',
                icon: false,
                onclick: function() {
                    // alert(tinymce.activeEditor.selection.getNode().nodeName);
                    editor.windowManager.open({
                        title: 'Insert Anchor',
                        body: [
                            {type: 'textbox', name: 'anchor', label: 'Anchor Name'}
                        ],
                        onsubmit: function(e) {
                            editor.focus();
                            // Insert content when the window form is submitted
                            var selectednode = editor.selection.getNode();
                            selectednode.setAttribute("id",e.data.anchor);
                            selectednode.setAttribute("class",'anchor-h');
                        }
                    });
                }
            });
//            editor.addButton('SelectImages', {
//                tooltip: 'Select images',
//                icon: 'readmore  glyph-icon icon-file-photo-o',
//                onclick: function () {
//                    direct_image_tinymce = true;
//                    $(".mce-ico.mce-i-image").first().click();
//                    $(".mce-ico.mce-i-browse").first().click();
//                }
//            });
            editor.on('change', function () {

                if (align_image_tinymce) {
                    var content = this.getContent();
                    var output = "";

                    if (gallary_tinymce) {
                        output = getGallaryFormat(this, content);
                    } else {
                        output = getNormalImagesFormat(this, content);
                    }

                    this.focus();
                    $(this.selection.getNode()).find("img").first().remove();
                    this.selection.setContent(output);
                    this.focus();
                }

                align_image_tinymce = "";
            });
            editor.addButton('addCodeTag', {
                title: 'تگ Code',
                image: tinymce.baseURL + '/images/code.png',
                onclick: function() {
                    editor.focus();
                    editor.selection.setContent(' <code>'+ editor.selection.getContent() + '</code> ');
                }
            });
        },
        file_picker_callback: elFinderBrowser,
        image_advtab: true,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor codesample directionality emoticons template paste  ',
            'searchreplace visualblocks code  fullscreen',
            'insertdatetime media table contextmenu paste code',
            'searchreplace  visualblocks visualchars code fullscreen '
        ],
        contenteditable:false,
        external_plugins: {
            codemirror: "/plugins/tinymce/plugins/codemirror/plugin.js"
        },
        codemirror: {
            indentOnInit: true,
            path: 'codemirror-4.8',
            config: {
                lineNumbers: true
            }
        },
        codesample_languages: [
            {text:'HTML/XML',value:'markup'},
            {text:"XML",value:"xml"},
            {text:"HTML",value:"html"},
            {text:"SVG",value:"svg"},
            {text:"CSS",value:"css"},
            {text:"Javascript",value:"javascript"},
            {text:"bash",value:"bash"},
            {text:"c",value:"c"},
            {text:"elixir",value:"elixir"},
            {text:"git",value:"git"},
            {text:"http",value:"http"},
            {text:"jade",value:"jade"},
            {text:"java",value:"java"},
            {text:"JSON",value:"json"},
            {text:"jsonp",value:"jsonp"},
            {text:"kotlin",value:"kotlin"},
            {text:"less",value:"less"},
            {text:"markdown",value:"markdown"},
            {text:"nginx",value:"nginx"},
            {text:"perl",value:"perl"},
            {text:"PHP",value:"php"},
            {text:"pure",value:"pure"},
            {text:"python",value:"python"},
            {text:"sas",value:"sas"},
            {text:"sass",value:"sass"},
            {text:"scss",value:"scss"},
            {text:"SQL",value:"sql"}


        ],
        codesample_content_css: "http://ourcodeworld.com/material/css/prism.css",
        codesample_dialog_width:'900px',
        toolbar1: 'code | insertfile undo redo | styleselect formatselect  cleanup removeformat |forecolor CBlockQuote bold italic hr | alignleft aligncenter alignright | bullist numlist outdent indent',
        toolbar2: 'ltr rtl | link anchor image SelectImages media | forecolor backcolor emoticons  | ReadMore | codesample | addCodeTag | customAanchor ',
        default_link_target: "_blank",
        link_assume_external_targets: true,
        link_context_toolbar: true,
        rel_list: [
            {title: 'None', value: ''},
            {title: 'nofollow', value: 'nofollow'}
        ],
        target_list: [
            {title: 'None', value: ''},
            {title: 'New page', value: '_blank'},
            {title: 'Same page', value: '_self'},
            {title: 'LIghtbox', value: '_lightbox'}
        ],
        link_list: [
            {title: 'laravelcity.com', value: 'http://www.laravelcity.com'},
        ],

        relative_urls: false,
        height: 500,
        content_css: [
            '/plugins/tinymce/content.min.css?v=1'
        ],
        valid_children : "+body[style]",
        extended_valid_elements : "script[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],style[type],style"

    });


</script>