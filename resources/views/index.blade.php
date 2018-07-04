@extends('master')

@section('scripts')
    {!! HTML::script('js/feed.js') !!}
    <script type="text/javascript">
        let selectedFeed = '{{ $selectedFeed }}';
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
                <a href="#" class="js-open-add-feed-form">Create a feed</a>

                @if ($feeds->isEmpty())
                    Feeds are empty. <a href="#" class="js-open-add-feed-form">Create a feed</a>
                @else
                    <ul class="feed-list">
                        @foreach($feeds as $feed)
                            <li data-id="{{ $feed->id }}" data-url="{{ $feed->getUrl() }}">
                                <a href="{{ route('home', ['feedId' => $feed->id]) }}" class="js-load-feed-content">{{ $feed->getUrl() }}</a>

                                <a href="#" class="js-open-update-feed-form">Edit</a>
                                <a href="#" class="js-remove-feed">Remove</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-8">
                <ul class="feed-post-list"></ul>
                <a href="#" class="js-load-more-content hidden" data-from-date="" data-feed-id="">Get more</a>
            </div>
        </div>
    </div>

    @include('modals.addFeed')
    @include('modals.editFeed')
    @include('modals.updatedFeed')
    @include('modals.confirmRemoveFeed')

@endsection