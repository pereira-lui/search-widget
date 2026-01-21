/**
 * Search Widget PDA - JavaScript
 * 
 * @package Search_Widget_PDA
 */

(function($) {
    'use strict';

    // Search Widget Class
    class SearchWidgetPDA {
        constructor(widget) {
            this.widget = widget;
            this.$widget = $(widget);
            this.widgetId = this.$widget.attr('id');
            this.triggerId = this.$widget.find('.search-pda-trigger').attr('id');
            this.popupId = this.$widget.find('.search-pda-popup').attr('id');
            
            this.$trigger = this.$widget.find('.search-pda-trigger');
            this.$popup = this.$widget.find('.search-pda-popup');
            this.$overlay = this.$popup.find('.search-pda-popup-overlay');
            this.$closeBtn = this.$popup.find('.search-pda-popup-close');
            this.$form = this.$popup.find('.search-pda-form');
            this.$input = this.$popup.find('.search-pda-input');
            this.$results = this.$popup.find('.search-pda-results');
            this.$resultsList = this.$popup.find('.search-pda-results-list');
            this.$loading = this.$popup.find('.search-pda-results-loading');
            this.$empty = this.$popup.find('.search-pda-results-empty');
            this.$viewAll = this.$popup.find('.search-pda-view-all');
            
            this.liveResults = this.$widget.data('live-results') === true;
            this.showThumbnails = this.$widget.data('show-thumbnails') === true;
            
            this.searchTimeout = null;
            this.currentRequest = null;
            this.minChars = 2;
            this.debounceTime = 300;
            
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Open popup
            this.$trigger.on('click', (e) => {
                e.preventDefault();
                this.openPopup();
            });

            // Close popup
            this.$closeBtn.on('click', () => this.closePopup());
            this.$overlay.on('click', () => this.closePopup());

            // Close on ESC
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen()) {
                    this.closePopup();
                }
            });

            // Form submit
            this.$form.on('submit', (e) => {
                if (this.$input.val().trim().length < this.minChars) {
                    e.preventDefault();
                    this.$input.focus();
                }
            });

            // Live search
            if (this.liveResults) {
                this.$input.on('input', () => this.handleInput());
                this.$input.on('keydown', (e) => this.handleKeydown(e));
            }
        }

        isOpen() {
            return this.$popup.attr('aria-hidden') === 'false';
        }

        openPopup() {
            this.$popup.attr('aria-hidden', 'false');
            $('body').addClass('search-pda-popup-open');
            
            // Focus input after animation
            setTimeout(() => {
                this.$input.focus();
            }, 100);

            // Trigger custom event
            $(document).trigger('searchWidgetPDA:opened', [this]);
        }

        closePopup() {
            this.$popup.attr('aria-hidden', 'true');
            $('body').removeClass('search-pda-popup-open');
            
            // Clear results
            this.clearResults();
            this.$input.val('');

            // Return focus to trigger
            this.$trigger.focus();

            // Trigger custom event
            $(document).trigger('searchWidgetPDA:closed', [this]);
        }

        handleInput() {
            const query = this.$input.val().trim();

            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            // Cancel previous request
            if (this.currentRequest) {
                this.currentRequest.abort();
            }

            // Clear if too short
            if (query.length < this.minChars) {
                this.clearResults();
                return;
            }

            // Debounce search
            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, this.debounceTime);
        }

        handleKeydown(e) {
            const $items = this.$resultsList.find('.search-pda-result-item');
            const $focused = $items.filter(':focus');
            let index = $items.index($focused);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (index < $items.length - 1) {
                    $items.eq(index + 1).focus();
                } else if (index === -1 && $items.length > 0) {
                    $items.first().focus();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (index > 0) {
                    $items.eq(index - 1).focus();
                } else if (index === 0) {
                    this.$input.focus();
                }
            } else if (e.key === 'Enter' && $focused.length) {
                e.preventDefault();
                window.location.href = $focused.attr('href');
            }
        }

        performSearch(query) {
            this.showLoading();

            this.currentRequest = $.ajax({
                url: searchWidgetPDA.ajaxurl,
                type: 'POST',
                data: {
                    action: 'search_widget_pda_search',
                    nonce: searchWidgetPDA.nonce,
                    search: query
                },
                success: (response) => {
                    this.hideLoading();
                    
                    if (response.success && response.data.results.length > 0) {
                        this.displayResults(response.data.results, response.data.search_url, response.data.total);
                    } else {
                        this.showEmpty();
                    }
                },
                error: (xhr, status) => {
                    if (status !== 'abort') {
                        this.hideLoading();
                        this.showEmpty();
                    }
                }
            });
        }

        displayResults(results, searchUrl, total) {
            this.$resultsList.empty();
            this.$empty.hide();

            results.forEach((item) => {
                const $item = this.createResultItem(item);
                this.$resultsList.append($item);
            });

            // Show "View All" if more results
            if (total > results.length) {
                this.$viewAll
                    .attr('href', searchUrl)
                    .text(searchWidgetPDA.strings.viewAll + ' (' + total + ')')
                    .show();
            } else {
                this.$viewAll.hide();
            }

            this.$results.show();
        }

        createResultItem(item) {
            const $item = $('<a>', {
                href: item.url,
                class: 'search-pda-result-item',
                tabindex: 0
            });

            // Thumbnail
            if (this.showThumbnails) {
                if (item.thumbnail) {
                    $item.append($('<img>', {
                        src: item.thumbnail,
                        alt: item.title,
                        class: 'search-pda-result-thumb'
                    }));
                } else {
                    $item.append($('<div>', {
                        class: 'search-pda-result-thumb-placeholder',
                        html: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>'
                    }));
                }
            }

            // Content
            const $content = $('<div>', { class: 'search-pda-result-content' });
            
            $content.append($('<h4>', {
                class: 'search-pda-result-title',
                text: item.title
            }));

            if (item.excerpt) {
                $content.append($('<p>', {
                    class: 'search-pda-result-excerpt',
                    text: item.excerpt
                }));
            }

            // Meta
            const $meta = $('<div>', { class: 'search-pda-result-meta' });
            
            const postTypeLabels = {
                'post': 'Post',
                'page': 'Página',
                'blog_post': 'Blog'
            };
            
            $meta.append($('<span>', {
                class: 'search-pda-result-type',
                text: postTypeLabels[item.post_type] || item.post_type
            }));

            if (item.date) {
                $meta.append($('<span>', {
                    class: 'search-pda-result-date',
                    text: item.date
                }));
            }

            $content.append($meta);
            $item.append($content);

            return $item;
        }

        showLoading() {
            this.$resultsList.empty();
            this.$empty.hide();
            this.$viewAll.hide();
            this.$loading.show();
            this.$results.show();
        }

        hideLoading() {
            this.$loading.hide();
        }

        showEmpty() {
            this.$resultsList.empty();
            this.$viewAll.hide();
            this.$empty.show();
            this.$results.show();
        }

        clearResults() {
            this.$resultsList.empty();
            this.$loading.hide();
            this.$empty.hide();
            this.$viewAll.hide();
        }
    }

    // Initialize all widgets
    $(document).ready(function() {
        $('.search-pda-widget').each(function() {
            new SearchWidgetPDA(this);
        });
    });

    // Also initialize for Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/search_widget_pda.default', function($scope) {
                $scope.find('.search-pda-widget').each(function() {
                    new SearchWidgetPDA(this);
                });
            });
        }
    });

})(jQuery);
