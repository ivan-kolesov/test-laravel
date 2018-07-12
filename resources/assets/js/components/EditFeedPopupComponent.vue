<template>
    <div id="modal-edit-feed" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit feed</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="editFeedUrl">Url</label>
                            <input type="text" class="form-control" id="editFeedUrl" name="url" v-model="url" v-bind:class="{ 'is-invalid': isUrlInvalid }"/>
                            <div class="invalid-feedback" v-html="urlErrorText"></div>
                        </div>
                        <button type="button" class="btn btn-primary" v-on:click="editFeed">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        created() {
            Event.$on('edit-feed-popup', (feed) => {
                this.url = feed.url;
                this.feedId = feed.id;

                $('#modal-edit-feed').modal();
            });
        },
        data() {
            return {
                feedId: null,
                url: null,
                urlErrorText: null,
                isUrlInvalid: false,
            };
        },
        methods: {
            editFeed(e) {
                e.preventDefault();
                e.stopPropagation();

                let data = {
                    id: this.feedId,
                    url: this.url
                };

                axios.post('/feed/update', data).then(response => {
                    $('#modal-edit-feed').modal('hide');
                    $('#modal-updated-feed').modal();
                }).catch(error => {
                    if (error.response.data.errors.url !== undefined) {
                        this.isUrlInvalid = true;
                        this.urlErrorText = error.response.data.errors.url.toString();
                    }
                });
            }
        }
    }
</script>