<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Document</title>
</head>
<body style="background: #f5f5f5;padding: 20px;">
<div class="well">
    <input  type="text" placeholder="Search" class="form-control" name="title" id="title">
</div>

<br>
<div id="post-box">
    @include('FileManager::partials.postList',['posts'=>$posts])
</div>

</body>
</html>

