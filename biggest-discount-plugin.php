<?php
/*
Plugin Name: Maior Desconto WooCommerce
Plugin URI: https://github.com/11Mathias/biggest-discount-wp-plugin
Description: Calcula automaticamente o desconto dos produtos e permite ordenação por maior desconto.
Version: 1.1.0
Author: Daniel Mathias
Author URI: https://www.linkedin.com/in/daniel-mathias-883858321/
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class MaiorDescontoWooCommerce
{
    public function __construct()
    {
        // Hooks
        add_action('save_post', function($post_id) {
            if (get_post_type($post_id) === 'product') {
                $this->calcular_desconto_produto($post_id);
            }
        });
        add_action('add_meta_boxes', [$this, 'adicionar_metabox_desconto']);

        // Filtros para ordenação no front-end
        add_filter('woocommerce_get_catalog_ordering_args', [$this, 'ordenar_por_maior_desconto']);
        add_filter('woocommerce_default_catalog_orderby_options', [$this, 'adicionar_opcao_maior_desconto']);
        add_filter('woocommerce_catalog_orderby', [$this, 'adicionar_opcao_maior_desconto']);
    }

    /**
     * Calcula automaticamente o desconto (%) baseado nos preços regular e promocional
     */
    // refatorado usando object calisthenics
    public function calcular_desconto_produto($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        //o cast para float vai garantir que o calculo seja numericos.
        $regular_price = (float) get_post_meta($post_id, '_regular_price', true);
        $sale_price = (float) get_post_meta($post_id, '_sale_price', true);
   
        if ($regular_price == 0.0 || $sale_price == 0.0 || $sale_price >= $regular_price ) {
            update_post_meta($post_id, '_product_discount', 0);
            return;
        }

        $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;
        update_post_meta($post_id, '_product_discount', round($discount_percentage, 2));
    }
    /**
     * Adiciona um Metabox no Admin para visualizar o desconto calculado
     */
    public function adicionar_metabox_desconto()
    {
        add_meta_box(
            'product_discount_metabox',
            __('Desconto Calculado', 'woocommerce'),
            [$this, 'renderizar_metabox_desconto'],
            'product',
            'side',
            'default'
        );
    }

    /**
     * Renderiza o conteúdo do Metabox
     */
    public function renderizar_metabox_desconto($post)
    {
        $discount = get_post_meta($post->ID, '_product_discount', true);

        echo '<p><strong>Desconto Atual:</strong></p>';
        echo '<p style="font-size: 18px;">' . (esc_html($discount) ? esc_html($discount) . '%' : 'Nenhum desconto') . '</p>';
        echo '<p><small>* Baseado no preço normal e preço promocional.</small></p>';
    }

    /**
     * Adiciona a opção de "Maior desconto" na lista de ordenação
     */
    public function adicionar_opcao_maior_desconto($options)
    {
        $options['highest_discount'] = __('Maior desconto', 'woocommerce');
        return $options;
    }

    /**
     * Modifica os argumentos de ordenação para funcionar com o maior desconto
     */
    public function ordenar_por_maior_desconto($args)
    {
        if (isset($_GET['orderby']) && 'highest_discount' === $_GET['orderby']) {
            $args['meta_key'] = '_product_discount';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';


            // Garante que ate os produtos com 0% de desconto apareçam na tela
            $args['meta_query'][] = [
                'key' => '_product_discount',
                'compare' => 'EXISTS',
                'type' => 'NUMERIC'
            ];
        }
        
        return $args;
    }
}

// Inicializar o plugin
new MaiorDescontoWooCommerce();

/**
    *Rode esse add_action apenas 1 vez para forçar a atualização de todos os produtos no endpoint ('localhost/?forcar_atualizacao_descontos')
*/

// add_action('init', function() {
//     if (isset($_GET['forcar_atualizacao_descontos']) && current_user_can('manage_woocommerce')) {
//         $args = [
//             'post_type' => 'product',
//             'posts_per_page' => -1,
//             'post_status' => 'publish'
//         ];

//         $produtos = get_posts($args);
//         $plugin = new MaiorDescontoWooCommerce();

//         foreach ($produtos as $produto) {
//             $plugin->calcular_desconto_produto($produto->ID);
//         }

//         echo count($produtos) . ' produtos atualizados.';
//         exit;
//     }
// });

