$(function() {
    let Feed = {
        selectedFeed: null,
        fromDate: null,
        page: 1,

        bindEvents: function () {
            $('.js-add-feed').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let urlField = $(this).closest('form').find('input[name="url"]'),
                    data = {
                        _token: application.getCsrfToken(),
                        url: urlField.val()
                    };

                $.post('/feed/add', data, function (response) {
                    window.location.href = response.redirect;
                }).fail(function (xhr) {
                    let responseText = JSON.parse(xhr.responseText);
                    if (responseText.errors.url !== undefined) {
                        urlField.addClass('is-invalid');
                        urlField.next('.invalid-feedback').html(responseText.errors.url);
                    }
                });
            });

            $('.js-open-add-feed-form').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-add-feed').modal();
            });

            $('.js-open-update-feed-form').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let feedId = $(this).closest('li').data('id'),
                    feedUrl = $(this).closest('li').data('url'),
                    form = $('.js-update-feed').closest('form');

                form.find('input[name="feed_id"]').val(feedId);
                form.find('input[name="url"]').val(feedUrl);

                $('#modal-edit-feed').modal();
            });

            $('.js-update-feed').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let form = $(this).closest('form'),
                    urlField = form.find('input[name="url"]'),
                    data = {
                        _token: application.getCsrfToken(),
                        id: form.find('input[name="feed_id"]').val(),
                        url: urlField.val()
                    };

                $.post('/feed/update', data, function (response) {
                    $('#modal-edit-feed').modal('hide');
                    $('#modal-updated-feed').modal();
                }).fail(function (xhr) {
                    let responseText = JSON.parse(xhr.responseText);
                    if (responseText.errors.url !== undefined) {
                        urlField.addClass('is-invalid');
                        urlField.next('.invalid-feedback').html(responseText.errors.url);
                    }
                })
            });

            $('.js-load-feed-content').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $(this).closest('ul').find('li').removeClass('list-group-item-primary');
                $(this).closest('li').addClass('list-group-item-primary');

                Feed.setSelectedFeed($(this).closest('li').data('id'));
                Feed.clearPosts();
                Feed.loadPosts();
            });

            $('.js-remove-feed').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let feedId = $(this).closest('li').data('id'),
                    feedUrl = $(this).closest('li').data('url');

                $('#modal-confirm-remove-feed-url').text(feedUrl);
                $('#modal-confirm-remove-feed').modal()
                    .find('button.btn-danger').attr('data-feed-id', feedId);
            });

            $('#modal-confirm-remove-feed').find('button.btn-danger').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let data = {
                    _token: application.getCsrfToken(),
                    id: $(this).data('feed-id')
                };

                $.post('/feed/remove', data, function (response) {
                    window.location.href = response.redirect;
                });
            });

            $('.js-load-more-content').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                Feed.loadPosts();
            });
        },
        setSelectedFeed: function (id) {
            Feed.selectedFeed = id;
            Feed.fromDate = null;
            Feed.page = 1;
        },
        incrementPage: function () {
            Feed.page++;
        },
        loadPosts: function () {
            let data = {
                _token: application.getCsrfToken(),
                feed_id: Feed.selectedFeed,
                from_date: Feed.fromDate,
                page: Feed.page,
                read: '0'
            };

            $.post('/feed/get_content', data, function (response) {
                response.posts.forEach(function (post) {
                    if (Feed.fromDate === null) {
                        Feed.fromDate = post.created_at;
                    }

                    let postHtml = '<li class="border" data-id="' + post.id + '">';
                    postHtml += '<span class="date">' + post.created_at + '</span>';
                    postHtml += '<span class="title">' + post.title + '</span>';
                    postHtml += '<div class="detailed hidden">';
                    postHtml += '<span class="description">' + post.description + '</span>';
                    postHtml += '<span class="link"><a href="' + post.permalink + '" target="_blank">Read more</a></link>';
                    postHtml += '</div>';
                    postHtml += '</li>';
                    $('.feed-post-list').append(postHtml);
                });

                $('.feed-post-list li').not('.read').off('click').on('click', function () {
                    $(this).addClass('read').off('click');
                    $(this).find('.detailed').removeClass('hidden');
                    let postId = $(this).data('id');
                    Feed.postMarkRead(postId);
                });

                if (response.hasMore) {
                    Feed.incrementPage();
                    $('.js-load-more-content').show();
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
        },
    };

    Feed.bindEvents();

    if (selectedFeed !== undefined) {
        Feed.setSelectedFeed(selectedFeed);
    }
    Feed.loadPosts();
});