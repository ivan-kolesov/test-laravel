<template>
    <div class="container">
        <ul class="feed-post-list">
            <li v-for="post in posts" v-on:click.once="postMarkRead(post)" v-bind:class="{read: post.read}" class="border">
                <span class="date">{{ post.created_at }}</span>
                <span class="title" v-html="post.title"></span>
                <div class="detailed" v-bind:class="{hidden: !post.read}">
                    <div class="actions">
                        <a href="#" v-on:click.once="postMarkUnRead(post)">Mark unread</a>
                    </div>
                    <div class="description" v-html="post.description"></div>
                    <div class="link">
                        <a href="#" v-bind:href="post.permalink" target="_blank">Read more</a>
                    </div>
                </div>
            </li>
        </ul>

        <button type="button" class="btn btn-primary btn-lg btn-block"
                v-on:click="loadPosts" v-if="displayLoadMoreButton">Get more</button>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
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
                displayLoadMoreButton: false
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
            },
            postMarkRead(post) {
                let data = {
                    id: post.id
                };
                post.read = true;
                axios.post('/feed/mark_read', data);
            },
            postMarkUnRead(post) {
                let data = {
                    id: post.id
                };
                post.read = false;
                axios.post('/feed/mark_unread', data);
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

    ul.feed-post-list li img {
        max-width: 100%;
    }

    ul.feed-post-list li:hover {
        background: #c4e3f3;
    }

    ul.feed-post-list li .date {
        display: block;
        font-style: italic;
    }

    ul.feed-post-list li .title {
        display: block;
        font-weight: bold;
    }

    ul.feed-post-list li .description {
        display: block;
        font-style: italic;
    }

    ul.feed-post-list li .link {
        display: block;
        font-style: italic;
        font-size: 90%;
        color: #5e5d5d;
    }

    ul.feed-post-list li.read {
        padding: 10px;
        list-style: none;
        color: #9d9d9d;
    }
</style>