<template>
    <ul class="list-group feed-list">
        <li class="list-group-item list-group-item-primary">
            <a href="#" v-on:click="fireFeedSelected(null, $event)">All</a>
        </li>
        <li v-for="feed in feeds" class="list-group-item" v-bind:class="{'list-group-item-primary': feed.id === selectedFeedId}">
            <a v-bind:href="'/' + feed.id" v-on:click="fireFeedSelected(feed.id, $event)">{{ feed.name }}</a>

            <a href="#" class="badge badge-info" v-on:click="fireEditFeedPopup(feed, $event)">Edit</a>
            <a href="#" class="badge badge-danger" v-on:click="fireRemoveFeedPopup(feed, $event)">Remove</a>
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            let initialState = JSON.parse(window.__INITIAL_STATE__) || {};

            return {
                feeds: initialState.feeds,
                selectedFeedId: initialState.selectedFeed
            };
        },
        methods: {
            fireFeedSelected(feedId, e) {
                e.preventDefault();
                e.stopPropagation();

                this.selectedFeedId = feedId;
                Event.$emit('select-feed', feedId);
            },
            fireEditFeedPopup(feed, e) {
                e.preventDefault();
                e.stopPropagation();

                Event.$emit('edit-feed-popup', feed);
            },
            fireRemoveFeedPopup(feed, e) {
                e.preventDefault();
                e.stopPropagation();

                Event.$emit('remove-feed-popup', feed);
            }
        }
    }
</script>