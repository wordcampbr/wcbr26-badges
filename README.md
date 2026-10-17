# Badges do WordCamp Brasil 2026 no WordPress Playground

Blueprints do [WordPress Playground](https://playground.wordpress.net/) com o gerador de foto de perfil do [WordCamp Brasil 2026](https://brasil.wordcamp.org/2026/). Cada link abre um WordPress no navegador, já na página do gerador do papel, com o cabeçalho do site do evento. A foto não sai do navegador.

| Papel | Link |
| --- | --- |
| Participante | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/participante/blueprint.json) |
| Palestrante | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/palestrante/blueprint.json) |
| Painelista | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/painelista/blueprint.json) |
| Patrocinador | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/patrocinador/blueprint.json) |
| Micropatrocinador | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/micropatrocinador/blueprint.json) |
| Organização | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/organizacao/blueprint.json) |
| Voluntariado | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/voluntario/blueprint.json) |
| Mídia parceira | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/midia/blueprint.json) |
| Líder de mesa | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/lider-de-mesa/blueprint.json) |
| Influenciador | [Abrir no Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wordcampbr/wcbr26-badges/main/influenciador/blueprint.json) |

Os badges ficam em [brasil.wordcamp.org/2026/badges](https://brasil.wordcamp.org/2026/badges/).

## Como funciona

- `<papel>/blueprint.json`: instala o plugin [Profile Frame Generator](https://wordpress.org/plugins/profile-frame-generator/), grava os arquivos de `comum/`, importa o `content.xml` e roda `comum/playground.php`.
- `<papel>/content.xml`: a moldura do papel (da mídia de brasil.wordcamp.org/2026, pelo Photon) e a página do gerador.
- `comum/playground.php`: baixa as fontes de brasil.wordcamp.org/2026, aplica os estilos globais, o cabeçalho, o rodapé e o menu overlay do site do evento (com os links de lá) e deixa o gerador como página inicial.
- `comum/wcbr-badges-pfg.php`: logo igual ao da produção, estilo do gerador com a identidade do evento.
- `comum/identidade.json`: estilos globais, cabeçalho, rodapé (com os patrocinadores como a produção os mostra), menu overlay e menus de brasil.wordcamp.org/2026.
- `comum/profile-frame-generator-pt_BR.l10n.php`: tradução da interface do gerador.

Imagens e fontes vêm da mídia de brasil.wordcamp.org/2026; aqui fica só o que a mídia não aceita. Os arquivos são gerados por um script; edite a fonte e gere de novo, em vez de editar aqui.
