$(function() {
    $('.js-add-feed').on('click', function (e) {
        e.preventDefault();

        let data = {
            _token: application.getCsrfToken(),
            url: $(this).closest('form').find('input[name="url"]').val()
        };

        $.post('/feed/add', data, function (response) {
            window.location.href = response.redirect;
        }).fail(function () {
            alert('Error creating feed');
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

        let form = $(this).closest('form'),
            data = {
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
        Feed.fromDate = null;
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
                    let postHtml = '<li data-id="' + post.id + '">';
                    postHtml += '<span class="date">' + post.created_at + '</span>';
                    postHtml += '<span class="title">' + post.title + '</span>';
                    postHtml += '<span class="description">' + post.description + '</span>';
                    postHtml += '<span class="link"><a href="' + post.permalink + '" target="_blank">Read more</a></link>';
                    postHtml += '</li>';
                    $('.feed-post-list').append(postHtml);

                    fromDate = post.created_at;
                });

                $('.feed-post-list li').not('.read').off('click').on('click', function () {
                    $(this).addClass('read').off('click');
                    let postId = $(this).data('id');
                    Feed.postMarkRead(postId);
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
        },
        postMarkRead: function (postId) {
            let data = {
                _token: application.getCsrfToken(),
                id: postId
            };
            $.post('/feed/mark_read', data, function (response) {
            });
        }
    };
});