# 🛍️ Maior Desconto WooCommerce

Plugin para **WordPress + WooCommerce** que calcula automaticamente o percentual de desconto dos produtos com base no preço normal e no preço promocional. Também adiciona a opção de ordenação por **"Maior Desconto"** na loja.

---

## 📦 Funcionalidades

- ✅ Calcula automaticamente o percentual de desconto ao salvar produtos.
- ✅ Exibe o desconto calculado no admin do produto.
- ✅ Adiciona opção de ordenação por **Maior Desconto** na loja.
- ✅ Compatível com produtos simples do WooCommerce.

---

## 🚀 Instalação

1. Faça o download ou clone o repositório:

   ```bash
   git clone https://github.com/danielmathiass/biggest-discount-wp-plugin.git
   ```
2. (Opcional) Renomeie a pasta para:

```bash
maior-desconto-woocommerce
```
3. Mova a pasta para o diretório de plugins do seu WordPress:

```bash
wp-content/plugins/
```
4. Acesse o painel do WordPress, vá até Plugins e ative Maior Desconto WooCommerce.

## ⚙️ Como funciona

1. Cálculo automático

Ao salvar ou atualizar um produto, o plugin calcula:

```bash
Desconto (%) = (Preço Normal - Preço Promocional) / Preço Normal * 100
```
E salva como um metadado personalizado: _product_discount.

2. Metabox no painel do produto

Na edição de produtos no painel do WordPress, o plugin exibe:

- O percentual de desconto calculado

- Informação contextual: "Baseado no preço normal e preço promocional"

3. Ordenação por maior desconto

Na loja WooCommerce, o plugin adiciona a opção:

```bash
Ordenar por: Maior desconto
```
Essa opção organiza os produtos do maior para o menor desconto percentual.

4. Atualização em massa 

Caso tenha produtos antigos e queira forçar o recálculo dos descontos de todos, ative temporariamente o seguinte código no final do plugin:

```bash
// add_action('init', function() {
//     if (isset($_GET['forcar_atualizacao_descontos']) && current_user_can('manage_woocommerce')) {
//         $args = [
//             'post_type' => 'product',
//             'posts_per_page' => -1,
//             'post_status' => 'publish'
//         ];
//
//         $produtos = get_posts($args);
//         $plugin = new MaiorDescontoWooCommerce();
//
//         foreach ($produtos as $produto) {
//             $plugin->calcular_desconto_produto($produto->ID);
//         }
//
//         echo count($produtos) . ' produtos atualizados.';
//         exit;
//     }
// });
```

Depois, acesse no navegador:
```bash
https://seudominio.com/?forcar_atualizacao_descontos
```
### ⚠️ Importante: remova ou comente novamente essa função após o uso, por segurança.

## 🧪 Compatibilidade
- WordPress 5.0 ou superior

- WooCommerce 4.0 ou superior

- PHP 7.4+


