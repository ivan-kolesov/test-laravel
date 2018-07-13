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
            <button type="button" class="btn btn-primary btn-lg btn-block"
                    v-on:click="fireLoadPosts"
                    v-bind:class="{hidden: !isDisplayLoadMoreButton}">Get more</button>
        </div>
    </div>
</template>

<script>
    import Feeds from './FeedsComponent';
    import Posts from './PostsComponent';

    export default {
        created() {
            Event.$on('display-load-more-button', (flag) => {
                this.isDisplayLoadMoreButton = flag;
            });
        },
        data() {
            let initialState = JSON.parse(window.__INITIAL_STATE__) || {};

            return {
                feeds: initialState.feeds,
                selectedFeedId: initialState.selectedFeed !== 'undefined' ? parseInt(initialState.selectedFeed) : null,
                isDisplayLoadMoreButton: true
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