<template>
    <div @click.once="markRead(post)" v-bind:class="{read: post.read}">
        <span class="date">{{ post.created_at }}</span>
        <span class="title" v-html="post.title"></span>
        <div class="detailed" v-if="post.read">
            <div class="actions">
                <a href="#" @click.once.prevent.stop="markUnRead(post)">Mark unread</a>
            </div>
            <div class="description" v-html="post.description"></div>
            <div class="link">
                <a href="#" v-bind:href="post.permalink" target="_blank">Read more</a>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            post: {
                type: Object,
                required: true
            }
        },
        methods: {
            markRead(post) {
                let data = {
                    id: post.id
                };
                post.read = true;
                axios.post('/feed/mark_read', data);
            },
            markUnRead(post) {
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

    ul.feed-post-list li .read {
        padding: 10px;
        list-style: none;
        color: #9d9d9d;
    }
</style>