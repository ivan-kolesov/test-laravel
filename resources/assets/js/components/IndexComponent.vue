<template>
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="container">
                    <b-link class="btn btn-primary" v-b-modal.modal-add-feed>Add a feed</b-link>
                    <br/>
                    <br/>
                </div>

                <feeds-list :feeds="feeds" :parentSelectedFeedId="selectedFeedId"></feeds-list>
            </div>
            <div class="col-8">
                <posts :parentSelectedFeedId="selectedFeedId"></posts>
            </div>
        </div>

        <add-feed-popup></add-feed-popup>
    </div>
</template>

<script>
    import Feeds from './FeedsComponent';
    import Posts from './PostsComponent';
    import AddFeedPopup from './AddFeedPopupComponent';

    export default {
        data() {
            let initialState = JSON.parse(window.__INITIAL_STATE__) || {};

            return {
                feeds: initialState.feeds,
                selectedFeedId: initialState.selectedFeed !== 'undefined' ? parseInt(initialState.selectedFeed) : null
            };
        },
        components: {
            'feeds-list': Feeds,
            'posts': Posts,
            'add-feed-popup': AddFeedPopup
        }
    }
</script>