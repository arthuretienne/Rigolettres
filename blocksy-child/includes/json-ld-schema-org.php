<?php
/**
 * Migré depuis Code Snippet #7 : [Rigolettres] JSON-LD Schema.org
 * Description : Organization + WebSite + Product + BreadcrumbList + Article schemas. Source: audit.md Phase A.5
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] JSON-LD Schema.org
 *
 * Injecte :
 *   - Organization + WebSite sur toutes les pages
 *   - Product + Offer sur fiches produit WooCommerce
 *   - BreadcrumbList sur toutes les pages non-home
 *   - Article sur posts blog
 *
 * Source : audit.md Phase A.5
 * Déployé via Code Snippets (scope=global, priority=20 pour passer après SEO meta)
 */

if (!function_exists('rigolettres_emit_ld_json')) {
    function rigolettres_emit_ld_json($data) {
        if (empty($data)) return;
        $open = '<' . 'scr' . 'ipt type="application/ld+json">';
        $close = '</' . 'scr' . 'ipt>';
        echo "\n" . $open . "\n";
        echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . $close . "\n";
    }
}

if (!function_exists('rigolettres_build_organization')) {
    function rigolettres_build_organization() {
        $site_url = home_url('/');
        $logo_url = home_url('/wp-content/uploads/logo-pato.png');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => $site_url . '#organization',
            'name' => 'Rigolettres',
            'url' => $site_url,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo_url,
            ],
            'founder' => [
                '@type' => 'Person',
                'name' => 'Brigitte Étienne-Camillerapp',
                'jobTitle' => 'Orthophoniste',
            ],
            'foundingDate' => '2011',
            'foundingLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Mamers',
                    'postalCode' => '72600',
                    'addressCountry' => 'FR',
                ],
            ],
            'description' => 'Éditeur de jeux éducatifs et livres pour apprendre à lire, conjuguer et écrire le français. Fondé en 2011 par une orthophoniste.',
            'areaServed' => 'FR',
        ];
    }
}

if (!function_exists('rigolettres_build_website')) {
    function rigolettres_build_website() {
        $site_url = home_url('/');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $site_url . '#website',
            'url' => $site_url,
            'name' => 'Rigolettres',
            'inLanguage' => 'fr-FR',
            'publisher' => ['@id' => $site_url . '#organization'],
        ];
    }
}

if (!function_exists('rigolettres_build_product_ld')) {
    function rigolettres_build_product_ld() {
        if (!function_exists('wc_get_product')) return null;
        global $post;
        $product = wc_get_product($post->ID);
        if (!$product) return null;

        $img_url = '';
        $thumb_id = get_post_thumbnail_id($post->ID);
        if ($thumb_id) {
            $img = wp_get_attachment_image_src($thumb_id, 'large');
            if ($img) $img_url = $img[0];
        }

        $short = wp_strip_all_tags($product->get_short_description());
        if (!$short) $short = wp_trim_words(wp_strip_all_tags($product->get_description()), 40, '…');

        $availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

        $offer = [
            '@type' => 'Offer',
            'url' => get_permalink($post->ID),
            'priceCurrency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : 'EUR',
            'price' => wc_format_decimal($product->get_price(), 2),
            'availability' => $availability,
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => [
                '@type' => 'Organization',
                'name' => 'Rigolettres',
            ],
        ];

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $short ?: $product->get_name(),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Rigolettres',
            ],
            'offers' => $offer,
        ];
        if ($img_url) $data['image'] = $img_url;
        if ($product->get_sku()) $data['sku'] = $product->get_sku();
        return $data;
    }
}

if (!function_exists('rigolettres_build_breadcrumbs')) {
    function rigolettres_build_breadcrumbs() {
        if (is_front_page()) return null;

        $items = [];
        $position = 1;
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Accueil',
            'item' => home_url('/'),
        ];

        if (is_singular('product')) {
            global $post;
            // Shop page
            if (function_exists('wc_get_page_id')) {
                $shop_id = wc_get_page_id('shop');
                if ($shop_id > 0) {
                    $items[] = [
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => get_the_title($shop_id),
                        'item' => get_permalink($shop_id),
                    ];
                }
            }
            // Product category (first)
            $cats = get_the_terms($post->ID, 'product_cat');
            if ($cats && !is_wp_error($cats)) {
                $first = reset($cats);
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $first->name,
                    'item' => get_term_link($first),
                ];
            }
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title($post->ID),
                'item' => get_permalink($post->ID),
            ];
        } elseif (is_singular('post')) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => 'Journal',
                'item' => home_url('/blog/'),
            ];
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink(),
            ];
        } elseif (is_page()) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink(),
            ];
        } elseif (function_exists('is_shop') && is_shop()) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => 'Boutique',
                'item' => get_permalink(wc_get_page_id('shop')),
            ];
        } elseif (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $term->name,
                'item' => get_term_link($term),
            ];
        }

        if (count($items) < 2) return null;
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}

if (!function_exists('rigolettres_build_article_ld')) {
    function rigolettres_build_article_ld() {
        global $post;
        $img_url = '';
        $thumb_id = get_post_thumbnail_id($post->ID);
        if ($thumb_id) {
            $img = wp_get_attachment_image_src($thumb_id, 'large');
            if ($img) $img_url = $img[0];
        }
        $site_url = home_url('/');

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title($post->ID),
            'datePublished' => get_the_date('c', $post->ID),
            'dateModified' => get_the_modified_date('c', $post->ID),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Rigolettres',
                '@id' => $site_url . '#organization',
            ],
            'publisher' => ['@id' => $site_url . '#organization'],
            'mainEntityOfPage' => get_permalink($post->ID),
        ];
        if ($img_url) $data['image'] = $img_url;
        return $data;
    }
}

add_action('wp_head', function () {
    // Always emit Organization + WebSite
    rigolettres_emit_ld_json(rigolettres_build_organization());
    rigolettres_emit_ld_json(rigolettres_build_website());

    // Context-specific
    if (is_singular('product')) {
        $p = rigolettres_build_product_ld();
        if ($p) rigolettres_emit_ld_json($p);
    } elseif (is_singular('post')) {
        rigolettres_emit_ld_json(rigolettres_build_article_ld());
    }

    // Breadcrumbs on non-home
    $bc = rigolettres_build_breadcrumbs();
    if ($bc) rigolettres_emit_ld_json($bc);
}, 20);
