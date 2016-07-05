<!Doctype html>
<html>
<meta charset="utf8">
<head>

</head>
<body>

<form action="/fileUpLoader" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="myfile"><br/><br/>
    <input type ="text" name =user><br>
    <input type="submit" value="提交"/>
</form>


</body>

</html>