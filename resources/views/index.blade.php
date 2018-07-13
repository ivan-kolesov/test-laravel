@extends('master')

@section('content')

    <script type="text/javascript">
        window.__INITIAL_STATE__ = '@json([
            'selectedFeed' => $selectedFeed ?? 'undefined',
            'feeds' => $feeds,
        ])';
    </script>

    <div id="app">
        <index></index>
    </div>

@endsection