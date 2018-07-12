<template>
    <div class="modal fade" id="modal-confirm-remove-feed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm remove</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure remove <span v-html="url"></span> ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" v-on:click="removeFeed">Confirm remove</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        created() {
            Event.$on('remove-feed-popup', (feed) => {
                $('#modal-confirm-remove-feed').modal();

                this.url = feed.url;
                this.feedId = feed.id;
            });
        },
        data() {
            return {
                url: null,
                feedId: null
            };
        },
        methods: {
            removeFeed(e) {
                e.preventDefault();
                e.stopPropagation();

                let data = {
                    id: this.feedId
                };

                axios.post('/feed/remove', data).then(response => {
                    window.location.href = response.data.redirect;
                });
            }
        }
    }
</script>