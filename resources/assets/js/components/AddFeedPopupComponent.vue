<template>
    <div id="modal-add-feed" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add feed</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="addFeedUrl">Url</label>
                            <input type="text" class="form-control" id="addFeedUrl" name="url" v-model="url" v-bind:class="{ 'is-invalid': isUrlInvalid }" />
                            <div class="invalid-feedback" v-html="urlErrorText"></div>
                        </div>
                        <button type="button" class="btn btn-primary" v-on:click="addFeed">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
        },
        data() {
            return {
                url: null,
                urlErrorText: null,
                isUrlInvalid: false,
            };
        },
        methods: {
            addFeed(e) {
                e.preventDefault();
                e.stopPropagation();

                let data = {
                    url: this.url
                };

                axios.post('/feed/add', data).then(response => {
                    window.location.href = response.data.redirect;
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