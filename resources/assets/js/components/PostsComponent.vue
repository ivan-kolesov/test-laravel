<template>
    <div class="container">
        <ul class="feed-post-list">
            <post v-for="post in posts" :key="post.id" :post="post"></post>
        </ul>

        <button type="button" class="btn btn-primary btn-lg btn-block"
                @click="loadPosts" v-if="displayLoadMoreButton">Get more</button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Post from './PostComponent';

    export default {
        components: {
            'post': Post
        },
        mounted() {
            this.setSelectedFeed(this.parentSelectedFeedId);
            this.loadPosts();
        },
        props: {
            parentSelectedFeedId: {
                type: Number,
                required: false,
                default: null
            }
        },
        data() {
            return {
                fromDate: null,
                page: 1,
                posts: [],
                displayLoadMoreButton: null
            }
        },
        created() {
            EventBus.$on('select-feed', (feedId) => {
                this.setSelectedFeed(feedId);
                this.clearPosts();
                this.loadPosts();
            });

            EventBus.$on('load-posts', () => {
                this.loadPosts();
            });
        },
        methods: {
            setSelectedFeed(id) {
                this.selectedFeed = id;
                this.fromDate = null;
                this.page = 1;
            },
            incrementPage() {
                this.page++;
            },
            loadPosts() {
                let data = {
                    feed_id: this.selectedFeed,
                    from_date: this.fromDate,
                    page: this.page,
                    read: '0'
                };

                axios.post('/feed/get_posts', data).then(response => {
                    let responseData = response.data;

                    if (responseData.posts.length > 0) {
                        if (this.fromDate === null) {
                            this.fromDate = responseData.posts[0].created_at;
                        }

                        this.posts = this.posts.concat(responseData.posts);
                    }

                    if (responseData.hasMore) {
                        this.incrementPage();
                        this.displayLoadMoreButton = true;
                    } else {
                        this.displayLoadMoreButton = false;
                    }
                });
            },
            clearPosts() {
                this.posts = [];
            }
        }
    }
</script>

<style>
    ul.feed-post-list li {
        list-style-type: none;
        padding: 10px;
        cursor: pointer;
    }
</style>