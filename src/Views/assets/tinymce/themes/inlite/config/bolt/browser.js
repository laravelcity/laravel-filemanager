configure({
  configs: [
    './prod.js'
  ],
  sources: [
    source('amd', 'ephox/tinymce', '', mapper.constant('../../../../../tinymce')),
    source('amd', 'ephox.mcagar', '../../Lib/test', mapper.flat),
    source('amd', 'ephox', '../../Lib/test', mapper.flat)
  ]
});
