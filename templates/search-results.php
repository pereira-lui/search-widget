<?php
/**
 * Template de Página de Resultados de Pesquisa
 * 
 * @package Search_Widget_PDA
 */

get_header();

$search_query = get_search_query();
$paged = max(1, get_query_var('paged'));

// Query de pesquisa
$search_args = [
    's' => $search_query,
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
];

// Permitir filtrar tipos de post
$post_types = apply_filters('search_widget_pda_search_post_types', ['post', 'page', 'blog_post']);
$search_args['post_type'] = $post_types;

$search_results = new WP_Query($search_args);
$total_results = $search_results->found_posts;
?>

<div class="search-pda-page">
    
    <!-- Header da Pesquisa -->
    <header class="search-pda-page-header">
        <div class="search-pda-page-container">
            <h1 class="search-pda-page-title">
                <?php if (!empty($search_query)) : ?>
                    <?php printf(__('Resultados para: "%s"', 'search-widget-pda'), esc_html($search_query)); ?>
                <?php else : ?>
                    <?php _e('Pesquisar', 'search-widget-pda'); ?>
                <?php endif; ?>
            </h1>
            
            <?php if ($total_results > 0) : ?>
            <p class="search-pda-page-count">
                <?php printf(_n('%d resultado encontrado', '%d resultados encontrados', $total_results, 'search-widget-pda'), $total_results); ?>
            </p>
            <?php endif; ?>
            
            <!-- Formulário de Nova Pesquisa -->
            <form class="search-pda-page-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <div class="search-pda-page-input-wrapper">
                    <svg class="search-pda-page-input-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10.5" cy="10.5" r="7.5"></circle>
                        <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                    </svg>
                    <input type="search" 
                           class="search-pda-page-input" 
                           name="s" 
                           value="<?php echo esc_attr($search_query); ?>"
                           placeholder="<?php _e('Digite sua busca...', 'search-widget-pda'); ?>"
                           autocomplete="off">
                    <button type="submit" class="search-pda-page-submit">
                        <?php _e('Pesquisar', 'search-widget-pda'); ?>
                    </button>
                </div>
            </form>
        </div>
    </header>

    <!-- Resultados -->
    <main class="search-pda-page-main">
        <div class="search-pda-page-container">
            
            <?php if ($search_results->have_posts()) : ?>
            
            <div class="search-pda-page-grid">
                <?php while ($search_results->have_posts()) : $search_results->the_post(); 
                    $post_type = get_post_type();
                    $post_type_labels = [
                        'post' => __('Post', 'search-widget-pda'),
                        'page' => __('Página', 'search-widget-pda'),
                        'blog_post' => __('Blog', 'search-widget-pda'),
                    ];
                    $post_type_label = isset($post_type_labels[$post_type]) ? $post_type_labels[$post_type] : $post_type;
                ?>
                <article class="search-pda-page-card">
                    <a href="<?php the_permalink(); ?>" class="search-pda-page-card-link">
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="search-pda-page-card-image">
                            <?php the_post_thumbnail('medium_large'); ?>
                            <span class="search-pda-page-card-type"><?php echo esc_html($post_type_label); ?></span>
                        </div>
                        <?php else : ?>
                        <div class="search-pda-page-card-image search-pda-page-card-no-image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span class="search-pda-page-card-type"><?php echo esc_html($post_type_label); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="search-pda-page-card-content">
                            <h2 class="search-pda-page-card-title"><?php the_title(); ?></h2>
                            <p class="search-pda-page-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <div class="search-pda-page-card-meta">
                                <span class="search-pda-page-card-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo get_the_date('d/m/Y'); ?>
                                </span>
                                <?php if (get_post_type() === 'post' || get_post_type() === 'blog_post') : ?>
                                <span class="search-pda-page-card-author">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?php the_author(); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </article>
                <?php endwhile; ?>
            </div>
            
            <!-- Paginação -->
            <?php if ($search_results->max_num_pages > 1) : ?>
            <nav class="search-pda-page-pagination">
                <?php
                $pagination = paginate_links([
                    'total' => $search_results->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 6 15 12 9 18"></polyline></svg>',
                    'type' => 'array',
                ]);
                
                if ($pagination) :
                    foreach ($pagination as $page_link) :
                        echo '<span class="search-pda-page-pagination-item">' . $page_link . '</span>';
                    endforeach;
                endif;
                ?>
            </nav>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
            
            <?php else : ?>
            
            <!-- Nenhum resultado -->
            <div class="search-pda-page-no-results">
                <div class="search-pda-page-no-results-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10.5" cy="10.5" r="7.5"></circle>
                        <line x1="21" y1="21" x2="15.8" y2="15.8"></line>
                        <line x1="8" y1="8" x2="13" y2="13"></line>
                        <line x1="13" y1="8" x2="8" y2="13"></line>
                    </svg>
                </div>
                <h2 class="search-pda-page-no-results-title">
                    <?php _e('Nenhum resultado encontrado', 'search-widget-pda'); ?>
                </h2>
                <p class="search-pda-page-no-results-text">
                    <?php if (!empty($search_query)) : ?>
                        <?php printf(__('Não encontramos resultados para "%s". Tente usar palavras-chave diferentes.', 'search-widget-pda'), esc_html($search_query)); ?>
                    <?php else : ?>
                        <?php _e('Digite algo no campo de busca para pesquisar.', 'search-widget-pda'); ?>
                    <?php endif; ?>
                </p>
                
                <!-- Sugestões -->
                <div class="search-pda-page-suggestions">
                    <h3 class="search-pda-page-suggestions-title"><?php _e('Sugestões:', 'search-widget-pda'); ?></h3>
                    <ul class="search-pda-page-suggestions-list">
                        <li><?php _e('Verifique se todas as palavras estão escritas corretamente', 'search-widget-pda'); ?></li>
                        <li><?php _e('Tente palavras-chave diferentes', 'search-widget-pda'); ?></li>
                        <li><?php _e('Tente palavras-chave mais genéricas', 'search-widget-pda'); ?></li>
                        <li><?php _e('Tente menos palavras-chave', 'search-widget-pda'); ?></li>
                    </ul>
                </div>
                
                <!-- Posts Recentes -->
                <?php
                $recent_posts = new WP_Query([
                    'post_type' => $post_types,
                    'posts_per_page' => 6,
                    'post_status' => 'publish',
                ]);
                
                if ($recent_posts->have_posts()) :
                ?>
                <div class="search-pda-page-recent">
                    <h3 class="search-pda-page-recent-title"><?php _e('Publicações recentes', 'search-widget-pda'); ?></h3>
                    <div class="search-pda-page-recent-grid">
                        <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="search-pda-page-recent-item">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="search-pda-page-recent-image">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </div>
                            <?php endif; ?>
                            <span class="search-pda-page-recent-title-item"><?php the_title(); ?></span>
                        </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php endif; ?>
            
        </div>
    </main>
    
</div>

<?php get_footer(); ?>
