# Search Widget PDA

Widget de pesquisa para Elementor. Exibe um ícone de pesquisa que abre um popup com formulário de busca para todo o site.

## Funcionalidades

- ✅ Widget do Elementor com ícone de pesquisa personalizável
- ✅ Popup moderno com formulário de busca
- ✅ Resultados em tempo real enquanto digita (AJAX)
- ✅ Busca em todos os tipos de post (posts, páginas, blog_post, etc.)
- ✅ Exibição de thumbnails nos resultados
- ✅ Totalmente personalizável pelo painel do Elementor
- ✅ Design responsivo
- ✅ Acessível (navegação por teclado, ARIA labels)
- ✅ Atualização automática via GitHub

## Instalação

1. Faça o download do plugin
2. Faça upload para a pasta `/wp-content/plugins/`
3. Ative o plugin pelo painel do WordPress
4. No Elementor, procure pelo widget "Pesquisa PDA"

## Uso

1. Abra o Elementor em qualquer página
2. Procure por "Pesquisa PDA" na lista de widgets
3. Arraste o widget para onde desejar
4. Personalize as opções:
   - **Tipo de ícone**: Apenas ícone, texto ou ambos
   - **Título do popup**: Texto exibido no topo do popup
   - **Placeholder**: Texto de exemplo no campo de busca
   - **Resultados em tempo real**: Habilitar/desabilitar busca AJAX
   - **Mostrar thumbnails**: Exibir imagens nos resultados

## Personalização

### Cores e Estilos

O widget oferece várias opções de personalização pelo Elementor:

- Tamanho e cor do ícone
- Cor de fundo do botão
- Cores do popup (fundo, overlay, título)
- Cores do campo de busca
- Cor de destaque

### Filtrar Tipos de Post

Por padrão, a busca pesquisa em `post`, `page` e `blog_post`. Para alterar:

```php
add_filter('search_widget_pda_post_types', function($post_types) {
    return ['post', 'page', 'product', 'my_custom_post_type'];
});
```

## Requisitos

- WordPress 5.0+
- PHP 7.4+
- Elementor 3.0+

## Changelog

### 1.0.0
- Versão inicial
- Widget de pesquisa com popup
- Busca em tempo real via AJAX
- Suporte a múltiplos tipos de post
- Design responsivo

## Autor

Desenvolvido por [Lui](https://github.com/pereira-lui)

## Licença

GPL v2 or later
