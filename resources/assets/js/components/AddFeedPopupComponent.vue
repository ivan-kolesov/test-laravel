<template>
    <b-modal id="modal-add-feed" hide-footer title="Add feed">
        <form>
            <div class="form-group">
                <label for="addFeedUrl">Url</label>
                <input type="text" class="form-control" id="addFeedUrl" name="url" v-model="url" v-bind:class="{ 'is-invalid': isUrlInvalid }" />
                <div class="invalid-feedback" v-html="urlErrorText"></div>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="addFeed">Add</button>
        </form>
    </b-modal>
</template>

<script>
    import axios from 'axios';

    export default {
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