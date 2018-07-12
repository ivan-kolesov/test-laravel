@extends('master')

@section('content')

    <script type="text/javascript">
        window.__INITIAL_STATE__ = '@json([
            'selectedFeed' => $selectedFeed ?? 'undefined',
            'feeds' => $feeds,
        ])';
    </script>

    <div class="container" id="app">
        <index></index>

        <add-feed-popup></add-feed-popup>
        <edit-feed-popup></edit-feed-popup>
        <remove-feed-popup></remove-feed-popup>
    </div>

    @include('modals.updatedFeed')

@endsection