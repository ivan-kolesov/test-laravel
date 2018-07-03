let application = {
    getCsrfToken: function () {
        return $('meta[name="csrf-token"]').attr('content');
    }
};