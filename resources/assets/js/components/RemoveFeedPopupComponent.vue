<template>
    <b-modal id="modalConfirmRemoveFeed" ref="modalConfirmRemoveFeed" hide-footer title="Remove feed">
        <div class="modal-body">
            Are you sure remove <span v-html="feed.url"></span> ?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="hideModal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="removeFeed">Confirm remove</button>
        </div>
    </b-modal>
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
        methods: {
            removeFeed() {
                let data = {
                    id: this.feed.id
                };

                axios.post('/feed/remove', data).then(response => {
                    window.location.href = response.data.redirect;
                });
            },
            hideModal() {
                this.$refs.modalConfirmRemoveFeed.hide();
            }
        }
    }
</script>