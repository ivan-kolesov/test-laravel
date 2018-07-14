<template>
    <div class="container">
        <ul class="list-group feed-list">
            <li class="list-group-item"
                v-on:click="fireFeedSelected(null, $event)"
                v-bind:class="{'list-group-item-primary': selectedFeedId === null}"
            >
                <a href="#">All</a>
            </li>
            <li v-for="feed in feeds" class="list-group-item"
                v-on:click="fireFeedSelected(feed.id, $event)"
                v-bind:class="{'list-group-item-primary': feed.id === selectedFeedId}"
            >
                <a v-bind:href="'/' + feed.id">{{ feed.name }}</a>

                <b-link class="badge badge-info" v-b-modal.modalEditFeed v-on:click="passToEditFeed(feed, $event)">
                    Edit
                </b-link>
                <b-link class="badge badge-danger" v-b-modal.modalConfirmRemoveFeed v-on:click="passToRemoveFeed(feed, $event)">
                    Remove
                </b-link>
            </li>
        </ul>

        <edit-feed-popup :feed="editFeed"></edit-feed-popup>
        <remove-feed-popup :feed="removeFeed"></remove-feed-popup>
    </div>

</template>

<script>
    import EditFeedPopup from './EditFeedPopupComponent';
    import RemoveFeedPopup from './RemoveFeedPopupComponent';

    export default {
        mounted() {
            this.selectedFeedId = this.parentSelectedFeedId;
        },
        data() {
            return {
                selectedFeedId: null,
                editFeed: undefined,
                removeFeed: undefined
            };
        },
        components: {
            'edit-feed-popup': EditFeedPopup,
            'remove-feed-popup': RemoveFeedPopup,
        },
        props: {
            feeds: {
                type: Array,
                required: true
            },
            parentSelectedFeedId: {
                type: Number,
                required: false,
                default: null
            }
        },
        methods: {
            fireFeedSelected(feedId, e) {
                e.preventDefault();
                e.stopPropagation();

                this.selectedFeedId = feedId;
                EventBus.$emit('select-feed', feedId);
            },
            passToEditFeed(feed, e) {
                e.preventDefault();
                e.stopPropagation();

                this.editFeed = feed;
            },
            passToRemoveFeed(feed, e) {
                e.preventDefault();
                e.stopPropagation();

                this.removeFeed = feed;
            }
        }
    }
</script>

<style>
    ul.feed-list {
        list-style-type: none;
        cursor: pointer;
    }
</style>