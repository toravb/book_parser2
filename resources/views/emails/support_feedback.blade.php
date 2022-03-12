<!doctype html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<p>Name: {{ $form->name }}</p>
<p>Email: {{ $form->email }}</p>
<p>Subject: {{ $form->subject }}</p>
<p>Message: {{ $form->message }}</p>

</body>
</html>
