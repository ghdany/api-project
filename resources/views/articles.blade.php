<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>TheJournal - River Viewer</title>

    </head>

    <body>

        <h1>{{count($articles)}} Articles</h1>


        @if (count($articles))
        <ul>

            @foreach ($articles as $article)
                <li>
                    <h2>{{ $article["title"] or 'Missing title' }}</h2>
                    <p>{{ $article["excerpt"] or '' }}</p>

                    <img src="{{ $article["image"] or 'no_image.png' }}" alt="article image" />
                </li>
            @endforeach

        </ul>
        @endif
    </body>

</html>