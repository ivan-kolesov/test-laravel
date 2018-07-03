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
        <div class="feed-list-container">
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

            <div class="add-feed-popup" style="display: none;">
                <form>
                    <label>
                        Url:
                        <input type="text" name="url" size="100"/>
                    </label>

                    <button type="button" class="js-add-feed">Add</button>
                </form>

                <a href="#" class="js-close-forms">Close</a>
            </div>

            <div class="update-feed-form" style="display: none;">
                <form>
                    <label>
                        Url:
                        <input type="text" name="url" size="100"/>
                    </label>
                    <input type="hidden" name="feed_id" value=""/>

                    <button type="button" class="js-update-feed">Update</button>
                </form>

                <a href="#" class="js-close-forms">Close</a>
            </div>
        </div>

        <div class="feed-post-container">
            <ul class="feed-post-list"></ul>
            <a href="#" class="js-load-more-content" data-from-date="" data-feed-id="" style="display: none;">Load more</a>
        </div>
    </div>

@endsection