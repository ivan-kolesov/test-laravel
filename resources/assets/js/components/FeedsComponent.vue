<template>
    <div class="container">
        <ul class="list-group feed-list">
            <li class="list-group-item"
                @click="fireFeedSelected(null, $event)"
                v-bind:class="{'list-group-item-primary': selectedFeedId === null}"
            >
                <a href="#">All</a>
            </li>
            <li v-for="feed in feeds" class="list-group-item"
                @click.prevent.stop="fireFeedSelected(feed.id)"
                v-bind:class="{'list-group-item-primary': feed.id === selectedFeedId}"
            >
                <a v-bind:href="feedUrl(feed)">{{ feed.name }}</a>

                <b-link class="badge badge-info" v-b-modal.modalEditFeed @click.prevent.stop="passToEditFeed(feed)">
                    Edit
                </b-link>
                <b-link class="badge badge-danger" v-b-modal.modalConfirmRemoveFeed @click.prevent.stop="passToRemoveFeed(feed)">
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
            fireFeedSelected(feedId) {
                this.selectedFeedId = feedId;
                EventBus.$emit('select-feed', feedId);
            },
            passToEditFeed(feed) {
                this.editFeed = feed;
            },
            passToRemoveFeed(feed) {
                this.removeFeed = feed;
            },
            feedUrl: (feed) => {
                return '/' + feed.id;
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