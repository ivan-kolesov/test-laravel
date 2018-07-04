@extends('master')

@section('scripts')
    {!! HTML::script('js/feed.js') !!}
    <script type="text/javascript">
        let selectedFeed = {{ $selectedFeed ?? 'undefined' }};
    </script>
@endsection

@section('content')

    @php
        /**
        * @var \Illuminate\Support\Collection $feeds
        * @var \App\Models\Feed $feed
        */
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-4">
                <a href="#" class="js-open-add-feed-form">Add a feed</a>

                @if ($feeds->isEmpty())
                    Feeds are empty. <a href="#" class="js-open-add-feed-form">Add a feed</a>
                @else
                    <ul class="list-group list-group-flush feed-list">
                        @foreach($feeds as $feed)
                            <li data-id="{{ $feed->id }}" data-url="{{ $feed->getUrl() }}" class="list-group-item">
                                <a href="{{ route('home', ['feedId' => $feed->id]) }}" class="js-load-feed-content">{{ $feed->getName() }}</a>

                                <button type="button" class="btn btn-primary btn-sm js-open-update-feed-form">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm js-remove-feed">Remove</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-8">
                <ul class="feed-post-list"></ul>
                <button type="button" class="btn btn-primary btn-lg btn-block js-load-more-content hidden">Get more</button>
            </div>
        </div>
    </div>

    @include('modals.addFeed')
    @include('modals.editFeed')
    @include('modals.updatedFeed')
    @include('modals.confirmRemoveFeed')

@endsection