$(function() {
    $('.js-add-feed').on('click', function (e) {
        e.preventDefault();

        let data = {
            _token: application.getCsrfToken(),
            url: $(this).closest('form').find('input[name="url"]').val()
        };

        $.post('/feed/add', data, function (response) {
            window.location.href = response.redirect;
        });
    });

    $('.js-open-add-feed-form').on('click', function (e) {
        e.preventDefault();

        Feed.hideForms();

        $('.add-feed-popup').show();
    });

    $('.js-open-update-feed-form').on('click', function (e) {
        e.preventDefault();

        Feed.hideForms();

        let feedId = $(this).closest('li').data('id'),
            feedUrl = $(this).closest('li').data('url'),
            form = $('.js-update-feed').closest('form');

        form.find('input[name="feed_id"]').val(feedId);
        form.find('input[name="url"]').val(feedUrl);

        $('.update-feed-form').show();
    });

    $('.js-close-forms').on('click', function (e) {
        e.preventDefault();

        Feed.hideForms();
    });

    $('.js-update-feed').on('click', function (e) {
        e.preventDefault();

        let form = $(this).closest('form');
        let data = {
            _token: application.getCsrfToken(),
            id: form.find('input[name="feed_id"]').val(),
            url: form.find('input[name="url"]').val()
        };

        $.post('/feed/update', data, function (response) {
            alert('Feed has been updated');
        });
    });

    $('.js-load-feed-content').on('click', function (e) {
        e.preventDefault();

        Feed.selectedFeed = $(this).closest('li').data('id');
        Feed.clearPosts();
        Feed.loadPosts();
    });

    $('.js-remove-feed').on('click', function (e) {
        e.preventDefault();

        let feedId = $(this).closest('li').data('id'),
            feedUrl = $(this).closest('li').data('url');

        if (window.confirm('Are you sure remove ' + feedUrl)) {
            let data = {
                _token: application.getCsrfToken(),
                id: feedId
            };

            $.post('/feed/remove', data, function (response) {
                window.location.href = response.redirect;
            });
        }
    });

    $('.js-load-more-content').on('click', function (e) {
        e.preventDefault();

        Feed.loadPosts();
    });

    let Feed = {
        selectedFeed: null,
        fromDate: null,
        hideForms: function () {
            $('.add-feed-popup').hide();
            $('.update-feed-form').hide();
        },
        loadPosts: function () {
            let data = {
                _token: application.getCsrfToken(),
                feed_id: Feed.selectedFeed,
                from_date: Feed.fromDate || null,
                read: '0'
            };

            $.post('/feed/get_content', data, function (response) {
                let fromDate;
                response.forEach(function (post) {
                    $('.feed-post-list').append('<li>' + post.description + '</li>');

                    fromDate = post.created_at;
                });

                if (fromDate !== undefined) {
                    $('.js-load-more-content').show();
                    Feed.fromDate = fromDate;
                } else {
                    $('.js-load-more-content').hide();
                }
            });
        },
        clearPosts: function () {
            $('.feed-post-list').html('');
        }
    };
});