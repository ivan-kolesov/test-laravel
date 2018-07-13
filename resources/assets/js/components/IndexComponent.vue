<template>
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div>
                    <b-link class="btn btn-primary" role="button" v-b-modal.modal-add-feed>Add a feed</b-link>
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

        <add-feed-popup></add-feed-popup>
        <edit-feed-popup></edit-feed-popup>
        <remove-feed-popup></remove-feed-popup>
    </div>
</template>

<script>
    import Feeds from './FeedsComponent';
    import Posts from './PostsComponent';
    import AddFeedPopup from './AddFeedPopupComponent';

    export default {
        created() {
            EventBus.$on('display-load-more-button', (flag) => {
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
            'posts': Posts,
            'add-feed-popup': AddFeedPopup
        },
        methods: {
            fireLoadPosts(e) {
                e.preventDefault();
                e.stopPropagation();

                EventBus.$emit('load-posts');
            }
        }
    }
</script>