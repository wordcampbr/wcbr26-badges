<?php
/**
 * Monta o site do Playground depois do import: identidade e cabeçalho de brasil.wordcamp.org/2026,
 * e o gerador do papel como página inicial.
 */

require_once '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

// Sem usuário, o kses filtra os estilos globais e o termo do tema não é gravado.
wp_set_current_user( 1 );

$fontes = 'https://brasil.wordcamp.org/2026/files/fonts/';
$dir    = WP_CONTENT_DIR . '/uploads/wcbr/';
$id     = json_decode( file_get_contents( $dir . 'identidade.json' ), true );
$theme  = get_stylesheet();

// Fontes de brasil.wordcamp.org/2026 copiadas para o Playground: a produção não manda CORS para o
// navegador, mas o PHP do Playground as baixa pelo proxy de CORS dele.
wp_mkdir_p( $dir . 'fonts' );
preg_match_all( '#' . preg_quote( $fontes, '#' ) . '([\w.-]+\.woff2)#', wp_json_encode( $id['settings'], JSON_UNESCAPED_SLASHES ), $m );
foreach ( array_unique( $m[1] ) as $fonte ) {
	if ( ! file_exists( $dir . 'fonts/' . $fonte ) ) {
		$tmp = download_url( $fontes . $fonte );
		if ( ! is_wp_error( $tmp ) ) {
			rename( $tmp, $dir . 'fonts/' . $fonte );
		}
	}
}

// Estilos globais com as fontes locais.
$json = wp_json_encode(
	array(
		'version'                     => 3,
		'isGlobalStylesUserThemeJSON' => true,
		'settings'                    => $id['settings'],
		'styles'                      => $id['styles'],
	)
);
$json = str_replace( 'https:\/\/brasil.wordcamp.org\/2026\/files\/fonts\/', str_replace( '/', '\/', content_url( 'uploads/wcbr/fonts/' ) ), $json );
wp_update_post( array( 'ID' => WP_Theme_JSON_Resolver::get_user_global_styles_post_id(), 'post_content' => wp_slash( $json ) ) );

// Menus da produção. Sem os bindings de página, os links ficam com os endereços de lá.
$refs = array();
foreach ( $id['nav'] as $nav ) {
	$content = str_replace( ',"metadata":{"bindings":{"url":{"source":"core/post-data","args":{"field":"link"}}}}', '', $nav['content'] );
	$content = preg_replace( '/"id":\d+,/', '', $content );
	$refs[ $nav['id'] ] = wp_insert_post( array( 'post_type' => 'wp_navigation', 'post_status' => 'publish', 'post_title' => $nav['title'], 'post_content' => wp_slash( $content ) ) );
}

// Cabeçalho, rodapé e menu overlay da produção. O logo é trocado pelo da produção no mu-plugin.
foreach ( array( 'header' => 'Header', 'footer' => 'Footer', 'navigation-overlay' => 'Navigation Overlay' ) as $slug => $title ) {
	$content = preg_replace_callback(
		'/"ref":(\d+)/',
		function ( $m ) use ( $refs ) {
			return '"ref":' . ( isset( $refs[ $m[1] ] ) ? $refs[ $m[1] ] : $m[1] );
		},
		$id['parts'][ $slug ]['content']
	);
	$pid     = wp_insert_post( array( 'post_type' => 'wp_template_part', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $title, 'post_content' => wp_slash( $content ) ) );
	wp_set_object_terms( $pid, $theme, 'wp_theme' );
	wp_set_object_terms( $pid, $slug, 'wp_template_part_area' );
}

// Moldura: o importador a baixa pelo Photon; se não conseguiu, baixa de novo.
$att    = get_posts( array( 'post_type' => 'attachment', 'name' => 'wcbr26-badge-moldura-' . $wcbr_slug, 'numberposts' => 1, 'post_status' => 'inherit' ) );
$att_id = $att ? $att[0]->ID : 0;
if ( ! $att_id || ! file_exists( (string) get_attached_file( $att_id ) ) ) {
	$tmp = download_url( $wcbr_moldura );
	if ( ! is_wp_error( $tmp ) ) {
		$att_id = media_handle_sideload( array( 'name' => 'wcbr26-badge-moldura-' . $wcbr_slug . '.png', 'tmp_name' => $tmp ), 0, 'Moldura ' . $wcbr_papel );
	}
}

// O importador pode trocar o ID do anexo: o shortcode aponta para o ID novo.
$page = get_page_by_path( 'badge-' . $wcbr_slug );
wp_update_post( array( 'ID' => $page->ID, 'post_content' => preg_replace( '/\[profile_frame frame="\d+"\]/', '[profile_frame frame="' . (int) $att_id . '"]', $page->post_content ) ) );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page->ID );
update_option( 'blogname', 'Badge ' . $wcbr_papel . ' · WordCamp Brasil 2026' );
update_option( 'profileframegen_frame_id', $att_id );
update_option( 'profileframegen_gravatar_field', '1' );
update_option( 'profileframegen_instructions_text', '<ul><li><strong>Envie a foto:</strong> clique na área de envio e escolha a sua foto de perfil, ou use o Gravatar.</li><li><strong>Ajuste:</strong> arraste para posicionar, use a rolagem ou os botões para o zoom e gire se precisar.</li><li><strong>Baixe:</strong> clique no botão para baixar a nova foto de perfil.</li></ul>' );

// Página inicial: cabeçalho e rodapé da produção, com o gerador no meio.
$tpl = wp_insert_post(
	array(
		'post_type'    => 'wp_template',
		'post_status'  => 'publish',
		'post_name'    => 'front-page',
		'post_title'   => 'Página inicial',
		'post_content' => '<!-- wp:template-part {"slug":"header","tagName":"header"} /--><!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained","contentSize":"960px"}} --><main class="wp-block-group" style="padding:var(--wp--preset--spacing--40) var(--wp--preset--spacing--30) var(--wp--preset--spacing--50)"><!-- wp:post-content {"layout":{"type":"constrained","contentSize":"960px"}} /--></main><!-- /wp:group --><!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->',
	)
);
wp_set_object_terms( $tpl, $theme, 'wp_theme' );
