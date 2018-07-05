let app = {
    getCsrfToken: function () {
        return $('meta[name="csrf-token"]').attr('content');
    }
};