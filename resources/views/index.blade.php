<html>

    <head>
        <title>InnoFlow</title>
    </head>

    <body>

        <ul>
            @foreach ($commits as $commit)
                <li>{{ $commit->commitId . "  " . $commit->created_at }}</li>
            @endforeach
        </ul>

    </body>

</html>