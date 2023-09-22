<?php
/**
 * A configuração de base do WordPress
 *
 * Este ficheiro define os seguintes parâmetros: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, e ABSPATH. Pode obter mais informação
 * visitando {@link https://wordpress.org/support/article/editing-wp-config-php/ Editing
 * wp-config.php} no Codex. As definições de MySQL são-lhe fornecidas pelo seu serviço de alojamento.
 *
 * Este ficheiro contém as seguintes configurações:
 *
 * * Configurações de  MySQL
 * * Chaves secretas
 * * Prefixo das tabelas da base de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Definições de MySQL - obtenha estes dados do seu serviço de alojamento** //
/** O nome da base de dados do WordPress */
define( 'DB_NAME', 'fictional-university-wp' );

/** O nome do utilizador de MySQL */
define( 'DB_USER', 'root' );

/** A password do utilizador de MySQL  */
define( 'DB_PASSWORD', '' );

/** O nome do serviddor de  MySQL  */
define( 'DB_HOST', 'localhost' );

/** O "Database Charset" a usar na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O "Database Collate type". Se tem dúvidas não mude. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação.
 *
 * Mude para frases únicas e diferentes!
 * Pode gerar frases automáticamente em {@link https://api.wordpress.org/secret-key/1.1/salt/ Serviço de chaves secretas de WordPress.org}
 * Pode mudar estes valores em qualquer altura para invalidar todos os cookies existentes o que terá como resultado obrigar todos os utilizadores a voltarem a fazer login
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', '2nXASKOEPg&DOO=2Qa..0tFgeSZ_hXOHQAeK@R23L.?GH+mY?O]+ktJBD%[Gx|Q#' );
define( 'SECURE_AUTH_KEY', 'F7Tl@H]+wJE]{iPp:jdVFm8wB4-}Yv).k{t+]Z*:?>-Y u$:8] JIm#l/KFI_>d^' );
define( 'LOGGED_IN_KEY', 'gE=O2f8_}COZ/0loEPOuIiX4R42h^`Gh8*VDQ}>8V#<Ou^|5rA]gHDPhWc[q%}?I' );
define( 'NONCE_KEY', '>a7hIiJT=14c*gP]VeDjm3:v&y7[IZOT]e&}Bs34=J^D8nGgZzeCz|$)}#Q}),R7' );
define( 'AUTH_SALT', 'RTB`U <yBi*n6Oka+axd7/[QhpmJ1rYi/F!ji#,K1JG24$#_~rkAoRAA%#dpQ!+d' );
define( 'SECURE_AUTH_SALT', '1CHilh@dlmZDQvs.Z!f&[%YtUnK|EFCHxO^%aF=)z4y=xZWu6T(8idKJ`6=Y,v*@' );
define( 'LOGGED_IN_SALT', 'p5r(;v8FK+(Q|0SeWeM<e-,98M(0>QV&YG+3fJ_NB#GAf-@WFdkLfQ l5}8#^[:=' );
define( 'NONCE_SALT', '?Z{V@ibhf-4W,E7PwTUq#K_->]FQXMOXy_EyS*9>fNQ )><f3 dyZa=2BwuO%f,(' );

/**#@-*/

/**
 * Prefixo das tabelas de WordPress.
 *
 * Pode suportar múltiplas instalações numa só base de dados, ao dar a cada
 * instalação um prefixo único. Só algarismos, letras e underscores, por favor!
 */
$table_prefix = 'wp_';

/**
 * Para developers: WordPress em modo debugging.
 *
 * Mude isto para true para mostrar avisos enquanto estiver a testar.
 * É vivamente recomendado aos autores de temas e plugins usarem WP_DEBUG
 * no seu ambiente de desenvolvimento.
 *
 * Para mais informações sobre outras constantes que pode usar para debugging,
 * visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* E é tudo. Pare de editar! */

/** Caminho absoluto para a pasta do WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Define as variáveis do WordPress e ficheiros a incluir. */
require_once ABSPATH . 'wp-settings.php';
