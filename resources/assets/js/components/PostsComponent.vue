<template>
    <ul class="feed-post-list">
        <li v-for="post in posts" v-on:click.once="postMarkRead(post)" v-bind:class="{read: post.read}" class="border">
            <span class="date">{{ post.created_at }}</span>
            <span class="title">{{ post.title }}</span>
            <div class="detailed" v-bind:class="{hidden: !post.read}">
                <span class="description" v-html="post.description"></span>
                <span class="link">
                    <a href="#" v-bind:href="post.permalink" target="_blank">Read more</a>
                </span>
            </div>
        </li>
    </ul>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            this.setSelectedFeed(this.parentSelectedFeedId);
            this.loadPosts();
        },
        props: [
            'parentSelectedFeedId'
        ],
        data() {
            return {
                fromDate: null,
                page: 1,
                posts: []
            }
        },
        created() {
            Event.$on('select-feed', (feedId) => {
                this.setSelectedFeed(feedId);
                this.clearPosts();
                this.loadPosts();
            });

            Event.$on('load-posts', () => {
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
                        $('.js-load-more-content').show();
                    } else {
                        $('.js-load-more-content').hide();
                    }
                });
            },
            clearPosts() {
                this.posts = [];
            },
            postMarkRead(post) {
                let data = {
                    id: post.id
                };
                post.read = true;
                axios.post('/feed/mark_read', data);
            }
        }
    }
</script>