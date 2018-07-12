<template>
    <div class="row">
        <div class="col-4">
            <div>
                <a href="#" class="btn btn-primary" role="button" v-on:click="showAddFeedPopup">Add a feed</a>
                <br/>
                <br/>
            </div>

            <feeds-list :feeds="feeds" :parentSelectedFeedId="selectedFeedId"></feeds-list>
        </div>
        <div class="col-8">
            <posts :parentSelectedFeedId="selectedFeedId"></posts>
            <button type="button" class="btn btn-primary btn-lg btn-block js-load-more-content hidden" v-on:click="fireLoadPosts">Get more</button>
        </div>
    </div>
</template>

<script>
    import Feeds from './FeedsComponent';
    import Posts from './PostsComponent';

    export default {
        data() {
            let initialState = JSON.parse(window.__INITIAL_STATE__) || {};

            return {
                feeds: initialState.feeds,
                selectedFeedId: initialState.selectedFeed !== 'undefined' ? initialState.selectedFeed : null
            };
        },
        components: {
            'feeds-list': Feeds,
            'posts': Posts
        },
        methods: {
            showAddFeedPopup(e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-add-feed').modal();
            },
            fireLoadPosts(e) {
                e.preventDefault();
                e.stopPropagation();

                Event.$emit('load-posts');
            }
        }
    }
</script>