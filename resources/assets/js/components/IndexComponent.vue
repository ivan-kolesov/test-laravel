<template>
    <div class="row">
        <div class="col-4">
            <div>
                <a href="#" class="btn btn-primary" role="button" v-on:click="showAddFeedPopup">Add a feed</a>
                <br/>
                <br/>
            </div>

            <feeds-list></feeds-list>
        </div>
        <div class="col-8">
            <posts></posts>
            <button type="button" class="btn btn-primary btn-lg btn-block js-load-more-content hidden" v-on:click="fireLoadPosts">Get more</button>
        </div>
    </div>
</template>

<script>
    import Feeds from './FeedsComponent';
    import Posts from './PostsComponent';

    export default {
        components: {
            Feeds,
            Posts
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