<?php
/**
 * Plugin Name:    Discount by Answer for Easy Digital Downloads
 * Plugin URI:     http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Description:    Lets your customers answer your requests and issue discount codes as a reward.
 * Author:         Michael Uno
 * Author URI:     http://en.michaeluno.jp/
 * Version:        1.0.1
 * Text Domain:    discount-by-answer-for-easy-digital-downloads
 * Domain Path:    language
 */

/**s
 * Provides the basic information about the plugin.
 * 
 * @since    0.0.1       
 */
class DiscountByAnswerForEDD_Registry_Base {
 
    const VERSION        = '1.0.1';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
    const NAME           = 'Discount by Answer for Easy Digital Downloads';
    const DESCRIPTION    = 'Lets your customers answer your requests and issue discount codes as a reward.';
    const URI            = 'http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/';
    const AUTHOR         = 'Michael Uno';
    const AUTHOR_URI     = 'http://en.michaeluno.jp/';
    const PLUGIN_URI     = 'http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/';
    const COPYRIGHT      = 'Copyright (c) 2019, Michael Uno';
    const LICENSE        = 'GPLv2 or later';
    const CONTRIBUTORS   = '';
 
}

/**
 * Provides the common data shared among plugin files.
 * 
 * To use the class, first call the setUp() method, which sets up the necessary properties.
 * 
 * @package     Discount by Answer for Easy Digital Downloads
 * @since       0.0.1
*/
final class DiscountByAnswerForEDD_Registry extends DiscountByAnswerForEDD_Registry_Base {
    
    const TEXT_DOMAIN               = 'discount-by-answer-for-easy-digital-downloads';
    const TEXT_DOMAIN_PATH          = '/language';
    
    /**
     * The hook slug used for the prefix of action and filter hook names.
     * 
     * @remark      The ending underscore is not necessary.
     */    
    const HOOK_SLUG                 = 'edddba';    // without trailing underscore
    
    /**
     * The transient prefix. 
     * 
     * @remark      This is also accessed from uninstall.php so do not remove.
     * @remark      Up to 8 characters as transient name allows 45 characters or less ( 40 for site transients ) so that md5 (32 characters) can be added
     */    
    const TRANSIENT_PREFIX          = 'EDDDBA';

    const PRO_URL = 'http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/discount-by-answer-pro-for-easy-digital-downloads/';
    const INFO_FEED_URL = 'https://feeds.feedburner.com/ExtensionsOfDiscountByAnswerForEasyDigitalDownloads';

    /**
     * 
     * @since       0.0.1
     */
    static public $sFilePath = __FILE__;
    
    /**
     * 
     * @since       0.0.1
     */    
    static public $sDirPath;    

    /**
     * rtrim( sys_get_temp_dir(), '/' ) . '/' . DiscountByAnswerForEDD_Registry::$sTempDirBaseName;
     * @since   0.0.5
     * @var string
     */
    static public $sTempDirBaseName = '';

    /**
     * @since    0.0.1
     */
    static public $aOptionKeys = array(    
        'setting'           => 'edddba_settings',
    );

    /**
     * Represents the plugin options structure and their default values.
     * @var         array
     * @since       0.0.3
     */
    static public $aOptions = array(
        'delete'    => array(
            'delete_on_uninstall' => false,
        ),

        'style'    => array(
            'disable_styles' => false,
        ),
    );

    /**
     * Used admin pages.
     * @since    0.0.1
     */
    static public $aAdminPages = array(
        // key => 'page slug'        
        'setting'           => 'edddba_settings',
        'view_answer'       => 'edddba_view_answer',
    );
    
    /**
     * Used post types.
     * @since   0.0.1
     */
    static public $aPostTypes = array(
        // hint => actual slug
        'campaign' => 'edddba_campaign',
        'answer'   => 'edddba_answer',
    );
    
    /**
     * Used post types by meta boxes.
     * @since   0.0.1
     */
    static public $aMetaBoxPostTypes = array(
        // hint => post type slug
        'core'  => 'download'
    );
    
    /**
     * Used taxonomies.
     * @since   0.0.1
     */
    static public $aTaxonomies = array(
    );

    /**
     * Used user meta keys.
     * @var array
     * @since   0.0.1
     */
    static public $aUserMetas = array(
        // meta key => ...whatever values for notes
    );

    /**
     * @var array
     * @since   0.0.1
     */
    static public $aPostMetas = array(
        // Post Type Slug => array( meta key => hint )
        'download'         => array(

        ),
        'edd_payment'      => array(
            // Note that multiple campaign ids can be associated with a single payment,
            // where {n} is a campaign id
            '_edddba_campaign_id_{n}'   => 'stores the associated campaign id so that when a discount code is used, it can be checked whether it is used before',
        ),
        'edd_discount'     => array(
            '_edddba_campaign_id'           => 'stores the associated campaign id so that later when a discount code is used, campaign counts can be incremented and some other relevant data.',
            '_edddba_discount_auto_delete'  => 'whether to delete the campaign discount code after it is used'
        ),
        'edddba_campaign'  => array(
            '_edddba_campaign_maximum_uses'         => 'the maximum uses of a campaign',
            '_edddba_campaign_discount_use_count'   => 'the uses count',
            '_edddba_campaign_discount_issue_count' => 'how many times a discount code has been issued',
        ),
        'edddba_answer'    => array(
            '_edddba_discount_code'     => 'associated discount code, not id',
            '_edddba_discount_code_id'  => 'associated discount id, referred when deleting answers automatically in order to keep pending items',
            '_edddba_payment_id'        => 'associated payment',
            '_edddba_customer_id'       => 'associated customer id',
            '_edddba_campaign_id'       => 'associated campaign id',
            '_edddba_converted_{slug}'  => 'stores converted answers to help check if a given answer is already answered or not',
        ),
    );

