<template>
    <div>
        <b-modal id="modalEditFeed" ref="modalEditFeed" hide-footer title="Edit feed">
            <form>
                <div class="form-group">
                    <label for="editFeedUrl">Url</label>
                    <input type="text" class="form-control" id="editFeedUrl" name="url" v-model="feed.url" v-bind:class="{'is-invalid': isUrlInvalid}"/>
                    <div class="invalid-feedback" v-html="urlErrorText"></div>
                </div>
                <button type="button" class="btn btn-primary" v-on:click="editFeed">Update</button>
            </form>
        </b-modal>

        <b-modal ref="modalUpdatedFeed" hide-header hide-footer>
            Feed has been updated
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            feed: {
                type: Object,
                required: false,
                default: () => {
                    return {
                        id: null,
                        url: null
                    }
                }
            }
        },
        data() {
            return {
                urlErrorText: null,
                isUrlInvalid: false,
            };
        },
        methods: {
            editFeed(e) {
                e.preventDefault();
                e.stopPropagation();

                let data = {
                    id: this.feed.id,
                    url: this.feed.url
                };

                axios.post('/feed/update', data).then(response => {
                    this.$refs.modalEditFeed.hide();
                    this.$refs.modalUpdatedFeed.show();
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