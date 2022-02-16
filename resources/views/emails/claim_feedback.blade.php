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
<p>Subject: {{ $form->subject }}</p>
<p>Link source: {{ $form->link_source }}</p>
<p>Link content: {{ $form->link_content }}</p>
<p>Name: {{ $form->name }}</p>
<p>Email: {{ $form->email }}</p>
<p>Agreement: {{ $form->agreement }}</p>
<p>Copyright holder: {{ $form->copyright_holder }}</p>
<p>Interaction: {{ $form->interaction }}</p>


</body>
</html>