    /**
     * Used shortcode slugs
     * @since   0.0.1
     */
    static public $aShortcodes = array(
    );

    /**
     * Stores custom database table names.
     * @remark      The below is the structure
     * array(
     *      'slug (part of database wrapper class file name)' => array(
     *          'version'   => '0.1',
     *          'name'      => 'table_name',    // serves as the table name suffix
     *      ),
     *      ...
     * )
     * @since       0.0.3
     */
    static public $aDatabaseTables = array(
        // 'ft_tweets'        => array(
        // 'name'              => 'ft_tweets', // serves as the table name suffix
        // 'version'           => '0.0.1',
        // 'across_network'    => true,
        // 'class_name'        => 'DiscountByAnswerForEDD_DatabaseTable_ft_tweets',
        // ),
//        'ft_http_requests' => array(
//            'name'              => 'ft_http_requests',  // serves as the table name suffix
//            'version'           => '0.0.1',
//            'across_network'    => true,
//            'class_name'        => 'DiscountByAnswerForEDD_DatabaseTable_ft_http_requests',
//        ),
    );

    /**
     * Stores action hook names registered with WP Cron.
     * @var array
     * @since   0.0.1
     */
    static public $aScheduledActionHooks = array(
        // key (whatever) => value: the name of the action hook
    );

    /**
     * Stores custom keys for the WP Cron intervals.
     * @var array
     * @since   0.0.1
     */
    static public $aWPCronIntervals = array(
    );

    /**
     * @var array 
     * @since   0.0.1
     */
    static public $aCookieSlugs = array(
        // (any) => cookie slug (cookie slug where in $_COOKIE[ slug ])
    );


    /**
     * Sets up class properties.
     * @return      void
     * @since   0.0.1
     */
    static function setUp() {
        self::$sDirPath  = dirname( self::$sFilePath );  
    }    
    
    /**
     * @return      string
     * @since   0.0.1
     */
    public static function getPluginURL( $sPath='', $bAbsolute=false ) {
        $_sRelativePath = $bAbsolute
            ? str_replace('\\', '/', str_replace( self::$sDirPath, '', $sPath ) )
            : $sPath;
        if ( isset( self::$_sPluginURLCache ) ) {
            return self::$_sPluginURLCache . $_sRelativePath;
        }
        self::$_sPluginURLCache = trailingslashit( plugins_url( '', self::$sFilePath ) );
        return self::$_sPluginURLCache . $_sRelativePath;
    }
        /**
         * @since    0.0.1
         */
        static private $_sPluginURLCache;

    /**
     * Requirements.
     * @since    0.0.1
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.4',
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        // 'mysql'             => array(
            // 'version'   => '5.0.3', // uses VARCHAR(2083) 
            // 'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        // ),
        'functions'     => array(
            'EDD'   => 'The plugin requires <a href="https://wordpress.org/plugins/easy-digital-downloads/">Easy Digital Downloads</a>',
        ),
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
         'classes'       => array(
             'DOMDocument'  => 'The plugin requires the DOMXML extension.',
             'DOMXPath'     => 'The plugin requires the DOMXPath class available.',
             'EDD_Customer' => 'The plugin required Easy Digital Downloads v2.4 or above.',
         ),
        'constants'     => '', // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         => '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );

    /**
     * @param $sMessage
     * @param $sType
     * @since   0.0.1
     */
    static public function setAdminNotice( $sMessage, $sType ) {
        self::$aAdminNotices[] = array( 'message' => $sMessage, 'type' => $sType );
        add_action( 'admin_notices', array( __CLASS__, 'replyToShowAdminNotices' ) );
    }
        /**
         * @var array 
         * @since    0.0.1
         */
        static public $aAdminNotices = array();
        /**
         * @since   0.0.1
         */
        static public function replyToShowAdminNotices() {
            foreach( self::$aAdminNotices as $_aNotice ) {
                $_sType = esc_attr( $_aNotice[ 'type' ] );
                echo "<div class='{$_sType}'>"
                     . "<p>" . $_aNotice[ 'message' ] . "</p>"
                     . "</div>";
            }
        }

    /**
     * @param array $aClasses
     *
     * @throws Exception
     * @since   0.0.1
     */
    static public function registerClasses( array $aClasses ) {
        self::$___aAutoLoadClasses = $aClasses + self::$___aAutoLoadClasses;
        spl_autoload_register( array( __CLASS__, 'replyToLoadClass' ) );
    }
        /**
         * @var array 
         * @since   0.0.1
         */
        static private $___aAutoLoadClasses = array();
        /**
         * @param $sCalledUnknownClassName
         * @since   0.0.1
         */
        static public function replyToLoadClass( $sCalledUnknownClassName ) {
            if ( ! isset( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] ) ) {
                return;
            }
            include( self::$___aAutoLoadClasses[ $sCalledUnknownClassName ] );
        }

}
DiscountByAnswerForEDD_Registry::setUp();

// Do not load if accessed directly. Not exiting here because other scripts will load this main file such as uninstall.php and inclusion list generator
// and if it exists their scripts will not complete.
if ( ! defined( 'ABSPATH' ) ) {
    return;
}

if ( defined( 'DOING_TESTS' ) && DOING_TESTS ) {
    return;
}

include( dirname( __FILE__ ).'/include/library/apf/admin-page-framework.php' );
include(dirname(__FILE__) . '/include/DiscountByAnswerForEDD_Bootstrap.php');
new DiscountByAnswerForEDD_Bootstrap(
    DiscountByAnswerForEDD_Registry::$sFilePath,
    DiscountByAnswerForEDD_Registry::HOOK_SLUG    // hook prefix
);