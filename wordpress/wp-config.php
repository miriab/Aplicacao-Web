<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'cadastro' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'D8IinH$ymH?yFD+93xWf~yc02QUtJ}D&qTcR+o}`@,X|B9).>K{xe$.+;$BlPmZy' );
define( 'SECURE_AUTH_KEY',  'Y(;BR3_y;4C6Y><`FEHrfySx9/=(1 +n|F>9jnLji&;R>0s>@!DM6w#Z><yzt~mX' );
define( 'LOGGED_IN_KEY',    'Y-&G?-qner4`2?e3]C~p(?5E=32(yrmm@wAN Jf|?!vK8;ta3!46CqCnWtZ-v4|R' );
define( 'NONCE_KEY',        'u.oi[j#]l<^*&@E]);m V(vG)_d,g$,3&A_fhwm{4H|qE3g)<@FwJva;59%X1DZj' );
define( 'AUTH_SALT',        '+FMcP+ S2/n#vAbs!#}9[q*-$kh-Bi}n mt&tO$NOkg^xpz M@:=sk*ujArc8Gu;' );
define( 'SECURE_AUTH_SALT', '/(jb7!taafFAZZUJc3_<ryZ44m)e~ tqQQd;zcRM9=X;m{)z0^1Mn6^v?z:Cj>W6' );
define( 'LOGGED_IN_SALT',   ':aAG[:HF8sQM2H>gr]{f&HC3#{X;9MR=1:Hm0?TYHt%PCE8MarrtF{v3xl`@%}:w' );
define( 'NONCE_SALT',       '26yhc0e{-Qi%I[*1nh*@YR4biP0l(fJHyM9Pv{5Ya}3T][wM&Ng/`zRzQC?:(>y@' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
