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
                <div>
                    <a href="#" class="js-open-add-feed-form btn btn-primary" role="button">Add a feed</a>
                    <br/>
                    <br/>
                </div>

                <ul class="list-group feed-list">
                    <li class="list-group-item list-group-item-primary">
                        <a href="#" class="js-load-feed-content">All</a>
                    </li>
                    @foreach($feeds as $feed)
                        <li data-id="{{ $feed->id }}" data-url="{{ $feed->getUrl() }}" class="list-group-item">
                            <a href="{{ route('home', ['feedId' => $feed->id]) }}" class="js-load-feed-content">{{ $feed->getName() }}</a>

                            <a href="#" class="js-open-update-feed-form badge badge-info">Edit</a>
                            <a href="#" class="js-remove-feed badge badge-danger">Remove</a>
                        </li>
                    @endforeach
                </ul>
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